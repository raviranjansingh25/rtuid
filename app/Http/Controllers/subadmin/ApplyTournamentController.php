<?php

namespace App\Http\Controllers\subadmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\subadmin\Concerns\HandlesSubadminPermissions;
use Illuminate\Http\Request;
use App\Models\ApplyTournament;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\WeightCategory;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;

class ApplyTournamentController extends Controller
{
    use HandlesSubadminPermissions;

    public function edit(Request $request, $id = Null)
    {
        $denied = $this->denySubadminUnless($this->subadminCan('apply_tournament'));
        if ($denied) {
            return $denied;
        }

        $decrypted_id = get_decrypted_value($id, true);
        $getdata = ApplyTournament::find($decrypted_id);

        $current_date = Carbon::now()->format('Y-m-d');
        $user = User::find($getdata->user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        if (!$user->weight || !$user->gender || !$user->category) {
            return response()->json(['error' => 'Incomplete user profile'], 422);
        }
        
        $waight_category = WeightCategory::where(function ($query) use ($user) {
            $query->where('min', '<=', $user->weight)
                  ->where(function ($subQuery) use ($user) {
                      $subQuery->where('max', '>=', $user->weight)
                               ->orWhere('max', 0);
                  })
                  ->orWhere(function ($subQuery) use ($user) {
                      $subQuery->where('min', 0)
                               ->where('max', '>=', $user->weight);
                  });
        })
        ->where('gender', $user->gender)
        ->where('category', $user->category)
        ->where('status', 1)
        ->first();

        if (!$waight_category) {
            return response()->json(['error' => 'Weight category not found'], 404);
        }

        $tournaments = Tournament::where('status', 1)
            ->where('start_date', '<=', $current_date)
            ->where('end_date', '>=', $current_date)
            ->where('gender', $user->gender)
            ->where('category', $user->category)
            ->where('weight_category', $waight_category->id)
            ->get();
        
        $saveurl = url('subadmin/apply-tournament-name_edit_save/' . $id);
        $button = 'Update';
        $page_title = 'Update User Apply Tournament';
        
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'tournaments' => $tournaments
        );
        return view('subadmin.applytournament.add')->with($data);
    }
    public function edit_save(Request $request, $id = NUll)
    {
        $denied = $this->denySubadminUnless($this->subadminCan('apply_tournament'));
        if ($denied) {
            return $denied;
        }

            $decrypted_id  = get_decrypted_value($id, true);
            $data          = ApplyTournament::find($decrypted_id);
            $success_msg   = 'Apply Tournament Updated Successfully.';
            
        $Validatior = Validator::make($request->all(), [
            'actul_waight' => 'required',
        ]);

        if ($Validatior->fails()) {
            return back()->withInput()->withErrors($Validatior);
        } else {
            DB::beginTransaction();
            try {
                $tournaments = Tournament::find($request['tournament_id']);

                $data->actul_waight       = $request['actul_waight'];
                $data->waight_category        = $tournaments->weight_category;
                $data->turnament_id = $request['tournament_id'];
                $data->category = $tournaments['get_category']->title;
                $data->save();
                
                $user = User::find($data->user_id);
                $user->weight = $request['actul_waight'];
                $user->save();
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->back()->withSuccess($success_msg);
    }

    public function index()
    {
        $denied = $this->denySubadminUnless($this->subadminCan('apply_tournament'));
        if ($denied) {
            return $denied;
        }

        $data = $this->subadminViewData([
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'ajax_url' => url('/subadmin/apply-tournament-data')
        ]);
        return view('subadmin.applytournament.view')->with($data);
    }

    public function district_index()
    {
        $denied = $this->denySubadminUnless($this->subadminCan('apply_tournament'));
        if ($denied) {
            return $denied;
        }

        $data = $this->subadminViewData([
            'title' => 'View District Tournament',
            'page_title' => 'View District Tournament',
            'ajax_url' => url('/subadmin/district-apply-tournament-data')
        ]);
        return view('subadmin.applytournament.view')->with($data);
    }
    
    public function indexname($id)
    {
        $denied = $this->denySubadminUnless($this->subadminCan('apply_tournament'));
        if ($denied) {
            return $denied;
        }

        $data = $this->subadminViewData([
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'id' => $id
        ]);
        return view('subadmin.applytournament.viewname')->with($data);
    }
    
    public function anydata(Request $request)
    {
        $subadmin = $this->subadmin();
        $query = Tournament::orderBy('id', 'DESC')
            ->where('status', '<', 3)
            ->where(function ($q) {
                $q->where('is_district_tournament', '!=', 1)
                  ->orWhereNull('is_district_tournament');
            })
            ->where(function ($query) use ($request) {
            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }
            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        });

        // ALL = same as admin. District = all tournaments; applicants filtered in anydataname.
        if ($this->canAccessAllDistricts($subadmin) && !empty($request['district']) && $request['district'] !== 'all') {
            $district = $request['district'];
            $query->whereIn('id', function ($sub) use ($district) {
                $sub->select('turnament_id')
                    ->from('apply_tournaments')
                    ->whereIn('user_id', function ($userSub) use ($district) {
                        $userSub->select('id')->from('users')->where('district', $district);
                    });
            });
        }

        $anydata = $query->groupBy('title')->get();

        return Datatables::of($anydata)
            ->addColumn('action', function ($anydata) {
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/subadmin/apply-tournament-name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;';
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()->make(true);
    }

    public function district_anydata(Request $request)
    {
        $subadmin = $this->subadmin();
        $query = Tournament::orderBy('id', 'DESC')
            ->where('status', '<', 3)
            ->where('is_district_tournament', 1);
            
        if (!$this->canAccessAllDistricts($subadmin)) {
            $query->where('district_id', $subadmin->district);
        }

        $query->where(function ($query) use ($request) {
            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }
            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        });

        if ($this->canAccessAllDistricts($subadmin) && !empty($request['district']) && $request['district'] !== 'all') {
            $district = $request['district'];
            $query->where('district_id', $district);
        }

        $anydata = $query->groupBy('title')->get();

        return Datatables::of($anydata)
            ->addColumn('action', function ($anydata) {
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/subadmin/apply-tournament-name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;';
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()->make(true);
    }

    public function anydataname(Request $request,$id)
    {
        $subadmin = $this->subadmin();
        $decrypted_id  = get_decrypted_value($id, true);
        $data = Tournament::find($decrypted_id);
        $ids = Tournament::where('title',$data->title)->pluck('id')->toArray();

        $query = ApplyTournament::with(['get_waight_cat', 'get_user'])
            ->whereIn('turnament_id', $ids)
            ->orderBy('id', 'DESC')
            ->where('status', '<', 3)
            ->where(function ($query) use ($request) {
                if (!empty($request['title'])) {
                    $query->where('name', 'LIKE', '%' . $request['title'] . '%');
                }
                if (!empty($request['status'])) {
                    $query->where('status', $request['status']);
                }
            });

        if (!$this->canAccessAllDistricts($subadmin)) {
            $query->whereHas('get_user', function ($q) use ($subadmin, $request) {
                $q->where('district', $subadmin->district);
            });
        } elseif (!empty($request['district']) && $request['district'] !== 'all') {
            $query->whereHas('get_user', function ($q) use ($request) {
                $q->where('district', $request['district']);
            });
        }

        $allDistrictMode = $this->canAccessAllDistricts($subadmin);

        $datatable = Datatables::of($query->get())
            ->addColumn('waight_category', function ($anydata) {
                return isset($anydata['get_waight_cat']->title) ? $anydata['get_waight_cat']->title : 'N/A';
            })
            ->addColumn('status', function ($anydata) {
                if ($anydata->status == 1) {
                    $status = 2;
                    return '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')" class="btn btn-success btn-rounded btn-sm waves-effect waves-light">Active</span>';
                }

                $status = 1;
                return '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light">Deactive</span>';
            })
            ->addColumn('id_card', function ($anydata) {
                $encrypted_id = get_encrypted_value($anydata->id, true);
                return '<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="' . url('/subadmin/single_card/' . $encrypted_id) . '">Download</a>';
            })
            ->addColumn('form', function ($anydata) {
                $encrypted_id = get_encrypted_value($anydata->id, true);
                return '<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="' . url('/subadmin/reg_form_single/' . $encrypted_id) . '">Download</a>';
            })
            ->addColumn('action', function ($anydata) use ($allDistrictMode) {
                $encrypted_id = get_encrypted_value($anydata->id, true);

                if ($allDistrictMode) {
                    return '<a href="' . url('/subadmin/apply-tournament-name_edit/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;' .
                        '<i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
                }

                if ($anydata->final == 0) {
                    return '<a href="' . url('/subadmin/apply-tournament-name_edit/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;<i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
                }

                return 'N/A';
            });

        if ($allDistrictMode) {
            $datatable
                ->addColumn('it_uid', function ($anydata) {
                    return optional($anydata->get_user)->it_uid;
                });
        }

        return $datatable
            ->rawColumns(['status', 'action', 'image', 'event', 'get_waight_cat', 'category', 'waight_category', 'id_card', 'form'])
            ->addIndexColumn()->make(true);
    }

    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = ApplyTournament::find($id);
        if ($data) {
            $data->delete();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Apply Tournament User Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        $subadmin = $this->subadmin();
        if (empty($subadmin->can_apply)) {
            return response()->json(['status' => 'error', 'message' => 'Permission denied.'], 403);
        }

        $user = User::find($request['user_id']);
        $turnament = Tournament::with('get_category')->find($request['tournament_id']);
        $apply = ApplyTournament::where('user_id',$request['user_id'])->where('turnament_id',$request['tournament_id'])->first();
        if(!empty($apply)){
            return response()->json(['status' => 'failed']);
        }
        $apply = new ApplyTournament();
        $apply->user_id = $request['user_id'];
        $apply->turnament_id = $request['tournament_id'];
        $apply->referee_id = $subadmin->id;
        $apply->district = $user->district_name;
        $apply->code = $user->code_id;
        $apply->it_uid = $user->it_uid;
        $apply->gender = $user->user_gender;
        $apply->dob = $user->dob;
        $apply->name = $user->name;
        $apply->email = $user->email;
        $apply->phone = $user->contact_number;
        $apply->referee = $user->referee_name;
        $apply->category = $turnament['get_category']->title;
        $apply->event = $turnament->event_category;
        $apply->waight_category = $turnament->weight_category;
        $apply->actul_waight = $user->weight;
        $apply->save();

        return response()->json(['status' => 'success']);
    }

    public function get_tournament(Request $request)
    {
        $admin = $this->subadmin();
        if (empty($admin->can_apply)) {
            return response()->json(['error' => 'Permission denied.'], 403);
        }

        try {
            $current_date = Carbon::now()->format('Y-m-d');
            $user = User::find($request->user_id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            if (!$user->weight || !$user->gender || !$user->category) {
                return response()->json(['error' => 'Incomplete user profile'], 422);
            }
            
            $age = Carbon::now()->year - Carbon::parse($user->dob)->year;
            $category = Category::where('min','<=',$age)->where('max','>=',$age)->where('status',1)->pluck('id')->toArray();
        
            if (!$user->weight || !$user->gender || !$user->category) {
                return response()->json(['error' => 'Incomplete user profile'], 422);
            }
            
            $waight_category = WeightCategory::where(function ($query) use ($user) {
            $query->where('min', '<=', $user->weight)
                  ->where(function ($subQuery) use ($user) {
                      $subQuery->where('max', '>=', $user->weight)
                               ->orWhere('max', 0);
                  })
                  ->orWhere(function ($subQuery) use ($user) {
                      $subQuery->where('min', 0)
                               ->where('max', '>=', $user->weight);
                  });
        })
        ->where('gender', $user->gender)
        ->whereIn('category', $category)
        ->where('status', 1)
        ->pluck('id')->toArray();
    
            if (!$waight_category) {
                return response()->json(['error' => 'Weight category not found'], 404);
            }
    
            $tournaments = Tournament::with('get_waight_cat')->where('status', 1)
                ->whereDate('start_date', '<=', $current_date)
                ->whereDate('end_date', '>=', $current_date)
                ->where('gender', $user->gender)
                ->whereIn('category', $category)
                ->whereIn('weight_category', $waight_category)
                ->get();
                
            return response()->json($tournaments);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function changeVisibleStatus(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Invalid request']);
        }

        ApplyTournament::whereIn('id', $ids)->update(['final' => 1]);

        return response()->json(['success' => true]);
    }
}
