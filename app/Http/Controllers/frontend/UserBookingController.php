<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DoctorAvailability;
use App\Models\EmailPage;
use App\Models\VenderPackagePrice;
use App\Models\PackageBooking;
use App\Models\VenderNotAvailable;
use App\Models\SessionBooking;
use App\Models\VenderConsultPrice;
use Illuminate\Support\Facades\Config;
use App\Models\ConsultForm;
use DateTime;
use Carbon\Carbon;
use Auth;
use App\Mail\BookingMail;
use Mail;
use Session;

class UserBookingController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->guard('web')->user();
            if (!empty($user)) {
                date_default_timezone_set(isset($user->user_timezone)?$user->user_timezone:'Australia/Sydney');
            }
            return $next($request);
        });
    }
    public function getDisabledDates(Request $request)
    {
        try {
            $disabledDates = [];
            $currentDate = now();
            
            for ($i = 0; $i < 6; $i++) {
                $date = new DateTime();
                $date->modify("+$i month");

                // Extract Month and Year
                $month = $date->format('n'); // Month without leading zeros
                $year = $date->format('Y');

                // Retrieve Doctor Availability
                $calendar = DoctorAvailability::where('status', 1)
                ->where('vender_id', $request['vender_id'])
                ->where('date_type',1)
                ->get()
                ->toArray();

                $calendar2 = DoctorAvailability::where('status', 1)
                ->where('vender_id', $request['vender_id'])
                ->whereDate('end_date', '>=', $currentDate)
                ->get()
                ->toArray();

                $dates2 = [];
                foreach ($calendar2 as $day2) {
                    $startDate = new DateTime($day2['start_date']); // Start date
                    $endDate = new DateTime($day2['end_date']); // End date
                    $weekday = $day2['weeks']; // Weekday name, e.g., 'Wednesday'

                    while ($startDate <= $endDate) {
                       
                        $dates2[] = $startDate->format('Y-m-d'); // Add the date to the array
                        $startDate->modify('+7 days'); // Move to the next same weekday
                    }
                }
                
                $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                foreach ($calendar as $timetable) {
                    $weeks = explode(',', $timetable['weeks']);
                    foreach ($weeks as $week) {
                        $index = array_search($week, $daysOfWeek);
                        if ($index !== false) {
                            unset($daysOfWeek[$index]);
                        }
                    }
                }

                $daysNotInTables = array_values($daysOfWeek);
                
                // Generate Disabled Dates
                $dates = [];
                foreach ($daysNotInTables as $day) {
                    $date = new DateTime("$year-$month-01");
                    $date->modify("next $day");

                    while ($date->format('n') == $month) {
                        $dates[] = $date->format('Y-m-d');
                        $date->modify('+7 days');
                    }
                }
                $filteredDates = array_diff($dates, $dates2);
                $dates = array_values($filteredDates);
                $not_ava = VenderNotAvailable::where('vender_id', $request['vender_id'])->get()->toArray();

                // Filter Dates Within Month
                $datesWithinMonth = [];
                foreach ($not_ava as $entry) {
                    $startDate = new DateTime($entry['start_date']);
                    $endDate = new DateTime($entry['end_date']);
                    
                    while ($startDate <= $endDate) {
                        if ((int)$startDate->format('n') == $month) {
                            $datesWithinMonth[] = $startDate->format('Y-m-d');
                        }
                        $startDate->modify('+1 day');
                    }
                }

                // Merge Disabled Dates
                $mergedArray = array_merge($dates, $datesWithinMonth);
                $mergedArray = array_unique($mergedArray);

                // Merge Disabled Dates into Result Array
                $disabledDates = array_merge($disabledDates, $mergedArray);
            }

            return response()->json($disabledDates);
        } catch (\Throwable $e) {
            return $e->getMessage();
            return response()->json(['message' => 'Something went wrong.', 'status' => 500, 'data' => ''], 200);
        }
    }

    public function index($id)
    {
        $currentTime = Carbon::now();
        
        $currentDay = now()->format('l');
        // p($currentDay);
        $user_p = auth()->guard('web')->user();
        // p($currentTime);
        $decrypted_id  = get_decrypted_value($id, true);
        $user = User::find($decrypted_id);
        $calendar = DoctorAvailability::whereRaw("FIND_IN_SET('$currentDay',weeks ) > 0")->where('status', 1)->where('vender_id', $user->id)->orderBy('start_time', 'ASC')->get();

        $const_book = SessionBooking::where('user_id', $user_p->id)->where('status','active')->where('vender_id', $user->id)->count();
        if($const_book>0){
           
            $const = VenderConsultPrice::where('vender_id', $decrypted_id)->where('type', 2)->groupBy('time')->pluck('time')->toArray();
            if(empty($const)){
                $const = VenderConsultPrice::where('vender_id', $decrypted_id)->where('type', 1)->groupBy('time')->pluck('time')->toArray();
            }
        }else{
            $const = VenderConsultPrice::where('vender_id', $decrypted_id)->where('type', 1)->groupBy('time')->pluck('time')->toArray();
        }
        // p($const);

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
            // 'time' => $time,
            'my_package' => $my_package,
            'allowedDurations' => $const,
            'user_p' => $user_p,

        );
        return view('frontend.book')->with($data);
    }

    public function chek_availability(Request $request)
    {
        try {
            
            if(!empty($request['select_date']) && !empty($request['select_month'])){
                $date = $request['select_date'] . ' ' . $request['select_month'];
            }else{
                $date = Carbon::now()->format('d F Y');
            }

           

            $carbonDate = Carbon::createFromFormat('j F Y', $date);
    
            $formattedDate = $carbonDate->format('Y-m-d');

            $carbonDate = Carbon::createFromFormat('j F Y', $date);

            $formattedDate = $carbonDate->format('Y-m-d');

            
            
            
            $not_ava = VenderNotAvailable::where('vender_id', $request['vender_id'])->get()->toArray();
            
            
            
            $selectedDateTime = new DateTime($formattedDate);
            
            $available = true;
            foreach ($not_ava as $unavailableDate) {
                $startDate = new DateTime($unavailableDate['start_date']);
                $endDate = new DateTime($unavailableDate['end_date']);

                if ($selectedDateTime >= $startDate && $selectedDateTime <= $endDate) {
                    $available = false;
                    break;
                }
            }
            if ($available != 1) {
                return response()->json([
                    'message' => 'Slots not available',
                    'status' => 2,
                    'data'  => '',
                ], 200);
            }

            $currentDay = $carbonDate->format('l');

            $sec_time = $request['select_time'];

            $doctor = $request['vender_id'];
            $doc_time = User::find($request['vender_id']);
            // p($doc_time);

            $not_ava = VenderNotAvailable::
                where(function ($query) use ($currentDay,$formattedDate) {
                    $query->where(function ($q) use ($currentDay) {
                        $q->whereRaw("FIND_IN_SET('$currentDay', weeks) > 0")
                            ->where('date_type', 1);
                    })->orWhere(function ($q) use ($currentDay,$formattedDate) {
                        $q->whereRaw("FIND_IN_SET('$currentDay', weeks) > 0")
                            ->where('start_date', '<=', $formattedDate)
                            ->where('end_date', '>=', $formattedDate)
                            ->where('date_type', 2);
                    });
                })
                ->where('vender_id', $doctor)
                ->orderBy('start_time', 'ASC')
                ->get()->toArray();
            


            $calendar = DoctorAvailability::
                where(function ($query) use ($currentDay,$formattedDate) {
                    $query->where(function ($q) use ($currentDay) {
                        $q->whereRaw("FIND_IN_SET('$currentDay', weeks) > 0")
                            ->where('date_type', 1);
                    })->orWhere(function ($q) use ($currentDay,$formattedDate) {
                        $q->whereRaw("FIND_IN_SET('$currentDay', weeks) > 0")
                            ->where('start_date', '<=', $formattedDate)
                            ->where('end_date', '>=', $formattedDate)
                            ->where('date_type', 2);
                    });
                })
                ->where('status', 1)
                ->where('vender_id', $doctor)
                ->orderBy('start_time', 'ASC')
                ->get();

                // p($calendar);  
            $session_booking = SessionBooking::where('vender_id', $doctor)
                ->where('booking_date', $formattedDate)
                ->orderBy('start_time', 'DESC')->where('status', 'active')->get();
            
            $availableSlots = [];
            
            if (count($session_booking) > 0) {
                $mergedSchedule = [];
                
                foreach ($calendar as $slot) {
                    $startTime = Carbon::parse($slot->start_time);
                    $endTime = Carbon::parse($slot->end_time);
                    // Loop through each hour
                    $startTimeValue = '';
                    $endTimeValue = '';
                    $bufferTimeValue = '';
                    $tempObject = array();
                    $tempObject['id'] = $slot->id;
                    $tempObject['vender_id'] = $slot->vender_id;
                    $tempObject['weeks'] = $slot->weeks;
                    $tempObject['start_time'] = '';
                    $tempObject['end_time'] = '';
                    while (
                        $startTime <= $endTime
                    ) {
                        
                        $patientTimezone = auth()->guard('web')->user()->user_timezone;
                        $time = $startTime->format('H:i');
                        
                        // Output the current time
                        $session_booked = SessionBooking::where('vender_id', $doctor)
                            ->where('booking_date', $formattedDate)
                            ->where("start_time", $time)
                            ->where('session_id', $slot['id'])
                            ->orderBy('start_time', 'ASC')->where('status', 'active')->first();

                            
                        if (!empty($session_booked)) {
                            $startTimeValue = $session_booked->start_time;
                            $endTimeValue = $session_booked->end_time;
                            $add15Min =    Carbon::parse($session_booked->end_time);
                            $add15Min->addMinutes(15);
                            $bufferTimeValue = $add15Min;
                            $bufferTimeValue = $bufferTimeValue->format('H:i');
                            
                            // p($bufferTimeValue);
                        }
                        // p($startTimeValue);
                        if ($startTimeValue == $time) {

                            if ($tempObject['start_time'] != '') {
                                $tempObject['end_time'] = $time;
                                $mergedSchedule[] = $tempObject;

                                $tempObject = array();
                                $tempObject['id'] = $slot->id;
                                $tempObject['vender_id'] = $slot->vender_id;
                                $tempObject['weeks'] = $slot->weeks;
                                $tempObject['start_time'] = '';
                                $tempObject['end_time'] = '';
                                // print_r($tempObject);
                            }
                        } else if ($endTimeValue == $time) {
                            // p(2);
                        } else if ($bufferTimeValue == $time) {
                            // p($time);
                            $tempObject['start_time'] = $time;
                        } else {
                            
                            if ($tempObject['start_time'] == '') {
                                // p($tempObject['start_time']);
                                $tempObject['start_time'] = $time;
                                // p($tempObject['start_time']);
                            } else {
                                // p(6);
                                $tempObject['end_time'] = $time;
                            }
                        }

                        // Increment the time by 1 hour

                        // $startTime = $endTime;
                        $startTime->addMinutes(15);
                        // p($startTime);
                    }
                    $mergedSchedule[] = $tempObject;
                }
                // p($mergedSchedule);
                

                foreach ($mergedSchedule as $availability) {
                    

                    // $startTime = Carbon::parse($availability['start_time']);

                    // $endTime = Carbon::parse($availability['end_time']);

                    $doctorTimezone = $doc_time->timezone;
                    $patientTimezone = auth()->guard('web')->user()->user_timezone;
                    
                    $startTime = Carbon::parse($availability['start_time'], $doctorTimezone);
                    $endTime = Carbon::parse($availability['end_time'], $doctorTimezone);
                    
                    // Convert the start and end times to the patient's timezone
                    if(date('d F Y')==$date){
                        // p(1);
                        $startTime->setTimezone($patientTimezone);
                        $endTime->setTimezone($patientTimezone);
                    }
                    
                    while ($startTime->lt($endTime)) {
                        $slotEndTime = $startTime->copy()->addMinutes($sec_time);
                        $slotEndTime1 = $slotEndTime->copy()->addMinutes(15);
                        if ($slotEndTime1 <= $endTime) {
                            if($startTime->format('H:i') >= date('H:i') || date('d F Y')!=$date){
                                $availableSlots[] = [
                                    'start_time' => $startTime->format('H:i'),
                                    'end_time' => $slotEndTime->format('H:i'),
                                    'id' => $availability['id'],
                                ];
                            }
                            
                        }


                        $startTime->addMinutes($sec_time + 15);
                        
                    }
                }
                
            } else {
                // If there are no booked sessions, all slots are available
                
                foreach ($calendar as $availability) {
                    $doctorTimezone = $doc_time->timezone;
                    $patientTimezone = auth()->guard('web')->user()->user_timezone;
                    
                    $startTime = Carbon::parse($availability->start_time, $doctorTimezone);
                    $endTime = Carbon::parse($availability->end_time, $doctorTimezone);
                    
                    // Convert the start and end times to the patient's timezone
                    if(date('d F Y')==$date){
                        // p(1);
                        $startTime->setTimezone($patientTimezone);
                        $endTime->setTimezone($patientTimezone);
                    }
                    
                    while ($startTime->lt($endTime)) {
                        $slotEndTime = $startTime->copy()->addMinutes($sec_time);
                        
                        if ($slotEndTime <= $endTime) {
                            // p(date('H:i'));
                            if($startTime->format('H:i') >= date('H:i') || date('d F Y')!=$date){
                                // p($endTime);
                                $availableSlots[] = [
                                    'start_time' => $startTime->format('H:i'),
                                    'end_time' => $slotEndTime->format('H:i'),
                                    'id' => $availability->id,
                                ];
                            }
                        }
                        

                        $startTime->addMinutes($sec_time + 15);
                    }
                   
                }
            }
            

            
            $filteredSlots = [];
            
            foreach ($availableSlots as $availableSlot) {
                $isAvailable = true;
                foreach ($not_ava as $unavailableSlot) {
                    // Convert start_time and end_time strings to DateTime objects for easier comparison
                    $startAvailable = DateTime::createFromFormat('H:i', $availableSlot['start_time']);
                    $endAvailable = DateTime::createFromFormat('H:i', $availableSlot['end_time']);
                    $startUnavailable = DateTime::createFromFormat('H:i', $unavailableSlot['start_time']);
                    $endUnavailable = DateTime::createFromFormat('H:i', $unavailableSlot['end_time']);

                    // Check if the available slot overlaps with any unavailable slot
                    if (($startAvailable >= $startUnavailable && $startAvailable < $endUnavailable) ||
                        ($endAvailable > $startUnavailable && $endAvailable <= $endUnavailable) ||
                        ($startAvailable <= $startUnavailable && $endAvailable >= $endUnavailable)) {
                        $isAvailable = false;
                        break;
                    }
                }
                // If the available slot doesn't overlap with any unavailable slot, add it to the filtered array
                if ($isAvailable) {
                    $filteredSlots[] = $availableSlot;
                }
            }
            $availableSlots = $filteredSlots;
            
            
            if (!empty($availableSlots)) {
                usort($availableSlots, function ($a, $b) {
                    // Compare start_time values as strings
                    return strcmp($a['start_time'], $b['start_time']);
                });
                // $user_time = User::with('time')
                $sec_button = "";
                $doctorTimezone = $doc_time->timezone;
                $patientTimezone = auth()->guard('web')->user()->user_timezone;
                // echo $doctorTimezone;
                // echo $patientTimezone;
                // die;
                foreach ($availableSlots as $key => $cal) {
                    
                    // Assuming the format in your data is 'H:i' and we use the current date
                    $currentDate = Carbon::now()->format('Y-m-d');

                    $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $currentDate . ' ' . $cal['start_time'], $doctorTimezone);
                    $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $currentDate . ' ' . $cal['end_time'], $doctorTimezone);
                    
                    // Convert to patient's timezone
                    if(date('d F Y')!=$date){
                        
                        $startDateTime->setTimezone($patientTimezone);
                        $endDateTime->setTimezone($patientTimezone);
                        
                    }else{
                        
                        $cal['start_time'] = Carbon::createFromFormat('Y-m-d H:i', $currentDate . ' ' . $cal['start_time'], $patientTimezone);
                        $cal['end_time'] = Carbon::createFromFormat('Y-m-d H:i', $currentDate . ' ' . $cal['end_time'], $patientTimezone);

                        $cal['start_time']->setTimezone($doctorTimezone);
                        $cal['end_time']->setTimezone($doctorTimezone);

                        $cal['start_time'] = $cal['start_time']->format('H:i');
                        $cal['end_time'] = $cal['end_time']->format('H:i');
                        
                    }

                    $startTime = $startDateTime->format('h:i A');
                    $endTime = $endDateTime->format('h:i A');

                    $active = $key == 0 ? 'active' : '';

                    $sec_button .= '<div class="col-md-6 mb-3">
                        <button class="calendar-button ' . $active . '" data-sec-id="' . $cal['id'] . '" data-start="' . $cal['start_time'] . '" data-timezonestart="' . $startTime . '" data-end="' . $cal['end_time'] . '">
                            ' . $startTime . ' to ' . $endTime . '
                        </button>
                    </div>';
                    
                }
            //    p($sec_button);

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

    function updateScheduleWithBreaks($schedule, $bookingSessions)
    {
        $updatedSchedule = [];

        foreach ($schedule as $slot) {
            $updatedSchedule[] = $slot;

            foreach ($bookingSessions as $booking) {
                if ($booking['start_time'] >= $slot['start_time'] && $booking['start_time'] < $slot['end_time']) {
                    // Insert break after the booking
                    $breakStart = $booking['end_time'];
                    $breakEnd = date('H:i', strtotime("{$breakStart} +15 minutes"));

                    $updatedSchedule[] = [
                        'vender_id' => $slot['vender_id'],
                        'start_time' => $breakStart,
                        'end_time' => $breakEnd,
                        'weeks' => $slot['weeks'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        return $updatedSchedule;
    }

    public function preconsult_form(Request $request,$id = Null)
    {
        $file = auth()->guard('web')->user();
        $form = '';
        $user = '';
        $edit = '';
        $decrypted_id  = $request['vender_id'];
        if(!empty($id)){
            $form = ConsultForm::where(['user_id'=>$file->id,'vender_id'=>$id])->orderBy('id','DESC')->first();
            $user = User::find($id);
            $edit = 'Edit ';
        }else{
            $user = User::find($decrypted_id);
        }
        
        $data = array(
            'title' => 'Practitioners',
            'user' => $user,
            'file' => $file,
            'form' => $form,
            'edit' => $edit,
            'sec_id' => isset($form->session_id)?$form->session_id:$request['sec_id'],

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
        $calendar = DoctorAvailability::where('vender_id', $file->vender_id)->where('id', $file->session_id)->where('status', 1)->get();

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
        $user = Auth::user();
        $book_date = $request['date'] . ' ' . $request['month'];
        $carbonDate = Carbon::createFromFormat('d F Y', $book_date);
        $formattedDate = $carbonDate->format('Y-m-d');

        if (!empty($request['session_booking_id'])) {

            $purchage                    = SessionBooking::find($request['session_booking_id']);
            $purchage->booking_date      = $formattedDate;
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
            $page = EmailPage::find(21);
            $mailData = [
                'user' => $user->name,
                'message' => str_replace('[booking details]', $purchage->start_time . ' ' . $purchage['booking_date'], $page->description),
                'subject' => 'Rescheduled Consulation with Telimed'
            ];
            Mail::to($user->email)->send(new BookingMail($mailData));

            $success_msg = 'Session reschedule Successfully.';
            return redirect()->route('my_sessions')->withSuccess($success_msg);
            exit;
        } else {
            return response()->json(['status' => 2, 'message' => 'Session not reschedule'], 400);
        }
    }
}
