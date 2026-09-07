<?php

namespace App\Http\Controllers\other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ApplyTournament;
use App\Models\Tournament;
use App\Models\User;
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
        
        $waight_category = WeightCategory::where('min', '<=', $user->weight)
            ->where('max', '>=', $user->weight)
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
        return view('other.applytournament.add')->with($data);
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
        return view('other.applytournament.view')->with($data);
    }
    
    public function indexname($id)
    {
        $data = array(
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'id' => $id
        );
        return view('other.applytournament.viewname')->with($data);
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
                $action = '<a href="' . url('/shoper/apply-tournament-name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;  
                        
                        ';
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()->make(true);
    }

    public function anydataname(Request $request,$id)
    {
        $decrypted_id  = get_decrypted_value($id, true);
        $data = Tournament::find($decrypted_id);
        $ids = Tournament::where('title',$data->title)->pluck('id')->toArray();
        $anydata = [];
        $anydata = ApplyTournament::with('get_waight_cat')->whereIn('turnament_id',$ids)->orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

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
                $action = '<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="' . url('/shoper/single_card/' . $encrypted_id) . '">Download</a>';
                return $action;
            })
            ->addColumn('form', function ($anydata) {
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="' . url('/shoper/reg_form_single/' . $encrypted_id) . '">Download</a> 
                        
                        ';
                return $action;
            })
            ->rawColumns(['status', 'id_card', 'form', 'image', 'event', 'get_waight_cat', 'category','waight_category'])
            ->addIndexColumn()->make(true);
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
}
