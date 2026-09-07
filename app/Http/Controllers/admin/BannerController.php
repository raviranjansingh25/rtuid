<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use auth;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
{
    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Banner::find($decrypted_id);
        if ($id != "") {
            $saveurl = url('admin/banner/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Banner';
        } else {
            $saveurl = url('admin/banner/save');
            $button = 'Add';
            $page_title = 'Add Banner';
        }
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
        );
        return view('admin.banner.add')->with($data);
    }
    public function save(Request $request, $id = NUll)
    {
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = Banner::find($decrypted_id);
            $success_msg   = 'Category Updated Successfully.';
            $nameValidator = 'required|unique:categories,title,' . $decrypted_id . ',id,status,1';
        } else {
            $data          = new Banner;
            $success_msg   = 'Category Added Successfully.';
            $nameValidator = 'required|unique:categories,title,3,status';
        }
        $Validatior = Validator::make($request->all(), [
            'title' => $nameValidator,

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
                    $extensions = ['jpg', 'jpeg', 'png', 'JPEG', 'PNG', 'JPG',];
                    if (!in_array($ext, $extensions)) {
                        $status = 'File type is not allowed you have uploaded. Please upload any image !';
                        return back()->withInput()->withErrors($status);
                    }
                    $request->file('image')->move("uploads/banner", $name);
                    $data->image = 'uploads/banner/' . $name;
                }
                $data->title       = $request['title'];
                $data->slug        = Str::slug($request['title']);
                $data->description = $request['description'];
                $data->save();
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('banner')->withSuccess($success_msg);
    }

    public function index()
    {
        $data = array(
            'title' => 'View Banner',
            'page_title' => 'View Banner',
        );
        return view('admin.banner.view')->with($data);
    }

    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = Banner::orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
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
                $admin_id = Auth::guard('admin')->user()->id;
                if($admin_id == 1){
                $file_name = "category";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/admin/banner/add/' . $encrypted_id) . '" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fas fa-edit"></i></a> &nbsp;&nbsp;  
                        
                        <i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" data-toggle="tooltip" data-placement="bottom" title="Delete"></i>';
                return $action;
                }else{
                return 'N/A';
                }
            })
            ->rawColumns(['status', 'action', 'image'])
            ->addIndexColumn()->make(true);
    }
    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = Banner::find($id);
        if ($data) {
            $data->status = 3;
            $data->save();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Banner Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }


    public function changeStatus(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  Banner::find($id);
        if ($data) {
            $data->status = $status;
            $data->save();
            echo "Success";
        }
    }
}
