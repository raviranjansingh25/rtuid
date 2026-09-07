<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Tags;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class SubadminController extends Controller
{
    private function normalizeCountryCode($code)
    {
        $digits = preg_replace('/\D/', '', (string) $code);

        return $digits !== '' ? (int) $digits : 91;
    }

    private function normalizePhoneNumber($phone, $countryCode = 91)
    {
        $digits = preg_replace('/\D/', '', (string) $phone);

        if ($digits === '') {
            return '';
        }

        $countryCode = (string) $this->normalizeCountryCode($countryCode);

        while ($countryCode !== '' && str_starts_with($digits, $countryCode)) {
            $digits = substr($digits, strlen($countryCode));
        }

        return ltrim($digits, '0');
    }

    private function formatPhoneDisplay($phone, $countryCode = null)
    {
        $countryCode = $this->normalizeCountryCode($countryCode);
        $nationalNumber = $this->normalizePhoneNumber($phone, $countryCode);

        return '+' . $countryCode . ' ' . $nationalNumber;
    }

    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Admin::find($decrypted_id);
        $dist = Tags::where('status',1)->get();
        if ($id != "") {
            $saveurl = url('admin/subadmin/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Sub Admin';
        } else {
            $saveurl = url('admin/subadmin/save');
            $button = 'Add';
            $page_title = 'Add Sub Admin';
        }

        $phoneDisplay = '';
        $dialCode = 91;
        if (!empty($getdata)) {
            $dialCode = $this->normalizeCountryCode($getdata->country_code);
            $phoneDisplay = $this->normalizePhoneNumber($getdata->phone, $dialCode);
        }

        $selectedDistrict = old('district', isset($getdata->district) ? $getdata->district : '');
        if ($selectedDistrict !== 'all' && !empty($getdata) && !empty($getdata->select_all_district)) {
            $selectedDistrict = 'all';
        }

        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'dist'       => $dist,
            'phoneDisplay' => $phoneDisplay,
            'dialCode'   => $dialCode,
            'selectedDistrict' => $selectedDistrict,
        );
        return view('admin.subadmin.add')->with($data);
    }
    public function save(Request $request, $id = NUll)
    {
        // p((int)$request['country_code']);
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = Admin::find($decrypted_id);
            $success_msg   = 'Sub Admin Updated Successfully.';

            $validatior = Validator::make($request->all(), [
                'phone' => 'required|unique:admins,phone,' . $decrypted_id . ',id,status,1',
                'email' => 'required|unique:admins,email,' . $decrypted_id . ',id,status,1',
            ]);
        } else {
            $data          = new Admin;
            $success_msg   = 'Sub Admin Added Successfully.';

            $validatior = Validator::make($request->all(), [
                'phone' => 'required|unique:admins,phone,3,status',
                'password' => 'required',
                'confirm_password' => 'required|same:password'
            ], [
                'confirm_password.same' => 'The password and confirm password are not the same.',
            ]);
        }



        if ($validatior->fails()) {
            return back()->withInput()->withErrors($validatior);
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
                    $request->file('image')->move("uploads/subadmin", $name);
                    $data->image = 'uploads/subadmin/' . $name;
                }
                $data->name       = $request['name'];
                $data->middle_name = null;
                $data->last_name   = null;
                $data->email      = $request['email'];
                $countryCode = $this->normalizeCountryCode($request['country_code']);
                $data->country_code = $countryCode;
                $data->phone = $this->normalizePhoneNumber($request['phone'], $countryCode);
                $data->district               = $request['district'];
                if ($request['district'] === 'all') {
                    $data->select_all_district = 1;
                } else {
                    // Specific district selected → never treat as all-districts
                    $data->select_all_district = 0;
                }
                $data->add_weight             = (int) $request->input('add_weight', 0);
                $data->can_apply              = (int) $request->input('can_apply', 0);
                $data->athlete_detail         = (int) $request->input('athlete_detail', 0);
                $data->athlete_edit           = (int) $request->input('athlete_edit', 0);
                $data->apply_tournament       = (int) $request->input('apply_tournament', 0);
                $data->can_apply_tournament   = (int) $request->input('apply_tournament', 0);
                $data->can_coach              = (int) $request->input('can_coach', 0);
                $data->can_referee            = (int) $request->input('can_referee', 0);
                $data->can_draw_sheet         = (int) $request->input('can_draw_sheet', 0);
                $data->can_create_tournament  = (int) $request->input('can_create_tournament', 0);
                if ($request['password'] != "") {
                    $data->password      = Hash::make($request['password']);
                } elseif ($request['e_password'] != "") {
                    $data->password      = Hash::make($request['e_password']);
                }
                $data->role         = 2;
                $data->save();
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('subadmin')->withSuccess($success_msg);
    }

    public function index()
    {
        $data = array(
            'title' => 'View Sub Admin',
            'page_title' => 'View Sub Admin',
        );
        return view('admin.subadmin.view')->with($data);
    }

    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = Admin::where('id', '!=', 1)->orderBy('id', 'DESC')->where('status', '<', 3)->where(function ($query) use ($request) {

            if (!empty($request['name'])) {
                $query->where('name', 'LIKE', '%' . $request['name'] . '%');
            }
            if (!empty($request['phone'])) {
                $query->where('phone', $request['phone']);
            }
            if (!empty($request['email'])) {
                $query->where('email', $request['email']);
            }
            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })->orderBy('id','DESC')->get();

        return Datatables::of($anydata)

            ->addColumn('phone', function ($anydata) {
                return $this->formatPhoneDisplay($anydata->phone, $anydata->country_code);
            })

            ->addColumn('district_name', function ($anydata) {
    $districtId = (string) ($anydata->district ?? '');

    if ($districtId === 'all') {
        return 'All';
    }

    if ($districtId === '') {
        return 'N/A';
    }

    $district = Tags::find($districtId);

    return $district ? $district->title : 'N/A';
})

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
                $file_name = "Admin";
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '<a href="' . url('/admin/subadmin/add/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a> &nbsp;&nbsp;  
                        
                        <i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" title="Delete" data-toggle="tooltip" data-placement="bottom"></i>';
                return $action;
            })
            ->rawColumns(['status', 'action', 'image', 'type_data', 'phone', 'district_name'])
            ->addIndexColumn()->make(true);
    }
    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = Admin::find($id);
        if ($data) {
            $data->status = 3;
            $data->save();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Admin Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }


    public function changeStatus(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  Admin::find($id);
        if ($data) {
            $data->status = $status;
            $data->save();
            echo "Success";
        }
    }
}
