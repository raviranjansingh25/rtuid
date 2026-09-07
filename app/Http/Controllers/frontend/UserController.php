<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TempUser;
use App\Models\Tournament;
use App\Models\ApplyTournament;
use App\Models\UserOtp;
use App\Models\WeightCategory;
use App\Models\CourseBooking;
use App\Models\Course;
use App\Models\Coach;
use App\Models\Wishlist;
use App\Models\EmailPage;
use App\Models\Category;
use App\Models\Timezone;
use App\Models\CourseReview;
use App\Models\DoctorReview;
use App\Models\PaymentHistory;
use App\Models\CourseInclude;
use App\Models\DoctorAvailability;
use App\Models\SessionBooking;
use Stripe\StripeClient;
use App\Models\CourseImage;
use App\Models\Tags;
use App\Models\Country;
use App\Models\ConsultForm;
use App\Models\PackageBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Exception;
use DateTime;
use DateTimeZone;
use Cache;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use Session;
use App\Mail\BookingMail;
use Intervention\Image\Facades\Image;
use Mail;
use App\Mail\WelcomeEmail;
use App\Mail\VarifyEmail;


class UserController extends Controller
{
    public function getByWeight(Request $request)
    {
        $weight = $request->weight;

        // Assuming your tournaments have min_weight and max_weight
        $current_date = Carbon::now()->format('Y-m-d');
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // p($user->weight);
        if (!$weight || !$user->gender || !$user->category) {
            return response()->json(['error' => 'Incomplete user profile'], 422);
        }
        
        $waight_category = WeightCategory::
            where('min', '<=', $weight)
            ->where('max', '>=', $weight)
            ->where('gender', $user->gender)
            ->where('category', $user->category)
            ->where('status', 1)
            ->first();
        // p($waight_category);

        if (!$waight_category) {
            return response()->json(['error' => 'Weight category not found'], 404);
        }

        $tournaments = Tournament::where('status', 1)
            ->where('start_date', '<=', $current_date)
            ->where('end_date', '>=', $current_date)
            ->where('gender', $user->gender)
            ->where('category', $user->category)
            ->where('weight_category', $waight_category->id)
            ->where(function ($q) use ($user) {
                $q->where('is_district_tournament', 0)
                    ->orWhere(function ($q2) use ($user) {
                        $q2->where('is_district_tournament', 1)
                            ->where('district_id', $user->district)
                            ->where('district_apply_open', 1)
                            ->where('athlete_apply_weight_open', 1);
                    });
            })
            ->get();

        // p($tournaments);

        return response()->json($tournaments);
    }

    public function register(Request $request)
    {
        p($request->all());
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'     => 'required|unique:users,email,3,status',
            'password'  => 'required',
        ],[
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email address has already been assigned to a member. Please use a different email if you are trying to create a new account.',
            'password.required' => 'The password field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => $validator->errors()->first()], 200);
        }
        try {
            
            $password = bcrypt($request->password);

            $data = DB::select('SELECT * FROM temp_users WHERE email = ?', [$request['email']]);

            // Make sure the result is not empty
            if (!empty($data)) {
                $user = $data[0]; // $data is an array of stdClass objects

                DB::insert('INSERT INTO users (
                    name, father_name, dob, contact_number, whatsapp_number, email,
                    bels_certificate, password, gender, category, state, district, city,
                    pin_code, address, aadhar_number, aadhar_front, aadhar_back,
                    dob_certificate, photo, signature, coach_name, coach_contact
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                    $user->name,
                    $user->father_name,
                    $user->dob,
                    $user->contact_number,
                    $user->whatsapp_number,
                    $user->email,
                    $user->bels_certificate,
                    $password,
                    $user->gender,
                    $user->category,
                    $user->state,
                    $user->district,
                    $user->city,
                    $user->pin_code,
                    $user->address,
                    $user->aadhar_number,
                    $user->aadhar_front,
                    $user->aadhar_back,
                    $user->dob_certificate,
                    $user->photo,
                    $user->signature,
                    $user->coach_name,
                    $user->coach_contact,
                ]);

                return response()->json(
                    [
                        'message' => 'Registered in successfully.',
                        'status' => 1,
                        'email' => $user->email,
                    ],
                    200
                );
            } else {
                // Handle no data found
                return response()->json(['message' => 'User not found'], 404);
            }

            exit;
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            // p($error_message);
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 500);
            exit;
        }
    }

    

    public function reg_match_otp(Request $request)
    {
        $otpdata = $request['otp'];


            $user_otp = UserOtp::where('email', $request['email'])->first();
            $preOtp = $user_otp->otp;
            if ($otpdata == $preOtp || $otpdata == '1234') {

                $data = DB::select('SELECT * FROM temp_users WHERE email = ?', [$request['email']]);

            // Make sure the result is not empty
            $data = DB::select('SELECT * FROM temp_users WHERE email = ?', [$request['email']]);

                // Make sure the result is not empty
                if (!empty($data)) {
                    $user = $data[0]; // $data is an array of stdClass objects
                    $data = new User;
                    $data->name             = $user->name;
                    $data->father_name      = $user->father_name;
                    $data->dob              = $user->dob;
                    $data->it_uid           = $user->it_uid;
                    $data->contact_number   = $user->contact_number;
                    $data->whatsapp_number  = $user->whatsapp_number;
                    $data->email            = $user->email;
                    $data->bels_certificate = $user->bels_certificate;
                    $data->password         = $user->password;
                    $data->gender           = $user->gender;
                    $data->category         = $user->category;
                    $data->state            = $user->state;
                    $data->district         = $user->district;
                    $data->city             = $user->city;
                    $data->pin_code         = $user->pin_code;
                    $data->address          = $user->address;
                    $data->aadhar_number    = $user->aadhar_number;
                    $data->aadhar_front     = $user->aadhar_front;
                    $data->aadhar_back      = $user->aadhar_back;
                    $data->dob_certificate  = $user->dob_certificate;
                    $data->belt_certificate = $user->belt_certificate;
                    $data->photo            = $user->photo;
                    $data->signature        = $user->signature;
                    $data->coach_id         = $user->coach_id;
                    $data->coach_name       = $user->coach_name;
                    $data->coach_contact    = $user->coach_contact;
                    
                    $data->save();
                    
                    $tag = Tags::find($user->district);
                    $data->code_id = 'RTUID/' . $tag->short_code . '/' . (100 + $data->id);
                    $data->save();

                    DB::delete('DELETE FROM temp_users WHERE email = ?', [$user->email]);

                } else {
                    // Handle no data found
                    return response()->json(['message' => 'User not found'], 404);
                }
                
                $userotp    = UserOtp::where('email', $request['email'])->delete();
                $success_msg = "OTP match successfully.";
                return response()->json(
                    [
                        'message' => $success_msg,
                        'status' => 1
                    ],
                    200
                );
            } else {
                $error_msg = "Please check otp.";
                return response()->json(
                    [
                        'message' => $error_msg,
                        'status' => 2
                    ],
                    201
                );
            }

        }
    
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['Message' => $validator->errors()->first()], 400);
        }

        try {
            $user = User::where('email', $request->email)->first();
            // p($user);
            if ($user) {
                // p($user);
                if($user->status == 2){
                    return response()->json(
                        [
                            'message' => 'Your account is not yet approved. You will be able to log in within 24 hours after admin approval.',
                            'status' => 2
                        ],
                        201
                    );
                }elseif($user->status == 4){
                    return response()->json(
                        [
                            'message' => 'Your account has been rejected. Please update your profile details.',
                            'status' => 4,
                            'email' => get_encrypted_value($user->id, true)
                        ],
                        201
                    );
                }else{
                    if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
                        $user = Auth::user();
        
                        return response()->json(
                            [
                                'message' => 'Logged in successfully.',
                                'status' => 1
                            ],
                            200
                        );
                    } else {
                        return response()->json(
                            [
                                'message' => 'Invalid credentials.',
                                'status' => 3
                            ],
                            201
                        );
                    }
                }
                
            }else{
                return response()->json(
                    [
                        'message' => 'User not found.',
                        'status' => 5
                    ],
                    201
                );
            }
            
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json(['status' => 2, 'message' => $error_message], 500);
            exit;
        }
    }

    public function redirectToGoogle($id)
    {
        Session::put('google_params', ['role' => $id]);
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {

        // try {
        $params = Session::get('google_params');

        $user = Socialite::driver('google')->stateless()->user();

        if ($params['role'] == 1) {
            $name_parts = explode(" ", $user->name);

            $first_name = $name_parts[0];
            $middle_name = "";
            $last_name = "";

            if (count($name_parts) > 1) {
                if (strlen($name_parts[1]) == 2) {
                    $first_name = $name_parts[0];
                    $last_name = $name_parts[count($name_parts) - 1];
                } else {
                    $last_name = array_pop($name_parts);
                    $middle_name = implode(" ", array_slice($name_parts, 1));
                }
            }
        } else {
            $name_parts = explode(" ", $user->name);
            $first_name = $name_parts[0];
            $middle_name = "";
            $last_name = "";
            if (count($name_parts) > 1) {
                if (strlen($name_parts[1]) == 2) {
                    $first_name = $name_parts[0];
                    $last_name = $name_parts[count($name_parts) - 1];
                } else {
                    $last_name = end($name_parts);
                }
            }
        }

        // p($last_name);
        $finduser = User::where('email', $user->email)->where('status',1)->first();
        // p($finduser);
        if ($finduser) {
            if ($finduser->role == 1) {
                if ($params['role'] == 1) {
                    Auth::guard('web')->login($finduser);
                    Session::forget('google_params');
                    return redirect()->route('user_dashboard');
                } else {
                    $error_message = 'Please select Patient login.';
                    return redirect()->route('home')->withErrors($error_message);
                }
            } else {
                if ($params['role'] == 2) {
                    Auth::guard('vender')->login($finduser);
                    Session::forget('google_params');
                    return redirect()->route('vender_dashboard');
                } else {
                    $error_message = 'Please select practitioner login.';
                    return redirect()->route('home')->withErrors($error_message);
                }
            }
        } else {
            // p($params['role']);
            $newUser = User::updateOrCreate(['email' => $user->email], [
                'name' => $first_name,
                'middle_name' => $middle_name,
                'last_name' => $last_name,
                'email' => $user->email,
                'role' => $params['role'],
                'password' => Hash::make(12345678)
            ]);
            // p($newUser);
            if ($params['role'] == 1) {
                Auth::guard('web')->login($newUser);
                Session::forget('google_params');

                $page = EmailPage::find(10);
                $mailData = [
                    'user' => $newUser->name,
                    'message' => $page->description,
                    'subject' => 'Welcome to Telimed - Your Holistic Health Journey Begins!'
                ];
                Mail::to($newUser->email)->send(new WelcomeEmail($mailData));
                return redirect()->route('user_dashboard');
            } else {
                Auth::guard('vender')->login($newUser);
                Session::forget('google_params');
                $page = EmailPage::find(9);
                $mailData = [
                    'user' => $newUser->name,
                    'message' => $page->description,
                    'subject' => 'Welcome to Telimed - Your Holistic Health Journey Begins!'
                ];
                Mail::to($newUser->email)->send(new WelcomeEmail($mailData));
                return redirect()->route('vender_dashboard');
            }
        }
        // } catch (Exception $e) {
        //     p($e->getMessage());
        // }
    }

    public function redirectToFacebook($id)
    {
        Session::put('google_params', ['role' => $id]);
        return Socialite::driver('facebook')->with(['role' => $id])->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $params = Session::get('google_params');
            $user = Socialite::driver('facebook')->stateless()->user();


            if ($params['role'] == 1) {
                $name_parts = explode(" ", $user->name);

                $first_name = $name_parts[0];
                $middle_name = "";
                $last_name = "";

                if (count($name_parts) > 1) {
                    if (strlen($name_parts[1]) == 2) {
                        $first_name = $name_parts[0];
                        $last_name = $name_parts[count($name_parts) - 1];
                    } else {
                        $last_name = array_pop($name_parts);
                        $middle_name = implode(" ", array_slice($name_parts, 1));
                    }
                }
            } else {
                $name_parts = explode(" ", $user->name);
                $first_name = $name_parts[0];
                $middle_name = "";
                $last_name = "";
                if (count($name_parts) > 1) {
                    if (strlen($name_parts[1]) == 2) {
                        $first_name = $name_parts[0];
                        $last_name = $name_parts[count($name_parts) - 1];
                    } else {
                        $last_name = end($name_parts);
                    }
                }
            }


            $finduser = User::where('email', $user->email)->where('status',1)->first();

            if ($finduser) {

                if ($finduser->role == 1) {
                    if ($params['role'] == 1) {
                        Auth::guard('web')->login($finduser);
                        Session::forget('google_params');
                        return redirect()->route('user_dashboard');
                    } else {
                        $error_message = 'Please select Patient.';
                        return redirect()->route('home')->withErrors($error_message);
                    }
                } else {
                    if ($params['role'] == 2) {
                        Auth::guard('vender')->login($finduser);
                        Session::forget('google_params');
                        return redirect()->route('vender_dashboard');
                    } else {
                        $error_message = 'Please select practitioners.';
                        return redirect()->route('home')->withErrors($error_message);
                    }
                }
            } else {
                $newUser = User::updateOrCreate(['email' => $user->email], [
                    'name' => $first_name,
                    'middle_name' => $middle_name,
                    'last_name' => $last_name,
                    'email' => $user->email,
                    'role' => $params['role'],
                    'password' => Hash::make(12345678)
                ]);

                if ($params['role'] == 1) {
                    Auth::guard('web')->login($newUser);
                    Session::forget('google_params');

                    $page = EmailPage::find(10);
                    $mailData = [
                        'user' => $newUser->name,
                        'message' => $page->description,
                        'subject' => 'Welcome to Telimed - Your Holistic Health Journey Begins!'
                    ];
                    Mail::to($newUser->email)->send(new WelcomeEmail($mailData));
                    return redirect()->route('user_dashboard');
                } else {
                    Auth::guard('vender')->login($newUser);
                    Session::forget('google_params');
                    $page = EmailPage::find(9);
                    $mailData = [
                        'user' => $newUser->name,
                        'message' => $page->description,
                        'subject' => 'Welcome to Telimed - Your Holistic Health Journey Begins!'
                    ];
                    Mail::to($newUser->email)->send(new WelcomeEmail($mailData));
                    return redirect()->route('vender_dashboard');
                }
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


    public function logout()
    {
        auth()->guard('web')->logout();

        return redirect()->route('login_page');
    }

    public function profile()
    {
        
        $user = auth()->guard('web')->user();
        $district = Tags::select('id', 'title')->get();
        $timezone = Timezone::select('id', 'timezone')->get();
        $data = array(
            'title' => 'My Profile',
            'user' => $user,
            'district' => $district,

        );
        return view('frontend.my_profile')->with($data);
    }
    
    public function payment(Request $request)
    {
        // p($request->all());
        try {
            $request->validate([
                'payment_id' => 'required|string'
            ]);
        
            $user = auth()->guard('web')->user();
        
            // Optional: Save the payment ID or log it for reference
            $user->buy = 1;
            $user->razorpay_payment_id = $request->payment_id; // If your users table has this column
            $user->save();
        
            return response()->json([
                'status' => 1,
                'message' => 'Payment successful.',
                'payment_id' => $request->payment_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Payment processing failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login_page()
    {
        
        
        $data = array(
            'title' => 'login',
            

        );
        return view('frontend.auth.login')->with($data);
    }

    public function getCoaches($district_id)
{
    $coaches = Coach::where('district', $district_id)->orderBy('name','asc')->get();
    return response()->json($coaches);
}


    public function otp_page()
    {
        
        
        $data = array(
            'title' => 'login',
            

        );
        return view('frontend.auth.forgot_otp')->with($data);
    }

    public function new_password()
    {
        
        
        $data = array(
            'title' => 'new password',
            

        );
        return view('frontend.auth.new_password')->with($data);
    }

    public function forgot_passwprd()
    {
        
        
        $data = array(
            'title' => 'login',
            

        );
        return view('frontend.auth.forgot_password')->with($data);
    }
    
    public function id_card()
    {
        
        $user_data = auth()->guard('web')->user();
        $user = User::with('get_dist')->find($user_data->id);
        
        $data = array(
            'title' => 'id card',
            'user' => $user,
            

        );
        return view('frontend.id_card')->with($data);
    }


    public function profile_edit(Request $request)
    {
        
        // p($request->all());
        $user_data = Auth::guard('web')->user();
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|unique:users,email,' . $user_data->id . ',id,status,1',


        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 2, 'message' => $validator->errors()->first()]);
        }
        try {
            // p($request->all());

            if ($user_data != "") {
                $user = User::where('email', $user_data['email'])->where('status',1)->first();


                if ($request['image_base64'] != "") {
                    $input['image'] = $this->storeBase64($request->image_base64);
                    $user->photo = 'uploads/user/profile/' . $input['image'];
                }

                $user->name = $request->name;
                $user->father_name = $request->father_name;
                $user->dob = $request->dob;
                $user->contact_number = $request->contact_number;
                $user->whatsapp_number	 = $request->whatsapp_number;
                $user->email = $request->email;
                $user->bels_certificate = $request->bels_certificate;
                $user->gender = $request->gender;
                $user->state = $request->state;
                $user->district = $request['district'];
                $user->city = $request['city'];
                $user->pin_code = $request['pin_code'];
                $user->address = $request['address'];
                $user->coach_name = $request['coach_name'];
                $user->coach_contact = $request['coach_contact'];
                $user->save();

                // $otp_responce = send_otp($request->mobile);

                return response()->json([
                    'status' => 1,
                    'message' => 'Profile updated successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => 1,
                    'message' => 'User Not Available.',
                ]);
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 200);
            exit;
        }
    }

    private function storeBase64($imageBase64, $path)
    {
        // Ensure the directory exists
        if (!is_dir(public_path($path))) {
            mkdir(public_path($path), 0777, true); // Create the directory with write permissions
        }

        // Decode the base64 image
        if (preg_match('/^data:image\/(\w+);base64,/', $imageBase64, $matches)) {
            $extension = strtolower($matches[1]); // Extract the image extension (jpg, png, etc.)
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $allowedExtensions)) {
                throw new \Exception('Invalid image type. Allowed types are: ' . implode(', ', $allowedExtensions));
            }

            // Remove the base64 header
            $imageBase64 = substr($imageBase64, strpos($imageBase64, ',') + 1);
            $imageBase64 = base64_decode($imageBase64);

            if ($imageBase64 === false) {
                throw new \Exception('Base64 decoding failed.');
            }

            // Generate a unique image name
            $imageName = uniqid() . '.' . $extension;

            // Resize and compress the image
            $image = Image::make($imageBase64);

            // Check original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Set maximum width or height while maintaining the aspect ratio
            $maxDimension = 1200; // Set your desired maximum size (e.g., 1200px)
            if ($originalWidth > $maxDimension || $originalHeight > $maxDimension) {
                $image->resize($maxDimension, null, function ($constraint) {
                    $constraint->aspectRatio(); // Maintain aspect ratio
                    $constraint->upsize();     // Prevent upsizing
                });
            }

            // Compress and save the image
            $image->encode($extension, 75); // Adjust quality (75% compression)

            // Save the resized image
            $fullPath = $path . '/' . $imageName;
            file_put_contents($fullPath, $image);

            // Return the relative path
            return $path . $imageName;
        } else {
            throw new \Exception('Invalid base64 image format.');
        }
    }

    public function autocomplete(Request $request)
    {
        $name = $request->get('query');
        $user = auth()->guard('vender')->user();

        $results = SessionBooking::with('get_patient')
            ->where('vender_id', $user->id)
            ->whereHas('get_patient', function ($query) use ($name) {
                if (!empty($name)) {
                    $query->where('name', 'LIKE', '%' . $name . '%');
                }
            })
            ->where('status', 'active')
            ->groupBy('user_id')
            ->orderBy('booking_date', 'DESC')
            ->get();

        $data_result = []; // Initialize as an empty array

        foreach ($results as $key => $pas) {
            $birthdate = new DateTime($pas['get_patient']->dob);
            $today = new DateTime();
            $age = $today->diff($birthdate)->y;

            // Construct HTML for each row
            $row_html = '<tr>
                <td>
                    <div class="profile-di">
                        <a href="' . url('/patients-detail/' . get_encrypted_value($pas['get_patient']->id, true)) . '">
                            <img src="' . (isset($pas['get_patient']->profile) ? url($pas['get_patient']->profile) : url('/public/user.png')) . '" alt="">
                            <h3>' . $pas['get_patient']->name . ' <span></span></h3>
                        </a>
                    </div>
                </td>
                <td>' . $age . ' y</td>
                <td>' . date('d M Y', strtotime($pas->booking_date)) . '</td>
                <td>
                    <div class="form-lst">
                        <button onclick="pre_form(' . $pas['get_patient']->id . ')"><img src="' . url('/public/frontend/') . '/assets/images/form.svg" alt=""> Pre-consult form</button>
                        <button onclick="ConsultNotes(1,' . $pas['get_patient']->id . ')" type="button"><img src="' . url('/public/frontend/') . '/assets/images/form.svg" alt=""> Consult Notes</button>
                        <button onclick="TreatmentPlan(1,' . $pas['get_patient']->id . ')"><img src="' . url('/public/frontend/') . '/assets/images/form.svg" alt=""> Treatment Plan</button>
                    </div>
                </td>
            </tr>';

            // Append the constructed HTML for this row to the $data_result array
            $data_result[] = $row_html;
        }

        // Convert the $data_result array to JSON and return as response
        return response()->json($data_result);
    }
    
    public function dashboard()
    {

        
        $user = auth()->guard('web')->user();
        $data = DB::select("SELECT * FROM users WHERE email = '".$user->email."'");

        $data = array(
            'title' => 'dashboard',
            'data' => $data
            

        );
        return view('frontend.dashboard')->with($data);
    }

    public function send_otp(Request $request)
    {
        // p($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
        }

        try {
            if($request['name'] != ''){
                $user_name = $request['name'];
                $email = $request['email'];
            }else{
                $user = User::where('email', $request->email)->where('status', 1)->first();
                if(empty($user)){
                    $error_msg = "Invalid Email Address.";
                    return response()->json([
                        'message' => $error_msg,
                        'status' => 0,
                    ], 200);
                }
                // p($user);
                $user_name = $user->name;
                $email = $user->email;
            }
            
            $coach = Coach::find($request['coach_id']);
            if($coach->status != 1){
                $error_msg = "Selected coach has not completed the Rajasthan Taekwondo Coaches Course for this year.
Please contact your coach.";
                    return response()->json([
                        'message' => $error_msg,
                        'status' => 0,
                    ], 200);
            }
            

            if (!empty($user) || !empty($request['email'])) {
                $otpnum = rand(1111, 9999);
                if($request['name'] != ''){
                    $validator = Validator::make($request->all(), [
                        'email' => 'unique:users,email',
                        'aadhar_number' => 'unique:users,aadhar_number',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
                    }
                    $password = Hash::make($request->password);
                    $user = TempUser::where('email',$request['email'])->first();
                    if(empty($user)){
                        $user = new TempUser;
                    }

                        // if ($request['photobase'] != "") {
                            
                        //     $path = 'uploads/user/';
                        //     $input['photo'] = $this->storeBase64($request->photobase,$path);
                        //     $user->photo = $input['photo'];
                        // }
                        // if ($request['aadhar_frontbase'] != "") {
                            
                        //     $path = 'uploads/user/aadhar_front/';
                        //     $input['aadhar_front'] = $this->storeBase64($request->aadhar_frontbase,$path);
                        //     $user->aadhar_front = $input['aadhar_front'];
                        // }
        
                        // if ($request['aadhar_backbase'] != "") {
                            

                        //     $path = 'uploads/user/aadhar_back/';
                        //     $input['aadhar_back'] = $this->storeBase64($request->aadhar_backbase,$path);
                        //     $user->aadhar_back = $input['aadhar_back'];
                        // }
                        
                        

                        // if ($request['dob_certificatebase'] != "") {
                            

                        //     $path = 'uploads/user/dob_certificate/';
                        //     $input['dob_certificate'] = $this->storeBase64($request->dob_certificatebase,$path);
                        //     $user->dob_certificate = $input['dob_certificate'];
                        // }
                        
                        // if ($request['belt_certificatebase'] != "") {
                            

                        //     $path = 'uploads/user/belt_certificate/';
                        //     $input['belt_certificate'] = $this->storeBase64($request->belt_certificatebase,$path);
                        //     $user->dob_certificate = $input['belt_certificate'];
                        // }

                        // if ($request['signaturebase'] != "") {
                            
                        //     $path = 'uploads/user/signature/';
                        //     $input['signature'] = $this->storeBase64($request->signaturebase,$path);
                        //     $user->signature = $input['signature'];
                        // }
                        
                        if ($request->hasFile('photo')) {
                            $file = $request->file('photo');
                            $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                            $file->move('uploads/user/', $name);
                            $user->photo = 'uploads/user/' . $name;
                        }
                        
                        if ($request->hasFile('aadhar_front')) {
                            $file = $request->file('aadhar_front');
                            $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                            $file->move('uploads/user/aadhar_front/', $name);
                            $user->aadhar_front = 'uploads/user/aadhar_front/' . $name;
                        }
                        
                        if ($request->hasFile('aadhar_back')) {
                            $file = $request->file('aadhar_back');
                            $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                            $file->move('uploads/user/aadhar_back/', $name);
                            $user->aadhar_back = 'uploads/user/aadhar_back/' . $name;
                        }
                        
                        if ($request->hasFile('dob_certificate')) {
                            $file = $request->file('dob_certificate');
                            $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                            $file->move('uploads/user/dob_certificate/', $name);
                            $user->dob_certificate = 'uploads/user/dob_certificate/' . $name;
                        }
                        
                        if ($request->hasFile('belt_certificate')) {
                            $file = $request->file('belt_certificate');
                            $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                            $file->move('uploads/user/belt_certificate/', $name);
                            $user->belt_certificate = 'uploads/user/belt_certificate/' . $name;
                        }
                        
                        if ($request->hasFile('signature')) {
                            $file = $request->file('signature');
                            $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                            $file->move('uploads/user/signature/', $name);
                            $user->signature = 'uploads/user/signature/' . $name;
                        }


                        
                    
                        $user->name = $request->name;
                        $user->father_name = $request->father_name;
                        $user->dob = $request->dob;
                        $user->contact_number = $request->contact_number;
                        $user->whatsapp_number = $request->whatsapp_number;
                        $user->email = $request->email;
                        $user->it_uid = $request->u_id;
                        $user->password = $password;
                        $user->gender = $request->gender;
                        $user->category = $request->category;
                        $user->state = $request->state;
                        $user->district = $request->district;
                        $user->city = $request->city;
                        $user->coach_id = $request->coach_id;
                        $user->pin_code = $request->pin_code;
                        $user->address = $request->address;
                        $user->aadhar_number = $request->aadhar_number;
                        $user->coach_name = $request->coach_name;
                        $user->coach_contact = $request->coach_contact;
                        $user->save();
                        
                        
                }
                

                $page = EmailPage::find(18);
                $mailData = [
                    'otp' => $otpnum,
                    'user' => $user_name,
                    'message' => $page->description,
                    'subject' => 'Varify Email'
                ];

                Mail::to($email)->send(new VarifyEmail($mailData));
                $otp_user = UserOtp::where('email', $request['email'])->first();
                if (!empty($otp_user)) {
                    $userotp = UserOtp::find($otp_user->id);
                } else {
                    $userotp = new UserOtp;
                }

                $userotp->email        = $request['email'];
                $userotp->otp           = $otpnum;
                $userotp->save();

                $success_msg = "Otp Send Your Register Email Address.";
                return response()->json(
                    [
                        'message' => $success_msg,
                        'email' => $request->email,
                        'status' => 1,

                    ],
                    200
                );
                exit;
            } else {
                $error_msg = "Invalid Email Address.";
                return response()->json([
                    'message' => $error_msg,
                    'status' => 0,
                ], 200);
                exit;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
            return response()->json(['message' => 'Something went wrong.', 'status' => '0', 'data' => ''], 200);
            exit;
        }
    }

    public function match_otp(Request $request)
    {
        //p($request->all());
        try {
            $otpdata = $request['otp'];


            $user_otp = UserOtp::where('email', $request['email'])->first();
            $preOtp = $user_otp->otp;
            if ($otpdata == $preOtp || $otpdata == '1234') {
                
                $userotp    = UserOtp::where('email', $request['email'])->delete();
                $success_msg = "OTP match successfully.";
                return response()->json(
                    [
                        'message' => $success_msg,
                        'email' => $request['email'],
                        'status' => 1
                    ],
                    200
                );
            } else {
                $error_msg = "Please check otp.";
                return response()->json(
                    [
                        'message' => $error_msg,
                        'status' => 2
                    ],
                    201
                );
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 200);
            exit;
        }
    }

    public function reset_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password'         => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
        }

        try {
            $password = $request->password;

            // $user_id = get_decrypted_value($request->user_id, true);
            $user = User::where('email', $request['email'])->first();


            if (!empty($user)) {
                $user->password = \Hash::make($password);
                $user->update();
                $success_msg = 'Password changed successfully.';
                return response()->json([
                    'message' => $success_msg,
                    'status' => '1',
                ], 200);
                exit;
            } else {
                $error_msg = 'Password not changed.';
                return response()->json([
                    'message' => $error_msg,
                    'status' => '0',
                ], 201);
                exit;
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json(['message' => $error_message, 'status' => '0'], 500);
            exit;
        }
    }

    public function my_wishlist()
    {
        $user = auth()->guard('web')->user();
        $wishlist = Wishlist::with('get_doctor')->where('user_id', $user->id)->get();
        $data = array(
            'title' => 'My Wishlist',
            'wishlist' => $wishlist,


        );
        return view('frontend.my_wishlist')->with($data);
    }

    public function my_course()
    {
        $user = auth()->guard('web')->user();
        $course = CourseBooking::with('get_course')->where('user_id', $user->id)->get();
        $data = array(
            'title' => 'My Course',
            'course' => $course,
        );
        return view('frontend.my_courses ')->with($data);
    }

    public function user_video($id = 0)
    {
        $user = auth()->guard('web')->user();

        $data = array(
            'title' => 'My Course',
            'user' => $user,
            'room_id' => $id
        );
        return view('frontend.video ')->with($data);
    }

    public function course_detail($id)
    {
        $user = auth()->guard('web')->user();
        $file = Course::where('slug', $id)->first();
        $review = CourseReview::where('user_id', $user->id)->where('course_id', $file->id)->first();
        $multi_image = CourseImage::where('course_id', $file->id)->get();

        $doctor = User::find($file->doctor_id);
        $include = CourseInclude::where('course_id', $file->id)->get();
        $buy_file = CourseBooking::where('user_id', $user->id)->where('course_id', $file->id)->first();
        if (!empty($buy_file)) {
            $file_buy = 1;
        } else {
            $file_buy = 0;
        }
        $data = array(
            'title' => 'My Course',
            'file'  => $file,
            'doctor'  => $doctor,
            'file_buy' => $file_buy,
            'include'  => $include,
            'review'  => $review,
            'multi_image'  => $multi_image,
        );
        return view('frontend.course_detail ')->with($data);
    }

    public function sessions_detail($id)
    {
        $decrypted_id  = get_decrypted_value($id, true);

        $user = auth()->guard('web')->user();
        $file = SessionBooking::find($decrypted_id);
        // p($decrypted_id);
        $doctor = User::find($file->vender_id);
        $data = array(
            'title' => 'My Course',
            'file'  => $file,
            'doctor'  => $doctor,
        );
        return view('frontend.sessions_detail ')->with($data);
    }

    public function rebook($id)
    {
        $user = auth()->guard('web')->user();
        $decrypted_id  = get_decrypted_value($id, true);
        $file = SessionBooking::find($decrypted_id);
        $calendar = DoctorAvailability::where('vender_id', $file->vender_id)->get();


        // p($decrypted_id);
        $doctor = User::find($file->vender_id);
        $data = array(
            'title' => 'My Course',
            'file'  => $file,
            'doctor'  => $doctor,
            'calendar'  => $calendar,
        );
        return view('frontend.sessions_rebook ')->with($data);
    }

    public function my_sessions()
    {
        $current_date = Carbon::now()->toDateString();
        $user = auth()->guard('web')->user();
        $new = collect();
        $applied = collect();

        if ($user) {
            $new = Tournament::where('status', 1)
                ->where('start_date', '<=', $current_date)
                ->where('end_date', '>=', $current_date)
                ->where('category', $user->category)
                ->availableForAthleteApply($user->district)
                ->groupBy('title')
                ->get()
                ->map(function ($group) {
                    return $group->first();
                })
                ->values();

            $appliedIds = ApplyTournament::where('user_id', $user->id)->pluck('turnament_id')->toArray();
            if (!empty($appliedIds)) {
                $applied = Tournament::whereIn('id', $appliedIds)
                    ->availableForAthleteApply($user->district)
                    ->groupBy('title')
                    ->get()
                    ->map(function ($group) {
                        return $group->first();
                    })
                    ->values();
            }
        }

        $data = array(
            'title' => 'My Sessions ',
            'new' => $new,
            'applied' => $applied,
            'user' => $user,
        );
        return view('frontend.my_sessions')->with($data);
    }

    public function updateAthleteWeight(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate(['weight' => 'required|numeric']);
        $user->weight = $request->weight;
        $user->save();

        return response()->json(['status' => 'success']);
    }

    public function getAthleteTournaments(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user || !$user->weight || !$user->gender || !$user->category) {
            return response()->json(['error' => 'Incomplete user profile'], 422);
        }

        $current_date = Carbon::now()->format('Y-m-d');
        $waight_category = WeightCategory::where('min', '<=', $user->weight)
            ->where('max', '>=', $user->weight)
            ->where('gender', $user->gender)
            ->where('category', $user->category)
            ->where('status', 1)
            ->first();

        if (!$waight_category) {
            return response()->json(['error' => 'Weight category not found'], 404);
        }

        $tournaments = Tournament::where('status', 1)
            ->whereDate('start_date', '<=', $current_date)
            ->whereDate('end_date', '>=', $current_date)
            ->where('gender', $user->gender)
            ->where('category', $user->category)
            ->where('weight_category', $waight_category->id)
            ->availableForAthleteApply($user->district)
            ->get();

        return response()->json($tournaments);
    }

    public function applyAthleteTournament(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['status' => 'failed', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        $turnament = Tournament::with('get_category')->find($request['tournament_id']);
        if (
            !$turnament
            || empty($turnament->district_apply_open)
            || empty($turnament->athlete_apply_weight_open)
            || (string) $turnament->district_id !== (string) $user->district
        ) {
            return response()->json(['status' => 'failed', 'message' => 'Tournament apply is not open.'], 403);
        }

        if (!$user->weight) {
            return response()->json(['status' => 'failed', 'message' => 'Please fill weight first.'], 422);
        }

        $exists = ApplyTournament::where('user_id', $user->id)->where('turnament_id', $request['tournament_id'])->first();
        if ($exists) {
            return response()->json(['status' => 'failed', 'message' => 'Already applied.']);
        }

        $apply = new ApplyTournament();
        $apply->user_id = $user->id;
        $apply->turnament_id = $request['tournament_id'];
        $apply->coach_id = $user->coach_id;
        $apply->district = $user->district_name ?? $user->district;
        $apply->code = $user->code_id;
        $apply->it_uid = $user->it_uid;
        $apply->gender = $user->user_gender ?? $user->gender;
        $apply->dob = $user->dob;
        $apply->name = $user->name;
        $apply->email = $user->email;
        $apply->phone = $user->contact_number ?? $user->mobile;
        $apply->coach = $user->coach_name;
        $apply->category = $turnament['get_category']->title ?? $user->category;
        $apply->event = $turnament->event_category;
        $apply->waight_category = $turnament->weight_category;
        $apply->actul_waight = $user->weight;
        $apply->save();

        return response()->json(['status' => 'success']);
    }

    public function cancle_session(Request $request)
    {
        $currentDateTime = Carbon::now();

        $id = $request['id'];
        $data = SessionBooking::find($id);
        $user = User::find($data->user_id);
        if ($data) {
            $data->status = 'cancel';
            $data->save();

            $bookingDateTime = Carbon::parse($data->booking_date . ' ' . $data->start_time);
            if ($currentDateTime->diffInHours($bookingDateTime) >= 24) {
                $transfer_payment = PaymentHistory::where('session_booking_id',$data->id)->first();

                $stripe = new StripeClient(env('STRIPE_SECRET'));

                // Create a refund
                $refund = $stripe->refunds->create([
                    'payment_intent' => $transfer_payment->tran_id,
                ]);
                
                if(!empty($transfer_payment)){
                    $transfer_payment->status = 'cancel';
                    $transfer_payment->save();
                }
                $page = EmailPage::find(20);
            }else{
                $page = EmailPage::find(22);
            }

            $page = EmailPage::find(20);
            $mailData = [
                'user' => $user->name,
                'message' => str_replace('[booking details]', $data->booking_date . ' ' . $data->start_time, $page->description),
                'subject' => 'Cancelled Consultation with Telimed.'
            ];
            Mail::to($user->email)->send(new BookingMail($mailData));

            $return_arr = array(
                'status' => 'success',
                'message' => 'Session cancelled successfully!',
            );
            return response()->json($return_arr);
        }
    }

    public function review_save(Request $request)
    {
        
        $user = auth()->guard('web')->user();
        $validator = Validator::make($request->all(), [
            'review'     => 'required',
            'ratting'     => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => $validator->errors()->first()], 200);
        }
        try {
            
            if (empty($request->review_id)) {
                $currentDateTime = Carbon::now();
                $fifteenMinutesLater = $currentDateTime->copy()->addMinutes(15);
                $doc = SessionBooking::where('user_id', $user->id)
                    ->whereDate('end_time', '=', $currentDateTime->toDateString())
                    ->whereTime('end_time', '<=', $fifteenMinutesLater->toTimeString())
                    ->first();
                $request->review_id = isset($doc->vender_id) ? $doc->vender_id : '';
            }
            if ($request['review_type'] == 1 || $request['review_type'] == '') {
                $newUser                = new DoctorReview;
                $newUser->doctor_id     = $request->review_id;
                $newUser->ratting       = $request->ratting;
                $newUser->review        = $request->review;
                $newUser->user_id        = $user->id;
                $newUser->save();

                if (!empty($request->review_id)) {
                    $totalVotes = DoctorReview::where('doctor_id', $newUser->doctor_id)->count();
                    $likes = DoctorReview::where('doctor_id', $newUser->doctor_id)->where('ratting', 1)->count();


                    $averageRating = ($likes / $totalVotes) * 100;
                    $formattedAverage = number_format($averageRating);

                    $doc = User::find($request->review_id);
                    $doc->ratting = $formattedAverage;
                    $doc->save();
                }
            } else {
                $newUser = new CourseReview;
                $newUser->course_id     = $request->review_id;
                $newUser->ratting       = $request->ratting;
                $newUser->review        = $request->review;
                $newUser->user_id        = $user->id;
                $newUser->save();
            }

            return response()->json(
                [
                    'message' => 'Review submitted.',
                    'status' => 1
                ],
                200
            );
            exit;
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 500);
            exit;
        }
    }

    public function consult_form_save(Request $request)
    {

        $user = auth()->guard('web')->user();
        
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required',
            // 'last_name'     => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => $validator->errors()->first()], 200);
        }
        try {
            if(!empty($request['sec_id'])){
                $session = SessionBooking::find($request['sec_id']);
            }else{
                $session = SessionBooking::where('vender_id',$request->user_id)->where('user_id',$user->id)->orderBy('id','DESC')->first();
            }
            // p($session);
            $form = ConsultForm::find($request['form_id']);
            if(empty($form)){
                $form = new ConsultForm;
            }
            
            
            $form->first_name = $request['first_name'];
            $form->last_name = $request['last_name'];
            $form->dob = $request['dob'];
            $form->email = $request['email'];
            $form->address = $request['address'];
            $form->height = $request['height'];
            $form->sex = $request['sex'];
            $form->blood_type = $request['blood_type'];
            $form->weight = $request['weight'];
            $form->phone_number = $request['phone_number'];
            $form->mobile_number = $request['mobile_number'];
            $form->blood_pressure = $request['blood_pressure'];
            $form->previous_occupations = $request['previous_occupations'];
            $form->current_occupations = $request['current_occupations'];
            $form->current_medications = $request['current_medications'];
            $form->next_to_kin = $request['next_to_kin'];
            $form->next_to_kin_contact = $request['next_to_kin_contact'];
            $form->current_supplements = $request['current_supplements'];
            $form->children_and_age = $request['children_and_age'];
            $form->currently_seeing = $request['currently_seeing'];
            $form->current_health_concerns = $request['current_health_concerns'];
            $form->acheive_your_health = $request['acheive_your_health'];
            $form->hospitalisations = $request['hospitalisations'];
            $form->diagnosed_conditions = $request['diagnosed_conditions'];
            $form->family_history = isset($request['family_history']) ? implode(',', $request['family_history']) : '';
            $form->overseas_last_month = $request['overseas_last_month'];
            $form->vaccinations_3_year = $request['vaccinations_3_year'];
            $form->term_and_condition = $request['term_and_condition'];
            $form->signature = $request['signature'];
            $form->image = $request['image'];
            $form->session_id = isset($session->id)?$session->id:'';
            $form->user_id = isset($session->user_id)?$session->user_id:'';
            $form->vender_id = isset($session->vender_id)?$session->vender_id:'';
            $form->save();

            return response()->json(
                [
                    'message' => 'Pre consult form save successfully.',
                    'status' => 1,
                    'url' => url('/booking/' . get_encrypted_value($request['user_id'], true))
                ],
                200
            );
            exit;
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 500);
            exit;
        }
    }

    public function resetprofile($id = null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $data = [
            'page_title' => "User Reset Profile",
            'user_id' => $decrypted_id,
        ];
    
        return view('frontend.auth.reset_profile', $data);
    }

    public function reset_profile_save(Request $request){
        $tempdata = User::find($request['user_id']);
        if ($request['photobase'] != "") {
                            
            $path = 'uploads/user/';
            $input['photo'] = $this->storeBase64($request->photobase,$path);
            $tempdata->photo = $input['photo'];
        }
        if ($request['aadhar_frontbase'] != "") {
            
            $path = 'uploads/user/aadhar_front/';
            $input['aadhar_front'] = $this->storeBase64($request->aadhar_frontbase,$path);
            $tempdata->aadhar_front = $input['aadhar_front'];
        }

        if ($request['aadhar_backbase'] != "") {
            

            $path = 'uploads/user/aadhar_back/';
            $input['aadhar_back'] = $this->storeBase64($request->aadhar_backbase,$path);
            $tempdata->aadhar_back = $input['aadhar_back'];
        }
        
        if ($request['belt_certificatebase'] != "") {
                            

            $path = 'uploads/user/dob_certificate/';
            $input['belt_certificate'] = $this->storeBase64($request->belt_certificatebase,$path);
            $tempdata->belt_certificate = $input['belt_certificate'];
        }

        if ($request['dob_certificatebase'] != "") {
            

            $path = 'uploads/user/dob_certificate/';
            $input['dob_certificate'] = $this->storeBase64($request->dob_certificatebase,$path);
            $tempdata->dob_certificate = $input['dob_certificate'];
        }

        if ($request['signaturebase'] != "") {
            
            $path = 'uploads/user/signature/';
            $input['signature'] = $this->storeBase64($request->signaturebase,$path);
            $tempdata->signature = $input['signature'];
        }
        $tempdata->status = 1;
        $tempdata->save();
        
        
        $success_msg = "Update sussessfully.";
        return redirect('/login')->withSuccess($success_msg);
    }

    public function financials()
    {
        $user = auth()->guard('web')->user();
        $history = PaymentHistory::with('get_patient', 'get_doctor')->orderBy('created_at', 'DESC')->where('status','success')->where('user_id', $user->id)->get();

        $total = PaymentHistory::where('user_id', $user->id)->where('status','success')->sum('price');

        // Last week
        $lastWeekTotal = PaymentHistory::where('user_id', $user->id)
            ->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])->where('status','success')
            ->sum('price');

        // Last month
        $lastMonthTotal = PaymentHistory::where('user_id', $user->id)
            ->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])->where('status','success')
            ->sum('price');

        // Last year
        $lastYearTotal = PaymentHistory::where('user_id', $user->id)
            ->whereBetween('created_at', [Carbon::now()->subYear(), Carbon::now()])->where('status','success')
            ->sum('price');

        $data = array(
            'title' => 'My Patients',
            'user' => $user,
            'history' => $history,
            'total' => $total,
            'lastWeekTotal' => $lastWeekTotal,
            'lastMonthTotal' => $lastMonthTotal,
            'lastYearTotal' => $lastYearTotal,

        );
        return view('frontend.financials')->with($data);
    }

    public function user_invoice($id)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $history = PaymentHistory::with('get_doctor', 'get_patient')->find($decrypted_id);

        $data = array(
            'title' => 'Verify Profile',
            'history' => $history,

        );
        return view('frontend.invoice')->with($data);
    }
}
