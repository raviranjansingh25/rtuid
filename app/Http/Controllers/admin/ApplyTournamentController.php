<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ApplyTournament;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use App\Models\WeightCategory;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class ApplyTournamentController extends Controller
{
    public function edit(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = ApplyTournament::find($decrypted_id);

        $current_date = Carbon::now()->format('Y-m-d');
        $user = User::find($getdata->user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // p($user->weight);
        if (!$user->weight || !$user->gender || !$user->category) {
            return response()->json(['error' => 'Incomplete user profile'], 422);
        }
        
       $waight_category = WeightCategory::where(function ($query) use ($user) {
            $query->where('min', '<=', $user->weight)
                  ->where(function ($subQuery) use ($user) {
                      $subQuery->where('max', '>=', $user->weight)
                               ->orWhere('max', 0); // Over category
                  })
                  ->orWhere(function ($subQuery) use ($user) {
                      $subQuery->where('min', 0)
                               ->where('max', '>=', $user->weight); // Under category
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
        
        $saveurl = url('admin/apply-tournament-name_edit_save/' . $id);
        $button = 'Update';
        $page_title = 'Update User Apply Tournament';
        
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'tournaments' => $tournaments
        );
        return view('admin.applytournament.add')->with($data);
    }

    public function add(Request $request, $id = Null)
    {
        
        $decrypted_id  = get_decrypted_value($id, true);
        $data = Tournament::find($decrypted_id);
        $ids = Tournament::where('title',$data->title)->get();
        $user = User::where('status',1)->get();
        
        $saveurl = url('admin/apply-tournament-name_edit_save');
        $button = 'Update';
        $page_title = 'Update User Apply Tournament';
        
        $data = array(
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'tournaments' => $ids,
            'user'       => $user
        );
        return view('admin.applytournament.add_new')->with($data);
    }
    
    public function updateEditUid(Request $request)
{
    $ids = $request->input('ids');
    $editUid = $request->input('edit_uid', 1);

    if (!empty($ids)) {
        // Table का नाम बदलें अगर User / Registration टेबल अलग है
        DB::table('users')->whereIn('id', $ids)->update([
            'edit_uid' => $editUid
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selected users updated successfully!'
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'No records selected!'
    ], 400);
}
    public function add_save(Request $request, $id = NUll)
    {
        p($request->all());
        
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
                $data->save();
                
                $user = User::find($data->user_id);
                $user->weight = $request['actul_waight'];
                $user->save();
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->back()->withSuccess($success_msg);
    }
    public function edit_save(Request $request, $id = NUll)
    {
        // p($request->all());
        
            $decrypted_id  = get_decrypted_value($id, true);
            
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
                // p($tournaments['get_category']);
                if(!empty($id)){
                    $data          = ApplyTournament::find($decrypted_id);
                    $data->actul_waight       = $request['actul_waight'];
                    $data->waight_category        = $tournaments->weight_category;
                    $data->turnament_id = $request['tournament_id'];
                    $data->category = $tournaments['get_category']->title;
                    $data->save();
                    
                    $user = User::find($data->user_id);
                    $user->weight = $request['actul_waight'];
                    $user->save();
                }else{
                    $user = User::find($request['user']);
                    // p($user);
                    $turnament = Tournament::find($request['tournament_id']);

                    $apply = new ApplyTournament();
                    $apply->user_id = $request['user'];
                    $apply->turnament_id = $request['tournament_id'];
                    $apply->coach_id = $user->coach_id;
                    $apply->district = $user->district_name;
                    $apply->code = $user->code_id;
                    $apply->it_uid = $user->it_uid;
                    $apply->gender = $user->user_gender;
                    $apply->dob = $user->dob;
                    $apply->name = $user->name;
                    $apply->email = $user->email;
                    $apply->phone = $user->contact_number;
                    $apply->coach = $user->coach_name;
                    $apply->category = $tournaments['get_category']->title;
                    $apply->event = $turnament->event_category;
                    $apply->waight_category = $turnament->weight_category;
                    $apply->actul_waight = $request['actul_waight'];
                    $apply->save();

                    $user = User::find($apply->user_id);
                    $user->weight = $request['actul_waight'];
                    $user->save();
                }

                
                
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->back()->withSuccess($success_msg);
    }

    public function index()
    {
        $data = array(
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'ajax_url' => url('/admin/apply-tournament-data')
        );
        return view('admin.applytournament.view')->with($data);
    }

    public function district_index()
    {
        $data = array(
            'title' => 'View District Tournament',
            'page_title' => 'View District Tournament',
            'ajax_url' => url('/admin/district-apply-tournament-data'),
            'is_district' => true
        );
        return view('admin.applytournament.view')->with($data);
    }
    
    public function indexname($id)
    {
        // p($id);
        $data = array(
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'id' => $id
        );
        return view('admin.applytournament.viewname')->with($data);
    }
    
    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = Tournament::orderBy('id', 'DESC')->where('status', '<', 3)
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
        })->groupBy('title')->get();

        return Datatables::of($anydata)
        
            ->addColumn('action', function ($anydata) {
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/admin/apply-tournament-name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;  
                        
                        ';
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()->make(true);
    }

    public function district_anydata(Request $request)
    {
        $anydata = [];
        $anydata = Tournament::with('get_district')->orderBy('id', 'DESC')->where('status', '<', 3)
        ->where('is_district_tournament', 1)
        ->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }

            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })->groupBy('title')->get();

        return Datatables::of($anydata)
            ->addColumn('district_name', function ($anydata) {
                return isset($anydata['get_district']) ? $anydata['get_district']->title : 'N/A';
            })
            ->addColumn('action', function ($anydata) {
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/admin/apply-tournament-name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;  
                        
                        ';
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()->make(true);
    }

   public function anydataname(Request $request, $id)
{
    $decrypted_id = get_decrypted_value($id, true);
    $data = Tournament::find($decrypted_id);
    $ids = Tournament::where('title', $data->title)->pluck('id')->toArray();

    
    $anydata = ApplyTournament::with(['get_waight_cat', 'get_user'])
        ->whereIn('turnament_id', $ids)
        ->where('status', '<', 3)
        ->where(function ($query) use ($request) {
            if (!empty($request['title'])) {
                $query->where('name', 'LIKE', '%' . $request['title'] . '%');
            }

            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })
        ->orderBy('id', 'DESC')
        ->get();

    return Datatables::of($anydata)

        // User ID निकालना
        ->addColumn('user_id', function ($anydata) {
            return $anydata->user_id ?? (optional($anydata->get_user)->id ?? $anydata->id);
        })
        
         ->addColumn('it_uid', function ($anydata) {
            return $anydata->get_user->it_uid;
        })

        // get_user रिलेशनशिप से edit_uid फैच करना
        ->addColumn('edit_uid', function ($anydata) {
            return optional($anydata->get_user)->edit_uid ?? $anydata->edit_uid ?? 0;
        })

        ->addColumn('waight_category', function ($anydata) {
            return isset($anydata['get_waight_cat']->title) ? $anydata['get_waight_cat']->title : 'N/A';
        })
        
        ->addColumn('status', function ($anydata) {
            if ($anydata->status == 1) {
                $status = 2;
                return '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')" class="btn btn-success btn-rounded btn-sm waves-effect waves-light">Active</span>';
            } else {
                $status = 1;
                return '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light">Deactive</span>';
            }
        })

        ->addColumn('id_card', function ($anydata) {
            $encrypted_id = get_encrypted_value($anydata->id, true);
            return '<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="' . url('/admin/single_card/' . $encrypted_id) . '">Download</a>';
        })

        ->addColumn('form', function ($anydata) {
            $encrypted_id = get_encrypted_value($anydata->id, true);
            return '<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="' . url('/admin/reg_form_single/' . $encrypted_id) . '">Download</a>';
        })

        ->addColumn('action', function ($anydata) {
            $encrypted_id = get_encrypted_value($anydata->id, true);
            return '<a href="' . url('/admin/apply-tournament-name_edit/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;' .
                   '<i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
        })

        ->rawColumns(['status', 'action', 'waight_category', 'id_card', 'form'])
        ->addIndexColumn()
        ->make(true);
}

    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = ApplyTournament::find($id);
        if ($data) {
            // $data->status = 3;
            $data->delete();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Apply Tournament User Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }

    public function getByWeight(Request $request)
    {
        $weight = $request->weight;

        // Assuming your tournaments have min_weight and max_weight
        $current_date = Carbon::now()->format('Y-m-d');
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $age = Carbon::now()->year - Carbon::parse($user->dob)->year;
        // p($user->dob);
        $category = Category::where('min','<=',$age)->where('max','>=',$age)->where('status',1)->pluck('id')->toArray();
        
        // p($user->category);
        if (!$weight || !$user->gender || !$user->category) {
            return response()->json(['error' => 'Incomplete user profile'], 422);
        }
        
        // $waight_category = WeightCategory::
        //     where('min', '<=', $weight)
        //     ->where('max', '>', $weight)
        //     ->where('gender', $user->gender)
        //     ->whereIn('category', $category)
        //     ->where('status', 1)
        //     ->pluck('id')->toArray();
            
        $waight_category = WeightCategory::where(function ($query) use ($user,$weight) {
            $query->where('min', '<=', $weight)
                  ->where(function ($subQuery) use ($weight) {
                      $subQuery->where('max', '>=', $weight)
                               ->orWhere('max', 0); // Over category
                  })
                  ->orWhere(function ($subQuery) use ($weight) {
                      $subQuery->where('min', 0)
                               ->where('max', '>=', $weight); // Under category
                  });
        })
        ->where('gender', $user->gender)
        ->whereIn('category', $category)
        ->where('status', 1)
        ->pluck('id')->toArray();
        // p($category);

        if (!$waight_category) {
            return response()->json(['error' => 'Weight category not found'], 404);
        }

        $tournaments = Tournament::where('status', 1)
            ->where('gender', $user->gender)
            ->whereIn('category', $category)
            ->whereIn('weight_category', $waight_category)
            ->get();

        // p($tournaments);

        return response()->json($tournaments);
    }
}
