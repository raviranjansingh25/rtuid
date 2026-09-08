<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\WeightCategory;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ApplyTournament;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class TournamentController extends Controller
{
    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Tournament::find($decrypted_id);
        $event = Event::where('status',1)->get();
        $event_cat = EventCategory::where('status',1)->get();
        $category = Category::where('status',1)->get();
        $weightcategory = WeightCategory::where('status',1)->get();
        if ($id != "") {
            $saveurl = url('admin/tournament/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Tournament';
        } else {
            $saveurl = url('admin/tournament/save');
            $button = 'Add';
            $page_title = 'Add Tournament';
        }
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'event'      => $event,
            'event_cat'  =>$event_cat,
            'category'   =>$category,
            'weight_category' => $weightcategory,
        );
        return view('admin.tournament.add')->with($data);
    }
    
    public function edit(Request $request, $id)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Tournament::find($decrypted_id);
        $saveurl = url('admin/tournament_edit/save/' . $id);
        $page_title = 'Update Tournament';
        
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            // 'button'     => $button,
            'title'      => $page_title,
        );
        return view('admin.tournament.edit')->with($data);
    }
    
    public function editsave(Request $request, $id = NUll)
    {
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = Tournament::find($decrypted_id);
            $success_msg   = 'Tournament Updated Successfully.';
            $nameValidator = 'required';
        } else {
            
            $success_msg   = 'Tournament Added Successfully.';
            $nameValidator = 'required';
        }
        $Validatior = Validator::make($request->all(), [
            'title' => $nameValidator,

        ]);

        if ($Validatior->fails()) {
            return back()->withInput()->withErrors($Validatior);
        } else {

            DB::beginTransaction();
            try {
                
                $currentDate = Carbon::now()->format('Y-m-d');
                $data = Tournament::where('title',$data->title)->get();
                foreach($data as $gender){
                    $gender->title              = $request['title'];
                    $gender->start_date         = $request['start_date'];
                    $gender->end_date           = $request['end_date'];
                    $gender->save();
                    
                    if ($gender->end_date >= $currentDate) {
                        // p(1);
                        // Update all ApplyTournament entries with matching tournament_id
                        ApplyTournament::where('turnament_id', $gender->id)->update(['final' => 0]);
                    }
                    
                }
                       
                
                
                
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('tournament')->withSuccess($success_msg);
    }

    public function save(Request $request, $id = NUll)
    {
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = Tournament::find($decrypted_id);
            $success_msg   = 'Tournament Updated Successfully.';
            $nameValidator = 'required';
        } else {
            
            $success_msg   = 'Event Added Successfully.';
            $nameValidator = 'required';
        }
        $Validatior = Validator::make($request->all(), [
            'title' => $nameValidator,

        ]);

        if ($Validatior->fails()) {
            return back()->withInput()->withErrors($Validatior);
        } else {

            DB::beginTransaction();
            try {
                
                
                
                foreach($request['gender'] as $gender){
                    foreach($request['event'] as $event){
                        if($event == 2){
                            $event_cat = EventCategory::where('status',1)->get();
                            foreach ($event_cat as $eve){
                                
                                    $data                     = new Tournament;
                                    $data->title              = $request['title'];
                                    $data->event              = $event;
                                    $data->gender             = $gender;
                                    $data->category           = $waight_cat->category;
                                    $data->event_category     = $eve->id;
                                    $data->start_date         = $request['start_date'];
                                    $data->end_date           = $request['end_date'];
                                    $data->save();
                                
                            }
                        }else{
                            // p($weight);
                            $weight = WeightCategory::whereIn('category',$request['category'])->where('gender',$gender)->get();
                            
                             foreach ($weight as $waight_cat){
                                $data          = new Tournament;
                                $data->title              = $request['title'];
                                $data->event              = $event;
                                $data->gender             = $gender;
                                $data->category           = $waight_cat->category;
                                $data->weight_category    = $waight_cat->id;
                                $data->start_date         = $request['start_date'];
                                $data->end_date           = $request['end_date'];
                                $data->save();
                            }
                        }
                    }
                   
                }
                
                
                
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('tournament')->withSuccess($success_msg);
    }

   
    
    public function index()
    {
        $data = array(
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'ajax_url' => url('/admin/tournament-data')
        );
        return view('admin.tournament.view')->with($data);
    }
    
    public function district_index()
    {
        $data = array(
            'title' => 'View District Tournament',
            'page_title' => 'View District Tournament',
            'ajax_url' => url('/admin/district-tournament-data'),
            'is_district' => true
        );
        return view('admin.tournament.view')->with($data);
    }
    
    public function indexname($id)
    {
        $data = array(
            'title' => 'View Tournament',
            'page_title' => 'View Tournament',
            'id' => $id
        );
        return view('admin.tournament.viewname')->with($data);
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
                $action = '<a href="' . url('/admin/tournament_name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;<a href="' . url('/admin/tournament/edit/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a>  
                        
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
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/admin/tournament_name/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View"></i></a>&nbsp;&nbsp;<a href="' . url('/admin/tournament/edit/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a>';
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()->make(true);
    }

    public function anydataname(Request $request,$id)
    {
        $decrypted_id  = get_decrypted_value($id, true);
        $data = Tournament::find($decrypted_id);
        $anydata = [];
        $anydata = Tournament::where('title',$data->title)->with('get_event','get_waight_cat','get_category')->orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }

            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })->get();
        // p($anydata);

        return Datatables::of($anydata)
        
             ->addColumn('category', function ($anydata) {
                return $anydata['get_category']->title;
            })
            
            ->addColumn('gender_name', function ($anydata) {
                if($anydata->gender == 1){
                    $gen = 'Male';
                }elseif($anydata->gender == 2){
                    $gen = 'Female';
                }else{
                    $gen = 'Other';
                }
                return $gen;
            })

            ->addColumn('event', function ($anydata) {
                return $anydata['get_event']->title;
            })
            
            
            
            ->addColumn('get_waight_cat', function ($anydata) {
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

            ->addColumn('action', function ($anydata) {
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/admin/tournament/add/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;  
                        
                        <i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
                return $action;
            })
            ->rawColumns(['status', 'action', 'image', 'event', 'get_waight_cat', 'category'])
            ->addIndexColumn()->make(true);
    }

    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = Tournament::find($id);
        if ($data) {
            $data->status = 3;
            $data->save();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Tournament Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }

    public function changeStatus(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  Tournament::find($id);
        if ($data) {
            $data->status = $status;
            $data->save();
            echo "Success";
        }
    }
}
