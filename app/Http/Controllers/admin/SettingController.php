<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Tournament;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Mail\VarifyEmail;
use Mail;
use Image;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $event = Tournament::groupBy('title')->get();
        $data = array(
            'title' => "Setting",
            'page_title' => "Setting",
            'setting' => $setting,
            'event' => $event,
            'saveurl' => route('setting_save'),
        );
        return view('admin.setting')->with($data);
    }

    public function email_send(Request $request)
    {

        $Validatior = Validator::make($request->all(), [
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($Validatior->fails()) {
            return back()->withInput()->withErrors($Validatior);
        } else {
            DB::beginTransaction();
            try {

                $user = $request['user'];
                foreach ($user as $send) {
                    $newUser = User::find($send);
                    $mailData = [
                        'subject'       => $request['subject'],
                        'message'       => $request['message'],
                        'image'         => $request['image'],
                        'signature'     => $request['signature'],
                        'user_name'     => $newUser->name,
                    ];

                    Mail::to($newUser->email)->send(new VarifyEmail($mailData));
                }
                p($user);
            } catch (\Exception $e) {
                DB::rollback();
                $error_message = $e->getMessage();
                p($error_message);
                return back()->withInput()->withErrors($error_message);
            }
            DB::commit();
        }
        return redirect()->route('email')->withSuccess($success_msg);
    }

    public function email()
    {
        $user = User::where('status', 1)->where('role', 2)->get();
        $data = array(
            'title' => "User Send Email",
            'page_title' => "User Send Email",

            'saveurl' => route('email_send'),
            'user' => $user
        );
        return view('admin.user_email')->with($data);
    }

    public function save(Request $request)
    {
        $setting = Setting::first();
        if ($setting != "") {
            $request->validate([
                'header_logo' => 'image',
            ]);


            if ($request['header_logo'] != "") {
                $file = $request->file('header_logo');
                $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $extensions = ['jpg', 'jpeg', 'png', 'JPEG', 'PNG', 'JPG'];
                if (!in_array($ext, $extensions)) {
                    $status = 'File type is not allowed you have uploaded. Please upload any image !';
                    return back()->withInput()->withErrors($status);
                }
                $request->file('header_logo')->move("uploads/setting", $name);
                $setting->header_logo = 'uploads/setting/' . $name;
            }

            if ($request['footer_logo'] != "") {
                $file = $request->file('footer_logo');
                $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                // $ext = pathinfo($name, PATHINFO_EXTENSION);
                //     $extensions = ['jpg', 'jpeg', 'png', 'JPEG', 'PNG','JPG'];
                //     if(! in_array($ext, $extensions))
                //     {
                //         $status = 'File type is not allowed you have uploaded. Please upload any image !';
                //         return back()->withInput()->withErrors($status);                   
                //     }
                $request->file('footer_logo')->move("uploads/setting", $name);
                $setting->footer_logo = 'uploads/setting/' . $name;
            }

            if ($request['fav_icon'] != "") {
                $file = $request->file('fav_icon');
                $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                // $ext = pathinfo($name, PATHINFO_EXTENSION);
                //     $extensions = ['jpg', 'jpeg', 'png', 'JPEG', 'PNG','JPG'];
                //     if(! in_array($ext, $extensions))
                //     {
                //         $status = 'File type is not allowed you have uploaded. Please upload any image !';
                //         return back()->withInput()->withErrors($status);                   
                //     }
                $request->file('fav_icon')->move("uploads/setting", $name);
                $setting->fav_icon = 'uploads/setting/' . $name;
            }

            $setting->sitename           = $request['sitename'];
            $setting->email              = $request['email'];
            $setting->phone              = $request['phone'];
            $setting->address            = $request['address'];
            $setting->map                = $request['map'];
            $setting->short_description  = $request['short_description'];
            $setting->facebook           = $request['facebook'];
            $setting->instagram          = $request['instagram'];
            $setting->youtube            = $request['youtube'];
            $setting->twitter            = $request['twitter'];
            $setting->tiktok            = $request['tiktok'];
            $setting->commission            = $request['commission'];
            $setting->event = isset($request['event']) ? implode(',', $request['event']) : '';

            $setting->save();

            // $address = $setting->address; // Google HQ
            // $apiKey = 'AIzaSyBsgcZ-KEzokRcW4OIHYKG55-4dZbnw_i0';
            // $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key='.$apiKey);
            // $geo = json_decode($geo, true); // Convert the JSON to an array
            // if (isset($geo['status']) && ($geo['status'] == 'OK')) {
            //   $latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
            //   $longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
            // }

            $setting->save();
            return back()->withSuccess("Setting Update Successfully.");
        } else {
            return back()->withInput()->withErrors("Setting Update Not Successfully.");
        }
    }
}
