<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Category;
use App\Models\Page;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Testimonial;
use App\Models\CourseBooking;
use App\Models\NewsLetter;
use App\Models\ContactEnquery;
use App\Models\Feature;
use App\Models\Tags;
use App\Models\CourseImage;
use App\Models\CourseInclude;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;
use Cache;
use Session;
use Illuminate\Support\Facades\Auth;
use Validator;
use stripe;
use Stripe\StripeClient;
use HasApiTokens;


class HomeController extends Controller
{
    public function index()
    {
        $banner = Banner::where('status', 1)->orderBy('id', 'DESC')->get();
        $category = Category::where('status', 1)->orderBy('title', 'asc')->get();
        $about = Page::where('status', 1)->find(7);
        $feature = Feature::where('status', 1)->get();
        $testimonial = Testimonial::where('status', 1)->get();
        $course = Course::where('status', 1)->orderBy('id', 'DESC')->limit(3)->get();

        $data = array(
            'title' => 'Home',
            'banner' => $banner,
            'category' => $category,
            'about' => $about,
            'feature' => $feature,
            'testimonial' => $testimonial,
            'course' => $course,

        );
        return view('frontend.index')->with($data);
    }

    public function register()
    {
        $dist = Tags::where('status',1)->get();
        $coach = Coach::orderBy('name','asc')->get();
        $category = Category::where('status',1)->get();
        $data = array(
            'title' => 'Register',
            'dist' =>$dist,
            'coach' =>$coach,
            'category' => $category
            

        );
        return view('frontend.register')->with($data);
    }

    public function program(Request $request)
    {


        $course = Gallery::where('status', 1)->orderBy('id', 'DESC')->where(function ($query) use ($request) {

            if (!empty($request['search'])) {
                $query->where('title', 'LIKE', '%' . $request['search'] . '%');
            }

            
        })->get();

       

        $data = array(
            'title' => 'Course',

            'course' => $course,
            

        );
        return view('frontend.programs')->with($data);
    }

    public function program_detail($id)
    {
        $data = Course::where('slug', $id)->first();
        $multi_image = CourseImage::where('course_id', $data->id)->get();
        $user = User::find($data->doctor_id);
        $similar = Course::where(function ($query) use ($data, $user) {

            $fruitArray = explode(",", $data->tags);
            $query->where('doctor_id', $user->id)->orWhereIn('tags', $fruitArray);
        })->where('status', 1)->where('id', '!=', $data->id)->get();
        $include = CourseInclude::where('course_id', $data->id)->get();

        $userauth = auth()->guard('web')->user();

        $cart = '';
        if (!empty($userauth)) {
            $buy_file = CourseBooking::where('user_id', $userauth->id)->where('course_id', $data->id)->first();
            if (!empty($buy_file)) {
                $file_buy = 1;
            } else {
                $file_buy = 0;
            }
        } else {
            $file_buy = 0;
        }
        $data = array(
            'title' => $data->title,
            'data' => $data,
            'user' => $user,
            'similar' => $similar,
            'file_buy' => $file_buy,
            'multi_image' => $multi_image,
            'include' => $include,

        );
        return view('frontend.program_details')->with($data);
    }

    public function newsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'      => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'Message' => $validator->errors()->first()], 200);
        }
        try {
            $newUser  = NewsLetter::where('email', $request['email'])->first();
            if (empty($newUser)) {
                $newUser = new NewsLetter;
            }

            $newUser->email     = $request->email;
            $newUser->save();

            return response()->json([
                'message' => 'Congratulation, your request send successfully.', 'status' => '1'
            ], 200);
            exit;
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            // p($error_message);
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 500);
            exit;
        }
    }


    public function contact()
    {
        // Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // $transfer_payment = Stripe\Transfer::create([
        //     'amount' => 1 * 100,
        //     'currency' => 'USD',
        //     // 'source_transaction' => $booking->txn_number,
        //     'destination' => 'acct_1Mvy5g2eP5rCNq8w',
        // ]);

        // p($transfer_payment);


        $data = array(
            'title' => 'Contact Us',

        );
        return view('frontend.contact_us')->with($data);
    }

    public function category_detail(Request $request)
    {
        // p($request['tab']);
        if (!empty($request['tab'])) {
            $file = Category::where('status', 1)->where('slug', $request['tab'])->first();
        } else {
            $file = Category::where('status', 1)->orderBy('title', 'asc')->first();
        }
        // p($file);
        $category = Category::where('status', 1)->orderBy('title', 'asc')->get();
        $data = array(
            'title' => isset($file->title) ? $file->title : 'Our Modalities',
            'category' => $category,
            'file' => $file,


        );
        return view('frontend.our_modalities')->with($data);
    }


    public function contact_enquery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'phone'     => 'required|numeric',
            'message'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['Message' => $validator->errors()->first()], 400);
        }
        try {
            $newUser                   = new ContactEnquery;
            $newUser->name             = $request->name;
            $newUser->phone           = $request->phone;
            $newUser->email            = $request->email;
            $newUser->message      = $request->message;
            $newUser->role      = $request->role;

            $newUser->save();

            return response()->json([
                'message' => 'Congratulation, your request send successfully.', 'status' => '1'
            ], 200);
            exit;
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            // p($error_message);
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 500);
            exit;
        }
    }
}
