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

/**
 * Spread players across $matchCount matches (2 slots each).
 * Fills one slot per match first, then second slots — so 2 blanks
 * become one blank in each match, never both blanks in the same match.
 */
function distributeMatchSlots(array $players, int $matchCount): array
{
    $matches = [];
    for ($i = 0; $i < max(0, $matchCount); $i++) {
        $matches[$i] = [];
    }
    if ($matchCount <= 0) {
        return $matches;
    }

    $entries = [];
    foreach ($players as $userId => $userName) {
        $entries[] = [$userId, $userName];
    }

    $i = 0;
    $total = count($entries);

    for ($m = 0; $m < $matchCount && $i < $total; $m++) {
        [$uid, $uname] = $entries[$i++];
        $matches[$m][$uid] = $uname;
    }

    for ($m = 0; $m < $matchCount && $i < $total; $m++) {
        if (count($matches[$m]) < 2) {
            [$uid, $uname] = $entries[$i++];
            $matches[$m][$uid] = $uname;
        }
    }

    return $matches;
}

/**
 * Place bye-seeds into a round like image-2:
 * seed+blank, blank+blank (W vs W), seed+blank, seed+blank...
 * Never put two seeds in one match while another match is fully empty.
 */
function placeSeededRound(array $seeds, int $matchCount): array
{
    if ($matchCount <= 0) {
        return [];
    }

    // More seeds than matches → fill evenly (one each, then seconds)
    if (count($seeds) >= $matchCount) {
        return distributeMatchSlots($seeds, $matchCount);
    }

    $matches = [];
    for ($i = 0; $i < $matchCount; $i++) {
        $matches[$i] = [];
    }

    $seedEntries = [];
    foreach ($seeds as $userId => $userName) {
        $seedEntries[] = [$userId, $userName];
    }
    $seedCount = count($seedEntries);
    $wwCount = $matchCount - $seedCount;

    // Build order: seed, [ww], seed, seed... (WW after first seed)
    $types = array_fill(0, $seedCount, 'seed');
    for ($w = 0; $w < $wwCount; $w++) {
        $insertAt = min(count($types), 1 + $w * 2);
        array_splice($types, $insertAt, 0, ['ww']);
    }
    while (count($types) < $matchCount) {
        $types[] = 'ww';
    }
    $types = array_slice($types, 0, $matchCount);

    $si = 0;
    foreach ($types as $m => $type) {
        if ($type === 'seed' && $si < $seedCount) {
            [$uid, $uname] = $seedEntries[$si++];
            $matches[$m][$uid] = $uname;
        }
    }

    return $matches;
}

/**
 * Group earlier-round matches so each next-round match gets 1 feeder per blank slot.
 * seed+blank → 1 feeder; blank+blank → 2 feeders.
 * Skips fully-filled targets (0 blanks) so arrows attach only where Upcoming exists.
 * Returns [ ['target_index' => int, 'matches' => [...]], ... ]
 */
function groupFeedersByBlanks(array $feederMatches, array $targetMatches): array
{
    if (empty($feederMatches)) {
        return [];
    }

    $groups = [];
    $fi = 0;
    $feederCount = count($feederMatches);

    foreach ($targetMatches as $targetIndex => $target) {
        $blanks = 0;
        if (empty($target[0]['user_id'])) {
            $blanks++;
        }
        if (empty($target[1]['user_id'])) {
            $blanks++;
        }
        if ($blanks < 1) {
            continue;
        }

        $group = [];
        for ($n = 0; $n < $blanks && $fi < $feederCount; $n++) {
            $group[] = $feederMatches[$fi++];
        }
        if (!empty($group)) {
            $groups[] = [
                'target_index' => (int) $targetIndex,
                'matches'      => $group,
            ];
        }
    }

    // Leftover feeders (safety) — attach to remaining blank targets if any
    while ($fi < $feederCount) {
        $groups[] = [
            'target_index' => null,
            'matches'      => [$feederMatches[$fi++]],
        ];
    }

    return $groups;
}

/**
 * Fix existing rounds where one match has 2 players and another is fully empty.
 */
function rebalanceDrawSheetBlanks($tournamentId): void
{
    $sheets = \App\Models\DrawSheet::where('tournament_id', $tournamentId)
        ->whereNotIn('match', [6, 7])
        ->orderBy('sheet')
        ->orderBy('match')
        ->orderBy('match_group')
        ->orderBy('group_set')
        ->get()
        ->groupBy(function ($row) {
            return $row->sheet . '-' . $row->match;
        });

    foreach ($sheets as $rows) {
        $byGroup = $rows->groupBy('match_group');
        $matchCount = $byGroup->count();
        if ($matchCount < 2) {
            continue;
        }

        $filledCounts = [];
        $players = [];
        foreach ($byGroup as $groupRows) {
            $filled = 0;
            foreach ($groupRows as $row) {
                if (!empty($row->user_id)) {
                    $filled++;
                    $players[] = [
                        'user_id'   => $row->user_id,
                        'user_name' => $row->user_name,
                        'status'    => $row->status,
                    ];
                }
            }
            $filledCounts[] = $filled;
        }

        // One match fully filled + another fully empty → redistribute seeds
        if (min($filledCounts) > 0 || max($filledCounts) < 2) {
            continue;
        }
        if (count($players) < 1) {
            continue;
        }

        $seedMap = [];
        foreach ($players as $idx => $player) {
            $seedMap[$idx] = $player['user_name'];
        }
        $distributed = placeSeededRound($seedMap, $matchCount);

        $slotPlan = [];
        foreach ($distributed as $matchIndex => $matchPlayers) {
            $slotPlan[$matchIndex] = [null, null];
            $slot = 0;
            foreach ($matchPlayers as $key => $name) {
                $slotPlan[$matchIndex][$slot++] = $players[$key];
            }
        }

        $groupKeys = $byGroup->keys()->values();
        foreach ($groupKeys as $matchIndex => $groupKey) {
            $groupRows = $byGroup[$groupKey]->values();
            // Ensure 2 slots exist in plan
            if (!isset($slotPlan[$matchIndex])) {
                $slotPlan[$matchIndex] = [null, null];
            }
            foreach ($groupRows as $slotIndex => $row) {
                if ($slotIndex > 1) {
                    break;
                }
                $payload = $slotPlan[$matchIndex][$slotIndex] ?? null;
                if ($payload) {
                    $row->user_id = $payload['user_id'];
                    $row->user_name = $payload['user_name'];
                    $row->status = $payload['status'];
                } else {
                    $row->user_id = null;
                    $row->user_name = null;
                    if ($row->status === null || $row->status === '' || $row->status === 'pending' || (int) $row->status === 1) {
                        $row->status = 'pending';
                    }
                }
                $row->save();
            }
        }
    }
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
