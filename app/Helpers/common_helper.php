<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\UserOtp;
use App\Models\User;
use App\Models\Convenien;
use App\Models\ChatUser;
use App\Models\SessionBooking;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\VarifyEmail;
use App\Mail\WelcomeEmail;

function imageUrl($value, $type)
{
  if ($value && file_exists(public_path() . '/' . $value)) {
    return asset('public/' . $value);
  } else {
    return asset('public/users/images/169475900832.png');
  }
}

function splitIntoEqualParts($array, $parts = 2) {
    $length = count($array);
    $chunks = [];
    $start = 0;

    for ($i = $parts; $i > 0; $i--) {
        $size = ceil($length / $i);
        $chunks[] = array_slice($array, $start, $size, true);
        $start += $size;
        $length -= $size;
    }

    return $chunks;
}

function groupMatches($drawSheets, $matchType)
{
    if (!isset($drawSheets[$matchType])) {
        return [];
    }

    $matches = [];
    $grouped = $drawSheets[$matchType]->groupBy('match_group');

    foreach ($grouped as $group) {
        $match = [];
        foreach ($group as $player) {
            $match[] = [
                'user_id'   => $player->user_id,
                'user_name' => $player->user_name,
                'group_set' => $player->group_set,
                'match_group' => $player->match_group,
                'district' => $player->district,
                'code' => $player->code,
                'sheet' => $player->sheet,
                'status_class' => ($player->status ?? 1) == 2 ? 'winner' : 
                  (($player->status ?? 1) == 3 ? 'looser' : ''),

                
            ];
        }
        // हमेशा 2 slots रहेंगे
        $match = array_pad($match, 2, ['user_id' => null, 'user_name' => null, 'group_set' => null]);
        // p($match);
        $matches[] = $match;
    }

    return $matches;
}

function join_now_button($user_id,$vender_id)
{
  $user_chat = 0;
  $user = User::find($user_id);
  $vender = User::find($vender_id);
  
  $currentDateUser = Carbon::now()->format('Y-m-d');
  $currentTime = Carbon::now()->setTimezone($user->user_timezone)->format('H:i');
  
  $chat_user_booking = SessionBooking::where('user_id', $user['id'])->where('vender_id', $vender['id'])->where('booking_date', $currentDateUser)->where('status', 'active')->get();

  foreach($chat_user_booking as $key=>$booking_data){
      
      if ($currentDateUser < $booking_data->booking_date || ($currentDateUser == $booking_data->booking_date && $currentTime < $booking_data->user_end_time)){
          
          $user_chat = 1;
      }
  }
 
  return $user_chat;
}
function createThread($data)
{

  $q = Convenien::where(['ticket_type' => $data['ticket_type'], 'ticket_id' => $data['ticket_id'], 'type' => $data['type'] ?? 'SINGLE']);

  if ($data['type'] == 'SINGLE') {
    $q->whereHas('chatuser', function ($q) use ($data) {
      $q->where('user_id', $data['from_id']);
    })
      ->whereHas('chatuser', function ($q) use ($data) {
        $q->where('user_id', $data['to_id']);
      });
  }
  $convenien = $q->first();
  if (!isset($convenien)) {
    $convenien = new Convenien;
    $convenien->type = $data['type'] ?? 'SINGLE';
    $convenien->group_name = $data['group_name'] ?? '';
    $convenien->ticket_type = $data['ticket_type'] ?? 'Event';
    $convenien->ticket_id = $data['ticket_id'] ?? 0;
    $convenien->date_time = $data['booking_date_time'] ?? 0;
    $convenien->last_message = '';
    $convenien->save();
  }

  if (isset($data['from_id']) && !empty($data['from_id'])) {
    $chatUser = ChatUser::where('convenience_id', $convenien->id)->where('user_id', $data['from_id'])->first();
    if (!isset($chatUser)) {
      $chatUser =  new ChatUser;
      $chatUser->convenience_id = $convenien->id;
      $chatUser->user_id = $data['from_id'];
      $chatUser->save();
    }
  }



  if (isset($data['to_id']) && !empty($data['to_id'])) {
    $chatUser = ChatUser::where('convenience_id', $convenien->id)->where('user_id', $data['to_id'])->first();
    if (!isset($chatUser)) {
      $chatUser =  new ChatUser;
      $chatUser->convenience_id = $convenien->id;
      $chatUser->user_id = $data['to_id'];
      $chatUser->save();
    }
  }

  /*$chat =  Chat::where('convenience_id',$convenien->id)->first();
        if(!isset($chat)){
            $chat=  new Chat;
            $chat->convenience_id = $convenien->id;
            $chat->chat_user_id = $chatUser->id;
            $chat->from_id = $data['from_id'] ?? 0;
            $chat->to_id = $data['to_id']  ?? 0;
            $chat->is_read = 0;
            
            $chat->message = '';
            $chat->save();
    
        }*/
  return $convenien->id;
}

function timeAgo($time_ago)
{
  $time_ago = strtotime($time_ago);
  $cur_time   = time();
  $time_elapsed   = $cur_time - $time_ago;
  $seconds    = $time_elapsed;
  $minutes    = round($time_elapsed / 60);
  $hours      = round($time_elapsed / 3600);
  $days       = round($time_elapsed / 86400);
  $weeks      = round($time_elapsed / 604800);
  $months     = round($time_elapsed / 2600640);
  $years      = round($time_elapsed / 31207680);
  // Seconds
  if ($seconds <= 60) {
    return "just now";
  }
  //Minutes
  else if ($minutes <= 60) {
    if ($minutes == 1) {
      return "one minute ago";
    } else {
      return "$minutes minutes ago";
    }
  }
  //Hours
  else if ($hours <= 24) {
    if ($hours == 1) {
      return "an hour ago";
    } else {
      return "$hours hrs ago";
    }
  }
  //Days
  else if ($days <= 7) {
    if ($days == 1) {
      return "yesterday";
    } else {
      return "$days days ago";
    }
  }
  //Weeks
  else if ($weeks <= 4.3) {
    if ($weeks == 1) {
      return "a week ago";
    } else {
      return "$weeks weeks ago";
    }
  }
  //Months
  else if ($months <= 12) {
    if ($months == 1) {
      return "a month ago";
    } else {
      return "$months months ago";
    }
  }
  //Years
  else {
    if ($years == 1) {
      return "one year ago";
    } else {
      return "$years years ago";
    }
  }
}

function current_location($ip, $lat, $long)
{
  $currentUserInfo = Location::get($ip);
  // p($currentUserInfo);
  if (isset($currentUserInfo->latitude) && isset($currentUserInfo->longitude) && !empty($lat) && !empty($long)) {
    return distance($currentUserInfo->latitude, $currentUserInfo->longitude, $lat, $long, "K");
  }
}

function slug($mode, $title)
{
  $originalSlug = Str::slug($title);
  $slug = $originalSlug;
  $counter = 2;

  while (app("App\Models\\$mode")::where('slug', $slug)->exists()) {
    $slug = $originalSlug . '-' . $counter;
    $counter++;
  }

  return $slug; // Add this line to return the computed slug
}


function p($p, $exit = 1)
{
  echo '<pre>';
  print_r($p);
  echo '</pre>';
  if ($exit == 1) {
    exit;
  }
}



function get_encrypted_value($key, $encrypt = false)
{
  $encrypted_key = null;
  if (!empty($key)) {
    if ($encrypt == true) {
      $key = Crypt::encrypt($key);
    }
    $encrypted_key = $key;
  }
  return $encrypted_key;
}

function get_decrypted_value($key, $decrypt = false)
{
  $decrypted_key = null;
  if (!empty($key)) {
    if ($decrypt == true) {
      $key = Crypt::decrypt($key);
    }
    $decrypted_key = $key;
  }
  return $decrypted_key;
}


//send otp function
function send_otp($mobile)
{
  $otpnum = rand(111111, 999999);
  // $curl = curl_init();

  // curl_setopt_array($curl, array(
  //   CURLOPT_URL => "http://2factor.in/API/V1/3565904e-cc65-11ed-81b6-0200cd936042/SMS/" . $mobile . "/" . $otpnum . "/Cityroom%20OTP",
  //   CURLOPT_RETURNTRANSFER => true,
  //   CURLOPT_ENCODING => "",
  //   CURLOPT_MAXREDIRS => 10,
  //   CURLOPT_TIMEOUT => 30,
  //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //   CURLOPT_CUSTOMREQUEST => "GET",
  //   CURLOPT_POSTFIELDS => "",
  //   CURLOPT_HTTPHEADER => array(
  //     "content-type: application/x-www-form-urlencoded"
  //   ),
  // ));

  // $response = curl_exec($curl);
  // $err = curl_error($curl);

  // curl_close($curl);

  // if ($err) {
  //   return 2;
  // } else {
  //   $response;
  // }

  $otp_user = UserOtp::where('mobile', $mobile)->first();
  if (!empty($otp_user)) {
    $userotp = UserOtp::find($otp_user->id);
  } else {
    $userotp = new UserOtp;
  }

  $userotp->mobile        = $mobile;
  $userotp->otp           = $otpnum;
  $userotp->save();

  return 1;
}

function send_mail_otp($email)
{
  // p(1);
  $otpnum = rand(11111, 99999);


  $otp_user = UserOtp::where('email', $email)->first();
  if (!empty($otp_user)) {
    $userotp = UserOtp::find($otp_user->id);
  } else {
    $userotp = new UserOtp;
  }

  $userotp->email        = $email;
  $userotp->otp           = $otpnum;
  $userotp->save();

  return 1;
}

function send_mail_welcome($email)
{
  // p(1);


  $user = User::where('email', $email)->first();
  $mailData = [

    'user' => $user->name,
    'link' => url('/'),
  ];

  Mail::to($user->email)->send(new WelcomeEmail($mailData));

  return 1;
}

//send otp function
function send_otp1($mobile)
{
  $otpnum = rand(1111, 9999);
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "http://2factor.in/API/V1/3565904e-cc65-11ed-81b6-0200cd936042/SMS/" . $mobile . "/" . $otpnum . "/Cityroom%20OTP",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => array(
      "content-type: application/x-www-form-urlencoded"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    return 2;
  } else {
    $response;
  }

  $otp_user = UserOtp::where('mobile', $mobile)->first();
  if (!empty($otp_user)) {
    $userotp = UserOtp::find($otp_user->id);
  } else {
    $userotp = new UserOtp;
  }

  $userotp->mobile        = $mobile;
  $userotp->otp           = $otpnum;
  $userotp->save();

  return 1;
}

/**
 *  Check file exist or not
 * */
function check_file_exist($file_name, $custome_key, $thumbnail = false, $default_img = false)
{
  $return_file        = '';
  $config_upload_path = \Config::get('custom.' . $custome_key);
  if ($thumbnail == true) {
    $path = $config_upload_path['thumb_display_path'];
  } else {
    $path =  $config_upload_path['display_path'];
  }
  if (!empty($file_name)) {
    if (is_file($path . $file_name)) {
      $return_file = url($path . $file_name);
    } else {
      $return_file = url('public/default-image/default.png');
    }
  }
  return $return_file;
}



function distance($lat1, $lon1, $lat2, $lon2, $unit)
{
  if (($lat1 == $lat2) && ($lon1 == $lon2)  && (empty($lon1) || empty($lon2))) {
    return 0;
  } else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return number_format(($miles * 1.609344), 2);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

function sendnotification($sender_id, $resiver_id, $title, $message, $type)
{
  $data = new Notification;
  $data->sender_id = $sender_id;
  $data->resiver_id = $resiver_id;
  $data->title = $title;
  $data->message = $message;
  $data->sender_type = $type;
  $data->seen = 1;
  $data->save();
  return true;
}


//Send Firebase Notification to User using App
function apiNotificationForApp($token, $title, $type = null, $description = null)
{
  $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
  $fcmNotification = [
    "to" => $token,
    "notification" => [
      "title"      => $title,
      "body"       => $description,
      "type"       => $type
    ],
    'data' => [
      "extra_data" => 'asd',
      "title"      => $title,
      "body"       => $description,
      "type"       => $type

    ]
  ];

  $headers = [
    'Authorization:key=AAAA8br0QXQ:APA91bEL7bh-1igyftfZEJRbvpA5AdVoxm6VTClbuGXeB65eNGgdQ7rWzmxSyAB3dRTycFojPy8-_ZYcPY1vcgAjDpmeYFUuP8DKySb1PzfqDOSBTrr9-SSpfozMNsUW2_-HG1DK3Ge6',
    'Content-Type: application/json'
  ];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $fcmUrl);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
  $result = curl_exec($ch);
  // p($result);
  curl_close($ch);
  return true;
}
