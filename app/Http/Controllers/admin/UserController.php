<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;;
use App\Models\Tags;
use App\Models\Coach;
use App\Models\Category;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use Auth;
use File;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = User::find($decrypted_id);
         $dist = Tags::where('status',1)->get();
        $coaches = Coach::where('status',1)->get();
        $category = Category::where('status',1)->get();
        if ($id != "") {
            $saveurl = url('admin/user/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Athlete';
        } else {
            $saveurl = url('admin/user/save');
            $button = 'Add';
            $page_title = 'Add Athlete';
        }
        $data = array(
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'dist'      => $dist,
            'coaches'   => $coaches,
            'category'  => $category
        );
        return view('admin.user.add')->with($data);
    }
    public function save(Request $request, $id = NUll)
    {
        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = User::find($decrypted_id);
            $success_msg   = 'Athlete Updated Successfully.';
            $nameValidator = 'required|unique:users,mobile,' . $decrypted_id . ',id,status,1';
            $Validatior = Validator::make($request->all(), [
                // 'phone' => $nameValidator,
            ]);
        } else {
            $data          = new User;
            $success_msg   = 'Athlete Added Successfully.';
            $nameValidator = 'required|unique:users';
            $Validatior = Validator::make($request->all(), [
                'contact_number' => $nameValidator,
                'password'              => 'required',
                'confirm_password' => 'required|same:password'
            ], [
                'confirm_password.same' => 'The password and confirm password are not the same.',
            ]);
        }

        if ($Validatior->fails()) {
            return back()->withInput()->withErrors($Validatior);
        } else {

            DB::beginTransaction();
            try {
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/user/', $name);
                    $data->photo = 'uploads/user/' . $name;
                }
                
                if ($request->hasFile('aadhar_front')) {
                    $file = $request->file('aadhar_front');
                    $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/user/aadhar_front/', $name);
                    $data->aadhar_front = 'uploads/user/aadhar_front/' . $name;
                }
                
                if ($request->hasFile('aadhar_back')) {
                    $file = $request->file('aadhar_back');
                    $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/user/aadhar_back/', $name);
                    $data->aadhar_back = 'uploads/user/aadhar_back/' . $name;
                }
                
                if ($request->hasFile('dob_certificate')) {
                    $file = $request->file('dob_certificate');
                    $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/user/dob_certificate/', $name);
                    $data->dob_certificate = 'uploads/user/dob_certificate/' . $name;
                }
                
                if ($request->hasFile('belt_certificate')) {
                    $file = $request->file('belt_certificate');
                    $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/user/belt_certificate/', $name);
                    $data->belt_certificate = 'uploads/user/belt_certificate/' . $name;
                }
                
                if ($request->hasFile('signature')) {
                    $file = $request->file('signature');
                    $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/user/signature/', $name);
                    $data->signature = 'uploads/user/signature/' . $name;
                }


                
            
                $data->name = $request->name;
                $data->it_uid = $request->it_uid;
                $data->father_name = $request->father_name;
                $data->dob = $request->dob;
                $data->contact_number = $request->contact_number;
                $data->whatsapp_number = $request->whatsapp_number;
                $data->email = $request->email;
                $data->it_uid = $request->it_uid;
                if ($request['password'] != "") {
                    $data->password      = Hash::make($request['password']);
                } elseif ($request['e_password'] != "") {
                    $data->password      = Hash::make($request['e_password']);
                }
                $data->gender = $request->gender;
                $data->category = $request->category;
                $data->state = $request->state;
                $data->district = $request->district;
                $data->city = $request->city;
                $data->coach_id = $request->coach_id;
                $data->pin_code = $request->pin_code;
                $data->address = $request->address;
                $data->aadhar_number = $request->aadhar_number;
                $data->coach_name = $request->coach_name;
                $data->coach_contact = $request->coach_contact;
                $data->save();
                
                $tag = Tags::find($data->district);
                $data->code_id = 'RTUID/' . $tag->short_code . '/' . (100 + $data->id);
                $data->save();

                // if (empty($id)) {
                //     $tag = Tags::find($data->district);
                //     $data->code_id = 'RTUID/' . $tag->short_code . '/' . (100 + $data->id);
                //     $data->save();
                // }
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('user')->withSuccess($success_msg);
    }

    private function storeBase64($imageBase64)
    {
        list($type, $imageBase64) = explode(';', $imageBase64);
        list(, $imageBase64)      = explode(',', $imageBase64);
        $imageBase64 = base64_decode($imageBase64);
        $imageName = rand(11111, 99999) . '.png';
        $path = 'uploads/user/profile/' . $imageName;


        file_put_contents($path, $imageBase64);

        return $imageName;
    }
    public function index()
    {
        $data = array(
            'title' => 'View Athlete',
            'page_title' => 'View Athlete',
        );
        return view('admin.user.view')->with($data);
    }

public function anydata(Request $request)
{
    $admin = Auth::guard('admin')->user();

    $query = User::query()
        ->where('status', '!=', 3)
        ->when($admin->id != 1, function ($q) use ($admin) {
            $q->where('district', $admin->district);
        })
        ->when(!empty($request->title), function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->title . '%');
        })
        ->when(!empty($request->status), function ($q) use ($request) {
            $q->where('status', $request->status);
        })
        ->orderBy('id','DESC');

    return DataTables::eloquent($query)

        ->addColumn('checkbox', function ($anydata) {
            return '<input type="checkbox" class="row_checkbox" value="' . $anydata->id . '">';
        })

        ->addColumn('profile', function ($anydata) {

            $img = !empty($anydata->profile) && file_exists(public_path($anydata->profile))
                ? url($anydata->profile)
                : url('public/noimage.png');

            return '<img style="border-radius:50%;" src="' . $img . '" width="35" height="35">';
        })

        ->editColumn('name', function ($anydata) {
            return trim($anydata->name . ' ' . $anydata->last_name);
        })

        ->editColumn('contact_number', function ($anydata) {
            return isset($anydata->country_code)
                ? '+' . $anydata->country_code . ' ' . $anydata->contact_number
                : $anydata->contact_number;
        })

        ->addColumn('coach_act', function ($anydata) {

            $text = '';
            $color = '';

            if ($anydata->coach_act == 1) {
                $text = 'Pending';
                $color = 'danger';
            } elseif ($anydata->coach_act == 2) {
                $text = 'Active';
                $color = 'primary';
            } elseif ($anydata->coach_act == 3) {
                $text = 'Updated';
                $color = 'success';
            }

            return '
            <div class="btn-group">
                <button class="btn btn-' . $color . ' dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                    ' . $text . '
                </button>

                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" onclick="coach_act(' . $anydata->id . ',1)">Pending</a>
                    <a class="dropdown-item" href="#" onclick="coach_act(' . $anydata->id . ',2)">Approved</a>
                    <a class="dropdown-item" href="#" onclick="coach_act(' . $anydata->id . ',3)">Updated</a>
                </div>
            </div>';
        })

        ->addColumn('status', function ($anydata) {

            $text = '';
            $color = '';

            if ($anydata->status == 1) {
                $text = 'Approved';
                $color = 'success';
            } elseif ($anydata->status == 2) {
                $text = 'Pending';
                $color = 'primary';
            } elseif ($anydata->status == 4) {
                $text = 'Reject';
                $color = 'danger';
            }

            return '
            <div class="btn-group">
                <button class="btn btn-' . $color . ' dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                    ' . $text . '
                </button>

                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" onclick="changeStatus(' . $anydata->id . ',2)">Pending</a>
                    <a class="dropdown-item" href="#" onclick="changeStatus(' . $anydata->id . ',1)">Approved</a>
                    <a class="dropdown-item" href="#" onclick="changeStatus(' . $anydata->id . ',4)">Reject</a>
                </div>
            </div>';
        })

        ->addColumn('subadmin_verified_by', function ($anydata) {
            if (empty($anydata->subadmin_verified)) {
                return '<span class="badge bg-secondary">Not Verified</span>';
            }

            $subadmin = \App\Models\Admin::find($anydata->verified_by_subadmin);

            return '<span class="badge bg-success">Verified by Subadmin' . ($subadmin ? ': ' . e($subadmin->name) : '') . '</span>';
        })

        ->addColumn('action', function ($anydata) {

            $admin = Auth::guard('admin')->user();

            $encrypted_id = get_encrypted_value($anydata->id, true);

            if ($admin->id == 1) {

                return '
                <a href="' . url('/admin/user/detail/' . $encrypted_id) . '">
                    <i class="mdi mdi-eye text-info"></i>
                </a>

                <a href="' . url('/admin/user/add/' . $encrypted_id) . '">
                    <i class="fas fa-edit"></i>
                </a>';
            }

            return '
            <a href="' . url('/admin/user/detail/' . $encrypted_id) . '">
                <i class="mdi mdi-eye text-info"></i>
            </a>';
        })

        ->rawColumns([
            'checkbox',
            'profile',
            'coach_act',
            'status',
            'subadmin_verified_by',
            'action'
        ])

        ->addIndexColumn()

        ->make(true);
}


    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = User::find($id);
        if ($data) {
            $data->status = 3;
            $data->save();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Athlete Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }
    
    public function bulkCoachPermission(Request $request)
{
    $ids = $request->ids;
    

    if (empty($ids)) {
        return response()->json(['status' => 'error', 'message' => 'No users selected.']);
    }

    User::whereIn('id', $ids)->update(['coach_act' => 2]); // or whatever value

    return response()->json(['status' => 'success', 'message' => 'Coach permission applied successfully.']);
}


    public function changeStatus(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  User::find($id);
        if ($data) {
            $data->status = $status;
            $data->save();
            echo "Success";
        }
    }
    
    public function coach_act(Request $request)
    {
        $id   = $request['id'];
        $status = $request['status'];
        $data  =  User::find($id);
        if ($data) {
            $data->coach_act = $status;
            $data->save();
            echo "Success";
        }
    }

    public function detail(Request $request, $id)
    {
        $decrypted_id = get_decrypted_value($id, true);

        $user = User::find($decrypted_id);
        // $refar = User::where('user_refral', $user->refar_code)->get();
        // p($user);
        $data = array(
            'title' => 'View Athlete Detail',
            'page_title' => 'View Athlete Detail',
            'user' => $user,

        );
        return view('admin.user.detail')->with($data);
    }
}
