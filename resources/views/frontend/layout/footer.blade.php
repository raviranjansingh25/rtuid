@php
$service = \App\Models\Category::where('status',1)->orderBy('title','asc')->get();
$settingdata = App\Models\Setting::first();
@endphp

<footer>
  <div class="container">
    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="logo-txt-social">
          <img src="{{url($settingdata->header_logo)}}" height="100" width="100" alt="">
          <p>{{$setting->short_description}}</p>
          <ul class="socal-icon">
            <li><a href="{{$setting->instagram}}" target="_blank"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/insta.svg" alt=""></a></li>
            <!--<li><a href="{{$setting->tiktok}}" target="_blank"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/tik-tok.svg" alt=""></a></li>-->
            <li><a href="{{$setting->youtube}}" target="_blank"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/youtube.svg" alt=""></a></li>
            <li><a href="{{$setting->facebook}}" target="_blank"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/facebook.svg" alt=""></a></li>
            <li><a href="{{$setting->twitter}}" target="_blank"><img style="height: 43px; border-radius: 50%;" class="img-fluid" src="{{url('/public/')}}/twiter.webp" alt=""></a></li>
          </ul>
        </div>
      </div>
      <div class="col-xl-4 col-md-6">
        <!--<h3>Useful Links</h3>-->
        <!--<ul>-->
        <!--<li><a href="{{route('home')}}">Home</a></li>-->
        <!--  <li><a href="{{route('about')}}">About Us</a></li>-->
        <!--  <li><a href="{{route('web_faq')}}">FAQ</a></li>-->
        <!--  <li><a href="{{route('program')}}">Gallery</a></li>-->
        <!--  <li><a href="{{route('contact')}}">Contact Us</a></li>-->
          
          <!-- <div class="dropdown">
            <div class="dropdown-toggle" id="dropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Why Join Telimed
            </div>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
              <a class="dropdown-item" href="{{url('/why-join-telimed/patient')}}">For Patient</a>
              <a class="dropdown-item" href="{{url('/why-join-telimed/practitioner')}}">For Practitioner</a>
            </div>
          </div> -->

          
          
        <!--</ul>-->
      </div>
      
      <div class="col-xl-4 col-md-6">
        <h3>Subscribe</h3>
        <form action="" class="news-ltr">
          <input type="email" name="email" placeholder="Your Email" id="newsletter">
          <button type="button" onclick="newsletter_data()" class="btn-primary d-block w-100">Email</button>
          <p>Get the latest updates via email. You can unsubscribe at any time.</p>
        </form>
      </div>
      <div class="col-md-12 copy-right-line-f">
        <div class="row">
          <div class="col-md-6">
            <div class="footer_copyp">

              <p>© <script>
                  document.write(new Date().getFullYear())
                </script> by Rajasthan Taekwondo.</p>
            </div>
          </div>
          <div class="col-md-6">
            <ul class="footer_copy_links">
              <li><a href="{{route('web_faq')}}">FAQs</a></li>
              <li><a href="{{route('privacy_policy')}}">Privacy Policy</a></li>
              <li><a href="{{route('terms_condition')}}">Terms of Service</a></li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</footer>


<div class="modal" id="ConsultNotes" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg ">
    <div id="pane" class="resizable">
      <div class="modal-content">
        <div class="modal-body p-0">
          <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
          <div class="consult-notes py-5 px-4 mt-4">
            <h2 class="modal-title text-center">Consult Notes</h2><br>
            <!-- <p class="text-center form-note"><img src="{{url('/public/')}}/images/form.svg" alt=""> Consult Notes</p> -->
            <form id="consult_notes" class="cmn-frm" method="post">
              @csrf
              <input type="hidden" name="room_id" value="{{isset($room_id)?$room_id:''}}">
              <input type="hidden" name="id" id="id">
              <input type="hidden" name="user_id" id="user_id">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Patient Name">Patient Name</label>
                    <input type="text" name="name" id="name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Patient Name">Consult Number</label>
                    <input type="text" name="consult_name" id="consult_name">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Main Concerns</label>
                    <input type="text" name="main_concerns" id="main_concerns">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                  
                    <textarea name="description" id="description" cols="30" rows="10"></textarea>
                  </div>
                </div>
                <div class="col-md-12 text-center">
                  <button type="submit" class="btn-primary px-5">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal" id="TreatmentPlan" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div id="pane2" class="resizable">
      <div class="modal-content">
        <div class="modal-body p-0">
        
          <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
          <div class="consult-notes py-5 px-4 mt-4">
            <h2 class="modal-title text-center">Treatment Plan</h2><br>
            <!-- <p class="text-center form-note"><img src="{{url('/public/')}}/images/form.svg" alt=""> Treatment Plan Form</p> -->
            <form id="treatment_plan" class="cmn-frm" method="post">
              @csrf
              <input type="hidden" name="room_id" id="room_id" value="{{isset($room_id)?$room_id:''}}">
              <input type="hidden" name="id" id="treat_id">
              <input type="hidden" name="user_id" id="treat_user_id">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Patient Name">Patient Name</label>
                    <input type="text" name="Patient_Name" id="Patient_Name" placeholder="Patient Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Patient Name">Consultation Type</label>

                    <select name="Consultation_Type" class="">
                      <option value="">Select Consultation Type</option>
                      <option value="Initial">Initial</option>
                      <option value="Follow up">Follow up</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Consultation date</label>
                    <input type="date" name="Consultation_Date" id="Consultation_Date" placeholder="Consultation Type">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">General note to patient:</label>
                    <textarea name="General_note_to_patient" id="General_note_to_patient" cols="30" rows="3" placeholder="General note to patient"></textarea>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Recommendations/Prescriptions:</label>
                    
                    <textarea name="Recommendations" id="Recommendations"  cols="30" rows="3" placeholder="Recommendations/Prescriptions"></textarea>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Dietary Recommendations:</label>
                    <textarea name="Dietary_Recommendations" id="Dietary_Recommendations" cols="30" rows="3" placeholder="Dietary Recommendations"></textarea>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Supplement:</label>
                    <textarea name="Supplement" id="Supplement" cols="30" rows="3" placeholder="Supplement"></textarea>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Handouts or documents to attach:</label>
                    <input type="file" name="Handouts_or_documents_to_attach" id="Handouts_or_documents_to_attach" placeholder="Handouts or documents to attach">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Next Appointment:</label>
                    <textarea name="Next_Appointment" id="Next_Appointment" cols="30" rows="3" placeholder="Next Appointment"></textarea>
                  </div>
                </div>
                <div class="col-md-12 text-center">
                  <button type="submit" class="btn-primary px-5">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal" id="TreatmentPlanUser" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div id="pane2" class="resizable">
      <div class="modal-content">
        <div class="modal-body p-0">
        <button id="downloadPDF" class="btn btn-primary">Download PDF</button>
          <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
          <div class="consult-notes py-5 px-4 mt-4" >
            <h2 class="modal-title text-center">Treatment Plan</h2><br>
            <!-- <p class="text-center form-note"><img src="{{url('/public/')}}/images/form.svg" alt=""> Treatment Plan Form</p> -->
            
            
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Patient Name</label>
                   
                    <p id="UserPatient_Name"></p>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Consultation Type</label>

                    <p id="UserConsultation_Type"></p>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Consultation date</label>
                    
                    <p id="UserConsultation_Date"></p>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">General note to patient:</label>
                    
                    <p id="UserGeneral_note_to_patient"></p>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Recommendations/Prescriptions:</label>
                    
                    <p id="UserRecommendations"></p>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Dietary Recommendations:</label>
                    
                    <p id="UserDietary_Recommendations"></p>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Supplement:</label>
                    
                    <p id="UserSupplement"></p>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Handouts or documents to attach:</label>
                    
                    <p id="UserHandouts_or_documents_to_attach"></p>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Patient Name">Next Appointment:</label>
                    
                    <p id="UserNext_Appointment"></p>
                  </div>
                </div>
               
              </div>
            
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="signmdl" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close-1.svg" alt=""></button>
        <div class="login-sign-mdl">
          <div class="row">
            <div class="col-md-6">
              <div class="login-hero sign-up-align">
                <div class="login-hero_main">
                  <div class="login_hero_cn">
                    <h2>Holistic Health At Your <br>
                      Fingertips</h2>
                    <p>
                      Join for free.
                    </p>
                  </div>
                  <div class="login_hero_img change_image">
                    <img class=" img-fluid" src="{{url('/public/')}}/pt-img.png" alt="">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 ">
              <div class="login-type">
                <h3>Sign up</h3>
                <p>Select Role</p>


                
                <div class="tab-content " id="nav-tabContent">
                  <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <form id="form-register" class="cmn-frm" method="post">
                      @csrf
                      <input type="hidden" name="role" value="1" class="form-control">
                      <div class="row">
                        <div class="col-md-6 col-12">
                          <div class="form-floating">
                            <input type="text" name="name" class="form-control" placeholder="First Name">
                            <label for="floatingInput">First Name</label>
                          </div>
                        </div>
                        <div class="col-md-6 col-12">
                          <div class="form-floating">
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name">
                            <label for="floatingInput">Last Name</label>
                          </div>
                        </div>
                      </div>



                      <!-- <label id="name-error" class="error" for="name"></label> -->
                      <div class="form-floating">
                        <input type="email" name="email" class="form-control" placeholder="name@example.com">
                        <label for="floatingInput">Email Address</label>
                      </div>
                      <!-- <label id="email-error" class="error" for="email"></label> -->
                      <div class="row">
                        <div class="col-md-6 col-12">
                          <div class="form-floating">
                            <span><i toggle="#password1" class="fa fa-eye-slash toggle-password"></i></span>
                            <input type="password" name="password" class="form-control pe-5" pattern="[A-Za-z]{3}" id="password1" placeholder="Create Password">
                            <label for="floatingInput">Create Password</label>
                          </div>
                        </div>
                        <div class="col-md-6 col-12">
                          <div class="form-floating">
                            <span><i toggle="#password2" class="fa fa-eye-slash toggle-password"></i></span>
                            <input type="password" name="confirm_password" class="form-control pe-5" id="password2" placeholder="Confirm Password">
                            <label for="floatingInput">Confirm Password</label>
                          </div>
                        </div>
                      </div>
                      <!-- <label id="password1-error" class="error" for="password1"></label> -->

                      <!-- <label id="password2-error" class="error" for="password2"></label> -->
                      <div class="check_box_error">
                        <div class="d-flex justify-content-between mb-3">
                          <label for="">
                            <input type="checkbox" name="checkbox" value="1" id=""> I accept all <a href="{{route('terms_condition')}}" class="text-btn">terms &
                              conditions.</a>
                          </label>
                        </div>
                        <label id="checkbox-error" class="error" for="checkbox"></label>
                      </div>
                      <button type="submit" class="btn-primary w-100">Sign up</button>
                    </form>
                    <div class="or-txt text-center">
                      <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/or.png" alt="">
                    </div>
                    <div class="social-login">
                      <a class="google-lg" href="{{ url('auth/google/1') }}">Login with Google</a>
                      <a class="facebbok-lg" href="{{ url('auth/facebook/1') }}"><span>Login with Facebook</span></a>
                    </div>
                  </div>

                  

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="loginmdl" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close-1.svg" alt=""></button>
        <div class="login-sign-mdl">
          <div class="row">
            <div class="col-md-6">
              <div class="login-hero new-sign-img sign-up-align">
                <div>
                  <h2>Holistic Health At Your <br>
                    Fingertips</h2>
                  <p>Welcome back</p>
                </div>
                <span class="change_image">
                  <img class="img-fluid" src="{{url('/public/')}}/pt-img.png" alt=""></span>
              </div>
            </div>
            <div class="col-md-6 ">
              <div class="login-type">
                <h3>Login</h3>
                
                
                <div class="tab-content " id="nav-tabContent">
                  <div class="tab-pane fade active show" id="user_tap_login" role="tabpanel" aria-labelledby="nav-home-tab">
                    <form id="form-login" class="cmn-frm" method="post">
                      @csrf
                      <div class="form-floating">

                        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email Address</label>
                      </div>
                      <!-- <label id="floatingInput-error" class="error" for="floatingInput"></label> -->
                      <div class="form-floating ">
                        <span><i toggle="#password-field" class="fa fa-eye-slash toggle-password"></i></span>
                        <input type="password" name="password" class="form-control pe-5" id="password-field" placeholder="name@example.com">
                        <label for="floatingInput">Password</label>

                      </div>
                      <!-- <label id="password1-error" class="error" for="password1"></label> -->

                      <div class="d-flex justify-content-between mb-3">

                        <a href="" class="text-btn" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">Forgot Password?</a>
                      </div>
                      <button type="submit" class="btn-primary w-100">Login</button>
                    </form>
                    
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>



<div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
        <div class="forgot-pss">
          <h2 class="modal-title">Forgot Password</h2>
          <p>Please enter your registered email below and follow the prompts to reset your password.</p>
          <form id="forgot_password" method="post" class="cmn-frm">
            @csrf
            <div class="form-floating">
              <input type="email" name="email" class="form-control" placeholder="name@example.com">
              <label for="floatingInput">Enter Email Address</label>
            </div>
            <!-- <label id="email-error" class="error" for="email"></label> -->
            <button type="submit" class="d-block btn-primary w-100">Send</button>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="otp_model" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
        <div class="forgot-pss">
          <h2 class="modal-title">Please verify account</h2>
          <p>A one time verification code has been sent to your registered email address. Please enter the code below and click submit.</p>
          <form id="match_otp" method="post" class="cmn-frm">
            @csrf
            <div class="form-floating">
              <input type="hidden" class="form-control user_email" name="email">
              <input type="text" class="form-control" name="otp" pattern="\d{5}" placeholder="name@example.com">
              <label for="floatingInput">Enter OTP Number</label>
            </div>
            <!-- <label id="otp-error" class="error" for="otp">Please Enter OTP number.</label> -->
            <button type="submit" class="d-block btn-primary w-100">Submit</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="reset_password_model" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
        <div class="forgot-pss">
          <h2 class="modal-title">Reset Password</h2>
          <p>Please enter your new password.</p>
          <form id="reset_password" method="post" class="cmn-frm">
            @csrf
            <div class="form-floating">
              <input type="hidden" class="form-control user_email" name="email">
              <span><i toggle="#new_password" class="fa fa-eye-slash toggle-password"></i></span>
              <input type="password" class="form-control" id="new_password" name="password" placeholder="Enter Password">
              <label for="floatingInput">Enter Password</label>
            </div>
            <!-- <label id="new_password-error" class="error" for="new_password"></label> -->
            <div class="form-floating">
              <input type="hidden" class="form-control user_email" name="email">
              <span><i toggle="#confirm_password" class="fa fa-eye-slash toggle-password"></i></span>
              <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Confirm Password">
              <label for="floatingInput">Enter Confirm Password</label>
            </div>
            <!-- <label id="confirm_password-error" class="error" for="confirm_password"></label> -->

            <button type="submit" class="d-block btn-primary w-100">Save</button>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="customerPopup" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div id="containImage"></div>
      </div>
    </div>

  </div>
</div>
</div>


</div>
@if(!empty(auth()->guard('web')->user()) || !empty(auth()->guard('vender')->user()))
  @if(!empty(auth()->guard('web')->user()))
 
  <input type="hidden" value="{{auth()->guard('web')->user()->id;}}" id="unread_get_user">
  @else
  
  <input type="hidden" value="{{auth()->guard('vender')->user()->id;}}" id="unread_get_user">
  @endif
@endif





<!-- Jquery needed -->
<script src="{{url('/public/frontend/')}}/assets/js/rome.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.bundle.min.js"></script>
<script src="{{ url('/public/admin/') }}/assets/libs/select2/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.querySelectorAll('.pay-button').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            var options = {
                "key": "PSwqlU4fIY8kXxUl95XMYFLA",
                "amount": "59000",
                "currency": "INR",
                "name": "RTUID",
                "description": "Test Transaction",
                "handler": function (response){
                    // alert("Payment Successful! ID: " + response.razorpay_payment_id);
                    
                    fetch('/payment/success', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_id: response.razorpay_payment_id
                        })
                    }).then(res => res.json())
                      .then(data => {
                        if (data.status === 1) {
                            Swal.fire({
                                title: 'Payment Successful!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Optional: redirect after confirmation
                                window.location.href = '/dashboard'; // Change to your desired route
                            });
                        } else {
                            Swal.fire({
                                title: 'Payment Error',
                                text: data.message || 'Something went wrong.',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(err => {
                        console.error("Payment save error:", err);
                        Swal.fire({
                            title: 'Network Error',
                            text: 'Failed to save payment. Please try again.',
                            icon: 'error'
                        });
                    });
                },
                "prefill": {
                    "name": "John Doe",
                    "email": "john@example.com",
                    "contact": "9999999999"
                },
                "theme": {
                    "color": "#3399cc"
                }
            };

            var rzp = new Razorpay(options);
            rzp.open();
        });
    });
</script>

<script>
  document.getElementById("downloadPDF").addEventListener("click", function () {
    const modalContent = document.querySelector("#TreatmentPlanUser .modal-content");
    const downloadButton = document.getElementById("downloadPDF");
    if (!modalContent) {
        console.error("Modal content not found!");
        return;
    }
    downloadButton.style.display = "none";
    // Use html2canvas to capture the modal content as an image
    html2canvas(modalContent, { scale: 2 }).then((canvas) => {
        const imgData = canvas.toDataURL("image/png");

        // Create a jsPDF instance
        const pdf = new jspdf.jsPDF({
            orientation: "portrait",
            unit: "mm",
            format: "a4",
        });

        // Calculate dimensions
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();

        const imgWidth = canvas.width * 0.264583; // Convert px to mm
        const imgHeight = canvas.height * 0.264583;

        if (imgHeight > pageHeight) {
            // Scale the image to fit within a single page
            const scaleFactor = pageHeight / imgHeight;
            const adjustedWidth = imgWidth * scaleFactor;
            const adjustedHeight = imgHeight * scaleFactor;
            pdf.addImage(imgData, "PNG", 0, 0, adjustedWidth, adjustedHeight);
        } else {
            pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
        }

        // Save the PDF
        pdf.save("TreatmentPlan.pdf");
        downloadButton.style.display = "block";
    });
});

</script>


<script>
  $(document).ready(function() {
    $('.select2').select2();
  });
</script>
<script>
  $(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
<script>
  function cropimage(input_class, ratio, width, height) {
    url = "{{route('imageCroper')}}?input_class=" + input_class + '&ration=' + ratio + '&width=' + width + '&height=' + height;
    $("#containImage").load(url, function(responseTxt, statusTxt, xhr) {
      if (statusTxt == "success")
        $('#customerPopup').modal('show');
      if (statusTxt == "error")
        alert("Error: " + xhr.status + ": " + xhr.statusText);
    });
  }
</script>
<script>
  function change_image(type) {
    if (type == 1) {
      var change_image = `<img class=" img-fluid" src="{{url('/public/')}}/pt-img.png" alt="">`;
    } else {
      var change_image = `<img class=" img-fluid" src="{{url('/public/')}}/dr-img.png" alt="">`;
    }
    // alert(change_image);
    $('.change_image').html(change_image);
  }
</script>
<script>
  function register_model() {
    $('#signmdl').modal('show');
  }

  function login_model() {

    $('#loginmdl').modal('show');
  }



  function TreatmentPlanUser(type, user_id) {
    $.ajax({
      url: "{{ route('TreatmentPlanUser_data') }}",
      type: 'GET',
      data: {
        '_token': '<?php echo csrf_token() ?>',
        'type': type,
        'user_id': user_id,
      },
      beforeSend: function() {
        $("#preloader").show();
      },
      success: function(res) {
        $("#preloader").hide();

        if (res.status == 1) {
          $("#UserPatient_Name").html(res.Patient_Name);
          $("#UserConsultation_Type").html(res.Consultation_Type);
          $("#UserConsultation_Date").html(res.Consultation_Date);
          $("#UserGeneral_note_to_patient").html(res.General_note_to_patient);
          $("#UserRecommendations").html(res.Recommendations);
          $("#UserDietary_Recommendations").html(res.Dietary_Recommendations);
          $("#UserSupplement").html(res.Supplement);
          $("#UserHandouts_or_documents_to_attach").html(res.Handouts_or_documents_to_attach);
          $("#UserNext_Appointment").html(res.Next_Appointment);


          $('#TreatmentPlanUser').modal('show');

        } else {
          $("#preloader").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: res.message,
          })
        }
      },
      error: function(error) {
        // $("#preloader").hide();
        swal({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong!',
        })
      }
    });

  }



  function review_model(id, type,role) {
    if(role == 1){
      $('#exampleModalToggle').modal('show');
      $('.review_id').val(id);
      $('.review_type').val(type);
    }
    
  }
</script>


<script>
  $("#review").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      // 'review': {
      //   required: true
      // },
      'ratting': {
        required: true
      },
    },
    messages: {
      // 'review': "Please Enter review.",
      'ratting': "Please select ratting.",
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      $.ajax({
        url: "{{ route('review_save') }}",
        type: 'GET',
        data: $('#review').serialize(),
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();

          if (res.status == 1) {
            $('#exampleModalToggle').modal('hide');
            swal({
                title: "Success!",
                text: res.message,
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 2000
              })
              .then(() => {
                window.location.reload();
              })

          } else {
            $("#preloader").hide();
            swal({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            })
          }
        },
        error: function(error) {
          $("#preloader").hide();
          $('#exampleModalToggle').modal('hide');
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          })
        }
      });
    },
  });
</script>

<script>
  $.validator.addMethod("validate_password", function(value, element) {
    return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/.test(value);
  }, "Password must contain atleast one number, one lowercase letter, and one uppercase letter");
  
  $("#form-register").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      'name': {
        required: true
      },
      'last_name': {
        required: true
      },
      'email': {
        required: true
      },

      'password': {
        required: true,
        minlength: 8, // Minimum length of the password
        validate_password: true
      },
      'confirm_password': {
        required: true,
        equalTo: '#password1'
      },
      'checkbox': {
        required: true
      },

    },

    messages: {
      'name': "Please Enter first name.",
      'last_name': "Please Enter last name.",
      'email': "Please Enter email address.",
      'password': {
        required: "Please enter a password.",
        minlength: "Password must be at least 8 characters long.",
        validate_password: "Please enter a valid password. It must contain at least one digit, one lowercase letter, one uppercase letter, and be at least 8 characters long."
      },
      'confirm_password': {
        required: "Please enter confirm password.",
        equalTo: "Confirm password and password are not same."
      },
      'checkbox': "Please agree to all the Term & Conditions.",
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      $.ajax({
        url: "{{ route('register_save') }}",
        type: 'GET',
        data: $('#form-register').serialize(),
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();

          if (res.status == 1) {
            $('#signmdl').modal('hide');
            var form = document.getElementById('form-register');
            var inputs = form.querySelectorAll('input');

            inputs.forEach(function(input) {
              input.value = null;
            });
            $('#signmdl').modal('hide');
            $('.user_email').val(res.email);

            $('#reg_otp_model').modal('show');


          } else {
            $("#preloader").hide();
            swal({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            })
          }
        },
        error: function(error) {
          $("#preloader").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          })
        }
      });
    },
  });
</script>



<script>
  $("#form-login").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {

      'email': {
        required: true
      },

      'password': {
        required: true
      },


    },
    messages: {

      'email': "Please Enter email address.",
      'password': "Please enter password.",

    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      var currentRouteName = "{{ Route::currentRouteName() }}";

      $.ajax({
        url: "{{ route('login_save') }}",
        type: 'GET',
        data: $('#form-login').serialize(),
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();

          if (res.status == 1) {
            $('#loginmdl').modal('hide');
            swal({
                title: "Success!",
                text: res.message,
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 2000
              })
              .then(() => {
                
                  window.location = "{{route('user_dashboard')}}";
               

              })

          } else {
            $("#preloader").hide();
            swal({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            })
          }
        },
        error: function(error) {
          $("#preloader").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          })
        }
      });
    },
  });
</script>



<script>
  function register_model() {

    $('#signmdl').modal('show');


  }
</script>

<script>
  $.validator.addMethod("validate_password1", function(value, element) {
    if (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/.test(value)) {
      return true;
    } else {
      return false;
    }
  }, "Please enter a valid password. It must contain at least one digit, one lowercase letter, one uppercase letter, and be at least 8 characters long.");

  $("#form-register").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      'name': {
        required: true
      },
      'email': {
        required: true
      },

      'password': {
        required: true,
        validate_password1: true
      },
      'confirm_password': {
        required: true,
        equalTo: '#password1'
      },
      'checkbox': {
        required: true
      },

    },
    messages: {
      'name': "Please Enter name.",
      'email': "Please Enter email address.",
      'password': {
        required: "Please enter password.",
        validate_password1: "Please enter a valid password. It must contain at least one digit, one lowercase letter, one uppercase letter, and be at least 8 characters long."
      },
      'confirm_password': {
        required: "Please enter confirm password.",
        equalTo: "Confirm password and password are not same."
      },
      'checkbox': "Please agree to all the Term & Conditions.",
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      $.ajax({
        url: "{{ route('register_save') }}",
        type: 'GET',
        data: $('#form-register').serialize(),
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();

          if (res.status == 1) {
            $('#signmdl').modal('hide');
            swal({
                title: "Success!",
                text: res.message,
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 2000
              })
              .then(() => {
                window.location.reload();
              })

          } else {
            $("#preloader").hide();
            swal({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            })
          }
        },
        error: function(error) {
          $("#preloader").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          })
        }
      });
    },
  });
</script>

<script>
  function login_model() {

    $('#loginmdl').modal('show');

    $("#form-login").validate({

      onfocusout: function(element) {
        $(element).valid();
      },

      rules: {

        'email': {
          required: true
        },

        'password': {
          required: true
        },


      },
      messages: {

        'email': "Please Enter email address.",
        'password': "Please enter password.",

      },
      errorPlacement: function(error, element) {
        if (element.attr("name") == "data[Payment][phone]") {
          error.insertAfter(".error-placement");
        } else {
          error.insertAfter(element);
        }
      },

      submitHandler: function(form) {
        $.ajax({
          url: "{{ route('login_save') }}",
          type: 'GET',
          data: $('#form-login').serialize(),
          beforeSend: function() {
            $("#preloader").show();
          },
          success: function(res) {
            $("#preloader").hide();

            if (res.status == 1) {
              $('#loginmdl').modal('hide');
              swal({
                  title: "Success!",
                  text: res.message,
                  icon: "success",
                  dangerMode: true,
                  buttons: false,
                  timer: 2000
                })
                .then(() => {
                  window.location.reload();
                })

            } else {
              $("#preloader").hide();
              swal({
                icon: 'error',
                title: 'Oops...',
                text: res.message,
              })
            }
          },
          error: function(error) {
            $("#preloader").hide();
            swal({
              icon: 'error',
              title: 'Oops...',
              text: 'Something went wrong!',
            })
          }
        });
      },
    });
  }
</script>

<script>
  $("#forgot_password").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      'email': {
        required: true
      },
    },
    messages: {

      'email': "Please Enter email address.",


    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      $.ajax({
        url: "{{ route('send_otp') }}",
        type: 'GET',
        data: $('#forgot_password').serialize(),
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();

          if (res.status == 1) {
            $('#loginmdl').modal('hide');
            swal({
                title: "Success!",
                text: res.message,
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 2000
              })
              .then(() => {
                $('#exampleModalToggle2').modal('hide');
                $('.user_email').val(res.email);

                $('#otp_model').modal('show');
              })

          } else {
            $("#preloader").hide();
            swal({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            })
          }
        },
        error: function(error) {
          $("#preloader").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          })
        }
      });
    },
  });
</script>



<script>
  $("#match_otp").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      'otp': {
        required: true,
        number: true,
        digits: true,
        minlength: 4,
        maxlength: 4
      },
    },
    messages: {

      'otp': "Please Enter valid OTP number.",


    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      $.ajax({
        url: "{{ route('user_match_otp') }}",
        type: 'GET',
        data: $('#match_otp').serialize(),
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();
          
          if (res.status == 1) {
            $('#loginmdl').modal('hide');
            $('#otp_model').modal('hide');
            swal({
                title: "Success!",
                text: res.message,
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 2000
              })
              .then(() => {

                $('#reset_password_model').modal('show');
              })

          } else {
            $("#preloader").hide();
            swal({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            })
          }
        },
        error: function(error) {
          $("#preloader").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          })
        }
      });
    },
  });
</script>

<script>
  $("#reset_password").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      'password': {
        required: true
      },
      'confirm_password': {
        required: true,
        equalTo: "#new_password"
      },
    },
    messages: {
      'password': "Please Enter password.",
      'confirm_password': "Please Enter confirm password.",
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      $.ajax({
        url: "{{ route('user_reset_password') }}",
        type: 'GET',
        data: $('#reset_password').serialize(),
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();
          $('#reset_password_model').modal('hide');
          if (res.status == 1) {
            $('#loginmdl').modal('hide');
            swal({
                title: "Success!",
                text: res.message,
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 2000
              })
              .then(() => {

                $('#loginmdl').modal('show');
              })

          } else {
            $("#preloader").hide();
            swal({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            })
          }
        },
        error: function(error) {
          $("#preloader").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          })
        }
      });
    },
  });
</script>

<script type="text/javascript">
  function newsletter_data() {
    var email = $('#newsletter').val();
    if (email != '') {
      $.ajax({
        url: "{{ url('/newsletter') }}",
        datatType: 'json',
        data: {
          '_token': '<?php echo csrf_token() ?>',
          'email': email,
        },

        success: function(res) {
          if (res.status == 1) {
            swal({
              title: "Success!",
              text: res.message,
              icon: "success",
              dangerMode: true,
              buttons: false,
              timer: 2000
            })
            $("#newsletter").val("");
          } else {
            swal({
              icon: 'error',
              title: 'Oops...',
              text: 'Plese enter email address!',
            })
          }
        }
      });
    } else {
      swal({
        icon: 'error',
        title: 'Oops...',
        text: 'Plese enter email address!',
      })
    }

  }
</script>

<script> 


  $('.slider').slick({
    draggable: true,
    autoplay: true,
    autoplaySpeed: 7000,
    arrows: false,
    dots: false,
    fade: true,
    speed: 500,
    infinite: true,
    cssEase: 'ease-in-out',
    touchThreshold: 100
  })
  // new WOW().init();

  $(document).ready(function() {
    $('.service-slide').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      speed: 300,
      infinite: true,
      autoplaySpeed: 5000,
      autoplay: true,
      responsive: [{
          breakpoint: 1600,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 2,
          }
        }
        
      ]
    });
  });
  $(document).ready(function() {
    $('.testimonial-slider').slick({
      slidesToShow: 2,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      speed: 300,
      infinite: true,
      autoplaySpeed: 5000,
      autoplay: true,
      responsive: [

        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });
  });



  //     $(".toggle-password").click(function() {
  //     $(this).toggleClass("fa-eye fa-eye-slash");
  //     input = $(this).parent().find("input");
  //     if (input.attr("type") == "password") {
  //         input.attr("type", "text");
  //     } else {
  //         input.attr("type", "password");
  //     }
  // });



  function loadmore(id) {
    let fn = "loadless(" + id + ")";
    $("#loadmorebtn" + id).attr('src', "./images/view-password.svg");
    $("#loadmorebtn" + id).attr('onclick', fn);
    let input = $("#password" + id).parent().find("input");
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    }
  }

  function loadless(id) {
    let fn = "loadmore(" + id + ")";
    $("#loadmorebtn" + id).attr('src', "./images/hide-password.svg");
    $("#loadmorebtn" + id).attr('onclick', fn);
    let input = $("#password" + id).parent().find("input");
    if (input.attr("type") != "password") {
      input.attr("type", "password");
    }
  }
</script>

<script>
  $(".h-search-btn").click(function() {
    $(".h-srch").toggleClass("slide");
  });
  $(".mobile_nav").click(function() {

    var mm = $(".mobile_menu"),
      mn = $(".mobile_nav"),
      a = "active";

    if (mm.hasClass(a) && mn.hasClass(a)) {
      mm.removeClass(a).fadeOut(200);
      mn.removeClass(a);
      $('.mobile_menu li').each(function() {
        $(this).removeClass('slide');
      });
    } else {
      mm.addClass(a).fadeIn(200);
      mn.addClass(a);
      $('.mobile_menu li').each(function(i) {
        var t = $(this);
        setTimeout(function() {
          t.addClass('slide');
        }, (i + 1) * 100);
      });
    }

  });



  // I've added annotations to make this easier to follow along at home. Good luck learning and check out my other pens if you found this useful


  // First let's set the colors of our sliders
  const settings = {
    fill: '#1abc9c',
    background: '#d7dcdf'
  }

  // First find all our sliders
  const sliders = document.querySelectorAll('.range-slider');

  // Iterate through that list of sliders
  // ... this call goes through our array of sliders [slider1,slider2,slider3] and inserts them one-by-one into the code block below with the variable name (slider). We can then access each of wthem by calling slider
  Array.prototype.forEach.call(sliders, (slider) => {
    // Look inside our slider for our input add an event listener
    //   ... the input inside addEventListener() is looking for the input action, we could change it to something like change
    slider.querySelector('input').addEventListener('input', (event) => {
      // 1. apply our value to the span
      slider.querySelector('span').innerHTML = event.target.value;
      // 2. apply our fill to the input
      applyFill(event.target);
    });
    // Don't wait for the listener, apply it now!
    applyFill(slider.querySelector('input'));
  });

  // This function applies the fill to our sliders by using a linear gradient background
  function applyFill(slider) {
    // Let's turn our value into a percentage to figure out how far it is in between the min and max of our input
    const percentage = 100 * (slider.value - slider.min) / (slider.max - slider.min);
    // now we'll create a linear gradient that separates at the above point
    // Our background color will change here
    const bg = `linear-gradient(90deg, ${settings.fill} ${percentage}%, ${settings.background} ${percentage+0.1}%)`;
    slider.style.background = bg;
  }
</script>


<script type="text/javascript">
  $(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));

    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });
</script>








<script>
  "use strict";

  // Minimum resizable area
  var minWidth = 400;
  var minHeight = 600;

  // Thresholds
  var FULLSCREEN_MARGINS = -10;
  var MARGINS = 4;

  // End of what's configurable.
  var clicked = null;
  var onRightEdge, onBottomEdge, onLeftEdge, onTopEdge;

  var rightScreenEdge, bottomScreenEdge;

  var preSnapped;

  var b, x, y;

  var redraw = false;
  var pane = document.getElementById('pane');

  var ghostpane = document.getElementById('ghostpane');

  function setBounds(element, x, y, w, h) {
    element.style.left = x + 'px';
    element.style.top = y + 'px';
    element.style.width = w + 'px';
    element.style.height = h + 'px';
  }

  function hintHide() {
    setBounds(ghostpane, b.left, b.top, b.width, b.height);
    ghostpane.style.opacity = 0;
  }

  // Mouse events
  pane.addEventListener('mousedown', onMouseDown);
  document.addEventListener('mousemove', onMove);
  document.addEventListener('mouseup', onUp);

  // Touch events	
  pane.addEventListener('touchstart', onTouchDown);
  document.addEventListener('touchmove', onTouchMove);
  document.addEventListener('touchend', onTouchEnd);

  function onTouchDown(e) {
    onDown(e.touches[0]);
    e.preventDefault();
  }

  function onTouchMove(e) {
    onMove(e.touches[0]);
  }

  function onTouchEnd(e) {
    if (e.touches.length == 0) onUp(e.changedTouches[0]);
  }

  function onMouseDown(e) {
  if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return; // Skip resizing if clicking on an input or textarea field
  onDown(e);
  e.preventDefault();
}

  function onDown(e) {
    calc(e);

    var isResizing = onRightEdge || onBottomEdge || onTopEdge || onLeftEdge;

    clicked = {
      x: x,
      y: y,
      cx: e.clientX,
      cy: e.clientY,
      w: b.width,
      h: b.height,
      isResizing: isResizing,
      isMoving: !isResizing && canMove(),
      onTopEdge: onTopEdge,
      onLeftEdge: onLeftEdge,
      onRightEdge: onRightEdge,
      onBottomEdge: onBottomEdge
    };
  }

  function canMove() {
    return x > 0 && x < b.width && y > 0 && y < b.height &&
      y < 30;
  }

  function calc(e) {
    b = pane.getBoundingClientRect();
    x = e.clientX - b.left;
    y = e.clientY - b.top;

    onTopEdge = y < MARGINS;
    onLeftEdge = x < MARGINS;
    onRightEdge = x >= b.width - MARGINS;
    onBottomEdge = y >= b.height - MARGINS;

    rightScreenEdge = window.innerWidth - MARGINS;
    bottomScreenEdge = window.innerHeight - MARGINS;
  }

  var e;

  function onMove(ee) {
    calc(ee);

    e = ee;

    redraw = true;

  }

  function animate() {
    requestAnimationFrame(animate);

    if (!redraw) return;

    redraw = false;

    if (clicked && clicked.isResizing) {
      if (clicked.onRightEdge) pane.style.width = Math.max(x, minWidth) + 'px';
      if (clicked.onBottomEdge) pane.style.height = Math.max(y, minHeight) + 'px';

      if (clicked.onLeftEdge) {
        var currentWidth = Math.max(clicked.cx - e.clientX + clicked.w, minWidth);
        if (currentWidth > minWidth) {
          pane.style.width = currentWidth + 'px';
          pane.style.left = e.clientX + 'px';
        }
      }

      if (clicked.onTopEdge) {
        var currentHeight = Math.max(clicked.cy - e.clientY + clicked.h, minHeight);
        if (currentHeight > minHeight) {
          pane.style.height = currentHeight + 'px';
          pane.style.top = e.clientY + 'px';
        }
      }

      hintHide();

      return;
    }

    if (clicked && clicked.isMoving) {

      if (b.top < FULLSCREEN_MARGINS || b.left < FULLSCREEN_MARGINS || b.right > window.innerWidth - FULLSCREEN_MARGINS || b.bottom > window.innerHeight - FULLSCREEN_MARGINS) {
        setBounds(ghostpane, 0, 0, window.innerWidth, window.innerHeight);
        ghostpane.style.opacity = 0.2;
      } else if (b.top < MARGINS) {
        setBounds(ghostpane, 0, 0, window.innerWidth, window.innerHeight / 2);
        ghostpane.style.opacity = 0.2;
      } else if (b.left < MARGINS) {
        setBounds(ghostpane, 0, 0, window.innerWidth / 2, window.innerHeight);
        ghostpane.style.opacity = 0.2;
      } else if (b.right > rightScreenEdge) {
        setBounds(ghostpane, window.innerWidth / 2, 0, window.innerWidth / 2, window.innerHeight);
        ghostpane.style.opacity = 0.2;
      } else if (b.bottom > bottomScreenEdge) {
        setBounds(ghostpane, 0, window.innerHeight / 2, window.innerWidth, window.innerWidth / 2);
        ghostpane.style.opacity = 0.2;
      } else {
        hintHide();
      }

      if (preSnapped) {
        setBounds(pane,
          e.clientX - preSnapped.width / 2,
          e.clientY - Math.min(clicked.y, preSnapped.height),
          preSnapped.width,
          preSnapped.height
        );
        return;
      }

      pane.style.top = (e.clientY - clicked.y) + 'px';
      pane.style.left = (e.clientX - clicked.x) + 'px';

      return;
    }

    if (onRightEdge && onBottomEdge || onLeftEdge && onTopEdge) {
      pane.style.cursor = 'nwse-resize';
    } else if (onRightEdge && onTopEdge || onBottomEdge && onLeftEdge) {
      pane.style.cursor = 'nesw-resize';
    } else if (onRightEdge || onLeftEdge) {
      pane.style.cursor = 'ew-resize';
    } else if (onBottomEdge || onTopEdge) {
      pane.style.cursor = 'ns-resize';
    } else if (canMove()) {
      pane.style.cursor = 'move';
    } else {
      pane.style.cursor = 'default';
    }
  }

  animate();

  function onUp(e) {
    calc(e);

    if (clicked && clicked.isMoving) {
      var snapped = {
        width: b.width,
        height: b.height
      };

      if (b.top < FULLSCREEN_MARGINS || b.left < FULLSCREEN_MARGINS || b.right > window.innerWidth - FULLSCREEN_MARGINS || b.bottom > window.innerHeight - FULLSCREEN_MARGINS) {
        setBounds(pane, 0, 0, window.innerWidth, window.innerHeight);
        preSnapped = snapped;
      } else if (b.top < MARGINS) {
        setBounds(pane, 0, 0, window.innerWidth, window.innerHeight / 2);
        preSnapped = snapped;
      } else if (b.left < MARGINS) {
        setBounds(pane, 0, 0, window.innerWidth / 2, window.innerHeight);
        preSnapped = snapped;
      } else if (b.right > rightScreenEdge) {
        setBounds(pane, window.innerWidth / 2, 0, window.innerWidth / 2, window.innerHeight);
        preSnapped = snapped;
      } else if (b.bottom > bottomScreenEdge) {
        setBounds(pane, 0, window.innerHeight / 2, window.innerWidth, window.innerWidth / 2);
        preSnapped = snapped;
      } else {
        preSnapped = null;
      }

      hintHide();
    }

    clicked = null;
  }
</script>




</body>

</html>