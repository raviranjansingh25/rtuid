<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shoper;
use App\Models\Tags;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class ShoperController extends Controller
{
    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Shoper::find($decrypted_id);
        $dist = Tags::where('status',1)->get();
        if ($id != "") {
            $saveurl = url('admin/shoper/save/' . $id);
            $button = 'Update';
            $page_title = 'Update shoper';
        } else {
            $saveurl = url('admin/shoper/save');
            $button = 'Add';
            $page_title = 'Add shoper';
        }
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'dist'       => $dist
        );
        return view('admin.shoper.add')->with($data);
    }

    public function save(Request $request, $id = NUll)
    {
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = Shoper::find($decrypted_id);
            $success_msg   = 'shoper Updated Successfully.';
            $nameValidator = 'required|unique:shopers,name,' . $decrypted_id . ',id,status,1';
        } else {
            $data          = new Shoper;
            $success_msg   = 'shoper Added Successfully.';
            $nameValidator = 'required|unique:shopers,name,3,status';
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
                    $request->file('image')->move("uploads/coach", $name);
                    $data->image = 'uploads/coach/' . $name;
                }
                
                $data->name              = $request['name'];
                $data->email       = $request['email'];
                $data->phone        = $request['phone'];
                // $data->district               = $request['district'];
                if ($request['password'] != "") {
                    $data->password      = Hash::make($request['password']);
                } elseif ($request['e_password'] != "") {
                    $data->password      = Hash::make($request['e_password']);
                }
                $data->save();
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('shoper')->withSuccess($success_msg);
    }

    public function index()
    {
        $data = array(
            'title' => 'View Shoper',
            'page_title' => 'View Shoper',
        );
        return view('admin.shoper.view')->with($data);
    }

    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = Shoper::orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('name', 'LIKE', '%' . $request['title'] . '%');
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
                $action = '<a href="' . url('/admin/shoper/add/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;  
                        
                        <i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
                return $action;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->addIndexColumn()->make(true);
    }

    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = Shoper::find($id);
        if ($data) {
            $data->status = 3;
            $data->save();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Shoper Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }

    public function changeStatus(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  Shoper::find($id);
        if ($data) {
            $data->status = $status;
            $data->save();
            echo "Success";
        }
    }
}
