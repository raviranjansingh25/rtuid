<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\WeightCategory;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class WeightCategoryController extends Controller
{
    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = WeightCategory::find($decrypted_id);
        $category = Category::where('status',1)->get();
        if ($id != "") {
            $saveurl = url('admin/weightcategory/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Weight Category';
        } else {
            $saveurl = url('admin/weightcategory/save');
            $button = 'Add';
            $page_title = 'Add Weight Category';
        }
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'category'   => $category
        );
        return view('admin.weightcategory.add')->with($data);
    }

    public function save(Request $request, $id = NUll)
    {
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = WeightCategory::find($decrypted_id);
            $success_msg   = 'Weight Category Updated Successfully.';
            $nameValidator = 'required';
        } else {
            $data          = new WeightCategory;
            $success_msg   = 'Weight Category Added Successfully.';
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
                
                $data->title              = $request['title'];
                $data->category       = $request['category'];
                $data->gender        = $request['gender'];
                $data->min        = $request['min'];
                $data->max        = $request['max'];

                
                $data->save();
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('weightcategory')->withSuccess($success_msg);
    }

    public function index()
    {
        $data = array(
            'title' => 'View Weight Category',
            'page_title' => 'View Weight Category',
        );
        return view('admin.weightcategory.view')->with($data);
    }

    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = WeightCategory::orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('title', 'LIKE', '%' . $request['title'] . '%');
            }

            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })->get();

        return Datatables::of($anydata)


            ->addColumn('gender', function ($anydata) {
                if($anydata->gender == 1){
                    $text = 'Male';
                }else if($anydata->gender == 2){
                    $text = 'Female';
                }else{
                    $text = 'Other';
                }
                return $text;
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
                $action = '<a href="' . url('/admin/weightcategory/add/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;  
                        
                        <i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
                return $action;
            })
            ->rawColumns(['status', 'action', 'image', 'gender'])
            ->addIndexColumn()->make(true);
    }

    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = WeightCategory::find($id);
        if ($data) {
            $data->status = 3;
            $data->save();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Weight Category Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }

    public function changeStatus(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  WeightCategory::find($id);
        if ($data) {
            $data->status = $status;
            $data->save();
            echo "Success";
        }
    }
}
