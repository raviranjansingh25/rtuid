<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Convenien;
use App\Models\SessionBooking;
use App\Models\Timezone;
use App\Models\Chat;
use Carbon\Carbon;
use App\Models\ChatUser;

class ChatController extends Controller
{

    public function chat($id = Null)
    {

        $auth = auth()->guard('web')->user();
        
        $user = User::where('role', 2)->whereNot('id', $auth->id)->where('status', 1)->get();
        
        $chat_list = [];
        
        if(empty($id)){
            $data[0] = '';
            $user_id = $auth->id;
            $booking = SessionBooking::where('user_id', $auth->id)->where('status', 'active')->get()->toArray();
            
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
                // p($data);
                $booking = array_column($data, 'vender_id');
                // p($booking);
                
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
                    
                    $user_data = User::find($data['chatuser'][1]['user_id']);
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

        //join now button function
        $user_chat = 0;
        $currentDateUser = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->setTimezone($auth->user_timezone)->format('H:i');
        
        $chat_user_booking = SessionBooking::where('user_id', $auth->id)->where('booking_date', $currentDateUser)->where('status', 'active')->get();
        
        foreach($chat_user_booking as $key=>$booking_data){
            
            if (($currentDateUser == $booking_data->booking_date && ($currentTime <= $booking_data->user_end_time) && $currentTime >= $booking_data->user_start_time)){
                
                $user_chat = 1;
            }
        }
        // end join now botton function
        
       

        $timezone = date_default_timezone_get();
        
        $data = array(
            'title' => 'Chat',
            'user' => $user,
            'timezone' =>isset($timezone)?$timezone:config('app.timezone'),
            'chat_list' => $chat_list,
            'user_chat' => (isset($auth->id) && isset($user_data->id)) ? join_now_button($auth->id,
            $user_data->id) : 0,
            'user_data' => $user_data,
            'ROOM_ID' => isset($matches[0]) ? $matches[0] : '',
            'RECEIVER_ID' => $id,

        );
        return view('frontend.chat')->with($data);
    }
}
