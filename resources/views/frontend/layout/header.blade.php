<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="icon" type="image/x-icon" href="{{url('/public/frontend/')}}/assets/images/fav.svg">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@if($title != ""){{ $title }} | @endif {{$setting->sitename}}</title>
  <meta property="og:image" content="{{url($setting->header_logo)}}" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" rel="stylesheet" />
  <!-- <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/animate.min.css" /> -->
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/style.css" />
</head>
<style type="text/css">
  .error {
    color: red;
    /* float: left; */
  }

  .form-floating {
    /* margin-bottom: inherit; */
  }
  .pdf_down_div{
    width: 100%;
    word-wrap: break-word;
  }
</style>

<body>

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
          <a href="">Home</a>
          <a href="">About Us</a>
          @if(!empty(Auth::guard('web')->user()))
          <button class="btn-primary" data-bs-toggle="modal" onclick="register_model()" role="button">Sign up</button>
          <button class="btn-primary" data-bs-toggle="modal" onclick="login_model()" role="button">Login</button>
          @else
          <div class='logo'>
            <span><a href="{{url('/')}}"><img class="img-fluid" height="50px" src="{{url('/public/frontend/')}}/assets/images/logo.svg" alt=""></a></span>
          </div>
          @endif
        </nav>
      </div>
      <div class='logo'>
        <span><a href="{{url('/')}}"><img class="img-fluid" src="{{url($setting->header_logo)}}" alt=""></a></span>
      </div>
      
      @if(!empty(Auth::guard('web')->user()))
      <div class='icons'>

        <div class='block'>
          <a class='notification' href="{{route('my_wishlist')}}" title='Notifications'>
            <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/h-like.svg" alt="">
          </a>
        </div>

        <div class='block'>
          <a class='user_profile' href="{{route('user_dashboard')}}" title='User profile'>
            <img class="img-fluid" src="{{isset(Auth::guard('web')->user()->profile)?url(Auth::guard('web')->user()->profile):url('/public/noimage.png')}}" alt="">
          </a>
        </div>

      </div>
      @endif

    </div>
  </header>