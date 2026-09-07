@extends('frontend.layout.layout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
<style type="text/css">
  .my-account .tab-pane input {
    margin-bottom: 0px; 
}
.iti--separate-dial-code .iti__selected-flag {
    height: 44px;
}


</style> 
 <!-- Hero Section  -->
    <section class="mt-110">
      
    </section>  

<!-- Demo header-->
<section class="py-5 header my-account">
    <div class="container py-4">

        <div class="row">
            <div class="col-md-3">
              <div class="profile">
                <img src="{{isset($user->profile)?url($user->profile):url('/public/frontend/assets/img/profile_pic.png')}}" style="height: 90px; width: 90px; border-radius: 50%;" alt="" />
                <h4>{{$user->name}}<span>{{$user->mobile}}</span></h4>
              </div>
                <!-- Tabs nav -->
                <div class="nav flex-column nav-pills nav-pills-custom profile-respon-mbl" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link py-4 px-0 {{ isset($_GET['tab']) ? $_GET['tab']=='profile' ? 'active' : '' : 'active' }}" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                        <i class=""><img src="{{url('/public/frontend/')}}/assets/img/user2.svg" alt="" /></i>
                        <span class="font-weight-bold small">My Profile <small>Your personal details</small></span></a>

                    <a class="nav-link py-4 px-0 {{ isset($_GET['tab']) ? $_GET['tab']=='wallet' ? 'active' : '' : '' }}" id="v-pills-wallet-tab" data-toggle="pill" href="#v-pills-wallet" role="tab" aria-controls="v-pills-wallet" aria-selected="false">
                        <i class=""><img src="{{url('/public/frontend/')}}/assets/img/wallet_icon.svg" alt="" /></i>
                        <span class="font-weight-bold small">My Wallet<small>Earn money Details</small></span></a>

                    <a class="nav-link py-4 px-0 {{ isset($_GET['tab']) ? $_GET['tab']=='Bookings' ? 'active' : '' : '' }}" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                        <i class=""><img src="{{url('/public/frontend/')}}/assets/img/my-boking.svg" alt="" /></i>
                        <span class="font-weight-bold small">My Bookings<small>Your bookings details</small></span></a>

                    <a class="nav-link py-4 px-0 {{ isset($_GET['tab']) ? $_GET['tab']=='Favorites' ? 'active' : '' : '' }}" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                        <i class=""><img src="{{url('/public/frontend/')}}/assets/img/like.svg" alt="" /></i>
                        <span class="font-weight-bold small">Favorites<small>Your favorite property details</small></span></a>

                    <a class="nav-link py-4 px-0 {{ isset($_GET['tab']) ? $_GET['tab']=='Invite' ? 'active' : '' : '' }}" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-invite" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                        <i class=""><img src="{{url('/public/frontend/')}}/assets/img/gift_present.svg" alt="" /></i>
                        <span class="font-weight-bold small">Invite & Earn<small>Send invite your friends</small></span></a>

                    <a class="nav-link py-4 px-0 {{ isset($_GET['tab']) ? $_GET['tab']=='Notifications' ? 'active' : '' : '' }}" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-notifi" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                        <i class=""><img src="{{url('/public/frontend/')}}/assets/img/notifi.svg" alt="" /></i>
                        <span class="font-weight-bold small">Notifications<small>Your bookings notifications</small></span></a>

                    <a class="nav-link py-4 px-0" href="{{route('user_logout')}}">
                        <i class=""><img src="{{url('/public/frontend/')}}/assets/img/out.svg" alt="" /></i>
                        <span class="font-weight-bold small">Logout</span></a>
                    </div>
                    
                    
            </div>


            <div class="col-md-9">
                <!-- Tabs content -->
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade rounded bg-white {{ isset($_GET['tab']) ? $_GET['tab']=='profile' ? 'show active' : '' : 'show active' }} ps-4" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <h4 class="font-italic mb-4">My Profile</h4>
                        <h5 class="font-italic mb-2 mt-2">Your Personal Details</h5>
                        <a class="edt-prft" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><img src="{{url('/public/frontend/')}}/assets/img/edit_alt.svg" alt="" /> Edit Details</a>
                        <ul>
                          <li><span>Name</span>
                            {{$user->name}}
                          </li>
                          <li><span>Email</span>
                            {{$user->email}}
                          </li>
                          <li><span>Mobile Number</span>
                            {{isset($user->country_code)?'+'.$user->country_code.' ':''}}{{$user->mobile}}
                          </li>
                        </ul>
                        <h5 class="font-italic mb-2 mt-5">Your Password</h5>
                        <a class="edt-prft" id="v-pills-password-tab" data-toggle="pill" href="#v-pills-password" role="tab" aria-controls="v-pills-password" aria-selected="false"><img src="{{url('/public/frontend/')}}/assets/img/edit_alt.svg" alt="" /> Change Password</a>
                        <ul>
                          <li><span>Password</span>
                            ********
                          </li>
                        </ul>
                    </div>
                    
                    <div class="tab-pane fade rounded bg-white  ps-4" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-password-tab">
                        <h5 class="font-italic mb-2 mt-5">My Profile</h5>
                        <form id="profile" method="post">
                          @csrf
                          <div class="row">
                            <div class="col-lg-4 col-md-12">
                              <div class="form-group">
                                <label for="">Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="{{isset($user->name)?$user->name:''}}" value="{{isset($user->name)?$user->name:''}}" name="name" />
                              </div>
                              <div class="form-group">
                                <label for="">Email <span class="text-danger">*</span></label>
                                <input type="email" value="{{isset($user->email)?$user->email:''}}" name="email" placeholder="Enter Email Address" />
                              </div>
                              <div class="form-group">
                                <label for="Mobile Number">Mobile Number <span class="text-danger">*</span></label>
                                  <input type="text" id="mobile_code" name="mobile" value="{{ old('phone',isset($user->mobile) ? '+'.$user->country_code.' '.$user->mobile : '' )}}" class="form-control" placeholder="Phone Number">               
                              </div>
                              <button type="submit" class="pre-btn p-2 px-3">Update</button>
                            </div>
                          </div>
                        </form>   
                    </div>

                    <div class="tab-pane fade rounded bg-white ps-4" id="v-pills-password" role="tabpanel" aria-labelledby="v-pills-password-tab">
                        
                        <h5 class="font-italic mb-2 mt-5">Your Password</h5>
                        <form id="change_password">
                          <div class="row">
                            <div class="col-lg-4 col-md-12">
                              <div class="form-group">
                                <label for="">Current Password <span class="text-danger">*</span></label>
                                <input type="Password" name="current_password" placeholder="********" />
                              </div>
                              <div class="form-group">
                                <label for="">New Password <span class="text-danger">*</span></label>
                                <input type="Password" placeholder="New Password" name="new_password" />
                              </div>
                              <button type="submit" class="pre-btn p-2 px-3">Update</button>
                            </div>
                          </div>
                        </form>
                    </div>

                    <div class="tab-pane fade rounded bg-white {{ isset($_GET['tab']) ? $_GET['tab']=='wallet' ? 'show active' : '' : '' }} ps-4" id="v-pills-wallet" role="tabpanel" aria-labelledby="v-pills-wallet-tab">
                        <h4 class="font-italic mb-4">My Wallet</h4>
                        <div class="wlt-bg">
                          <img src="{{url('/public/frontend/')}}/assets/img/wallet_iconl.svg" alt="" />
                          <h5>Available Balance
                            <span>₹{{isset($user->wallet)?$user->wallet:'0'}}</span>
                          </h5>
                        </div>
                        <h5 class="font-italic mb-2 mt-5">Wallet Transactions</h5>

                        @foreach($wallet_booking as $wallet_book)
                        @php
                          $check_in = strtotime($wallet_book['check_in']);
                          $check_out = strtotime($wallet_book['check_out']);
                          $day_diff = $check_out - $check_in;
                          $day_count = $day_diff/(60*60*24);
                        @endphp
                        <div class="row trancton">
                          <div class="col-md-12"><p>{{date_format($wallet_book->created_at,"D, d M Y | h:i A");}}</p></div>
                          <div class="col-md-6 d-flex">
                            <img src="{{url('/public/frontend/')}}/assets/img/hotel-img.png" alt="" />
                            <div class="">
                              <h6>{{$wallet_book['get_hotel']->hotel_name}}<span>{{$day_count}} Nights X {{$wallet_book->guest_qty}} Guest</span></h6>
                              <p>ID : {{$wallet_book->booking_id}}</p>
                            </div>
                          </div>
                          <div class="col-md-6 htl-prc">- ₹{{$wallet_book->total_amount}}</div>
                        </div>
                        @endforeach
                        
                            
                    </div>
                    
                    <div class="tab-pane fade rounded bg-white ps-4 {{ isset($_GET['tab']) ? $_GET['tab']=='Bookings' ? 'show active' : '' : '' }} bookings-tab" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                        <h4 class="font-italic mb-4">My Bookings</h4>
                        <nav>
                          <div class="nav nav-tabs mb-3 " id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Current Bookings</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Past Bookings</button>
                          </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">
                          <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="row">

                              @foreach($current_booking as $current_book)
                              <div class="col-md-6">
                                <div class="prty-dtl-pr">
                                  <div class="bknid-dt">
                                    <P>Booking # {{$current_book->booking_id}}</P>
                                    <p>{{date_format($current_book->created_at,"D, d M Y | h:i A")}}</p>
                                  </div>
                                  <div class="hotl-cntct">{{$current_book->guest_name}} <span>{{$current_book->phone}}</span></div>
                                  <div class="htl-pic">
                                    <img class="img-fluid" src="{{url($current_book['get_hotel']->profile)}}" alt="" height="100px" width="100px">
                                    <div>
                                      <h3>{{$current_book['get_hotel']->hotel_name}}</h3>
                                      <p>{{$current_book['get_hotel']->address}} <span>{{$current_book['get_room'][0]->category}}</span></p>
                                      <!-- <h5><span>Rate & Review </span></h5> -->
                                    </div>                                    
                                  </div>
                                  <ul>
                                    <!-- <li><span>Booking Date</span>Fri, 15 Dec 22 - Sat, 16 Dec 22</li> -->
                                    <li><span>Booking Date</span>{{date('D, d M y', strtotime($current_book['check_in']))}} - {{date('D, d M y', strtotime($current_book['check_out']))}}</li>
                                    <li><span>Room</span>{{$current_book['room_qty']}}</li>
                                    <li><span>Guest</span>{{$current_book['guest_qty']}}</li>
                                  </ul>
                                    <div class="htl-prce">
                                      <h4><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/rupee.svg" alt=""> {{$current_book->total_amount}} </h4>
                                      
                                      <a class="bdr-btn" href="{{url('/booking-detail-current/'.get_encrypted_value($current_book->id, true))}}" tabindex="0">View Details</a>
                                    </div>
                                 </div>
                              </div>
                              @endforeach

                              
                            </div>
                          </div>
                          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="row">
                              @foreach($past_booking as $past_book)
                              <div class="col-md-6">
                                <div class="prty-dtl-pr">
                                  <div class="bknid-dt">
                                    <P>Booking # {{$past_book->booking_id}}</P>
                                    <p>{{date_format($past_book->created_at,"D, d M Y | h:i A")}}</p>
                                  </div>
                                  <div class="hotl-cntct">{{$past_book->guest_name}} <span>{{$past_book->phone}}</span></div>
                                  <div class="htl-pic">
                                    <img class="img-fluid" src="{{url($past_book['get_hotel']->profile)}}" alt="" height="100px" width="100px">
                                    <div>
                                      <h3>{{$past_book['get_hotel']->hotel_name}}</h3>
                                      <p>{{$past_book['get_hotel']->address}} <span>{{$past_book['get_room'][0]->category}}</span></p>
                                      <!-- <h5><span>Rate & Review </span></h5> -->
                                    </div>                                    
                                  </div>
                                  <ul>
                                    
                                    <li><span>Booking Date</span>{{date('D, d M y', strtotime($past_book['check_in']))}} - {{date('D, d M y', strtotime($past_book['check_out']))}}</li>
                                    <li><span>Room</span>{{$past_book['room_qty']}}</li>
                                    <li><span>Guest</span>{{$past_book['guest_qty']}}</li>
                                  </ul>
                                    <div class="htl-prce">
                                      <h4><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/rupee.svg" alt=""> {{$past_book->total_amount}} </h4>
                                      
                                      
                                      <p><span>STATUS</span>
                                        @if($past_book->booking_status==1)
                                        <span class="text-warning">Pending</span>
                                        @elseif($past_book->booking_status==2)
                                        <span class="text-success">Completed</span>
                                        @else
                                        <span class="text-danger">Cancel</span>
                                        @endif
                                      </p>
                                      <a class="bdr-btn" href="{{url('/booking-detail-past/'.get_encrypted_value($past_book->id, true))}}" tabindex="0">View Details</a>
                                    </div>
                                 </div>
                              </div>
                              @endforeach

                            </div>
                          </div>
                        </div>
                        
                    </div>
                    
                    <div class="tab-pane fade rounded bg-white ps-4 {{ isset($_GET['tab']) ? $_GET['tab']=='Favorites' ? 'show active' : '' : '' }} favoritee" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <h4 class="font-italic mb-4">Favorites</h4>
                        <h5 class="font-italic mb-2 mt-3">Your Favorite Properties</h5>
                        <div class="row">
                          @foreach($favorites as $fav)
                          <div class="col-md-6">
                              <div class="prty-dtl-pr">
                                <div class="htl-pic">
                                  <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/hotel-img.png" alt="">
                                  <div>
                                    <h3>{{$fav['gethotel']->hotel_name}}</h3>
                                    <h5><span>{{$fav['gethotel']->hotel_review}}.0 <img class="m-0" src="{{url('/public/frontend/')}}/assets/img/Polygon.svg" alt=""></span> ({{$fav['gethotel']->review_count}} Ratings)</h5>
                                    <p>{{$fav['gethotel']->address}}</p>
                                  </div>                                    
                                </div>
                                  <div class="htl-prce">
                                    <h4><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/rupee.svg" alt=""> {{$fav['gethotel']->discount_rent}} <span><img src="{{url('/public/frontend/')}}/assets/img/com-rupee.svg" alt=""> {{$fav['gethotel']->room_rent}}</span></h4>
                                    <a class="bdr-btn" href="{{url('/detail/'.$fav['gethotel']->slug)}}" tabindex="0">View Details</a>
                                  </div>
                                  <h6>per room per night</h6>
                               </div>
                            </div>
                            @endforeach
                        </div>


                    </div>

                    <div class="tab-pane fade rounded bg-white ps-4 {{ isset($_GET['tab']) ? $_GET['tab']=='Invite' ? 'show active' : '' : '' }} invite-fr" id="v-pills-invite" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <h4 class="font-italic mb-4">Invite</h4>
                        <div class="rew-ref">
                          <p>{{$reward_point}}<span>Reward Point Credit</span></p>
                          <p class="blu">{{count($referrals)}}<span>Referrals</span></p>
                          <a class="bdr-btn" href="#" data-bs-toggle="modal" data-bs-target="#redamModalToggle">Redeem</a>
                        </div>
                        <div class="gt200">Get 200 Reward Points Credit for every friend you refer!, Share your friends the experience of learning from world's best!</div>
                        <img src="{{url('/public/frontend/')}}/assets/img/friendss.svg" alt="" />
                        <ul>
                          <li>Invite a friend to cityroom</li>
                          <li>That friend spends 300 Rupees or more booking their first lesson.</li>
                          <li>After they finish their lesson, you both receive a 200 Reward Points.</li>
                        </ul>
                        <div class="gt200 mb-2">Share your referral link</div>
                        <div class="input-group cop-lnk">    
                          <input type="text" id="myInput" class="form-control" value="{{url('/register/'.$user->refar_code)}}" readonly>
                          <a  onclick="copy_refar()"><img src="{{url('/public/frontend/')}}/assets/img/content_copy.svg" alt="" /></a>
                        </div>
                        <div class="gt200 mb-2">Share via Social media</div>
                        <div class="socl-icn">
                          <a href=""><img src="{{url('/public/frontend/')}}/assets/img/mail-icn.svg" alt="" /></a>
                          <a href="" class="ms-3"><img src="{{url('/public/frontend/')}}/assets/img/whats-icn.svg" alt="" /></a>
                          <a href="" class="ms-3"><img src="{{url('/public/frontend/')}}/assets/img/twtr-icn.svg" alt="" /></a>
                          <a href="" class="ms-3"><img src="{{url('/public/frontend/')}}/assets/img/fb-icn.svg" alt="" /></a>
                        </div>
                    </div>
                    <div class="tab-pane fade rounded bg-white {{ isset($_GET['tab']) ? $_GET['tab']=='Notifications' ? 'show active' : '' : '' }} ps-4" id="v-pills-notifi" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <h4 class="font-italic mb-4">Notification</h4>
                        @foreach($notification_date as $date)
                        <h5 class="font-italic mb-2 mt-3">{{date_format($date->created_at,"d M Y")}}</h5>
                        <ul class="notift">
                          @php
                          $notification = App\Models\Notifications::whereDate('created_at', '=', $date->created_at)->get();

                          @endphp
                          @foreach($notification as $not)
                          <li>
                            <img src="{{url('/public/frontend/')}}/assets/img/ct-log.svg" alt="" />
                            <p><b>Cityroom</b> {{$not->message}}
                              <span><img src="{{url('/public/frontend/')}}/assets/img/clock-gr.svg" alt="" /> {{date_format($date->created_at,"h:i A")}}</span>
                            </p>
                          </li>
                          @endforeach
                        </ul>
                        @endforeach
                    
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</section>
    <div class="modal otp-mdl fade " id="redamModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"> 
          <div class="modal-body p-5">
            <h3>Friends you've referred</h3>
            <div class="frnds-lst">
              @foreach($referrals as $refar)
              <div class="frnds-n">
                <div class="frnds1">
                  <img src="{{isset($refar->profile)?url($refar->profile):''}}" alt="" style="width:50px; height: 50px; border-radius: 50%;" />
                  <p>{{$refar->name}}<span>{{date_format($refar->created_at,"d M Y");}}</span></p>
                </div>
                <div class="frnds2">
                  <p><img src="{{url('/public/frontend/')}}/assets/img/money1.svg" alt="" /> 200<span>Completed</span></p>
                </div>
              </div>
              @endforeach
            </div>            
          </div>
        </div>
      </div>
    </div>
<script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<script src="{{url('/public/frontend/')}}/assets/js/rome.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>   -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
<script>
    function copy_refar() {
      // Get the text field
    var copyText = document.getElementById("myInput");

    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);
    
    // Alert the copied text
    // alert("Copied the text: " + copyText.value);
    }
</script>
<script>
    $("#profile").validate({

        onfocusout: function (element) {
            $(element).valid();
        },

        rules: {
            'name': {required: true},
            'email': {required: true},
            'mobile': {required: true},
        },
        messages: {
            'name': "Please Enter name.",        
            'email': "Please Enter email address.",        
            'mobile': "Please Enter mobile number.",                      
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "data[Payment][phone]") {
              error.insertAfter(".error-placement");
          } else {
              error.insertAfter(element);
          }
      },

      submitHandler: function(form) {
        var mobile_code = $('#mobile_code').val();
        var country_code = $('.iti__selected-dial-code').html();
        $.ajax(
        {
            url: "{{ route('user_update_profile') }}",
            type: 'GET',
            data: $('#profile').serialize()+"&country_code="+country_code,
            beforeSend: function() {
                $("#preloader").css("display", "block");
            },
            success: function (res)
            {
              $("#preloader").css("display", "none");
                if (res.status == 1)
                {
                    $('#exampleModalToggle').modal('show');
                    $('.otp_mobile').html(country_code+' '+mobile_code);
                    var timeleft = 60;
                     var downloadTimer = setInterval(function(){
                      timeleft--;
                      if(timeleft==0){
                        $('.resend_otp').css('display','block');
                      }
                      document.getElementById("countdowntimer").textContent = timeleft;
                      if(timeleft <= 0)

                        clearInterval(downloadTimer);
                    },1000);
                    
                }else{
                  $("#preloader").css("display", "none"); 
                  swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: res.message,
                  })
                }   
            },
            error: function (error) {
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
    $("#change_password").validate({

        onfocusout: function (element) {
            $(element).valid();
        },

        rules: {
            'current_password': {required: true},
            'new_password': {required: true},
        },
        messages: {
            'current_password': "Please Enter current password.",        
            'new_password': "Please Enter new password.",                       
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "data[Payment][phone]") {
              error.insertAfter(".error-placement");
          } else {
              error.insertAfter(element);
          }
      },

      submitHandler: function(form) {
        $.ajax(
        {
            url: "{{ route('user_change_password') }}",
            type: 'GET',
            data: $('#change_password').serialize(),
            beforeSend: function() {
                $("#preloader").show(); 
            },
            success: function (res)
            {
              $("#preloader").hide(); 
                if (res.status == 1)
                {
                  swal({
                    title: "Success!",
                    text: res.message,
                    icon: "success",
                    dangerMode: true,
                    buttons: false,
                    timer: 1000
                })
                .then(() => {
                      $("#change_password").load(location.href + " #change_password");
                  })
                
                    
                }else{
                  $("#preloader").hide(); 
                  swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: res.message,
                  })
                }   
            },
            error: function (error) {
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
  function varify_button(){
    if($("#otp input#first").val() == "" || $("#otp input#second").val() == "" || $("#otp input#third").val() == "" || $("#otp input#fourth").val() == ""){
      $('.error_otp').css("display", "block"); 
        return false;
    } 
    else 
    {
      var one = $("#otp input#one").val()
      var two = $("#otp input#two").val()
      var three = $("#otp input#three").val()
      var four = $("#otp input#four").val()
      var five = $("#otp input#five").val()
      var six = $("#otp input#six").val()
      var mobile = $("#mobile_code").val()
      $.ajax({
        url:"{{route('profile_match_otp')}}",
        
        data:{
          one,two,three,four,five,six,mobile
        },
        beforeSend: function() {
          $("#preloader").show();
        },

        success: function (res) {
          $("#preloader").hide();
          $('#exampleModalToggle').modal('hide');
          if (res.status==1) { 
              swal({
                    title: "Success!",
                    text: res.message,
                    icon: "success",
                    dangerMode: true,
                    buttons: false,
                    timer: 1000
                })
                       
          }
          else {
            swal("OTP is invalid", "Plesae enter correct OTP.", "warning");
          }
        },
        error: function (error) {
            $("#loading-image").hide();
            swal("Oops...", "An error occurred!", "warning");
             $("#order_button").attr("disabled", false);
        }
      })
    }
  }
</script>
<script>
    
    // -----Country Code Selection
    $("#mobile_code").intlTelInput({
     initialCountry: "in",
     separateDialCode: true,
  // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
   });
 </script>
    

    @endsection