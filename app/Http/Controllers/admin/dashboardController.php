<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Coach;
use App\Models\Referee;
use App\Models\Tournament;
use App\Models\ContactEnquery;
use App\Models\Course;
use App\Models\Page;
use App\Models\Convenien;
use App\Models\Feature;
use App\Models\PaymentHistory;
use App\Models\Faq;
use DB;
use Carbon\Carbon;
use Session;
use Stripe\Stripe; // Import the Stripe class
use Stripe\StripeClient;
use Stripe\Transfer;


class dashboardController extends Controller
{
    public function index()
    {



        
        $total_user         = User::where('role', 1)->where('status', '!=', 3)->count();
        $sub_admin         = Admin::whereNull('role')->where('status', '!=', 3)->count();
        $banner         = Banner::where('status', '<', 3)->count();
        $event         = Event::count();
        $tournaments = Tournament::where('status', '<', 3)
        ->groupBy('title')
        ->get()
        ->count();
        $coach         = Coach::where('status', '!=', 3)->count();
           $referee      = Referee::where('status', '<', 3)->count();
        $feature         = Feature::where('status', '<', 3)->count();

        


        $data = array(
            'title' => 'Dashboard',
            'total_user'        => $total_user,
            'sub_admin'        => $sub_admin,
            'banner'        => $banner,
            'event'        => $event,
            'tournaments'        => $tournaments,
            'coach'        => $coach,
            'referee'        => $referee,
            
            
        );
        return view('admin.dashboard')->with($data);
    }
}
