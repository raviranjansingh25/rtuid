@php
    $settingdata = App\Models\Setting::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>forgot password</title>
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
</head>
<style>
     .error{
    color: red;
 }
     @media only screen and (max-width: 600px) {
 .mob-img-res{
    width: 220px;
    height: 220px;
    position:relative;
    top:-340px;
    left:60px;
 }
}
    
</style>
<body>
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
                                <img src="{{url('public/frontend/')}}/img/figure/bg34-1.png" alt="Animated Image" width="600" height="600">
                            </div>
                        </div>
                        <div class="fxt-transformX-L-50 fxt-transition-delay-3">
                            <a href="{{url('/')}}" class="fxt-logo"><img src="{{url($settingdata->header_logo)}}" alt="Logo" height="200px" width="200px"></a>
                        </div>
                        <div class="fxt-transformX-L-50 fxt-transition-delay-5">
                            <div class="fxt-middle-content">
                                <h1 class="fxt-main-title">Code Verification</h1>
                                
                            </div>
                        </div>
                        <div class="fxt-transformX-L-50 fxt-transition-delay-7">
                            <div class="fxt-qr-code">
                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="fxt-column-wrap justify-content-center">
                        <div class="fxt-form">

                            @if(session()->has('success'))
                              <p class="alert alert-success text-muted m-b-10 col-lg-12 col-md-12 col-sm-12 col-xs-12 font-13 green">
                                  {{ session()->get('success') }}
                              </p>
                          @endif
                          @if($errors->any())
                              <p class="text-muted m-b-10 col-lg-12 col-md-12 col-sm-12 col-xs-12 font-13 alert alert-danger">{{$errors->first()}}</p>
                          @endif

                            <form id="match_otp" method="POST"  autocomplete="">
                                @csrf
                                <div class="form-group">
                                <input type="hidden" class="form-control" value="{{ request('email') }}" name="email">
                                    <label for="email" class="fxt-label">Code Verification</label>
                                    <input type="text" id="email" class="form-control" name="otp" placeholder="Enter OTP number" required >
                                </div>
                                <div class="form-group">
                                    <button class="fxt-btn-fill" type="submit" name="check-email" value="Continue">Submit</button>
                                </div>
                                
                            </form>
                            
                            <!--<form id="resend_otp" method="POST" action="{{ route('resend_otp') }}">-->
                            <!--    @csrf-->
                            <!--    <input type="hidden" name="email" value="{{ request('email') }}">-->
                            <!--    <button type="submit" class="btn btn-link">Resend OTP</button>-->
                            <!--</form>-->
                            
                            <p>📩 Didn’t Receive Your OTP? <br>If you haven’t received the OTP in your inbox, please make sure to check your Spam, Junk, or Important folders.

Sometimes automated messages may be filtered by your email provider.


— Rajasthan Taekwondo</p>



                            
                        </div>
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

    <script src="{{url('/public/frontend/')}}/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script src="{{url('/public/frontend/')}}/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
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
                window.location.href = "{{ route('new_password') }}?email=" + encodeURIComponent(res.email);
                
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

</body>

</html>