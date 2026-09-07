<?php

namespace App\Http\Controllers\subadmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\subadmin\Concerns\HandlesSubadminPermissions;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tags;
use App\Models\Coach;
use App\Models\Category;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;

class UserController extends Controller
{
    use HandlesSubadminPermissions;

    public function add(Request $request, $id = Null)
    {
        $admin = $this->subadmin();
        if ($denied = $this->denyAllDistrictWrite('You can only view athletes when assigned to all districts.')) {
            return $denied;
        }

        $isSingleDistrict = $this->isSingleDistrictSubadmin($admin);

        if (!empty($id)) {
            if (empty($admin->athlete_edit)) {
                return redirect()->route('subadmin_user')->withErrors('You do not have permission to edit athletes.');
            }
        } elseif (!$isSingleDistrict) {
            return redirect()->route('subadmin_user')->withErrors('You do not have permission to add athletes.');
        }

        $decrypted_id = get_decrypted_value($id, true);
        $getdata = User::find($decrypted_id);
        $dist = Tags::where('status',1)->get();
        if ($isSingleDistrict) {
            $dist = Tags::where('status', 1)->where('id', $admin->district)->get();
        }
        $coaches = Coach::where('status',1)->get();
        $category = Category::where('status',1)->get();
        if ($id != "") {
            $saveurl = url('subadmin/user/save/' . $id);
            $button = 'Update';
            $page_title = 'Update Athlete';
        } else {
            $saveurl = url('subadmin/user/save');
            $button = 'Add';
            $page_title = 'Add Athlete';
        }
        $data = $this->subadminViewData([
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'button'     => $button,
            'title'      => $page_title,
            'dist'      => $dist,
            'coaches'   => $coaches,
            'category'  => $category,
            'isSingleDistrict' => $isSingleDistrict,
            'lockedDistrict' => $isSingleDistrict ? $admin->district : null,
        ]);
        return view('subadmin.user.add')->with($data);
    }
    public function save(Request $request, $id = NUll)
    {
        $admin = $this->subadmin();
        if ($denied = $this->denyAllDistrictWrite('You can only view athletes when assigned to all districts.')) {
            return $denied;
        }
        if (!empty($id)) {
            if (empty($admin->athlete_edit)) {
                return redirect()->route('subadmin_user')->withErrors('You do not have permission to edit athletes.');
            }
        } elseif (!$this->isSingleDistrictSubadmin($admin)) {
            return redirect()->route('subadmin_user')->withErrors('You do not have permission to add athletes.');
        }

        if (!empty($id)) {
            $decrypted_id  = get_decrypted_value($id, true);
            $data          = User::find($decrypted_id);
            $success_msg   = 'Athlete Updated Successfully.';
            $nameValidator = 'required|unique:users,mobile,' . $decrypted_id . ',id,status,1';
            $Validatior = Validator::make($request->all(), [
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

                $data->name = $request->name ?? $data->name;
                $data->it_uid = $request->it_uid ?? $data->it_uid;
                $data->father_name = $request->father_name ?? $data->father_name;
                $data->dob = $request->dob ?? $data->dob;
                $data->contact_number = $request->contact_number ?? $data->contact_number;
                $data->whatsapp_number = $request->whatsapp_number ?? $data->whatsapp_number;
                $data->email = $request->email ?? $data->email;
                $data->gender = $request->gender ?? $data->gender;
                $data->category = $request->category ?? $data->category;
                $data->state = $request->state ?? $data->state;
                if ($this->isSingleDistrictSubadmin($admin)) {
                    $data->district = $admin->district;
                } else {
                    $data->district = $request->district ?? $data->district;
                }
                $data->city = $request->city ?? $data->city;
                $data->coach_id = $request->coach_id ?? $data->coach_id;
                $data->pin_code = $request->pin_code ?? $data->pin_code;
                $data->address = $request->address ?? $data->address;
                $data->aadhar_number = $request->aadhar_number ?? $data->aadhar_number;
                $data->coach_name = $request->coach_name ?? $data->coach_name;
                $data->coach_contact = $request->coach_contact ?? $data->coach_contact;
                $data->status = 2;
                $data->role = 1;

                if (empty($id) && $this->isSingleDistrictSubadmin($admin)) {
                    $data->added_by_subadmin = $admin->id;
                    $data->subadmin_verified = 0;
                    $data->verified_by_subadmin = null;
                    $data->coach_act = 1;
                } else {
                    $data->coach_act = 3;
                }
                
                if (!empty($request->password)) {
                    $data->password = Hash::make($request->password);
                } elseif (!empty($request->e_password)) {
                    $data->password = Hash::make($request->e_password);
                }
                
                $data->save();

                if (empty($id)) {
                    if ($this->isSingleDistrictSubadmin($admin)) {
                        $data->code_id = $this->generateSubadminAthleteCode($data->id, $data->district);
                    } else {
                        $tag = Tags::find($data->district);
                        $data->code_id = 'RTUID/' . $tag->short_code . '/' . (100 + $data->id);
                    }
                    $data->save();
                }
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('subadmin_user')->withSuccess($success_msg);
    }

    public function index()
    {
        $admin = $this->subadmin();
        $denied = $this->denySubadminUnless(
            !empty($admin->athlete_detail) || !empty($admin->athlete_edit),
            'You do not have permission to view athletes.'
        );
        if ($denied) {
            return $denied;
        }

        $data = $this->subadminViewData([
            'title' => 'View Athletes',
            'page_title' => 'View Athletes',
        ]);
        return view('subadmin.user.view')->with($data);
    }

    public function anydata(Request $request)
    {
        $admin = $this->subadmin();
        if (empty($admin->athlete_detail) && empty($admin->athlete_edit)) {
            return response()->json(['data' => []]);
        }

        $anydata = User::where('status', '!=', 3)
            ->where('role', 1)
            ->where(function ($query) use ($request) {
            if (!empty($request['title'])) {
                $query->where('name', 'LIKE', '%' . $request['title'] . '%');
            }
            if (!empty($request['status'])) {
                $query->where('status', $request['status']);
            }
        });
        $this->applyDistrictScope($anydata, $admin, $request['district'] ?? null);
        $anydata = $anydata->orderBy('id', 'DESC')->get();

        $allDistrictMode = $this->canAccessAllDistricts($admin);
        $singleDistrictMode = $this->isSingleDistrictSubadmin($admin);

        $datatable = Datatables::of($anydata)
            ->addColumn('profile', function ($anydata) {
                if ($anydata['profile'] != "" || file_exists($anydata['profile'])) {
                    $img = url($anydata['profile']);
                } else {
                    $img = url('public/noimage.png');
                }
                return '<img style="border-radius: 50%;" alt="image" src=' . $img . ' width="35" height="35px">';
            })
            ->addColumn('name', function ($anydata) {
                return isset($anydata->name) ? trim($anydata->name . ' ' . ($anydata->last_name ?? '')) : ($anydata->last_name ?? '');
            })
            ->addColumn('phone', function ($anydata) {
                return isset($anydata->country_code) ? '+' . $anydata->country_code . ' ' . $anydata->mobile : $anydata->mobile;
            })
            ->addColumn('status', function ($anydata) use ($allDistrictMode, $singleDistrictMode) {
                if ($anydata->status == 1) {
                    $text = 'Approved';
                    $color = 'success';
                } elseif ($anydata->status == 2) {
                    $text = 'Pending';
                    $color = 'primary';
                } elseif ($anydata->status == 4) {
                    $text = 'Reject';
                    $color = 'danger';
                } else {
                    $text = 'Unknown';
                    $color = 'secondary';
                }

                if ($allDistrictMode || $singleDistrictMode) {
                    return '<span class="btn btn-' . $color . ' btn-sm">' . $text . '</span>';
                }

                return '<div class="btn-group"><button class="btn btn-' . $color . ' dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">' . $text . '</button></div>';
            })
            ->addColumn('action', function ($anydata) use ($admin, $allDistrictMode, $singleDistrictMode) {
                $encrypted_id = get_encrypted_value($anydata->id, true);
                $action = '';

                if (!empty($admin->athlete_detail)) {
                    $action .= '<a href="' . url('/subadmin/user/detail/' . $encrypted_id) . '"><i class="mdi mdi-eye text-info" title="View" data-toggle="tooltip" data-placement="bottom"></i></a>&nbsp;&nbsp;';
                }

                if ($singleDistrictMode && empty($anydata->subadmin_verified)) {
                    $action .= '<a href="#" onclick="verifyAthlete(' . $anydata->id . '); return false;"><i class="mdi mdi-check-circle text-success" title="Verify (1st Stage)" data-toggle="tooltip" data-placement="bottom"></i></a>&nbsp;&nbsp;';
                } elseif (!$allDistrictMode && !$singleDistrictMode && !empty($admin->athlete_edit) && ($anydata->coach_act == 2 || $anydata->status == 4)) {
                    $action .= '<a href="' . url('/subadmin/user/add/' . $encrypted_id) . '"><i class="fas fa-edit" title="Edit" data-toggle="tooltip" data-placement="bottom"></i></a>&nbsp;&nbsp;';
                }

                return $action;
            });

        if ($allDistrictMode) {
            $datatable->addColumn('coach_act', function ($anydata) {
                if ($anydata->coach_act == 1) {
                    $text = 'Pending';
                    $color = 'danger';
                } elseif ($anydata->coach_act == 2) {
                    $text = 'Approved';
                    $color = 'primary';
                } elseif ($anydata->coach_act == 3) {
                    $text = 'Updated';
                    $color = 'success';
                } else {
                    $text = 'N/A';
                    $color = 'secondary';
                }

                return '<span class="btn btn-' . $color . ' btn-sm">' . $text . '</span>';
            });
        }

        if ($singleDistrictMode) {
            $datatable->addColumn('subadmin_verification', function ($anydata) {
                if (!empty($anydata->subadmin_verified)) {
                    return '<span class="badge bg-success">Verified</span>';
                }

                return '<span class="badge bg-warning text-dark">Pending Verification</span>';
            });
        }

        return $datatable
            ->rawColumns(['status', 'action', 'state_data', 'profile', 'total_referral', 'wallet', 'phone', 'name', 'coach_act', 'subadmin_verification'])
            ->addIndexColumn()->make(true);
    }
    public function delete(Request $request)
    {
        if ($denied = $this->denyAllDistrictWrite()) {
            return response()->json(['status' => 'error', 'message' => 'Permission denied.'], 403);
        }
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
        if ($denied = $this->denyAllDistrictWrite()) {
            return response('Permission denied', 403);
        }
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
        $admin = $this->subadmin();
        if (empty($admin->athlete_detail)) {
            return redirect()->route('subadmin_user')->withErrors('You do not have permission to view athlete details.');
        }

        $decrypted_id = get_decrypted_value($id, true);
        $user = User::find($decrypted_id);

        if (!$this->canAccessAllDistricts($admin) && $user->district != $admin->district) {
            return redirect()->route('subadmin_user')->withErrors('You do not have permission to view this athlete.');
        }

        $data = $this->subadminViewData([
            'title' => 'View Athlete Detail',
            'page_title' => 'View Athlete Detail',
            'user' => $user,
        ]);
        return view('subadmin.user.detail')->with($data);
    }

    public function updateWeight(Request $request)
    {
        $admin = $this->subadmin();
        if (empty($admin->add_weight)) {
            return response()->json(['status' => 'error', 'message' => 'Permission denied.'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'weight' => 'required|numeric',
        ]);

        $user = User::find($request->user_id);
        $user->weight = $request->weight;
        $user->save();

        return response()->json(['status' => 'success']);
    }

    public function verifyAthlete(Request $request)
    {
        $admin = $this->subadmin();
        if (!$this->isSingleDistrictSubadmin($admin)) {
            return response()->json(['status' => 'error', 'message' => 'Permission denied.'], 403);
        }

        $user = User::find($request->id);
        if (!$user || (string) $user->district !== (string) $admin->district) {
            return response()->json(['status' => 'error', 'message' => 'Athlete not found.'], 404);
        }

        $user->subadmin_verified = 1;
        $user->verified_by_subadmin = $admin->id;
        $user->coach_act = 2;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Athlete verified successfully.']);
    }
}
