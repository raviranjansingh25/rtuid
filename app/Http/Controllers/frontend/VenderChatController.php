<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Convenien;
use App\Models\ChatUser;
use App\Models\Chat;
use App\Models\SessionBooking;
use Carbon\Carbon;

class VenderChatController extends Controller
{
    public function vender_chat($id = Null)
    {

        $auth = auth()->guard('vender')->user();
        $user = User::where('role', 1)->whereNot('id', $auth->id)->where('status', 1)->get();
        $chat_list = [];
        if(empty($id)){
            $data[0] = '';
            $user_id = $auth->id;
            $booking = SessionBooking::where('vender_id', $auth->id)->where('status', 'active')->get()->toArray();
            // p($booking);
                $data = [];
                foreach ($booking as $data1) {
                    
                    // Convert the date_time string to a Carbon instance
                    $dateTime = Carbon::parse($data1['booking_date']);
                    $currentDate = Carbon::now();
                    
                    // Calculate the date range
                    $startDate = $dateTime->copy()->subDays(2)->toDateString(); // 2 days ago, end of the day
                    $endDate = $dateTime->copy()->addDays(5)->toDateString(); // 5 days from now, start of the day

                    // Check if the $dateTime falls within the desired range
                    if ($currentDate >= $startDate && $currentDate <= $endDate) {
                        // If the condition is true, store the data in the filtered array
                        $data[] = $data1;
                    }
                }
                
                $booking = array_column($data, 'user_id');
                
                
                    $chat_user = ChatUser::whereIn('user_id', $booking)->pluck('convenience_id')->toArray();
                    $room_id = ChatUser::whereIn('convenience_id',$chat_user)->where('user_id',$auth->id)->pluck('convenience_id')->toArray();

            $q1 = Convenien::with('chatuser.getUser')->whereIn('id',$room_id)
                //->where('last_message','!=','')
                ->whereHas('chatuser', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                })->withCount(['chat as total_unread' => function ($q) use ($user_id) {
                    $q->where('to_id', $user_id);
                }]);

            $data2 =  $q1->orderBy('updated_at', 'desc')->first();
            
            if(!empty($data2)){
                
                $data = $data2;
                
                if(!empty($data)){
                    
                    // $user_data = User::find($data['chatuser'][0]['user_id']);
                    $user_data = User::find($data['chatuser'][0]['user_id']);
                    $room1 = ChatUser::where('user_id', $user_data->id)->pluck('convenience_id')->toArray();
                    $room2 = ChatUser::where('user_id', $auth->id)->pluck('convenience_id')->toArray();
                    $matches = array_intersect($room1, $room2);
                    $matches = array_values($matches);
                    $chat_list = Chat::where('convenience_id',$matches[0])->get();
                }else{
                    $user_data = User::find($id);
                    $room1 = ChatUser::where('user_id', $id)->pluck('convenience_id')->toArray();
                    $room2 = ChatUser::where('user_id', $auth->id)->pluck('convenience_id')->toArray();
                    $matches = array_intersect($room1, $room2);
                    $matches = array_values($matches);
                }
                
            }else{
                $user_data = User::find($id);
                $room1 = ChatUser::where('user_id', $id)->pluck('convenience_id')->toArray();
                $room2 = ChatUser::where('user_id', $auth->id)->pluck('convenience_id')->toArray();
                $matches = array_intersect($room1, $room2);
                $matches = array_values($matches);
            }
            
        }else{
            $user_data = User::find($id);
            $room1 = ChatUser::where('user_id', $id)->pluck('convenience_id')->toArray();
            $room2 = ChatUser::where('user_id', $auth->id)->pluck('convenience_id')->toArray();
            $matches = array_intersect($room1, $room2);
            $matches = array_values($matches);
        }

        // $user_data = User::find($id);
        // $room1 = ChatUser::where('user_id', $id)->pluck('convenience_id')->toArray();
        // $room2 = ChatUser::where('user_id', $auth->id)->pluck('convenience_id')->toArray();
        // $matches = array_intersect($room1, $room2);
        // $matches = array_values($matches);
        
        $timezone = date_default_timezone_get();
        // p($user_data);
        $data = array(
            'title' => 'Chat',
            'user' => $user,
            'timezone' =>isset($timezone)?$timezone:config('app.timezone'),
            'user_data' => $user_data,
            'user_chat' => (isset($user_data->id) && isset($auth->id)) ? join_now_button($user_data->id, $auth->id) : 0,
            'chat_list' => $chat_list,
            'ROOM_ID' => isset($matches[0]) ? $matches[0] : '',
            'RECEIVER_ID' => $id,

        );
        return view('frontend.vender.chat')->with($data);
    }
}
