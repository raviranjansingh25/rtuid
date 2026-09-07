<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class EventCategoryController extends Controller
{
    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = EventCategory::find($decrypted_id);
        $event = Event::where('status',1)->get();
        if ($id != "") {
            $saveurl = url('admin/event-category/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Event Category';
        } else {
            $saveurl = url('admin/event-category/save');
            $button = 'Add';
            $page_title = 'Add Event Category';
        }
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'event'      => $event
        );
        return view('admin.eventcategory.add')->with($data);
    }

    public function save(Request $request, $id = NUll)
    {
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = EventCategory::find($decrypted_id);
            $success_msg   = 'Event Category Updated Successfully.';
            $nameValidator = 'required|unique:event_categories,title,' . $decrypted_id . ',id,status,1';
        } else {
            $data          = new EventCategory;
            $success_msg   = 'Event Added Successfully.';
            $nameValidator = 'required|unique:event_categories,title,3,status';
        }
        $Validatior = Validator::make($request->all(), [
            'title' => $nameValidator,

        ]);

        if ($Validatior->fails()) {
            return back()->withInput()->withErrors($Validatior);
        } else {

            DB::beginTransaction();
            try {
                
                $data->title              = $request['title'];
                $data->event              = $request['event'];
                $data->slug               = Str::slug($request['title']);
                $data->save();
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('event-category')->withSuccess($success_msg);
    }

    public function index()
    {
        $data = array(
            'title' => 'View Event Category',
            'page_title' => 'View Event Category',
        );
        return view('admin.eventcategory.view')->with($data);
    }

    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = EventCategory::with('get_event')->orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }

            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })->get();

        return Datatables::of($anydata)

            ->addColumn('event', function ($anydata) {
                return $anydata['get_event']->title;
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
                $action = '<a href="' . url('/admin/category/add/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;  
                        
                        <i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
                return $action;
            })
            ->rawColumns(['status', 'action', 'image', 'event'])
            ->addIndexColumn()->make(true);
    }

    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = EventCategory::find($id);
        if ($data) {
            $data->status = 3;
            $data->save();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Event Category Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }

    public function changeStatus(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  EventCategory::find($id);
        if ($data) {
            $data->status = $status;
            $data->save();
            echo "Success";
        }
    }
}
