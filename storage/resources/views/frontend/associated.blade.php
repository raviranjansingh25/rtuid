<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@if($title != ""){{ $title }} | @endif {{$setting->sitename}}</title>
    <link rel="shortcut icon" href="{{url($setting->fav_icon)}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" /> 
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/style.css" /> 
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
      rel="stylesheet"
    />
  
  </head>
  <style type="text/css">
    
    .error {
         text-transform: lowercase;
         color: red;
        } 

        .error::first-letter {
         text-transform: uppercase;
         
        }
        
        .iti__flag-container {
            height: 50px;
        }
        .lgn-frm input {
             margin-bottom: 0px; 
        }

  </style>
  <body>
    
    <!-- Hero Section  -->
    <section class="login-hero list-prty-hero">
      <div class="hdr-lgn">
        <a href="{{url('/')}}"><img class="img-fluid lgn-logo" src="{{url('/public/frontend/')}}/assets/img/logo.svg" alt=""></a>
        <a href="{{ url()->previous() }}"><img src="{{url('/public/frontend/')}}/assets/img/cls-login.svg" alt=""></a>
      </div>
       <div class="container">
        <div class="lgn-text text-center">
          <h1>Get Associated with <span>Cityroom</span> </h1>
          <p>Built to simplify business stays, and make sure both you and your employees <br>
            always have a good night’s sleep.</p>
            <form id="form-data" method="post" class="lgn-frm lst-frm-section">
              <p>Kindly fill in the below-required fields to connect with Cityroom and propel your 
                business with the help of our world-class services.</p>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="Mobile Number">Name <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Enter Name" name="name">               
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Mobile Number">Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" id="mobile_code" class="form-control" placeholder="Enter Phone Number" name="phone">               
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="Mobile Number">Company Name <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Enter Company Name" name="company_name">               
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="Mobile Number">Company City <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Enter Company City" name="city">               
                    </div>
                  </div>
                </div><br>
              <button type="submit" id="man-book" class="p-3 px-4 pre-btn">Request a Call</button>
            </form> 
        </div>
       </div>
    </section>


    <script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>
    <script src="{{url('/public/frontend/')}}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    $("#form-data").validate({

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'name': {required: true},
            'phone': {required: true},
            'city': {required: true},
            'company_name': {required: true},  
        },
        messages: {
            'name': "Please Enter mobile number.",               
            'phone': "Please Enter mobile number.", 
            'city': "Please enter city name.",    
            'company_name': "Please Enter company name.",                       
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
            url: "{{ route('associate_save') }}",
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
                        window.location="{{route('home')}}"
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
  <script>
    // -----Country Code Selection
    $("#mobile_code").intlTelInput({
     initialCountry: "in",
     separateDialCode: true,
  // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
   });
 </script>
  </body>
</html>
