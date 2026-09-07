<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Timezone;
use App\Models\DoctorAvailability;
use App\Models\Language;
use App\Models\Category;
use App\Models\UserDetail;
use App\Models\VenderOtherDocument;
use App\Models\Qualification;
use App\Models\Course;
use App\Models\SessionBooking;
use App\Models\EmailPage;
use App\Models\Specialities;
use App\Models\InsuranceProviderDetails;
use App\Models\UserQualification;
use App\Models\Country;
use App\Models\PaymentHistory;
use App\Models\VenderConsultPrice;
use App\Models\VenderPackagePrice;
use App\Models\ConsultForm;
use App\Models\VenderNotAvailable;
use App\Models\TreatmentPlan;
use App\Models\TempUser;
use App\Models\ConsultNote;
use App\Models\DoctorReview;
use App\Models\UserOtp;
use App\Models\AriaIntrest;
use App\Models\Consult;
use App\Models\ChatUser;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use App\Models\DoctorView;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use Cache;
use Illuminate\Support\Facades\Auth;
use Validator;
use Mail;
use DB;
use App\Mail\WelcomeEmail;
use App\Mail\VarifyEmail;

class VenderController extends Controller
{
    public function register(Request $request)
    {
        // p($request->role);
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'     => 'required|unique:users,email,3,status',
            'password'  => 'required',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email address has already been assigned to a member. Please use a different email if you are trying to create a new account.',
            'password.required' => 'The password field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => $validator->errors()->first()], 200);
        }
        try {

            $otpnum = rand(11111, 99999);
            if (!empty($temp_user)) {
                $newUser = TempUser::where('email', $request['email'])->where('role', $request->role)->first();
            } else {
                $newUser = new TempUser;
            }
            $newUser->name             = $request->name;
            $newUser->last_name             = $request->last_name;
            $newUser->email            = $request->email;
            $newUser->password         = Hash::make($request->password);
            $newUser->role             = $request->role;
            $newUser->save();

            $page = EmailPage::find(18);
            $mailData = [
                'otp' => $otpnum,
                'user' => $newUser->name,
                'message' => $page->description,
                'subject' => 'New Member Verfication'
            ];

            Mail::to($newUser->email)->send(new VarifyEmail($mailData));
            $otp_user = UserOtp::where('email', $newUser->email)->first();
            if (!empty($otp_user)) {
                $userotp = UserOtp::find($otp_user->id);
            } else {
                $userotp = new UserOtp;
            }

            $userotp->email        = $request->email;
            $userotp->otp           = $otpnum;
            $userotp->save();




            return response()->json(
                [
                    'message' => 'Register successfully.',
                    'status' => 1,
                    'email' => $request->email,
                ],
                200
            );


            //otp
            // $otp_responce = send_otp($request->email);

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
            if (Auth::guard('vender')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => 2, 'status' => 1]) || Auth::guard('vender')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => 2, 'status' => 2]) || Auth::guard('vender')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => 2, 'status' => 0])) {
                auth()->guard('web')->logout();
                $user = Auth::guard('vender')->user();

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
                        'status' => 2
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

    public function TreatmentPlan_data(Request $request)
    {
        try {
            $user = auth()->guard('vender')->user();
            if ($request->type == 1) {
                $form = TreatmentPlan::where('vender_id', $user->id)->where('user_id', $request['user_id'])->orderBy('id', 'DESC')->first();
            }
            $form = TreatmentPlan::where('vender_id', $user->id)->where('user_id', $request['user_id'])->orderBy('id', 'DESC')->first();


            return response()->json(
                [
                    'message' => 'Logged in successfully.',
                    'status' => 1,
                    'id' => isset($form->id) ? $form->id : '',
                    'user_id' => isset($request['user_id']) ? $request['user_id'] : '',
                    'Patient_Name' => isset($form->Patient_Name) ? $form->Patient_Name : '',
                    'Consultation_Type' => isset($form->Consultation_Type) ? $form->Consultation_Type : '',
                    'Consultation_Date' => isset($form->Consultation_Date) ? $form->Consultation_Date : '',
                    'General_note_to_patient' => isset($form->General_note_to_patient) ? $form->General_note_to_patient : '',
                    'Recommendations' => isset($form->Recommendations) ? $form->Recommendations : '',
                    'Dietary_Recommendations' => isset($form->Dietary_Recommendations) ? $form->Dietary_Recommendations : '',
                    'Supplement' => isset($form->Supplemen) ? $form->Supplement : '',
                    'Handouts_or_documents_to_attach' => isset($form->Handouts_or_documents_to_attach) ? $form->Handouts_or_documents_to_attach : '',
                    'Next_Appointment' => isset($form->Next_Appointment) ? $form->Next_Appointment : '',
                ],
                200
            );
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json(['status' => 2, 'message' => $error_message], 500);
            exit;
        }
    }

    public function ConsultNotes_data(Request $request)
    {
        try {
            $user = auth()->guard('vender')->user();
            if ($request->type == 1) {
                $form = ConsultNote::where('vender_id', $user->id)->where('user_id', $request['user_id'])->orderBy('id', 'DESC')->first();
            }
            $form = ConsultNote::where('vender_id', $user->id)->where('user_id', $request['user_id'])->orderBy('id', 'DESC')->first();
            // p($request['user_id']);

            return response()->json(
                [
                    'message' => 'Logged in successfully.',
                    'status' => 1,
                    'id' => isset($form->id) ? $form->id : '',
                    'user_id' => isset($request['user_id']) ? $request['user_id'] : '',
                    'name' => isset($form->name) ? $form->name : '',
                    'consult_name' => isset($form->consult_name) ? $form->consult_name : '',
                    'main_concerns' => isset($form->main_concerns) ? $form->main_concerns : '',
                    'description' => isset($form->description) ? $form->description : '',

                ],
                200
            );
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json(['status' => 2, 'message' => $error_message], 500);
            exit;
        }
    }
    public function get_consult_form(Request $request)
    {
        try {
            $user = auth()->guard('vender')->user();
            $form = ConsultForm::where('vender_id', $user->id)->orderBy('id', 'DESC')->first();
            $form_data = '';




            if (!empty($form)) {
                $form_data = '<p><span>First Name: </span>' . $form->first_name . '</p>
                <p><span>Last Name: </span>' . $form->last_name . '</p>
                <p><span>Last Name: </span>' . $form->last_name . '</p>
                <p><span>DOB: </span>' . $form->dob . '</p>
                <p><span>Email: </span>' . $form->email . '</p>
                <p><span> address: </span>' . $form->address . '</p>
                <p><span> address: </span>' . $form->address . '</p>
                <p><span> Height: </span>' . $form->height . '</p>
                <p><span> Sex: </span>' . $form->sex . '</p>
                <p><span> Blood type: </span>' . $form->blood_type . '</p>
                <p><span> Weight: </span>' . $form->weight . '</p>
                <p><span> Phone number: </span>' . $form->phone_number . '</p>
                <p><span> Mobile number: </span>' . $form->mobile_number . '</p>
                <p><span> Blood pressure: </span>' . $form->blood_pressure . '</p>
                <p><span> Previous occupations: </span>' . $form->previous_occupations . '</p>
                <p><span> Current occupations: </span>' . $form->current_occupations . '</p>
                <p><span> Next of Kin: </span>' . $form->next_to_kin . '</p>
                <p><span> Current Supplements: </span>' . $form->current_supplements . '</p>
                <p><span> Children and Ages: </span>' . $form->children_and_age . '</p>
                <p><span> What other health and/or medical professionals are you currently seeing?: </span>' . $form->currently_seeing . '</p>
                <p><span> What are you current health concerns?: </span>' . $form->current_health_concerns . '</p>
                <p><span> What are 3 changes you would like to acheive with your health?: </span>' . $form->acheive_your_health . '</p>
                <p><span> Have you had any hospitalisations, surgery, procedures, medical, test, accidents, injuries, C-sections? (What, When and Why?): </span>' . $form->hospitalisations . '</p>
                <p><span> Do yoy have any diagnosed conditions?: </span>' . $form->diagnosed_conditions . '</p>
                <p><span> Family History: </span>' . $form->family_history . '</p>
                <p><span> Have you been on any overseas trips in the last 12 months?: </span>' . $form->overseas_last_month . '</p>
                <p><span> What vaccinations have you had in the last 3 years?: </span>' . $form->vaccinations_3_year . '</p>
                <p><span> Signature: </span>' . $form->signature  . '</p>';
                return response()->json(
                    [
                        'message' => 'Logged in successfully.',
                        'status' => 1,
                        'pre_form' => $form_data,

                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'message' => 'Pre consult form not available .',
                        'status' => 2,
                    ],
                    200
                );
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json(['status' => 2, 'message' => $error_message], 500);
            exit;
        }
    }

    public function logout()
    {
        auth()->guard('vender')->logout();

        return redirect()->route('home');
    }

    public function vender_dashboard()
    {


        $user = auth()->guard('vender')->user();
        $user_time = user::find($user->id);
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i');

        $session = SessionBooking::where('vender_id', $user->id)
            ->where('status', 'active') // Ensure you check only one status here
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('booking_date', '<', $currentDate)
                    ->orWhere(function ($subquery) use ($currentDate, $currentTime) {
                        $subquery->where('booking_date', $currentDate)
                            ->where('end_time', '<', $currentTime);
                    });
            })
            ->pluck('id')
            ->toArray();

        $like_count = DoctorReview::where('doctor_id', $user->id)->where('ratting', 1)->count();

        $income = PaymentHistory::whereIn('session_booking_id',$session)->where('status', 'success')->sum('price');
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->setTimezone($user->user_timezone)->format('H:i');
        // p($currentTime);
        $comm = $income * $user->commission / 100;
        $tax = $income * 2.5 / 100;
        $income = $income - ($comm + $tax);

        $course = Course::where('doctor_id', $user->id)->orderBy('id', 'DESC')->where('status', 1)->limit(3)->get();
        // $currentbooking = SessionBooking::with('get_patient')
        //     ->where(function ($query) use ($currentDate, $currentTime) {
        //         $query->where('booking_date', '>', $currentDate)
        //             ->orWhere(function ($subquery) use ($currentDate, $currentTime) {
        //                 $subquery->where('booking_date', $currentDate)
        //                     ->where('start_time', '>', Carbon::parse($currentTime));
        //             });
        //     })->where('vender_id', $user->id)->orderBy('booking_date', 'asc')->limit(3)->get();

        $currentbooking = SessionBooking::with('get_patient')->where('vender_id', $user->id)
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('booking_date', '>', $currentDate)
                    ->orWhere(function ($subquery) use ($currentDate, $currentTime) {
                        $subquery->where('booking_date', $currentDate)
                            ->where('end_time', '>', $currentTime);
                    });
            })->where('status', 'active')->orderBy('booking_date', 'asc')->orderBy('start_time', 'asc')->limit(3)->get();
        // p($currentbooking);
        // p($currentbooking);
        $countsession = SessionBooking::where('vender_id', $user->id)
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('booking_date', '<', $currentDate)
                    ->orWhere(function ($subquery) use ($currentDate, $currentTime) {
                        $subquery->where('booking_date', $currentDate)
                            ->where('end_time', '<', $currentTime);
                    });
            })->where('status', 'active')->count();

        $graph = PaymentHistory::whereIn('session_booking_id',$session)
            ->where('status', 'success')
            ->select(DB::raw("SUM(price) as total_price"), DB::raw("MONTHNAME(created_at) as month_name"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('total_price', 'month_name');

        $click_graph = DoctorView::where('vender_id', $user->id)
            ->select(DB::raw("SUM(count) as count"), DB::raw("MONTHNAME(created_at) as month_name"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('count', 'month_name');

        $labels = $graph->keys();
        $data1 = $graph->values();

        $labels1 = $click_graph->keys();
        $data11 = $click_graph->values();

        $data = array(
            'title' => 'My Profile',
            // 'user' => $user,
            'currentbooking' => $currentbooking,
            'countsession' => $countsession,
            'course' => $course,
            'like_count' => $like_count,
            'income' => $income,
            'labels' => $labels,
            'data' => $data1,
            'labels1' => $labels1,
            'data1' => $data11,
            'user' => $user_time

        );
        return view('frontend.vender.vender_dashboard')->with($data);
    }

    public function my_calendar()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i');
        $user = auth()->guard('vender')->user();
        $calendar = DoctorAvailability::where('vender_id', $user->id)->groupBy('custom_id')->where('status', 1)->get();
        $not_available = VenderNotAvailable::where('vender_id', $user->id)->groupBy('custom_id')->get();

        // $currentbooking = SessionBooking::with('get_patient')
        //     ->where(function ($query) use ($currentDate, $currentTime) {
        //         $query->where('booking_date', '>', $currentDate)
        //             ->orWhere(function ($subquery) use ($currentDate, $currentTime) {
        //                 $subquery->where('booking_date', $currentDate)
        //                     ->where('start_time', '>', Carbon::parse($currentTime));
        //             });
        //     })->where('vender_id', $user->id)->where('status', 'active')->orderBy('booking_date', 'asc')->get();
        // p($currentbooking);

        $currentbooking = SessionBooking::with('get_patient')->where('vender_id', $user->id)
            ->where('status', 'active')->orderBy('start_time', 'asc')->get();

        $booking = [];
        foreach ($currentbooking as $book) {

            $booking[] =  (object)array(
                "id" => "1",
                "title" => $book['get_patient']->name,
                "url" => url('/patients-detail/' . get_encrypted_value($book->user_id, true)),
                "start" => $book->booking_date . ' ' . $book->start_time,
                "end" => $book->booking_date . ' ' . $book->end_time, // Uncommented and set end date and time
                "backgroundColor" => "#39CCCC",
                "cid" => $book->user_id
            );
        }

        $data = array(
            'title' => 'My Calendar',
            'user' => $user,
            'calendar' => $calendar,
            'currentbooking' => $currentbooking,
            'currentDate' => $currentDate,
            'booking' => $booking,
            'not_available' => $not_available,
        );
        return view('frontend.vender.my_calendar')->with($data);
    }

    public function complete_profile()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i');
        $user_id = auth()->guard('vender')->user()->id;
        $user = User::with('get_detail', 'multi_qulafication')->find($user_id);
        $countsession = SessionBooking::where('vender_id', $user->id)
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('booking_date', '<', $currentDate)
                    ->orWhere(function ($subquery) use ($currentDate, $currentTime) {
                        $subquery->where('booking_date', $currentDate)
                            ->where('end_time', '<', $currentTime);
                    });
            })->where('status', 'active')->count();
        $vender_consult_prices = VenderConsultPrice::where('vender_id', $user->id)->count();
        $user_detail = UserDetail::with('get_quali')->where('doctor_id', $user->id)->first();
        $user_details = UserDetail::where('doctor_id', $user->id)->count();
        if ($user_details > 0 && $vender_consult_prices > 0) {
            $complite = 100;
        } elseif ($user_details > 0) {
            $complite = 66;
        } else {
            $complite = 33;
        }
        $data = array(
            'title' => 'My Profile',
            'user' => $user,
            'countsession' => $countsession,
            'complite' => $complite,
            'user_detail' => $user_detail,


        );
        return view('frontend.vender.complite_profile')->with($data);
    }

    public function my_patients_detail($id)
    {
        $decrypted_id  = get_decrypted_value($id, true);
        $user = auth()->guard('vender')->user();
        $patients = User::find($decrypted_id);

        $doctor = SessionBooking::with('get_doctor')->groupBy('vender_id')->orderBy('booking_date', 'DESC')->where('user_id', $decrypted_id)->where('status', 'active')->whereNot('vender_id', $user->id)->get();

        $ConsultForm = ConsultForm::where('user_id', $decrypted_id)->where('vender_id', $user->id)->orderBy('id', 'DESC')->first();
        $ConsultNote = ConsultNote::where('user_id', $decrypted_id)->where('vender_id', $user->id)->orderBy('id', 'DESC')->first();
        // $TreatmentPlan = TreatmentPlan::where('user_id', $decrypted_id)->where('vender_id', $user->id)->orderBy('id', 'DESC')->first();
        $TreatmentPlan = TreatmentPlan::where('user_id', $decrypted_id)->orderBy('id', 'DESC')->get();

        $birthDateObj = Carbon::parse($patients->dob);
        $currentDateObj = Carbon::now();
        // Calculate the difference in years
        $age = $currentDateObj->diffInYears($birthDateObj);

        $session = SessionBooking::with('get_doctor')->groupBy('vender_id')->orderBy('booking_date', 'DESC')->where('user_id', $decrypted_id)->where('status', 'active')->whereNot('vender_id', $user->id)->get();


        $currentDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i');

        $countsession = SessionBooking::where('vender_id', $user->id)->where('user_id', $decrypted_id)
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('booking_date', '>', $currentDate)
                    ->orWhere(function ($subquery) use ($currentDate, $currentTime) {
                        $subquery->where('booking_date', $currentDate)
                            ->where('end_time', '>', $currentTime);
                    });
            })->where('status', 'active')->count();

        $lastsession = SessionBooking::where('vender_id', $user->id)->where('user_id', $decrypted_id)
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('booking_date', '<', $currentDate)
                    ->orWhere(function ($subquery) use ($currentDate, $currentTime) {
                        $subquery->where('booking_date', $currentDate)
                            ->where('end_time', '<', $currentTime);
                    });
            })->orderBy('booking_date', 'DESC')->where('status', 'active')->first();
        // p($countsession);

        $data = array(
            'title' => 'My Patients',
            'user' => $user,
            'patients' => $patients,
            'doctor' => $doctor,
            'age' => $age,
            'ConsultForm' => $ConsultForm,
            'ConsultNote' => $ConsultNote,
            'TreatmentPlan' => $TreatmentPlan,
            'countsession' => $countsession,
            'lastsession' => $lastsession,

        );
        return view('frontend.vender.patient-detail')->with($data);
    }

    public function vender_video($id = 0)
    {
        $user = auth()->guard('vender')->user();

        $data = array(
            'title' => 'My Course',
            'user' => $user,
            'room_id' => $id
        );
        return view('frontend.vender.video')->with($data);
    }

    public function my_patients(Request $request)
    {
        $user = auth()->guard('vender')->user();
        $patients = SessionBooking::with('get_patient')
            ->where('vender_id', $user->id)
            ->whereHas('get_patient', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request['search'] . '%');
            })
            // ->whereIn('id', function ($query) {
            //     $query->select(\DB::raw('MAX(id)'))
            //         ->from('session_bookings')
            //         ->groupBy('user_id');
            // })
            ->where('status', 'active')
            ->groupBy('user_id')
            ->orderBy('booking_date', 'DESC')
            ->get();
        // p($patients);
        if (!empty($request['search'])) {
            $button = 'Clear';
            $search = $request['search'];
        } else {
            $button = 'Search';
            $search = '';
        }

        $data = array(
            'title' => 'My Patients',
            'user' => $user,
            'patients' => $patients,
            'button' => $button,
            'search' => $search,

        );
        return view('frontend.vender.my-patients')->with($data);
    }

    public function financials()
    {
        $user = auth()->guard('vender')->user();

        $currentDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i');

        $session = SessionBooking::where('vender_id', $user->id)
            ->where('status', 'active') // Ensure you check only one status here
            ->where(function ($query) use ($currentDate, $currentTime) {
                $query->where('booking_date', '<', $currentDate)
                    ->orWhere(function ($subquery) use ($currentDate, $currentTime) {
                        $subquery->where('booking_date', $currentDate)
                            ->where('end_time', '<', $currentTime);
                    });
            })
            ->pluck('id')
            ->toArray();
        // p($session);
        $history = PaymentHistory::whereIn('session_booking_id', $session)

            ->with('get_patient')
            ->orderBy('created_at', 'DESC')
            ->get();
        // p($history);

        $total = PaymentHistory::whereIn('session_booking_id', $session)->where('vender_id', $user->id)->sum('price');
        $comm = $total * $user->commission / 100;
        // $tax = $total * 2.5 / 100;
        $total = $total - $comm;

        // Last week
        $lastWeekTotal = PaymentHistory::whereIn('session_booking_id', $session)->where('vender_id', $user->id)
            ->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])
            ->sum('price');

        $comm1 = $lastWeekTotal * $user->commission / 100;
        // $taxweek = $lastWeekTotal * 2.5 / 100;
        $lastWeekTotal = $lastWeekTotal - $comm1;

        // Last month
        $lastMonthTotal = PaymentHistory::whereIn('session_booking_id', $session)->where('vender_id', $user->id)
            ->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])
            ->sum('price');

        $comm2 = $lastMonthTotal * $user->commission / 100;
        // $taxmonth = $lastMonthTotal * 2.5 / 100;
        $lastMonthTotal = $lastMonthTotal - $comm2;

        // Last year
        $lastYearTotal = PaymentHistory::whereIn('session_booking_id', $session)->where('vender_id', $user->id)
            ->whereBetween('created_at', [Carbon::now()->subYear(), Carbon::now()])
            ->sum('price');


        $comm3 = $lastYearTotal * $user->commission / 100;
        // $taxyear = $lastYearTotal * 2.5 / 100;
        $lastYearTotal = $lastYearTotal - $comm3 ;
        // p($lastYearTotal);
        $data = array(
            'title' => 'My Patients',
            'user' => $user,
            'history' => $history,
            'total' => $total,
            'lastWeekTotal' => $lastWeekTotal,
            'lastMonthTotal' => $lastMonthTotal,
            'lastYearTotal' => $lastYearTotal,

        );
        return view('frontend.vender.financials')->with($data);
    }

    public function profile()
    {
        $user = auth()->guard('vender')->user();
        $vender_consult_prices = VenderConsultPrice::where('vender_id', $user->id)->count();
        // $vender_package_prices = VenderPackagePrice::where('vender_id', $user->id)->count();
        $user_details = UserDetail::where('doctor_id', $user->id)->count();
        if ($user_details > 0 && $vender_consult_prices > 0) {
            $complite = 100;
        } elseif ($user_details > 0) {
            $complite = 66;
        } else {
            $complite = 33;
        }
        $data = array(
            'title' => 'My Profile',
            'user' => $user,
            'complite' => $complite,
            'vender_consult_prices' => $vender_consult_prices,

        );
        return view('frontend.vender.my_profile')->with($data);
    }

    public function price_profile($id = Null)
    {
        $const_name = Consult::where('status', 1)->get();
        $vender = auth()->guard('vender')->user();
        if ($id != "") {
            $saveurl = url('/save-price-profile/' . $id);
            $page_title = 'Update consult price';
        } else {
            $saveurl = url('/save-price-profile');
            $page_title = 'Create consult price';
        }

        $data = array(
            'title' => 'My Profile',
            'user' => $vender,
            'getdata'    => $vender,
            'saveurl'    => $saveurl,
            'const_name'    => $const_name,

        );
        return view('frontend.vender.consult_price')->with($data);
    }

    public function price_profile_save(Request $request)
    {
        $vender = auth()->guard('vender')->user();
        if (!empty($request['consult'])) {
            $product_images = array_values($request['consult']);
            $varientimgdata = VenderConsultPrice::where('vender_id', $vender->id)->get();
            $imagecolumn = array_column($product_images, 'consult_id');

            $ifimage = $varientimgdata->pluck('id')->toArray();
            $notmatchimage = array_diff($ifimage, $imagecolumn);

            foreach ($product_images as $imgkey => $variantt_img) {
                if ($imgkey >= 0) {
                    $varianttimg_id = $variantt_img['consult_id'];

                    if ($varianttimg_id != "") {
                        $variantImg = VenderConsultPrice::find($varianttimg_id);
                    } else {
                        $variantImg = new VenderConsultPrice;
                    }
                    $con_name = Consult::find($variantt_img['consult_name']);

                    $variantImg->vender_id     = $vender->id;
                    $variantImg->type    = $variantt_img['type'];
                    $variantImg->consult_name    = $variantt_img['consult_name'];
                    $variantImg->consult_name_id    = $variantt_img['consult_name'];
                    $variantImg->consult_price    = $variantt_img['consult_price'];
                    $variantImg->time              = $con_name['min'];
                    $variantImg->save();
                }
            }
            if (count($notmatchimage) > 0) {
                foreach ($notmatchimage as $key => $notmatchimagevalue) {
                    $imagedelete = VenderConsultPrice::find($notmatchimagevalue);
                    $imagedelete->delete();
                }
            }
        }
        // p($request['package']);
        if (!empty($request['package'])) {
            $package_val = array_values($request['package']);
            $package_data = VenderPackagePrice::where('vender_id', $vender->id)->get();
            $packagecolumn = array_column($package_val, 'package_id');

            $ifpackage = $package_data->pluck('id')->toArray();
            $notmatchpackage = array_diff($ifpackage, $packagecolumn);

            foreach ($package_val as $imgkey => $variantt_img) {
                if (!empty($variantt_img['title'])) {
                    if ($imgkey >= 0) {
                        $varianttimg_id = $variantt_img['package_id'];

                        if ($varianttimg_id != "") {
                            $variantPac = VenderPackagePrice::find($varianttimg_id);
                        } else {
                            $variantPac = new VenderPackagePrice;
                        }


                        $variantPac->vender_id     = $vender->id;
                        $variantPac->title    = $variantt_img['title'];
                        $variantPac->price    = $variantt_img['price'];
                        $variantPac->time    = $variantt_img['no_of_consult'];
                        $variantPac->time_duration    = $variantt_img['duration'];
                        $variantPac->save();
                    }
                }
            }
            if (count($notmatchpackage) > 0) {
                foreach ($notmatchpackage as $key => $notmatchpackagevalue) {
                    $imagedelete = VenderPackagePrice::find($notmatchpackagevalue);
                    $imagedelete->delete();
                }
            }
        }
        return redirect()->back()->withSuccess('Consult Price added successfully');
    }

    public function profile_edit()
    {
        $user = auth()->guard('vender')->user();
        $timezone = Timezone::where('status', 1)->orderBy('timezone', 'ASC')->get();
        $language = Language::where('status', 1)->orderBy('language', 'ASC')->get();
        $arias = AriaIntrest::where('status', 1)->orderBy('title', 'ASC')->get();
        $specialities = Specialities::where('status', 1)->orderBy('title', 'ASC')->get();

        $country = Country::select('id', 'name')->orderBy('name', 'ASC')->get();
        $data = array(
            'title' => 'My Profile',
            'user' => $user,
            'timezone' => $timezone,
            'language' => $language,
            'arias' => $arias,
            'country' => $country,
            'specialities' => $specialities,

        );
        return view('frontend.vender.edit_profile')->with($data);
    }

    public function profile_save(Request $request)
    {
        // p($request->all());
        $user_data = Auth::guard('vender')->user();
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|unique:users,email,' . $user_data->id . ',id,status,1',
            'mobile'     => 'required|unique:users,mobile,' . $user_data->id . ',id,status,1',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 2, 'message' => $validator->errors()->first()]);
        }
        try {
            $user_data = Auth::guard('vender')->user();

            if ($user_data != "") {
                $user = User::find($user_data->id);


                if ($request['profile'] != "") {
                    $input['image'] = $this->storeBase64($request->image_base64);
                    $user->profile = 'uploads/user/profile/' . $input['image'];
                }

                if ($request->hasFile('video')) {
                    // Validate the video file
                
                    $file = $request->file('video');
                    $originalPath = $file->getRealPath();
                    $outputDirectory = public_path('uploads/user/video');
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                    // Generate a unique name for the output file
                    $outputName = uniqid($originalName . '_') . '.mp4';
                    $outputPath = $outputDirectory . '/' . $outputName;
                
                    // Ensure the output directory exists
                    if (!file_exists($outputDirectory)) {
                        mkdir($outputDirectory, 0755, true);
                    }
                
                    // Convert the video to MP4
                    $ffmpeg = FFMpeg::create([
                        'ffmpeg.binaries'  => '/usr/bin/ffmpeg',   // Replace with the correct path
                        'ffprobe.binaries' => '/usr/bin/ffprobe', // Replace with the correct path
                        'timeout'          => 3600,               // Set timeout (seconds)
                        'ffmpeg.threads'   => 12,                 // Set threads
                    ]);
                
                    $video = $ffmpeg->open($originalPath);
                    $format = new X264('libmp3lame', 'libx264');
                    $format->setKiloBitrate(1000);
                
                    $video->save($format, $outputPath);
                
                    // Save the file path to the database
                    $user->video = 'public/uploads/user/video/' . $outputName;
                }
                

                $user->name = $request->name;
                $user->last_name = $request->last_name;
                $user->dob = $request->dob;
                $user->gender = $request->gender;
                $user->address = $request->address;
                $user->email = $request->email;
                $user->abn_no = $request->abn_no;
                $user->mobile = $request->mobile;
                $user->country_code = $request->country_code;
                $user->timezone = $request->timezone;
                $user->language = isset($request['language']) ? implode(',', $request['language']) : '';
                $user->areas_of_intresres = isset($request['areas_of_intresres']) ? implode(',', $request['areas_of_intresres']) : '';
                $user->specialities = isset($request['specialities']) ? implode(',', $request['specialities']) : '';
                $user->experience = $request->experience;
                $user->age = $request->age;
                $user->bio = $request->bio;
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

    public function add_availability(Request $request)
    {

        $user_data = Auth::guard('vender')->user();
        $validator = Validator::make($request->all(), [
            'time'      => 'required',
            'weeks'     => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        try {
            $user_data = Auth::guard('vender')->user();
            $datacount = DoctorAvailability::where('vender_id', $user_data->id)->orderBy('custom_id', 'DESC')->first();
            // p($datacount->custom_id);

            if ($user_data != "") {

                $data = [
                    '_token' => $request['_token'],
                    'weeks' => $request['weeks'],
                    'time' => $request['time'],
                    'date_type' => $request['date_type'],
                    'start_date' => $request['start_date'],
                    'end_date' => $request['end_date']
                ];
                for ($i = 0; $i < count($data['weeks']); $i++) {

                    $availability = new DoctorAvailability;
                    $availability->vender_id = $user_data->id;
                    $availability->custom_id = isset($datacount->custom_id) ? $datacount->custom_id + 1 : 1;
                    $availability->weeks = $data['weeks'][$i];
                    $timeParts = explode('-', $data['time'][$i]);
                    // Trim any whitespaces from the time parts
                    $startTime = trim($timeParts[0]);
                    $endTime = trim($timeParts[1]);
                    // Convert to 24-hour format if needed
                    $startTime = date('H:i', strtotime($startTime));
                    $endTime = date('H:i', strtotime($endTime));
                    $availability->start_time = $startTime;
                    $availability->end_time = $endTime;
                    $availability->date_type = $data['date_type'];
                    $availability->start_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date'])));
                    $availability->end_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['end_date'])));
                    $availability->save();
                }


                $success_msg = 'Availability add successfully';
                return redirect()->back()->withSuccess($success_msg);
            } else {

                $success_msg = 'User Not Available';
                return redirect()->back()->withSuccess($success_msg);
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            p($error_message);
            return redirect()->back()->withSuccess($error_message);
            exit;
        }
    }

    public function add_not_availability(Request $request)
    {

        $user_data = Auth::guard('vender')->user();
        $validator = Validator::make($request->all(), [
            'time'      => 'required',
            'weeks'     => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        try {
            $user_data = Auth::guard('vender')->user();
            $datacount = VenderNotAvailable::where('vender_id', $user_data->id)->orderBy('custom_id', 'DESC')->first();
            // p($datacount);

            if ($user_data != "") {

                $data = [
                    '_token' => $request['_token'],
                    'weeks' => $request['weeks'],
                    'time' => $request['time'],
                    'date_type' => $request['date_type'],
                    'start_date' => $request['start_date'],
                    'end_date' => $request['end_date']
                ];
                for ($i = 0; $i < count($data['weeks']); $i++) {

                    $availability = new VenderNotAvailable;
                    $availability->vender_id = $user_data->id;
                    $availability->custom_id = isset($datacount->custom_id) ? $datacount->custom_id + 1 : 1;
                    $availability->weeks = $data['weeks'][$i];
                    $timeParts = explode('-', $data['time'][$i]);
                    $availability->start_time = date('H:i', strtotime($timeParts[0]));
                    $availability->end_time = date('H:i', strtotime($timeParts[1]));
                    $availability->date_type = $data['date_type'];
                    $availability->start_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date'])));
                    $availability->end_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['end_date'])));
                    $availability->save();
                }

                $success_msg = 'Not Availability add successfully';
                return redirect()->back()->withSuccess($success_msg);
            } else {

                $success_msg = 'User Not Available';
                return redirect()->back()->withSuccess($success_msg);
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();

            return redirect()->back()->withSuccess($error_message);
            exit;
        }
    }

    public function delete_availability(Request $request)
    {
        $user_data = Auth::guard('vender')->user();
        $id = $request['id'];
        $data = DoctorAvailability::where('custom_id', $id)->where('vender_id', $user_data->id)->update(['status' => 3]);
        // if ($data) {

        //     $data->status = 3;
        //     $data->save();
        $return_arr = array(
            'status' => 'success',
            'message' => 'Availability Deleted Sussessfully!',
        );
        return response()->json($return_arr);
        // }
    }

    public function delete_unavailability(Request $request)
    {
        $user_data = Auth::guard('vender')->user();
        $id = $request['id'];
        $data = VenderNotAvailable::where('custom_id', $id)->where('vender_id', $user_data->id)->delete();
        // if ($data) {

        //     $data->status = 3;
        //     $data->save();
        $return_arr = array(
            'status' => 'success',
            'message' => 'Availability Deleted Sussessfully!',
        );
        return response()->json($return_arr);
        // }
    }

    public function edit_availability(Request $request)
    {

        $user_data = Auth::guard('vender')->user();
        $validator = Validator::make($request->all(), [
            'time'      => 'required',
            'weeks'     => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        try {
            $user_data = Auth::guard('vender')->user();

            if ($user_data != "") {
                $time = DoctorAvailability::where('custom_id', $request->availability_id)->where('vender_id', $user_data->id)->delete();

                $data = [
                    '_token' => $request['_token'],
                    'weeks' => $request['weeks'],
                    'time' => $request['time'],
                    'date_type' => $request['date_type'],
                    'start_date' => $request['start_date'],
                    'end_date' => $request['end_date']
                ];
                for ($i = 0; $i < count($data['weeks']); $i++) {

                    $availability = new DoctorAvailability;
                    $availability->vender_id = $user_data->id;
                    $availability->custom_id = $request->availability_id;
                    $availability->weeks = $data['weeks'][$i];
                    $timeParts = explode('-', $data['time'][$i]);
                    // Trim any whitespaces from the time parts
                    $startTime = trim($timeParts[0]);
                    $endTime = trim($timeParts[1]);
                    // Convert to 24-hour format if needed
                    $startTime = date('H:i', strtotime($startTime));
                    $endTime = date('H:i', strtotime($endTime));
                    $availability->start_time = $startTime;
                    $availability->end_time = $endTime;
                    $availability->date_type = $data['date_type'];
                    $availability->start_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date'])));
                    $availability->end_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['end_date'])));
                    $availability->save();
                }
                $success_msg = 'Availability edit successfully';
                return redirect()->back()->withSuccess($success_msg);
            } else {

                $success_msg = 'User Not Available';
                return redirect()->back()->withSuccess($success_msg);
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();

            return redirect()->back()->withSuccess($error_message);
            exit;
        }
    }

    public function edit_not_availability(Request $request)
    {

        $user_data = Auth::guard('vender')->user();
        $validator = Validator::make($request->all(), [
            'time'      => 'required',
            'weeks'     => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        try {
            $user_data = Auth::guard('vender')->user();

            if ($user_data != "") {
                $time = VenderNotAvailable::where('custom_id', $request->not_availability_id)->where('vender_id', $user_data->id)->delete();

                $data = [
                    '_token' => $request['_token'],
                    'weeks' => $request['weeks'],
                    'time' => $request['time'],
                    'date_type' => $request['date_type'],
                    'start_date' => $request['start_date'],
                    'end_date' => $request['end_date']
                ];
                for ($i = 0; $i < count($data['weeks']); $i++) {

                    $availability = new VenderNotAvailable;
                    $availability->vender_id = $user_data->id;
                    $availability->custom_id = $request->not_availability_id;
                    $availability->weeks = $data['weeks'][$i];
                    $timeParts = explode('-', $data['time'][$i]);
                    // Trim any whitespaces from the time parts
                    $startTime = trim($timeParts[0]);
                    $endTime = trim($timeParts[1]);
                    // Convert to 24-hour format if needed
                    $startTime = date('H:i', strtotime($startTime));
                    $endTime = date('H:i', strtotime($endTime));
                    $availability->start_time = $startTime;
                    $availability->end_time = $endTime;
                    $availability->date_type = $data['date_type'];
                    $availability->start_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date'])));
                    $availability->end_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['end_date'])));
                    $availability->save();
                }
                $success_msg = 'Availability edit successfully';
                return redirect()->back()->withSuccess($success_msg);
            } else {

                $success_msg = 'User Not Available';
                return redirect()->back()->withSuccess($success_msg);
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();

            return redirect()->back()->withSuccess($error_message);
            exit;
        }
    }

    public function get_availability(Request $request)
    {
        try {
            $user_data = Auth::guard('vender')->user();

            if ($user_data != "") {
                $records = DoctorAvailability::where('custom_id', $request['availability_id'])->where('vender_id', $user_data->id)->get()->toArray();

                $records_one = DoctorAvailability::where('custom_id', $request['availability_id'])->where('vender_id', $user_data->id)->first()->toArray();
                $weeks = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $week_set = [];
                // p($records_one);

                foreach ($weeks as $week) {
                    $start_time = '';
                    $end_time = '';
                    $checked = '';
                    $time = '';

                    foreach ($records as $record) {
                        if ($record['weeks'] == $week) {
                            $start_time = date('h:i A', strtotime($record['start_time']));
                            $end_time = date('h:i A', strtotime($record['end_time']));
                            $time = $start_time . '-' . $end_time;
                            $checked = 'checked';
                            break;
                        }
                    }

                    $dayHtml = '<div class="row">
                                    <div class="col-md-5 mt-2 center-checkbox">
                                    <input type="checkbox" name="weeks[]" value="' . $week . '" id="' . $week . '-checkbox" ' . $checked . '>
                                    <label for="' . $week . '-checkbox">' . $week . '</label>
                                    
                                    </div>
                                    <div class="col-md-7 mt-2">
                                    <div class="input-group timerange">
                                        <input class="form-control time-input" value="' . $time . '" name="time[]" type="text" ' . ($start_time ? '' : 'disabled') . '>
                                    </div>
                                    </div>
                                </div>';

                    $week_set[] = $dayHtml;
                }

                $date_set = '<div class="col-md-12 mt-2 center-checkbox">
                <input type="radio" name="date_type" ' . ($records_one['date_type'] == 1 ? 'checked' : '') . ' id="every_week-checkbox" value="1">
                <label for="every_week-checkbox">Every Week</label>
                
              </div>
              <div class="col-md-12 mt-2 center-checkbox">
                <input type="radio" name="date_type" ' . ($records_one['date_type'] == 2 ? 'checked' : '') . ' id="Specific-checkbox" value="2">
                <label for="Specific-checkbox">Specific Date Range</label>
                
              </div>
              <div class="col-md-12 mt-2 center-checkbox">
                <div class="input-group input-daterange col-md-4">
                    <input type="text" name="start_date" value="' . ($records_one['date_type'] == 2 ? $records_one['start_date'] : '') . '" class="start-date form-control">
                    <span class="input-group-addon">to</span>
                    <input type="text" name="end_date" value="' . ($records_one['date_type'] == 2 ? $records_one['end_date'] : '') . '" class="end-date form-control">
                </div>
                
              </div>';

                // p($week_set);

                return response()->json([
                    'status' => 1,
                    'week_set' => $week_set,
                    'date_set' => $date_set,
                    'availability_id' => $request['availability_id'],
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

    public function get_not_availability(Request $request)
    {
        try {
            $user_data = Auth::guard('vender')->user();

            if ($user_data != "") {
                $records = VenderNotAvailable::where('custom_id', $request['availability_id'])->where('vender_id', $user_data->id)->get()->toArray();

                $records_one = VenderNotAvailable::where('custom_id', $request['availability_id'])->where('vender_id', $user_data->id)->first()->toArray();
                $weeks = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $week_set = [];
                // p($records_one);

                foreach ($weeks as $week) {
                    $start_time = '';
                    $end_time = '';
                    $checked = '';
                    $time = '';

                    foreach ($records as $record) {
                        if ($record['weeks'] == $week) {
                            $start_time = date('h:i A', strtotime($record['start_time']));
                            $end_time = date('h:i A', strtotime($record['end_time']));
                            $time = $start_time . '-' . $end_time;
                            $checked = 'checked';
                            break;
                        }
                    }

                    $dayHtml = '<div class="row">
                                    <div class="col-md-5 mt-2 center-checkbox">
                                    <input type="checkbox" name="weeks[]" value="' . $week . '" id="' . $week . '-checkbox" ' . $checked . '>
                                    <label for="' . $week . '-checkbox">' . $week . '</label>
                                    
                                    </div>
                                    <div class="col-md-7 mt-2">
                                    <div class="input-group timerange">
                                        <input class="form-control time-input" value="' . $time . '" name="time[]" type="text" ' . ($start_time ? '' : 'disabled') . '>
                                    </div>
                                    </div>
                                </div>';

                    $week_set[] = $dayHtml;
                }

                $date_set = '<div class="col-md-12 mt-2 center-checkbox">
                <input type="radio" name="date_type" ' . ($records_one['date_type'] == 1 ? 'checked' : '') . ' id="every_week-checkbox" value="1">
                <label for="every_week-checkbox">Every Week</label>
                
              </div>
              <div class="col-md-12 mt-2 center-checkbox">
                <input type="radio" name="date_type" ' . ($records_one['date_type'] == 2 ? 'checked' : '') . ' id="Specific-checkbox" value="2">
                <label for="Specific-checkbox">Specific Date Range</label>
                
              </div>
              <div class="col-md-12 mt-2 center-checkbox">
                <div class="input-group input-daterange col-md-4">
                    <input type="text" name="start_date" value="' . ($records_one['date_type'] == 2 ? $records_one['start_date'] : '') . '" class="start-date form-control">
                    <span class="input-group-addon">to</span>
                    <input type="text" name="end_date" value="' . ($records_one['date_type'] == 2 ? $records_one['end_date'] : '') . '" class="end-date form-control">
                </div>
                
              </div>';

                // p($week_set);

                return response()->json([
                    'status' => 1,
                    'week_set' => $week_set,
                    'date_set' => $date_set,
                    'availability_id' => $request['availability_id'],
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

    public function verify_profile()
    {
        $user = auth()->guard('vender')->user();
        $data = UserDetail::where('doctor_id', $user->id)->first();
        // p($data);
        $category = Category::where('status', 1)->get();
        $qualification = Qualification::where('status', 1)->get();

        $data = array(
            'title' => 'Verify Profile',
            'category' => $category,
            'qualification' => $qualification,
            'data' => $data,

        );
        return view('frontend.vender.verify-profile')->with($data);
    }

    public function verify_profile_save(Request $request)
    {

        $user_data = Auth::guard('vender')->user();
        $validator = Validator::make($request->all(), [
            'category'      => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 2, 'message' => $validator->errors()->first()]);
        }
        try {
            $user_data = Auth::guard('vender')->user();

            if ($user_data != "") {
                $user = UserDetail::where('doctor_id', $user_data->id)->first();
                if (empty($user)) {
                    $user = new UserDetail;
                }


                if (!empty($request['upload_id'])) {
                    $file1 = $request->file('upload_id');
                    $name1 = rand(11111, 99999) . '.' . $file1->getClientOriginalExtension();
                    $request->file('upload_id')->move("uploads/user/upload_id", $name1);
                    $user->upload_id = 'uploads/user/upload_id/' . $name1;
                }

                if (!empty($request['membership_number_doc'])) {
                    $file2 = $request->file('membership_number_doc');
                    $name2 = rand(11111, 99999) . '.' . $file2->getClientOriginalExtension();
                    $request->file('membership_number_doc')->move("uploads/user/membership_number_doc", $name2);
                    $user->membership_number_doc = 'uploads/user/membership_number_doc/' . $name2;
                }

                $user->doctor_id = $user_data->id;
                $user->category = $request->category;
                $user->organization_name = $request->organization_name;
                $user->organization_membership_no = $request->organization_membership_no;
                $user->upload_type = $request->upload_type;
                $user->save();

                // p($request['document']);
                if (!empty($request['document'])) {
                    $product_images3 = array_values($request['document']);
                    $varientimgdata3 = VenderOtherDocument::where('vender_id', $user_data->id)->get();
                    $imagecolumn3 = array_column($product_images3, 'document_id');

                    $ifimage3 = $varientimgdata3->pluck('id')->toArray();
                    $notmatchimage3 = array_diff($ifimage3, $imagecolumn3);

                    foreach ($product_images3 as $imgkey3 => $variantt_img3) {
                        // p($variantt_img3);
                        if ($imgkey3 >= 0) {
                            $varianttimg_id3 = $variantt_img3['document_id'];

                            if ($varianttimg_id3 != "") {
                                $variantImg3 = VenderOtherDocument::find($varianttimg_id3);
                            } else {
                                $variantImg3 = new VenderOtherDocument;
                            }

                            if (!empty($variantt_img3['upload_id'])) {
                                $file3 = $variantt_img3['upload_id'];
                                $name3 = rand(11111, 99999) . '.' . $file3->getClientOriginalExtension();
                                $variantt_img3['upload_id']->move("uploads/user/upload_id", $name3);
                                $variantImg3->upload_id = 'uploads/user/upload_id/' . $name3;
                            }


                            $variantImg3->vender_id     = $user_data->id;
                            $variantImg3->upload_type    = $variantt_img3['upload_type'];
                            $variantImg3->save();
                        }
                    }
                    if (count($notmatchimage3) > 0) {
                        foreach ($notmatchimage3 as $key3 => $notmatchimage3value) {
                            $imagedelete3 = VenderOtherDocument::find($notmatchimage3value);
                            $imagedelete3->delete();
                        }
                    }
                }

                if (!empty($request['qualification'])) {
                    $product_images1 = array_values($request['qualification']);
                    $varientimgdata1 = UserQualification::where('vender_id', $user_data->id)->get();
                    $imagecolumn1 = array_column($product_images1, 'qualification_id');

                    $ifimage1 = $varientimgdata1->pluck('id')->toArray();
                    $notmatchimage1 = array_diff($ifimage1, $imagecolumn1);

                    foreach ($product_images1 as $imgkey => $variantt_img1) {
                        // p($variantt_img1);
                        if ($imgkey >= 0) {
                            $varianttimg_id1 = $variantt_img1['qualification_id'];

                            if ($varianttimg_id1 != "") {
                                $variantImg1 = UserQualification::find($varianttimg_id1);
                            } else {
                                $variantImg1 = new UserQualification;
                            }

                            if ($variantt_img1['graduate_doc'] != "") {
                                $file11 = $variantt_img1['graduate_doc'];
                                $name11 = rand(11111, 99999) . '.' . $file11->getClientOriginalExtension();
                                $variantt_img1['graduate_doc']->move("uploads/user/graduate_doc", $name11);
                                $variantImg1->graduate_doc = 'uploads/user/graduate_doc/' . $name11;
                            }


                            $variantImg1->vender_id     = $user_data->id;
                            $variantImg1->qualification_id    = $variantt_img1['qualification'];
                            $variantImg1->qualification_name    = $variantt_img1['qualification'];
                            $variantImg1->year_graduated    = $variantt_img1['year_graduated'];
                            $variantImg1->save();
                        }
                    }
                    if (count($notmatchimage1) > 0) {
                        foreach ($notmatchimage1 as $key => $notmatchimage1value) {
                            $imagedelete = UserQualification::find($notmatchimage1value);
                            $imagedelete->delete();
                        }
                    }
                }


                if (!empty($request['policy'])) {
                    $product_images = array_values($request['policy']);
                    $varientimgdata = InsuranceProviderDetails::where('doctor_id', $user_data->id)->get();
                    $imagecolumn = array_column($product_images, 'policy_id');

                    $ifimage = $varientimgdata->pluck('id')->toArray();
                    $notmatchimage = array_diff($ifimage, $imagecolumn);

                    foreach ($product_images as $imgkey => $variantt_img) {
                        // p($variantt_img['image']);
                        if ($imgkey >= 0) {
                            $varianttimg_id = $variantt_img['policy_id'];

                            if ($varianttimg_id != "") {
                                $variantImg = InsuranceProviderDetails::find($varianttimg_id);
                            } else {
                                $variantImg = new InsuranceProviderDetails;
                            }


                            $variantImg->doctor_id     = $user_data->id;
                            $variantImg->provider_name    = $variantt_img['policy'];
                            $variantImg->save();
                        }
                    }
                    if (count($notmatchimage) > 0) {
                        foreach ($notmatchimage as $key => $notmatchimagevalue) {
                            $imagedelete = InsuranceProviderDetails::find($notmatchimagevalue);
                            $imagedelete->delete();
                        }
                    }
                }




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

    public function dashboard()
    {
        $user = auth()->guard('vender')->user();
        // p($user);
        $data = array(
            'title' => 'Dashboard',

        );
        return view('frontend.vender_dashboard')->with($data);
    }

    public function pre_consult_form($id)
    {

        $decrypted_id  = get_decrypted_value($id, true);
        $user = auth()->guard('vender')->user();
        $patients = User::find($decrypted_id);
        $data = ConsultForm::where('user_id', $decrypted_id)->where('vender_id', $user->id)->orderBy('id', 'DESC')->first();

        $data = array(
            'title' => 'Pre Consult Form',
            'data' => $data,

        );
        return view('frontend.preconsultform_reciept')->with($data);
    }
    public function treatment_plan($id)
    {

        $decrypted_id  = get_decrypted_value($id, true);
        $user = auth()->guard('vender')->user();
        $data = TreatmentPlan::find($decrypted_id);
        $data = array(
            'title' => 'Traetment Plan',
            'data' => $data,

        );
        return view('frontend.traetmentfoarm')->with($data);
    }
    public function consult_Notes($id)
    {

        $decrypted_id  = get_decrypted_value($id, true);
        $user = auth()->guard('vender')->user();
        $data = ConsultNote::where('user_id', $decrypted_id)->orderBy('id', 'DESC')->get();

        $data = array(
            'title' => 'Consult notes',
            'data' => $data,

        );
        return view('frontend.consultnotes')->with($data);
    }
    public function invoice($id)
    {

        $decrypted_id = get_decrypted_value($id, true);
        $history = PaymentHistory::with('get_doctor', 'get_patient')->find($decrypted_id);

        $data = array(
            'title' => 'Verify Profile',
            'history' => $history,

        );
        return view('frontend.vender.invoice')->with($data);
    }

//     public function account_update()
//     {
        
//         $user 	= auth()->guard('vender')->user();
// 		if (empty($user)) {
// 			return redirect()->route('vender_dashboard')->with('Failed','Please login first!');
// 		}
//         $apiKey = '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';
//         $encodedKey = base64_encode($apiKey . ':');
// 		//$account_id=$request->account_id;
// 		$curl = curl_init();

// 		curl_setopt_array($curl, array(
// 			CURLOPT_URL => 'https://api.stripe.com/v1/accounts',
// 			CURLOPT_RETURNTRANSFER => true,
// 			CURLOPT_ENCODING => '',
// 			CURLOPT_MAXREDIRS => 10,
// 			CURLOPT_TIMEOUT => 0,
// 			CURLOPT_FOLLOWLOCATION => true,
// 			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 			CURLOPT_CUSTOMREQUEST => 'POST',
// 			CURLOPT_POSTFIELDS => 'type=express&business_type=individual',
// 		//	CURLOPT_POSTFIELDS => '',
// 			CURLOPT_HTTPHEADER => array(
// 			'Content-Type: application/x-www-form-urlencoded',
// 			'Authorization: Basic ' . $encodedKey,
// 			),
// 		));

// 		$response = curl_exec($curl);
		
// 		curl_close($curl);
// 		$responses=json_decode($response,TRUE);
// 		//print_r($responses);die;
        
// 		$account_id=$responses['id'];
// 		$userId = $user->id;

// 		User::where('id',$userId)->update(['stripe_account_id'=>$account_id]);

// 		$curl = curl_init();

// 		curl_setopt_array($curl, array(
// 			CURLOPT_URL => 'https://api.stripe.com/v1/account_links',
// 			CURLOPT_RETURNTRANSFER => true,
// 			CURLOPT_ENCODING => '',
// 			CURLOPT_MAXREDIRS => 10,
// 			CURLOPT_TIMEOUT => 0,
// 			CURLOPT_FOLLOWLOCATION => true,
// 			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 			CURLOPT_CUSTOMREQUEST => 'POST',
// 			CURLOPT_POSTFIELDS => 'account='.$account_id.'&type=account_onboarding&refresh_url=https%3A%2F%2Foursistahood.com.au%2Fadd_bank_account&return_url=https://telimed.health/stripe_callback',
// 			CURLOPT_HTTPHEADER => array(
// 			'Content-Type: application/x-www-form-urlencoded',
// 			'Authorization: Basic ' . $encodedKey,
// 			),
// 		));

// 		$response = curl_exec($curl);
// 		$response=json_decode($response,TRUE);
// 		curl_close($curl);
// // p($response['url']);
// 		$url=$response['url'];

// 		return Redirect::to($url);

//     }

    public function account_update()
    {
        $user = Auth::user();
        // live
        $url = 'https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_PWrhAylLfInJ5IIXOzur5fVGyYsZ4spi&scope=read_write';
        

        //test
        // $url = 'https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_PWrhNaiE88Vi9gNwWU4JnkyK1S2Pv0yO&scope=read_write';
        return redirect($url);
    }

    public function stripe_callback(Request $request)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://connect.stripe.com/oauth/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            "client_secret=" . env('STRIPE_SECRET', env('STRIPE_SECRET')) . "&grant_type=authorization_code&code=" . $request->code . ""
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close($ch);
        $server_output = json_decode($server_output, true);
        // p($server_output);

        $user_data = auth()->guard('vender')->user();
        $user_data->stripe_account_id = isset($server_output['stripe_user_id']) ? $server_output['stripe_user_id'] : '';
        $user_data->save();
        return redirect()->route('vender_profile')->with('Success', 'Stripe account setup successfully');
    }

    public function consult_notes_save(Request $request)
    {
        try {
            $current_time = Carbon::now()->format('H:i:s');
            $current_date = Carbon::now()->format('Y-m-d');
            $user = auth()->guard('vender')->user();

            $room = ChatUser::where('convenience_id', $request['room_id'])->where('user_id', '!=', $user->id)->first();

            $session = SessionBooking::where('vender_id', $user->id)
                ->where('start_time', '<=', $current_time)
                ->where('end_time', '>=', Carbon::parse($current_time)->subMinutes(15))
                ->where('booking_date', $current_date)
                ->where('status', 'active')
                ->first();

            if (!empty($session)) {
                $session_id =  $session->id;
            } else {
                $session_id =  '';
            }
            if (!empty($request->id)) {
                $data = ConsultNote::find($request->id);
            } else {
                $data = new ConsultNote;
            }

            if (!empty($request['user_id'])) {
                $data->user_id = $request['user_id'];
            } else {
                $data->user_id = isset($room->user_id) ? $room->user_id : '';
            }
            $data->vender_id = $user->id;
            $data->session_id = $session_id;
            $data->name = $request['name'];
            $data->consult_name = $request['consult_name'];
            $data->main_concerns = $request['main_concerns'];
            $data->description = $request['description'];
            $data->save();
            return response()->json([
                'status' => 1,
                'message' => 'Consult notes added successfully.',
            ]);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'message' => $error_message,
                'status' => 0,
            ]);
        }
    }


    public function treatment_plan_save(Request $request)
    {

        try {
            $current_time = Carbon::now()->format('H:i:s');
            $current_date = Carbon::now()->format('Y-m-d');
            $user = auth()->guard('vender')->user();
            $room = ChatUser::where('convenience_id', $request['room_id'])->where('user_id', '!=', $user->id)->first();

            $session = SessionBooking::where('vender_id', $user->id)
                ->where('start_time', '<=', $current_time)
                ->where('end_time', '>=', Carbon::parse($current_time)->subMinutes(15))
                ->where('booking_date', $current_date)
                ->where('status', 'active')
                ->first();

            if (!empty($session)) {
                $session_id =  $session->id;
            } else {
                $session_id =  '';
            }
            if (!empty($request->id)) {
                $data = TreatmentPlan::find($request->id);
            } else {
                $data = new TreatmentPlan;
            }


            if (!empty($request['user_id'])) {
                $data->user_id = $request['user_id'];
            } else {
                $data->user_id = isset($room->user_id) ? $room->user_id : '';
            }

            $data->vender_id = $user->id;
            $data->session_id = $session_id;
            $data->Patient_Name = $request['Patient_Name'];
            $data->Consultation_Type = $request['Consultation_Type'];
            $data->Consultation_Date = $request['Consultation_Date'];
            $data->General_note_to_patient = $request['General_note_to_patient'];
            $data->Recommendations = $request['Recommendations'];
            $data->Dietary_Recommendations = $request['Dietary_Recommendations'];
            $data->Supplement = $request['Supplement'];
            $data->Handouts_or_documents_to_attach = $request['Handouts_or_documents_to_attach'];
            $data->Next_Appointment = $request['Next_Appointment'];
            $data->save();
            return response()->json([
                'status' => 1,
                'message' => 'Treatment plan added successfully.',
            ]);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'message' => $error_message,
                'status' => 0,
            ]);
        }
    }
}
