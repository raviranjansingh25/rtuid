<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class TestimonialController extends Controller
{
    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Testimonial::find($decrypted_id);
        if ($id != "") {
            $saveurl = url('admin/testimonial/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Testimonial';
        } else {
            $saveurl = url('admin/testimonial/save');
            $button = 'Add';
            $page_title = 'Add Testimonial';
        }
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
        );
        return view('admin.testimonial.add')->with($data);
    }
    public function save(Request $request, $id = NUll)
    {
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = Testimonial::find($decrypted_id);
            $success_msg   = 'Testimonial Updated Successfully.';
            $nameValidator = 'required';
        } else {
            $data          = new Testimonial;
            $success_msg   = 'Testimonial Added Successfully.';
            $nameValidator = 'required';
        }
        $Validatior = Validator::make($request->all(), [
            'name' => $nameValidator,

        ]);

        if ($Validatior->fails()) {
            return back()->withInput()->withErrors($Validatior);
        } else {

            DB::beginTransaction();
            try {
                if ($request['image'] != "") {
                    $file = $request->file('image');
                    $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    // $extensions = ['jpg', 'jpeg', 'png', 'JPEG', 'PNG', 'JPG',];
                    // if (!in_array($ext, $extensions)) {
                    //     $status = 'File type is not allowed you have uploaded. Please upload any image !';
                    //     return back()->withInput()->withErrors($status);
                    // }
                    $request->file('image')->move("uploads/category", $name);
                    $data->image = 'uploads/category/' . $name;
                }
                $data->name       = $request['name'];
                $data->designation = $request['designation'];
                $data->message = $request['message'];
                $data->ratting = $request['ratting'];
                $data->save();
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('testimonial')->withSuccess($success_msg);
    }

    public function index()
    {
        $data = array(
            'title' => 'View Testimonial',
            'page_title' => 'View Testimonial',
        );
        return view('admin.testimonial.view')->with($data);
    }

    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = Testimonial::orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }
            if (!empty($request['type'])) {
                $query->where('category_type', $request['type']);
            }
            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })->get();

        return Datatables::of($anydata)



            ->addColumn('image', function ($anydata) {
                if ($anydata['image'] != "" || file_exists($anydata['image'])) {
                    $img = url($anydata['image']);
                } else {
                    $img = url('public/noimage.png');
                }
                return '<img style="border-radius: 50%;" alt="image" src=' . $img . ' width="35" height="35px">';
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
                $action = '<a href="' . url('/admin/testimonial/add/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;  
                        
                        <i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
                return $action;
            })
            ->rawColumns(['status', 'action', 'image', 'type_data'])
            ->addIndexColumn()->make(true);
    }
    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = Testimonial::find($id);
        if ($data) {
            $data->status = 3;
            $data->save();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Testimonial Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }


    public function changeStatus(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  Testimonial::find($id);
        if ($data) {
            $data->status = $status;
            $data->save();
            echo "Success";
        }
    }
}
