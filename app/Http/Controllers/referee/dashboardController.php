<?php

namespace App\Http\Controllers\referee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banner;
use App\Models\ContactEnquery;
use App\Models\Course;
use App\Models\Page;
use App\Models\Convenien;
use App\Models\Feature;
use App\Models\PaymentHistory;
use App\Models\Faq;
use DB;
use Auth;
use Carbon\Carbon;
use Session;
use Stripe\Stripe; // Import the Stripe class
use Stripe\StripeClient;
use Stripe\Transfer;


class dashboardController extends Controller
{
    public function index()
    {
        $admin= Auth::guard('referee')->user();
        
        $total_user         = User::where('coach_id',$admin->id)->where('status', '<', 3)->count();
        $total_doctor         = User::where('role', 2)->where('status', '<', 3)->count();
        $banner         = Banner::where('status', '<', 3)->count();
        $contactEnquery         = ContactEnquery::count();
        $course         = Course::where('status', '<', 3)->count();
        $page         = Page::where('status', '<', 3)->count();
        $faq         = Faq::where('status', '<', 3)->count();
        $feature         = Feature::where('status', '<', 3)->count();
        // p($labels2);

        $data = array(
            'title' => 'Dashboard',
            'total_user'        => $total_user,
            'total_doctor'        => $total_doctor,
            'banner'        => $banner,
            'contactEnquery'        => $contactEnquery,
            'course'        => $course,
            'page'        => $page,
            'faq'        => $faq,
            'feature'        => $feature,
            
        );
        return view('referee.dashboard')->with($data);
    }

    public function refereeidcard()
    {
        $referee = Auth::guard('referee')->user();
        $district_name = '-';
        if (!empty($referee->district)) {
            $tag = \App\Models\Tags::find($referee->district);
            if ($tag) {
                $district_name = $tag->title;
            } else {
                $district_name = $referee->district;
            }
        }
        
        $data = array(
            'title' => 'Referee ID Card',
            'referee' => $referee,
            'district_name' => $district_name,
        );
        
        return view('referee.idcard')->with($data);
    }
}
