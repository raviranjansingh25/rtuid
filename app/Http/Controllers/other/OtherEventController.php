<?php

namespace App\Http\Controllers\other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\GroupEvent;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\EventApplied;
use App\Models\GroupEventList;
use App\Models\GroupEventApplied;
use App\Models\State;
use App\Models\EventList;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exports\ApplyedEventexport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use File;
use Image;

class OtherEventController extends Controller
{
    public function index(){
        return view('other.dashboard');
    }
    public function event(Request $request){
        $category = Category::where('status',1)->get();
        $subcategory = Subcategory::where('status',1)->get();
        $eventname = EventList::where('status',1)->get();
        $eventserch = Event::where('status',1)->groupBy('name')->get();
        $event = Event::groupBy('name')->where(function($query) use ($request)
            {
                if (!empty($request->input('eventname')) ) 
                {
                    
                    $query->where('name' ,$request->input('eventname'));
                }
                if (!empty($request->input('category')) ) 
                {
                    $query->where('category',$request->input('category'));
                }
                if (!empty($request->input('subcategory')) ) 
                {
                    $query->where('subcategory',$request->input('subcategory'));
                }
                if (!empty($request->input('event')) ) 
                {
                    $query->where('eventname' ,$request->input('event'));
                }
                if (!empty($request->input('date_from')) && !empty($request->input('date_to')) ){
                    $fr_date =  date("Y-m-d", strtotime($request->input('date_from')));
                    $to_date =  date("Y-m-d", strtotime($request->input('date_to')));

                    $query = $query->where('created_at','>=',$fr_date);
                    $query = $query->where('created_at','<=',$to_date);
                }
                elseif (!empty($request->input('date_from')) ) 
                {
                    $condate =  date("Y-m-d", strtotime($request->input('date_from')));
                    // p($date);
                    $query->where('created_at' ,'LIKE', '%' .$condate. '%');
                }
                elseif (!empty($request->input('date_to')) ) 
                {
                    $condate =  date("Y-m-d", strtotime($request->input('date_to')));
                    $query->where('created_at' ,'LIKE', '%' .$condate. '%');
                }
    
            })->get();
        
    	$data = array(
            'page_title' =>"View Tournament",
            'get_data' =>$event,
            'category' =>$category,
            'subcategory' =>$subcategory,
            'eventname' =>$eventname,
            'eventserch' =>$eventserch,
        );
        return view('other.event.view_by_name')->with($data);
    }

    public function event_by_name(Request $request,$id){
        $category = Category::where('status',1)->get();
        $subcategory = Subcategory::where('status',1)->get();
        $eventname = EventList::where('status',1)->get();
        $eventserch = Event::where('status',1)->groupBy('name')->get();
        $event = Event::where('name',$id)->where(function($query) use ($request)
            {
                if (!empty($request->input('eventname')) ) 
                {
                    
                    $query->where('name' ,$request->input('eventname'));
                }
                if (!empty($request->input('category')) ) 
                {
                    $query->where('category',$request->input('category'));
                }
                if (!empty($request->input('subcategory')) ) 
                {
                    $query->where('subcategory',$request->input('subcategory'));
                }
                if (!empty($request->input('event')) ) 
                {
                    $query->where('eventname' ,$request->input('event'));
                }
                if (!empty($request->input('date_from')) && !empty($request->input('date_to')) ){
                    $fr_date =  date("Y-m-d", strtotime($request->input('date_from')));
                    $to_date =  date("Y-m-d", strtotime($request->input('date_to')));

                    $query = $query->where('created_at','>=',$fr_date);
                    $query = $query->where('created_at','<=',$to_date);
                }
                elseif (!empty($request->input('date_from')) ) 
                {
                    $condate =  date("Y-m-d", strtotime($request->input('date_from')));
                    // p($date);
                    $query->where('created_at' ,'LIKE', '%' .$condate. '%');
                }
                elseif (!empty($request->input('date_to')) ) 
                {
                    $condate =  date("Y-m-d", strtotime($request->input('date_to')));
                    $query->where('created_at' ,'LIKE', '%' .$condate. '%');
                }
    
            })->get();
        
    	$data = array(
            'page_title' =>"View Tournament",
            'get_data' =>$event,
            'category' =>$category,
            'subcategory' =>$subcategory,
            'eventname' =>$eventname,
            'eventserch' =>$eventserch,
        );
        return view('other.event.view')->with($data);
    }


    public function group_event(Request $request){
        $category = Category::where('status',1)->get();
        $subcategory = Subcategory::where('status',1)->get();
        $eventserch = GroupEvent::where('status',1)->groupBy('title')->get();
        $eventname = GroupEventList::where('status',1)->get();
        // p($request['eventname']);
        $event = GroupEvent::group_by('title')->with('get_subcat','get_event')->where(function($query) use ($request)
            {
                if (!empty($request->input('eventname1')) ) 
                {

                    $query->where('title' ,$request->input('eventname1'));
                }
                if (!empty($request->input('category')) ) 
                {
                    $query->where('category' ,'LIKE', '%' . $request->input('category') . '%');
                }
                if (!empty($request->input('subcategory')) ) 
                {
                    $query->where('subcategory',$request->input('subcategory'));
                }
                if (!empty($request->input('eventname')) ) 
                {
                    $query->where('event_id' ,'LIKE', '%' . $request->input('eventname') . '%');
                }
                if (!empty($request->input('date_from')) && !empty($request->input('date_to')) ){
                    $fr_date =  date("Y-m-d", strtotime($request->input('date_from')));
                    $to_date =  date("Y-m-d", strtotime($request->input('date_to')));

                    $query = $query->where('created_at','>=',$fr_date);
                    $query = $query->where('created_at','<=',$to_date);
                }
                elseif (!empty($request->input('date_from')) ) 
                {
                    $condate =  date("Y-m-d", strtotime($request->input('date_from')));
                    // p($date);
                    $query->where('created_at' ,'LIKE', '%' .$condate. '%');
                }
                elseif (!empty($request->input('date_to')) ) 
                {
                    $condate =  date("Y-m-d", strtotime($request->input('date_to')));
                    $query->where('created_at' ,'LIKE', '%' .$condate. '%');
                }
    
            })->get();
    	$data = array(
            'page_title' =>"View Group Tournament",
            'get_data' =>$event,
            'category' =>$category,
            'subcategory' =>$subcategory,
            'eventserch' =>$eventserch,
            'eventname' =>$eventname,
        );
        return view('other.groupevent.view_by_name')->with($data);
    }

    public function group_event_by_name(Request $request,$id){
        $category = Category::where('status',1)->get();
        $subcategory = Subcategory::where('status',1)->get();
        $eventserch = GroupEvent::where('status',1)->groupBy('title')->get();
        $eventname = GroupEventList::where('status',1)->get();
        // p($request['eventname']);
        $event = GroupEvent::where('title',$id)->with('get_subcat','get_event')->where(function($query) use ($request)
            {
                if (!empty($request->input('eventname1')) ) 
                {

                    $query->where('title' ,$request->input('eventname1'));
                }
                if (!empty($request->input('category')) ) 
                {
                    $query->where('category' ,'LIKE', '%' . $request->input('category') . '%');
                }
                if (!empty($request->input('subcategory')) ) 
                {
                    $query->where('subcategory',$request->input('subcategory'));
                }
                if (!empty($request->input('eventname')) ) 
                {
                    $query->where('event_id' ,'LIKE', '%' . $request->input('eventname') . '%');
                }
                if (!empty($request->input('date_from')) && !empty($request->input('date_to')) ){
                    $fr_date =  date("Y-m-d", strtotime($request->input('date_from')));
                    $to_date =  date("Y-m-d", strtotime($request->input('date_to')));

                    $query = $query->where('created_at','>=',$fr_date);
                    $query = $query->where('created_at','<=',$to_date);
                }
                elseif (!empty($request->input('date_from')) ) 
                {
                    $condate =  date("Y-m-d", strtotime($request->input('date_from')));
                    // p($date);
                    $query->where('created_at' ,'LIKE', '%' .$condate. '%');
                }
                elseif (!empty($request->input('date_to')) ) 
                {
                    $condate =  date("Y-m-d", strtotime($request->input('date_to')));
                    $query->where('created_at' ,'LIKE', '%' .$condate. '%');
                }
    
            })->get();
    	$data = array(
            'page_title' =>"View Group Tournament",
            'get_data' =>$event,
            'category' =>$category,
            'subcategory' =>$subcategory,
            'eventserch' =>$eventserch,
            'eventname' =>$eventname,
        );
        return view('other.groupevent.view')->with($data);
    }
}
