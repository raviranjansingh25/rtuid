@php
$service1 = \App\Models\Category::where('status',1)->orderBy('title','asc')->get();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta property="og:title" content="@if($title != ""){{ $title }} | @endif {{$setting->sitename}}">

    
  <title>@if($title != ""){{ $title }} | @endif {{$setting->sitename}}</title>
  <meta property="og:image" content="{{url('/public/telimed_og.jpg')}}">
   
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{$setting->sitename}}">
    <meta property="og:logo" content="{{url('/public/telimed_og.jpg')}}" />
    <meta name="twitter:card" content="{{url('/public/telimed_og.jpg')}}">
    <meta name="twitter:title" content="@if($title != ""){{ $title }} | @endif {{$setting->sitename}}">
    <meta name="twitter:description" content="Description of your page">
    <meta name="twitter:image" content="{{url('/public/telimed_og.jpg')}}">
  <link href="{{ url('/public/admin/') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="icon" type="image/x-icon" href="{{isset($setting->fav_icon)?url($setting->fav_icon):url('/public/noimage.png')}}">
  <!-- <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/animate.min.css" /> -->
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/rome.css">
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/style.css" />


</head>

<style>
  .profile_img {

    height: 50px;
    width: 50px;
    border-radius: 50%;

  }
  .thread_details.active{
    background-color: #e1e1e1;
  }

  .list-chat {
    padding: 0px 0px;
}
  
  .actvty-type p.avalble:before {
      background-color: #fcc;
  }
  .error {
    color: red;
    /* float: left; */
  }

  .form-floating {
    /* margin-bottom: inherit; */
  }

  .input-group.change-passwords span {
    position: absolute;
    right: 0px;
    padding: 10px;
  }

  .input-group.change-passwords {
    position: relative;
  }

  .readchat{
    width: 9px;
    height: 9px;
    border-radius: 25px;
    position: absolute;
    z-index: 9;
    top: 22px;
    left: 61px;
    display: none;
}

  .readchat.active{
  
    border: 1px solid red;
    background-color: red;
    display: block;
}

.user_unread_count{
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: red;
  position: absolute;
  top: 0px;
  display: none;
  z-index: 9999;
}

.list-chat ul li {
  position: relative;
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
    border-top: 16px solid #06d7d3;
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 1s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;header_logo
    margin-top: -60px;
    margin-left: -60px;
  }

  .dropdown-item.active,
  .dropdown-item:active {
    color: #fff;header_logo
    text-decoration: none;
    background-color: #fff;
  }

  .chat-body {
    overflow-y: hidden;
  }

  .send-msg p {
    display: inline-block;
    background: #F6F6F6 0% 0% no-repeat padding-box;
    border-radius: 30px;
    padding: 10px 20px;
    margin: 0;
  }

  .select2-container {
    margin-bottom: 20px;
  }

  .head_nav span:hover {
    color: #12b78e;
  }

  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {header_logo
    -webkit-appearance: none;
    margin: 0;
  }
  .dropdown-toggle {
    white-space: nowrap; 
}
  /* Firefox */
  input[type=number] {
    -moz-appearance: textfield;
  }

  .pdf_down_div{
    width: 100%;
    word-wrap: break-word;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
  @media only screen and (max-width: 500px) {
    .logo img {
        width: 55px;
    }
}
</style>

<body>
  <div class="main_section">
    <div class="preloader" id="preloader">
      <div class="loader"></div>
    </div>
    <header>
      <div class='container-fluid'>
        <div class='mobile_nav'>
          <button class='burger' title='Open and close menu'>
            <span class='mobile_nav__label'>Open and close menu</span>
            <div class='top stripe'></div>
            <div class='middle stripe'></div>
            <div class='bottom stripe'></div>
          </button>
        </div>
        <div class='mobile_menu'>
          <nav>
            <ul>
            @if(!empty(Auth::guard('web')->user()))
              <li class='visited'>
                <a href="{{route('user_dashboard')}}">Dashboard</a>
              </li>
              <!--<li class="{{ request()->routeIs('home') ? 'visited' : '' }}">-->
              <!--  <a href="{{route('home')}}">Home</a>-->
              <!--</li>-->
              <!--<li class="{{ request()->routeIs('about') ? 'visited' : '' }}">-->
              <!--  <a href="{{route('about')}}">About Us</a>-->
              <!--</li>-->

              <!-- <li class="{{ request()->routeIs('faq') ? 'visited' : '' }} mobile-menu">-->
              <!--  <a href="{{url('/faq')}}">FAQ</a>-->
              <!--</li>-->
              <!--<li class="{{ request()->routeIs('program') ? 'visited' : '' }} mobile-menu">-->
              <!--  <a href="{{url('/program')}}">Gallery</a>-->
              <!--</li>-->
              <!--<li class="{{ request()->routeIs('contact') ? 'visited' : '' }} mobile-menu">-->
              <!--  <a href="{{url('/contact')}}">Contact Us</a>-->
              <!--</li>-->
              <a class="btn-primary me-2"  href="{{route('user_logout')}}">Logout</a>
              
              
              @else
              <!--<li class="{{ request()->routeIs('home') ? 'visited' : '' }}">-->
              <!--  <a href="{{route('home')}}">Home</a>-->
              <!--</li>-->
              <!--<li class="{{ request()->routeIs('about') ? 'visited' : '' }}">-->
              <!--  <a href="{{route('about')}}">About Us</a>-->
              <!--</li>-->

              <!-- <li class="{{ request()->routeIs('faq') ? 'visited' : '' }} mobile-menu">-->
              <!--  <a href="{{url('/faq')}}">FAQ</a>-->
              <!--</li>-->
              <!--<li class="{{ request()->routeIs('program') ? 'visited' : '' }} mobile-menu">-->
              <!--  <a href="{{url('/program')}}">Gallery</a>-->
              <!--</li>-->
              <!--<li class="{{ request()->routeIs('contact') ? 'visited' : '' }} mobile-menu">-->
              <!--  <a href="{{url('/contact')}}">Contact Us</a>-->
              <!--</li>-->
              
              <a class="btn-primary me-2"  href="{{route('register')}}">Sign up</a>
              <a class="btn-primary" href="{{route('login_page')}}">Login</a>
              @endif

              
            </ul>
          </nav>
        </div>
        <div class='logo'>
          <span><a href="{{url('/')}}"><img class="img-fluid" height="50px" src="{{isset($setting->header_logo)?url($setting->header_logo):url('/public/noimage.png')}}" alt=""></a></span>
        </div>

        @if(!empty(Auth::guard('web')->user()))
        <nav class='head_nav'>
          <ul>
          <!--<li><a href="{{route('home')}}"><span>Home</span></a></li>-->
          <!--<li><a href="{{route('about')}}" class="mx-3">About Us</a></li>-->
          <!--<li><a href="{{route('web_faq')}}" class="mx-3">FAQ</a></li>-->
          <!--<li><a href="{{route('program')}}" class="mx-3">Gallery</a></li>-->
          <!--<li><a href="{{route('contact')}}" class="mx-3">Contact Us</a></li>-->
            <li class="{{ request()->routeIs('user_dashboard') ? 'visited' : '' }}">
              <a href="{{route('user_dashboard')}}"><span>Dashboard</span></a>
            </li>
            

            
            
          </ul>
        </nav>
        <div class='icons'>
          
          <div class='block'>
            <a class='user_profile' href="{{route('user_dashboard')}}" title='User profile'>
              <img class="img-fluid profile_img" src="{{isset(Auth::guard('web')->user()->profile)?url(Auth::guard('web')->user()->profile):url('/public/user.png')}}" alt="">
            </a>
          </div>
        </div>
        
        @else
        <nav class='head_nav text-end'>
          <ul>
          <!--<li><a href="{{route('home')}}"><span>Home</span></a></li>-->
          <!--<li><a href="{{route('about')}}" class="mx-3">About Us</a></li>-->
          <!--<li><a href="{{route('web_faq')}}" class="mx-3">FAQ</a></li>-->
          <!--<li><a href="{{route('program')}}" class="mx-3">Gallery</a></li>-->
          <!--<li><a href="{{route('contact')}}" class="mx-3">Contact Us</a></li>-->
           

            <a class="btn-primary me-2" href="{{route('register')}}">Sign up</a>
            <a class="btn-primary" href="{{route('login_page')}}">Login</a>
          </ul>
        </nav>
        @endif

      </div>
    </header>
    <script src="{{url('/public/frontend/')}}/assets/js/jquery.min.js"></script>
    <!-- <script src="{{url('/public/frontend/')}}/assets/js/wow.min.js"></script> -->