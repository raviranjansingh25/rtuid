<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Category::find($decrypted_id);
        if ($id != "") {
            $saveurl = url('admin/category/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Category';
        } else {
            $saveurl = url('admin/category/save');
            $button = 'Add';
            $page_title = 'Add Category';
        }
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
        );
        return view('admin.category.add')->with($data);
    }

    public function save(Request $request, $id = NUll)
    {
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = Category::find($decrypted_id);
            $success_msg   = 'Category Updated Successfully.';
            $nameValidator = 'required|unique:categories,title,' . $decrypted_id . ',id,status,1';
        } else {
            $data          = new Category;
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
                    $request->file('image')->move("uploads/category", $name);
                    $data->image = 'uploads/category/' . $name;
                }
                if ($request['banner_image'] != "") {
                    $file1 = $request->file('banner_image');
                    $name1 = rand(11111, 99999) . '.' . $file1->getClientOriginalExtension();
                    $request->file('banner_image')->move("uploads/category", $name1);
                    $data->banner_image = 'uploads/category/' . $name1;
                }

                if ($request['icon'] != "") {
                    $file2 = $request->file('icon');
                    $name2 = rand(11111, 99999) . '.' . $file2->getClientOriginalExtension();
                    $request->file('icon')->move("uploads/category", $name2);
                    $data->icon = 'uploads/category/' . $name2;
                }
                $data->title              = $request['title'];
                $data->min        = $request['min'];
                $data->max        = $request['max'];
                $data->search_title       = $request['search_title'];
                $data->description        = $request['editor1'];

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
        return redirect()->route('category')->withSuccess($success_msg);
    }

    public function index()
    {
        $data = array(
            'title' => 'View Category',
            'page_title' => 'View Category',
        );
        return view('admin.category.view')->with($data);
    }

    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = Category::orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

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

            ->addColumn('doctor_count', function ($anydata) {
                $doc_count = UserDetail::where('category', $anydata->id)->count();
                return $doc_count;
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
            ->rawColumns(['status', 'action', 'image', 'doctor_count'])
            ->addIndexColumn()->make(true);
    }

    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = Category::find($id);
        if ($data) {
            $data->status = 3;
            $data->save();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Category Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }

    public function changeStatus(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  Category::find($id);
        if ($data) {
            $data->status = $status;
            $data->save();
            echo "Success";
        }
    }
}
