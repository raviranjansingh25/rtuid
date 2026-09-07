@php
    $settingdata = App\Models\Setting::first();
@endphp
<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Whats App Number</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/public/frontend/')}}/img/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/css/fontawesome-all.min.css">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/font/flaticon.css">
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/style.css">
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/css/responsive.css">
</head>

<style>
    @media only screen and (max-width: 600px) {
 .mob-img-res{
    width: 220px;
    height: 220px;
    position:relative;
    top:-340px;
    left:60px;
 }
 
 .fxt-template-layout34 .fxt-main-title {
    padding-top: 50px;
}
 
 /*.loaded.fxt-template-animation .fxt-transition-delay-10 {*/
 /*       margin-top: 50%;*/
 /*   }*/
}
    
</style>

<body >

    <div id="preloader" class="preloader">
        <div class='inner'>
            <div class='line1'></div>
            <div class='line2'></div>
            <div class='line3'></div>
        </div>
    </div>
    <section class="fxt-template-animation fxt-template-layout34" data-bg-image="img/elements/bg1.png">
        <div class="fxt-shape">
            <div class="fxt-transformX-L-50 fxt-transition-delay-1">
                <img src="{{url('public/frontend/')}}/img/elements/shape1.png" alt="Shape">
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="fxt-column-wrap justify-content-between">
                      <div class="fxt-animated-img">
                            <div class="fxt-transformX-L-50 fxt-transition-delay-10 mob-img-res">
                                <img src="{{url('public/frontend/')}}/img/figure/bg34-1.png" alt="Animated Image" width="400" height="400" class="">
                            </div>
                        </div>
                        <div class="fxt-transformX-L-50 fxt-transition-delay-3 ">
                            <a href="{{url('/')}}" class="fxt-logo"><img src="{{url($settingdata->logo)}}" alt="Logo" height="200px" width="200px"></a>
                        </div>
                        <div class="fxt-transformX-L-50 fxt-transition-delay-5">
                            <div class="fxt-middle-content">
                                
                            </div>
                        </div>
                        <div class="fxt-transformX-L-50 fxt-transition-delay-7">
                            <div class="fxt-qr-code">
                               
                            </div>
                        </div>
                        <h1 class="fxt-main-title">Update whatsapp Number</h1>

                    </div>
                </div>

                <div class="col-lg-4">

                    <div class="fxt-column-wrap justify-content-center" >
                        <div class="fxt-form">
                            @if(session()->has('success'))
                                <p class="alert alert-success text-muted m-b-10 col-lg-12 col-md-12 col-sm-12 col-xs-12 font-13 green">
                                    {{ session()->get('success') }}
                                </p>
                            @endif
                            @if($errors->any())
                                <p class="text-muted m-b-10 col-lg-12 col-md-12 col-sm-12 col-xs-12 font-13 alert alert-danger">{{$errors->first()}}</p>
                            @endif
                            <form method="POST" autocomplete="" action="{{$saveurl}}">
                                 @csrf
                                <div class="form-group">
                                    <input type="text" id="whatsapp" class="form-control" name="whatsapp" placeholder="Enter Whats app Number" required >
                                </div>
                                
                                
                                <div class="form-group">
                                    <button type="submit" class="fxt-btn-fill" name="login" value="Login">Save</button>
                                </div>
                            </form>
                        </div>
                       <div class="fxt-switcher-description1">If you don’t have an account You can<a href="{{route('register')}}" class="fxt-switcher-text ms-2">Sign Up</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- jquery-->
    <script src="{{url('/public/frontend/')}}/js/jquery-3.5.0.min.js"></script>
    <!-- Bootstrap js -->
    <script src="{{url('/public/frontend/')}}/js/bootstrap.min.js"></script>
    <!-- Imagesloaded js -->
    <script src="{{url('/public/frontend/')}}/js/imagesloaded.pkgd.min.js"></script>
    <!-- Validator js -->
    <script src="{{url('/public/frontend/')}}/js/validator.min.js"></script>
    <!-- Custom Js -->
    <script src="{{url('/public/frontend/')}}/js/main.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function() {
          $(".success-alert").hide();
            $(".success-alert").fadeTo(2000, 500).slideUp(500, function() {
              $(".success-alert").slideUp(500);
            });

        });     
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
          $(".danger-alert").hide();
            $(".danger-alert").fadeTo(2000, 500).slideUp(500, function() {
              $(".danger-alert").slideUp(500);
            });

        });     
    </script>
</body>


</html>