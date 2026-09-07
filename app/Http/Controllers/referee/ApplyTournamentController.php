<?php

namespace App\Http\Controllers\referee;

use App\Http\Controllers\Controller;
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
        
        $saveurl = url('referee/apply-tournament-name_edit_save/' . $id);
        $button = 'Update';
        $page_title = 'Update User Apply Tournament';
        
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'tournaments' => $tournaments
        );
        return view('referee.applytournament.add')->with($data);
    }
    public function edit_save(Request $request, $id = NUll)
    {
        
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
        );
        return view('referee.applytournament.view')->with($data);
    }
    
    public function indexname($id)
    {
        $data = array(
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'id' => $id
        );
        return view('referee.applytournament.viewname')->with($data);
    }
    
    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = Tournament::orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

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
                $action = '<a href="' . url('/referee/apply-tournament-name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;  
                        
                        ';
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()->make(true);
    }

    public function anydataname(Request $request,$id)
    {
        $referee= Auth::guard('referee')->user();
        $decrypted_id  = get_decrypted_value($id, true);
        $data = Tournament::find($decrypted_id);
        $ids = Tournament::where('title',$data->title)->pluck('id')->toArray();
        $anydata = [];
        $anydata = ApplyTournament::with('get_waight_cat')
        ->whereHas('get_user', function ($q) use ($referee) {
            $q->where('district',$referee->district);
        })
        ->whereIn('turnament_id',$ids)->orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }

            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })->get();
        // p($anydata);

        return Datatables::of($anydata)

        ->addColumn('waight_category', function ($anydata) {

            
            return isset($anydata['get_waight_cat']->title)?$anydata['get_waight_cat']->title:'N/A';
        })
        
            ->addColumn('status', function ($anydata) {

                if ($anydata->status == 1) {
                    $status = 2;
                    $statusval = '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')"  class="btn btn-success btn-rounded btn-sm waves-effect waves-light">Active</span>';
                } else {
                    $status = 1;
                    $statusval = '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light">Deactive</span>';
                }
                return $statusval;
            })

            ->addColumn('id_card', function ($anydata) {
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="' . url('/referee/single_card/' . $encrypted_id) . '">Download</a>';
                return $action;
            })
            ->addColumn('form', function ($anydata) {
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="' . url('/referee/reg_form_single/' . $encrypted_id) . '">Download</a> 
                        
                        ';
                return $action;
            })

            ->addColumn('action', function ($anydata) {
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = 'N/A';
                if($anydata->final == 0){
                    $action = '<a href="' . url('/referee/apply-tournament-name_edit/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;  
                        
                        <i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
                }
                
                return $action;
            })
            ->rawColumns(['status', 'action', 'image', 'event', 'get_waight_cat', 'category','waight_category', 'id_card', 'form'])
            ->addIndexColumn()->make(true);
    }

    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = ApplyTournament::find($id);
        // p($data);
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
        // p(1);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        $referee = auth()->guard('vender')->user();
        $user = User::find($request['user_id']);
        $turnament = Tournament::with('get_category')->find($request['tournament_id']);
        // p($turnament);
        $apply = ApplyTournament::where('user_id',$request['user_id'])->where('turnament_id',$request['tournament_id'])->first();
        if(!empty($apply)){
            return response()->json(['status' => 'failed']);
        }
        $apply = new ApplyTournament();
        $apply->user_id = $request['user_id'];
        $apply->turnament_id = $request['tournament_id'];
        $apply->referee_id = $referee->id;
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
        try {
            // $weight = $request->weight;
            $current_date = Carbon::now()->format('Y-m-d');
            $user = User::find($request->user_id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            // p($user->weight);
            if (!$user->weight || !$user->gender || !$user->category) {
                return response()->json(['error' => 'Incomplete user profile'], 422);
            }
            
            $age = Carbon::now()->year - Carbon::parse($user->dob)->year;
            
           
            
             $category = Category::where('min','<=',$age)->where('max','>=',$age)->where('status',1)->pluck('id')->toArray();
        
        // p($weight);
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
        ->whereIn('category', $category)
        ->where('status', 1)
        ->pluck('id')->toArray();
            // p($waight_category);
    
            if (!$waight_category) {
                return response()->json(['error' => 'Weight category not found'], 404);
            }
    
            $tournaments = Tournament::with('get_waight_cat')->where('status', 1)
                ->whereDate('start_date', '<=', $current_date)
                ->whereDate('end_date', '>=', $current_date)
                ->where('gender', $user->gender)
                ->whereIn('category', $category)
                // ->whereHas('get_waight_cat',function ($query) use ())
                ->whereIn('weight_category', $waight_category)
                ->get();
                
            // p($tournaments);

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

        ApplyTournament::whereIn('id', $ids)->update(['final' => 1]); // or whatever status you need

        return response()->json(['success' => true]);
    }


}
