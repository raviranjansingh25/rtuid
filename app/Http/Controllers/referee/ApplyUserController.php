<?php

namespace App\Http\Controllers\referee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tags;
use App\Models\ApplyTournament;
use App\Models\Tournament;
use App\Models\WeightCategory;
use App\Models\Coach;
use App\Models\Category;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;

class ApplyUserController extends Controller
{
    public function add(Request $request, $id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = User::find($decrypted_id);
        $dist = Tags::where('status',1)->get();
        $coaches = Coach::where('status',1)->get();
        $category = Category::where('status',1)->get();
        if ($id != "") {
            $saveurl = url('referee/applyuser/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Athlete';
        } else {
            $saveurl = url('referee/applyuser/save');
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
        return view('referee.applyuser.add')->with($data);
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
                $data->coach_act = 3;
                $data->save();

                if (empty($id)) {
                    $tag = Tags::find($data->district);
                    $data->code_id = 'RTUID/' . $tag->short_code . '/' . (100 + $data->id);
                    $data->save();
                }
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('apply_coach_user')->withSuccess($success_msg);
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
            'title' => 'Apply View Athletes',
            'page_title' => 'Apply View Athletes',
        );
        return view('referee.applyuser.view')->with($data);
    }

    public function anydata(Request $request)
    {
        $anydata = [];
        $admin= Auth::guard('referee')->user();
        
        $anydata = User::where('status', '!=', 3)->where('district',$admin->district)->where('role', 1)->orderBy('id', 'DESC')->where(function ($query) use ($request) {

            if (!empty($request['title'])) {
                $query->where('name', 'LIKE', '%' . $request['title'] . '%');
            }

            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        })->get();
        


        return Datatables::of($anydata)

            ->addColumn('profile', function ($anydata) {
                if ($anydata['profile'] != "" || file_exists($anydata['profile'])) {
                    $img = url($anydata['profile']);
                } else {
                    $img = url('public/noimage.png');
                }
                return '<img style="border-radius: 50%;" alt="image" src=' . $img . ' width="35" height="35px">';
            })

            ->addColumn('name', function ($anydata) {
                return isset($anydata->name) ? $anydata->name . ' ' . $anydata->last_name : $anydata->last_name;
            })

            ->addColumn('phone', function ($anydata) {
                return isset($anydata->country_code) ? '+' . $anydata->country_code . ' ' . $anydata->mobile : $anydata->mobile;
            })
            // ->addColumn('status', function ($anydata) {

            //     if ($anydata->status == 1) {
            //         $status = 2;
            //         $statusval = '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')"  class="btn btn-success btn-rounded btn-sm waves-effect waves-light">Active</span>';
            //     } else {
            //         $status = 1;
            //         $statusval = '<span onclick="changeStatus(' . $anydata->id . ',' . $status . ')" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light">Deactive</span>';
            //     }
            //     return $statusval;
            // })

            ->addColumn('waight_button', function ($anydata) {


                return '<div class="btn-group">
                        <button class="btn btn-success dropdown-toggle btn-sm" onclick="changeWaight(' . $anydata->id . ','.$anydata->status.')" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Waight
                        </button>
                        
                    </div>';
            })

            ->addColumn('apply', function ($anydata) {
                $current_date = Carbon::now()->format('Y-m-d');
            
                // Validate required fields
                if (!$anydata->weight || !$anydata->gender || !$anydata->category) {
                    return '<span class="badge bg-danger">Incomplete profile</span>';
                }
            
                $waight_category = WeightCategory::where('min', '<=', $anydata->weight)
                    ->where('max', '>=', $anydata->weight)
                    ->where('gender', $anydata->gender)
                    ->where('category', $anydata->category)
                    ->where('status', 1)
                    ->first();
            
                $tournaments = Tournament::where('status', 1)
                    ->where('start_date', '<=', $current_date)
                    ->where('end_date', '>=', $current_date)
                    ->where('gender', $anydata->gender)
                    ->pluck('id')->toArray();
            
                if (!empty($tournaments)) {
                    $apply = ApplyTournament::where('user_id', $anydata->id)
                        ->whereIn('turnament_id', $tournaments)
                        ->first();
                } else {
                    $apply = null;
                }
                
                
                if($anydata->status == 1){
                    if (!empty($apply)) {
                        return '<div class="btn-group">
                            <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Applied
                            </button>
                        </div>';
                    } else {
                        return '<div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle btn-sm" onclick="apply(' . $anydata->id . ')" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Apply
                            </button>
                        </div>';
                    }
                }else{
                    return '';
                }
                
            })


            ->rawColumns(['state_data', 'profile', 'total_referral', 'wallet', 'phone', 'name','waight_button','apply'])
            ->addIndexColumn()->make(true);
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

    public function detail(Request $request, $id)
    {
        $decrypted_id = get_decrypted_value($id, true);

        $user = User::find($decrypted_id);
        // $refar = User::where('user_refral', $user->refar_code)->get();
        // p($user);
        $data = array(
            'title' => 'Apply View Athlete Detail',
            'page_title' => 'Apply View Athlete Detail',
            'user' => $user,

        );
        return view('referee.applyuser.detail')->with($data);
    }

    public function updateWeight(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'weight' => 'required|numeric',
        ]);

        $user = User::find($request->user_id);
        $user->weight = $request->weight;
        $user->save();

        return response()->json(['status' => 'success']);
    }
}
