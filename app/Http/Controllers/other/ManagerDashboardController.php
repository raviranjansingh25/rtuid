<?php

namespace App\Http\Controllers\Subadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerDashboardController extends Controller
{
    public function index(){
    	$admin= Auth::guard('subadmin')->user();
    	// p($admin);
    	
    	$data = array(
            'page_title' =>"Subadmin Login",
        );
        return view('subadmin.dashboard')->with($data);
    }
}
