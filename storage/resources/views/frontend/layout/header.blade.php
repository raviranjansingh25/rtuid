@php
$header_city = App\Models\City::where('recommended',1)->where('status',1)->orderBy('order','ASC')->get();
@endphp
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@if($title != ""){{ $title }} | @endif {{$setting->sitename}}</title>
    <link rel="shortcut icon" href="{{url($setting->fav_icon)}}">
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/style.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/slick.css">
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/slick-theme.css">
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/rome.css">
  </head>
  <style type="text/css">
    .error{
      color: red!important;
      width: 100%;
      text-transform: lowercase; 

    }
    .error::first-letter {
       text-transform: uppercase; 
      }
    .lgn-frm input {
         margin-bottom: 0px; 
    }
    .sgn-frm .frm-cnct input, .sgn-frm .iti {
        margin-bottom: 5px;
    }
    .accordion a.active {
        color: #f03e39;
    }
    .lke-icn1 {
        position: absolute;
        right: 22px;
        top: 22px;
        z-index: 1;
    }
    .frm-cnct input, .frm-cnct textarea {
       margin-bottom: 0px; 
  }
  .form-group{
    margin-bottom: 20px; 
  }
  span.error_otp {
    padding: 1px 5px;
    margin-top: 5px;
    background: red;
    color: #fff;
    border-radius: 3px;
    display:none
  }
  .preloader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #333333b8;
    z-index: 99999;
    display: none;
  }

.loader {
  border: 16px solid #333333;
  border-top: 16px solid #f03e39;
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 1s linear infinite;
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -60px;
  margin-left: -60px;
}
.iti--separate-dial-code .iti__selected-flag {
    height: 50px;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
.gj-datepicker-bootstrap [role=right-icon] button {
    width: 38px;
    position: relative;
    border: 1px solid #ced4da;
    height: 60px;
    border-radius: 0px 5px 5px 0px;
}
.resend_otp{
  display: none;
}
.rd-container-attachment {
    position: absolute;
    z-index: 9;
}
  </style>
  
  <body>
    <div class="preloader" id="preloader">
      <div class="loader"></div>
    </div>
    <nav class="nav">
      <div class="container">
          <div class="logo">
            <a href="{{route('home')}}"><img src="{{url($setting->footer_logo)}}" alt="" /></a>
          </div>
          <div id="mainListDiv" class="main_list">
              <ul class="navlinks">
                  <li><a class="grn-btn" href="{{route('associated')}}"><img class="img-fluid me-2" src="{{url('/public/frontend/')}}/assets/img/plus-bg.svg" alt="" /> List Your Property</a></li>

                  
                  @if(!empty(Auth::guard('web')->user()))
                  <li class="profile-drop">
                    <img class="prfl-img" src="{{isset(Auth::guard('web')->user()->profile)?url(Auth::guard('web')->user()->profile):url('/public/frontend/assets/img/profile_pic.png')}}" alt="" />
                    <div class="prfle-act-dropdwn" style="display: none;">
                      <h3>Hi, {{Auth::guard('web')->user()->name}}...</h3>
                      <ul>
                        <li><a href="{{route('user_dashboard')}}?tab=profile" id="v-pills-home-tab" data-toggle="pill"><img src="{{url('/public/frontend/')}}/assets/img/my-profile.svg" alt=""> My Profile</a></li>
                        <li><a href="{{route('user_dashboard')}}?tab=wallet" id="v-pills-wallet-tab" data-toggle="pill"><img src="{{url('/public/frontend/')}}/assets/img/wallet.svg" alt=""> My Wallet</a></li>
                        <li><a href="{{route('user_dashboard')}}?tab=Bookings"><img src="{{url('/public/frontend/')}}/assets/img/bokns.svg" alt=""> My Bookings</a></li>
                        <li><a href="{{route('user_dashboard')}}?tab=Favorites"><img src="{{url('/public/frontend/')}}/assets/img/fave.svg" alt=""> Favorites</a></li>
                        <li><a href="{{route('user_dashboard')}}?tab=Invite"><img src="{{url('/public/frontend/')}}/assets/img/gift.svg" alt=""> Invite & Earn</a></li>
                        <li><a href="{{route('user_dashboard')}}?tab=Notifications"><img src="{{url('/public/frontend/')}}/assets/img/notifi-1.svg" alt=""> Notifications</a></li>
                        <li><a href="{{route('user_logout')}}?tab=profile"><img src="{{url('/public/frontend/')}}/assets/img/out.svg" alt=""> Sign Out</a></li>
                      </ul>
                    </div>
                  </li>
                  @else
                  <li><a href="{{route('login')}}" class="red-btn"><img class="img-fluid me-2" src="{{url('/public/frontend/')}}/assets/img/login-errow.svg" alt="" /> Sign In / Sign Up</a></li>
                  @endif
              </ul>
          </div>
          <span class="navTrigger">
              <i></i>
              <i></i>
              <i></i>
          </span>
      </div>
      <div class="container">
        <div class="cityes"> 
          <ul>
              <div class="items">
                @foreach($header_city as $city_data)
                <div class="item item1"> 
                  <li style="padding: 0px 0px;">
                      <a href="{{route('hotels',['city'=>$city_data->id])}}">
                      <img src="{{url('/public/frontend/')}}/assets/img/location.svg" alt=""><b>{{$city_data->city}}</b>
                    </a>
                  </li> 
                </div>
                @endforeach
              </div> 
          </ul>
          <a href="{{route('all_city')}}"><img src="{{url('/public/frontend/')}}/assets/img/location.svg" alt="">All Cities</a>
      </div>
      </div>
  </nav>