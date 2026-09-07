<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DoctorAvailability;
use App\Models\VenderPackagePrice;
use App\Models\PackageBooking;
use App\Models\SessionBooking;
use App\Models\VenderConsultPrice;
use Carbon\Carbon;
use Session;

class UserBookingController extends Controller
{
    
    public function index($id)
    {
        $currentDay = now()->format('l');
        // p($currentDay);
        $user_p = auth()->guard('web')->user();
        $decrypted_id  = get_decrypted_value($id, true);
        $user = User::find($decrypted_id);
        $calendar = DoctorAvailability::whereRaw("FIND_IN_SET('$currentDay',weeks ) > 0")->where('vender_id', $user->id)->orderBy('start_time', 'ASC')->get();
        $const = VenderConsultPrice::where('vender_id', $decrypted_id)->groupBy('time')->pluck('time')->toArray();

        $my_package = PackageBooking::where('user_id', $user_p->id)->where('vender_id', $user->id)->get();

        foreach ($calendar as $key => $cal) {

            $start_time = $cal->start_time;
            $startTime = Carbon::createFromFormat('H:i', $start_time);
            $end_time = Carbon::parse($startTime)->addMinutes(120);

            $time[] = array(
                'id' => $cal->id,
                'start_time' => $start_time,

                'end_time' => $end_time->format('H:i'),
            );
        }

        $data = array(
            'title' => 'Practitioners',
            'user' => $user,
            'calendar' => $calendar,
            'time' => $time,
            'my_package' => $my_package,
            'allowedDurations' => $const,
            'user_p' => $user_p,

        );
        return view('frontend.book')->with($data);
    }

    public function chek_availability(Request $request)
    {
        try {
            $date = $request['select_date'] . ' ' . $request['select_month'];

            $carbonDate = Carbon::createFromFormat('j F Y', $date);

            $formattedDate = $carbonDate->format('Y-m-d');

            $currentDay = $carbonDate->format('l');

            $time = $request['select_time'];
            $doctor = $request['vender_id'];

            $session_booking = SessionBooking::whereIn('id', function ($query) use ($formattedDate, $doctor) {
                $query->select(\DB::raw('MAX(id)'))
                    ->from('session_bookings')
                    ->where('vender_id', $doctor)
                    ->where('booking_date', $formattedDate)
                    ->groupBy('session_id');
            })
                ->orderBy('end_time', 'DESC')
                ->get();



            $timeSlots = [];

            if (count($session_booking) > 0) {

                $sec_id = $session_booking->pluck('session_id')->toArray();
                $uniqueArray = array_unique($sec_id);

                $ava_slot = DoctorAvailability::whereRaw("FIND_IN_SET('$currentDay', weeks) > 0")
                    ->where('vender_id', $doctor)
                    ->whereNotIn('id', $uniqueArray)
                    ->get();


                foreach ($ava_slot as $key => $cal) {

                    $start_time = $cal->start_time;
                    $startTime = Carbon::createFromFormat('H:i', $start_time);
                    $end_time = $startTime->copy()->addMinutes($time); // Use copy to avoid modifying the original instance


                    $endTime = Carbon::createFromFormat('H:i', $cal['end_time']);

                    if ($end_time <= $endTime) {
                        $timeSlots[] = [
                            'id' => $cal->id,
                            'start_time' => $start_time,
                            'end_time' => $end_time->format('H:i'),
                        ];
                    }
                }

                foreach ($session_booking as $key => $session) {
                    $calendar = DoctorAvailability::whereRaw("FIND_IN_SET('$currentDay', weeks) > 0")
                        ->where('vender_id', $doctor)
                        ->where('id', $session->session_id)
                        ->first();

                    $sec_sta_time = $time + 15;

                    $start_time = $session->end_time;
                    $startTime = Carbon::createFromFormat('H:i', $start_time);
                    $startTime->addMinutes(15);

                    $end_time = $startTime->copy()->addMinutes($time);
                    $endTime = Carbon::createFromFormat('H:i', $calendar['end_time']);
                    if ($end_time <= $endTime) {
                        $timeSlots[] = [
                            'id' => $calendar->id,
                            'start_time' => $startTime->format('H:i'),
                            'end_time' => $end_time->format('H:i'),
                        ];
                    }
                }
            } else {

                $calendar = DoctorAvailability::whereRaw("FIND_IN_SET('$currentDay', weeks) > 0")
                    ->where('vender_id', $doctor)
                    ->orderBy('start_time', 'ASC')
                    ->get();

                foreach ($calendar as $key => $cal) {

                    $start_time = $cal->start_time;
                    $startTime = Carbon::createFromFormat('H:i', $start_time);
                    $end_time = $startTime->copy()->addMinutes($time); // Use copy to avoid modifying the original instance
                    $endTime = $end_time;

                    if ($end_time <= $endTime) {
                        $timeSlots[] = [
                            'id' => $cal->id,
                            'start_time' => $start_time,
                            'end_time' => $end_time->format('H:i'),
                        ];
                    }
                }
            }
            // p($timeSlots);
            if (!empty($timeSlots)) {
                usort($timeSlots, function ($a, $b) {
                    // Compare start_time values as strings
                    return strcmp($a['start_time'], $b['start_time']);
                });
                $doctorTimezone = 'Australia/Adelaide';
                $patientTimezone = 'Australia/Brisbane';

                $sec_button = "";
                foreach ($timeSlots as $key => $cal) {
                    if ($key == 0) {
                        $active = 'active';
                    } else {
                        $active = '';
                    }

                    $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $cal['start_time'], $doctorTimezone);
                    $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $cal['end_time'], $doctorTimezone);

                    // Convert to patient's timezone
                    $startDateTime->setTimezone($patientTimezone);
                    $endDateTime->setTimezone($patientTimezone);

                    $startTime = $startDateTime->format('h:i a');
                    $endTime = $endDateTime->format('h:i a');

                    $sec_button .= '<div class="col-md-6 mb-3">
                        <button class="calendar-button ' . $active . '" data-sec-id="' . $cal['id'] . '" data-start="' . $cal['start_time'] . '" data-end="' . $cal['end_time'] . '">
                            ' . date("h:i a", strtotime($cal['start_time'])) . ' to ' . date("h:i a", strtotime($cal['end_time'])) . '
                            </button>
                        </div>';
                    }

                return response()->json([
                    'message' => 'data get successfully',
                    'status' => 1,
                    'data'  => $sec_button,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Slots not available',
                    'status' => 2,
                    'data'  => '',
                ], 200);
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
            return response()->json(['message' => 'Something went wrong.', 'status' => 500, 'data' => ''], 200);
        }
    }



    public function preconsult_form($id)
    {
        $file = auth()->guard('web')->user();
        // p($file);
        $decrypted_id  = get_decrypted_value($id, true);
        $user = User::find($decrypted_id);
        $data = array(
            'title' => 'Practitioners',
            'user' => $user,
            'file' => $file,

        );
        return view('frontend.preconsult_form')->with($data);
    }


    public function book_package($id)
    {
        $user_p = auth()->guard('web')->user();

        $decrypted_id  = get_decrypted_value($id, true);
        $my_package = PackageBooking::where('user_id', $user_p->id)->where('remaining_package', '>', 0)->where('vender_id', $decrypted_id)->pluck('package_id')->toArray();

        $user = User::find($decrypted_id);

        $package = VenderPackagePrice::whereNotIn('id', $my_package)->where('vender_id', $decrypted_id)->get();
        // p($package);
        $data = array(
            'title' => 'Practitioners',
            'user' => $user,
            'package' => $package,

        );
        return view('frontend.book_package')->with($data);
    }

    public function reschedule($id)
    {
        $user = auth()->guard('web')->user();
        $decrypted_id  = get_decrypted_value($id, true);
        $file = SessionBooking::find($decrypted_id);
        $calendar = DoctorAvailability::where('vender_id', $file->vender_id)->where('id', $file->session_id)->get();

        // $calendar = DoctorAvailability::whereRaw("FIND_IN_SET('$currentDay',weeks ) > 0")->where('vender_id', $user->id)->orderBy('start_time', 'ASC')->get();

        $const = VenderConsultPrice::where('vender_id', $file->vender_id)->where('time', $file->duration)->where('consult_price', $file->session_price)->pluck('time')->toArray();
        // p($const);

        $data = array(
            'title' => 'Practitioners',
            'user' => $user,
            'calendar' => $calendar,
            'allowedDurations' => $const,
            'file' => $file,
            'vender_id' => $file->vender_id,

        );


        return view('frontend.reschedule')->with($data);
    }


    public function reschedule_save(Request $request)
    {
        // p($request->all());
        $book_date = $request['date'] . ' ' . $request['month'];
        $carbonDate = Carbon::createFromFormat('d F Y', $book_date);
        p($carbonDate);
        if (!empty($request['session_booking_id'])) {

            $purchage                    = SessionBooking::find($request['session_booking_id']);
            $purchage->booking_date      = $carbonDate;
            $purchage->start_time        = $request->start_time;
            $purchage->end_time          = $request->end_time;
            $purchage->status            = 'active';
            $purchage->save();


            // $firebaseTokens  = User::where('id', $file->user_id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $not_user = User::find($file->user_id);
            //     $notification_msg = isset($file->file_type) ? $file->file_type == 1 ? $not_user->name . 'purchase your video' : $not_user->name . 'purchase your music' : '';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }

            // $not = new Notifications;
            // $not->message = isset($file->file_type) ? $file->file_type == 1 ? 'purchase your video' : 'purchase your music' : '';
            // $not->reciver_userId = $file->user_id;
            // $not->sender_userId = $user->id;
            // $not->save();


            // $resp = $this->hostHandAmountTransfer($purchage->id);


            // $firebaseTokens  = User::where('id', $user->id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $notification_msg = 'Purchage Successfully';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }

            $success_msg = 'Session reschedule Successfully.';
            return redirect()->route('my_sessions')->withSuccess($success_msg);
            exit;
        } else {
            return response()->json(['status' => 2, 'message' => 'Session not reschedule'], 400);
        }
    }
}
