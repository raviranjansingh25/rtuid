<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@if($title != ""){{ $title }} | @endif {{$setting->sitename}}</title>
  <link rel="shortcut icon" href="{{url($setting->fav_icon)}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" /> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/style.css" /> 
  <link
  href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
  rel="stylesheet"
  />
  
</head>
<style>
  .error{
    color: red;
    text-transform: capitalize;
  }
  .lgn-frm input {
         margin-bottom: 0px; 
    }
    .fgt-lnk {
      margin: 15px;
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
.input-group.change-passwords span {
    position: absolute;
    right: 0px;
    padding: 10px;
}
.input-group.change-passwords {
    position: relative;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
.iti--separate-dial-code .iti__selected-flag {
    height: 50px;
}

  </style>
  
<body>
<div class="preloader">
  <div class="loader"></div>
</div>
  <!-- Hero Section  -->
  <section class="login-hero">
    <div class="hdr-lgn">
      <a href="{{route('home')}}"><img class="img-fluid lgn-logo" src="{{url('/public/frontend/')}}/assets/img/logo.svg" alt=""></a>
      <a href="{{route('home')}}"><img src="{{url('/public/frontend/')}}/assets/img/cls-login.svg" alt=""></a>
    </div>
    <div class="container">
      <div class="lgn-text text-center">
        <h1>There’s a smarter way to <span>Cityroom</span> around</h1>
        <p>Sign up with your phone number and get exclusive access to discounts and savings on Cityroom <br>
        stays with our many travel partners.</p>
        <form id="form-data" method="post" class="lgn-frm">
          @csrf
          <h2>Sign In</h2>
          <div class="form-group">
            <label for="Mobile Number">Mobile Number <span class="text-danger">*</span></label>
            <input type="text" id="mobile_code" class="form-control" placeholder="Phone Number" name="phone">               
          </div>
          <div class="form-group position-relative">
            <label for="Mobile Number">Password <span class="text-danger">*</span></label>
            <div class="input-group change-passwords">
                <span ><i toggle="#password-field" class="fa fa-eye-slash toggle-password"></i></span>
                <input type="password" placeholder="Enter Password" name="password" id="password-field">  
            </div>           
          </div>
          <a class="fgt-lnk" href="{{route('forgote_password')}}">Forgot Password ?</a>
          <button type="submit" id="man-book" class="w-100 p-3 pre-btn">Sign In</button>
        </form>
        <p class="shnup-btn">New to Cityroom? <a href="{{route('register')}}">Create an account</a></p>
      </div>
    </div>
  </section>


  <script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>
  <script src="{{url('/public/frontend/')}}/assets/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    $("#form-data").validate({

        onfocusout: function (element) {
            $(element).valid();
        },

        rules: {
            'phone': {required: true},
            'password': {required: true},
            
        },
        messages: {
            'phone': "Please Enter mobile number.",        
            'password': "Please Enter password.",        
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "data[Payment][phone]") {
              error.insertAfter(".error-placement");
          } else {
              error.insertAfter(element);
          }
      },

      submitHandler: function(form) {
        var country_code = $('.iti__selected-dial-code').html();
        $.ajax(
        {
            url: "{{ route('login_save') }}",
            type: 'GET',
            data: $('#form-data').serialize()+"&country_code="+country_code,
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
                        window.location="{{$current_url}}"
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
    $('#man-book').click(function() {
     // window.location.replace("index.html");	
   });
    // -----Country Code Selection
    $("#mobile_code").intlTelInput({
     initialCountry: "in",
     separateDialCode: true,
	// utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
   });
 </script>
</body>
</html>
