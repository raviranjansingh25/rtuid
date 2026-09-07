<?php

namespace App\Http\Controllers\subadmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\subadmin\Concerns\HandlesSubadminPermissions;
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

class dashboardController extends Controller
{
    use HandlesSubadminPermissions;

    public function index()
    {
        $admin = $this->subadmin();

        $userQuery = User::where('role', 1)->where('status', '<', 3);
        $this->applyDistrictScope($userQuery, $admin);
        $total_user = $userQuery->count();

        $total_doctor         = User::where('role', 2)->where('status', '<', 3)->count();
        $banner         = Banner::where('status', '<', 3)->count();
        $contactEnquery         = ContactEnquery::count();
        $course         = Course::where('status', '<', 3)->count();
        $page         = Page::where('status', '<', 3)->count();
        $faq         = Faq::where('status', '<', 3)->count();
        $feature         = Feature::where('status', '<', 3)->count();

        $data = $this->subadminViewData([
            'title' => 'Dashboard',
            'total_user'        => $total_user,
            'total_doctor'        => $total_doctor,
            'banner'        => $banner,
            'contactEnquery'        => $contactEnquery,
            'course'        => $course,
            'page'        => $page,
            'faq'        => $faq,
            'feature'        => $feature,
        ]);
        return view('subadmin.dashboard')->with($data);
    }

    public function subadminidcard()
    {
        $subadmin = $this->subadmin();
        $district_name = $this->getAssignedDistrictName($subadmin);
        
        $data = $this->subadminViewData([
            'title' => 'Subadmin ID Card',
            'subadmin' => $subadmin,
            'district_name' => $district_name,
        ]);

        return view('subadmin.idcard')->with($data);
    }
}
