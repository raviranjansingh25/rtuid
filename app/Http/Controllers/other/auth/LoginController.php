<?php

namespace App\Http\Controllers\other\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\Shoper;
use Illuminate\Support\Facades\Hash;
use Validator;

class LoginController extends Controller
{
    public function index(){
        $data = array(
            'saveurl' =>url('/shoper/login-save'),
            'title' =>"Coach Login",
        );
        return view('other.auth/login')->with($data);
    }

    public function save(Request $request) {
          
// p(1);
      $email =$request['email'];
      $password = $request['password'];
      
      
      $admin = Shoper::where('email',$email)->first();
      // p($admin);

      if ($admin!="") {
       
          
          if(Auth::guard('shoper')->attempt(['email' => $email, 'password' => $password])) {
            
            return redirect('/shoper/dashboard')->withSuccess("You are successfully logged in");
          }
          else{
            return redirect()->back()->withInput()->withErrors("Invalid Password");
          }
          
      
      }else{
              return redirect()->back()->withInput()->withErrors("Please Enter valid Email Address");
          }
    }

    public function change_password(){
      $data = array(
        'title' =>'Change Password', 
        'saveurl' =>route('change_password_save'), 
      );
      return view('other.auth.change_password')->with($data);
    }

    public function change_password_save(Request $request)
    {
       $admin_id = Auth::guard('shoper')->user()->id;
       
       $adminDetails = Shoper::where(['id' => $admin_id])->first();

       if(Hash::check($request->get('current-password'), $adminDetails['password'])){
        $values=array(
              'password'          => Hash::make($request->get('new-password')),
        );

        $validatior = Validator::make($request->all(), [
            'current-password'          => 'required',
            'new-password'              => 'required|min:6|max:12|different:current-password',
            'new-password_confirmation' => 'required|min:6|max:12|same:new-password'
        ], [
            'new-password_confirmation.same' => 'The new password and new confirm password are not the same.',
            'new-password.different' => "New Password can't be same as current password",
        ]);

        if ($validatior->fails())
        {
                return redirect()->back()->withInput()->withErrors($validatior);

        } else {

          Shoper::where('id', $admin_id)->update($values);
            return redirect()->back()->withSuccess("Password changed successfully!");

        }

       } else {
            return redirect()->back()->withErrors("Incorrect current password. Please try again.");
       }
    }

    public function view_profile(){
      $profile= Auth::guard('shoper')->user();
      $data = array(
        'title' =>'Update Profile', 
        'saveurl' =>route('update_profile'), 
        'profile' => $profile,
      );
      return view('other.auth.profile_updeate')->with($data);
    }

    public function update_profile(Request $request){
        $profile= Auth::guard('shoper')->user();
        if ($profile!="") {
            if(!empty($request->image)) {
                $image = $request->file('image');
                $img_name = rand(11111, 99999) . '.' . $image->getClientOriginalExtension();
                $request->file('image')->move("uploads/profile", $img_name);
                $profile->image = 'uploads/profile/'.$img_name;
            }

            $profile->name  = $request['name'];
            $profile->email  = $request['email'];
            $profile->phone      = $request['phone'];
            $profile->country_code      = (int)$request['country_code'];
            $profile->save();
            return back()->withSuccess("Profile Updated Successfully.");
         }
         else{
            return back()->withInput()->withErrors("Something went wrong.");
        }
    }

  
    public function logout()
    {
        auth()->guard('shoper')->logout();
        
        return redirect()->route('shoperlogin');
    }
}
