<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Associate;
use Illuminate\Support\Str;
use App\Models\Team;
use App\Models\Page;
use App\Models\Faq;
use Illuminate\Support\Facades\Hash;
use Exception;
use Cache;
use Illuminate\Support\Facades\Auth;
use Validator;

class WebPageController extends Controller
{
    public function about()
    {

        $about = Page::find(7);
        $mission = Page::find(4);
        $vision = Page::find(5);
        $home = Page::find(7);
        $data = array(
            'title' => 'About Us',
            'about' => $about,
            'mission' => $mission,
            'vision' => $vision,
            'home' => $home,
        );
        return view('frontend.about')->with($data);
    }

    public function terms_condition()
    {

        $page = Page::find(2);
        $data = array(
            'title' => 'Terms & Condition',
            'page' => $page,
        );
        return view('frontend.term_conditions')->with($data);
    }

    public function privacy_policy()
    {

        $page = Page::find(1);
        $data = array(
            'title' => 'Privacy & Policy',
            'page' => $page,
        );
        return view('frontend.privacy_policy')->with($data);
    }

    public function why_join($id = Null)
    {
        $vender = auth()->guard('vender')->user();
        if ((!empty($vender) && $id == 'practitioner') || $id == 'practitioner') {
            $page = Page::find(6);
        } else {
            $page = Page::find(8);
        }

        $data = array(
            'title' => 'Privacy & Policy',
            'page' => $page,
        );
        return view('frontend.why_join')->with($data);
    }

    public function contact_us()
    {
        $data = array(
            'title' => 'Contact Us',
        );
        return view('frontend.contact')->with($data);
    }

    public function web_faq()
    {
        $faq = Faq::where('status', 1)->get();
        $data = array(
            'title' => 'FAQ',
            'faq' => $faq,
        );
        return view('frontend.faq')->with($data);
    }
}
