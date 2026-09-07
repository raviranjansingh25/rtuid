<?php

namespace App\Http\Controllers\other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shoper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class OtherLoginController extends Controller
{
    public function loginview(){
    	$data = array(
            'saveurl' =>url('/other/login/save'),
            'page_title' =>"Subadmin Login",
        );
        return view('other.auth.adminlogin')->with($data);
    }


    public function login(Request $request) {
    	// dd($request);
      $email =$request['email'];
      $password = $request['password'];
      
      $admin = Shoper::where('email',$email)->first();

      if ($admin!="") {
        
          if(Auth::guard('shoper')->attempt(['email' => $email, 'password' => $password])) {
            
            return redirect('/other/dashboard')->withSuccess("You are successfully logged in");;                // The user is active, not suspended, and exists.
          }
          else{
            return redirect()->back()->withInput()->withErrors("Invalid Email or Password");
          }
          
      }
      else{
              return redirect()->back()->withInput()->withErrors("Please Enter valid Email address");
          }
    }
    
    public function profile(){
      $adminprofile = Auth::guard('shoper')->user();
    //   p($adminprofile);
      $data = array(
            'page_title' =>"Profile",
            'adminprofile' =>$adminprofile,
        );
        return view('other.auth.profile')->with($data);
    }
    
    public function editprofile(){
        $admin_id = Auth::guard('shoper')->user()->id;
        $adminDetails = Shoper::find($admin_id);
        $data = array(
            'saveurl'   => url('/other/update_profile'),
            'page_title' => "Update Profile",
            'title' => "Update Profile",
            'adminprofile' => $adminDetails,
        );

        return view('other.auth.editprofile')->with($data);
    }

    public function updatprofile(Request $request){
        // p($request['image']);
        $admin_id = Auth::guard('shoper')->user()->id;
        // p($admin_id);
        $admin = Shoper::find($admin_id);
        // p($admin);

        if ($admin) {
            if ($request['image']!="") {
                $file = $request->file('image');
                $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                $request->file('image')->move("uploads/shoper", $name);
                $admin->image = 'uploads/shoper/'.$name;
            }
            $admin->name = $request['name'];
            $admin->email = $request['email'];
            $admin->phone = $request['phone'];
            $admin->save();

            $success_msg = 'Profile Update successfully.';
            return redirect()->route('other.view-profile')->withSuccess($success_msg);
        }else{
            $error_msd = 'Profile Update faled.';
            return back()->withInput()->withErrors($error_msd);
        }
    }

    public function change_password(){
      $data = array(
            'saveurl' => url('/other/setpassword'),
            'page_title' =>"Change Password",
        );
        return view('other.auth.change_password')->with($data);
    }


    public function changepassword(Request $request){
       $admin_id = Auth::guard('shoper')->user()->id;
       
       $adminDetails = Shoper::find($admin_id);
       
       if(Hash::check($request->get('current_password'), $adminDetails['password'])){
        // p($request->get('current-password'));
        $values=array(
              'password'          => Hash::make($request->get('new_password')),
        );
        
        $validatior = Validator::make($request->all(), [
            'current_password'          => 'required',
            'new_password'              => 'required|different:current_password',
            'Confirm_password' => 'required|same:new_password'
        ], [
            'Confirm_password.same' => 'The new password and new confirm password are not the same.',
            'new_password.different' => "New Password can't be same as current password",
        ]);

        if ($validatior->fails())
        {
                return redirect()->back()->withInput()->withInput()->withErrors($validatior);

        } else {

            ScoreMaster::where('id', $admin_id)->update($values);
            return redirect()->back()->withSuccess("Password changed successfully!");

        }

         } else {
              return redirect()->back()->withInput()->withErrors("Incorrect current password. Please try again.");
         }
    }

    public function logout()
      {
          auth()->guard('shoper')->logout();
          return redirect()->route('shoperlogin');
      }
}
