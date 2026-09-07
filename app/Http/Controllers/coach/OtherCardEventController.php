<?php

namespace App\Http\Controllers\coach;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ApplyTournament;
use App\Models\Tournament;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exports\ApplyedEventexport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use File;
use Image;

class OtherCardEventController extends Controller
{
    

    public function reg_form_single($id){
        $decrypted_id = get_decrypted_value($id, true);
        $event = ApplyTournament::find($decrypted_id);
        $tur = Tournament::find($event->turnament_id);
        $userdata = User::with('get_dist')->find($event->user_id);
        
       
        $data = array(
        	'saveurl' =>url('other/single-event-apply/'.$id),
            'title' => 'Dashboard',
            'user' => $userdata,
            'event' => $event,
            'tur' => $tur
            
         );
    
        return view('coach.card.reg_form')->with($data);
    }



    public function single_card($id){
        
        $decrypted_id = get_decrypted_value($id, true);
        $apply = ApplyTournament::where('id',$decrypted_id)->first();
       
        $tur = Tournament::find($apply->turnament_id);
        $userdata = User::find($apply->user_id);
       
        
        $data = array(
            'title' => 'Dashboard',
            'user' => $userdata,
            'tur' => $tur,
            'apply' => $apply
            
         );
        return view('coach.card.single_card')->with($data);
    }


   
    public function all_single_reg_form (Request $request,$id){
        // $apply = EventApplied::where('event_id',$id)->with('get_newevent','get_user')->get();
        $data = array(
            'title' =>"Applied Group Tournament",
            // 'get_data' =>$apply,
            'event_id' =>$id,

        );
        return view('coach.card.all_single_reg_form')->with($data);
    }

    public function all_single_card(Request $request,$id){
        // $decrypted_id = get_decrypted_value($id, true);
        // $apply = ApplyTournament::where('turnament_id',$decrypted_id)->first();
        // p($apply);
        $data = array(
            'title' =>"Applied Group Tournament",
            // 'get_data' =>$apply,
            'event_id' =>$id,

        );
        return view('coach.card.all_single_card')->with($data);
    }
}
