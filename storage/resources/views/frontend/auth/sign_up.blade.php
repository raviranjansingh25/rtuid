@extends('frontend.layout.layout')
@section('content')
<style type="text/css">
  .input-group.change-passwords span {
    position: absolute;
    right: 0px;
    z-index: 9;
    padding: 10px;
}
.input-group.change-passwords {
    position: relative;
}
</style>
    <!-- Hero Section  -->
    <section class="sign-up contect-hero  mt-110">
      <div class="container ctr-algn">
        <div class="hero-heading">
          <h1>{{$title}}</h1>
          <p>Let's Connect with the World!</p>
        </div>
      </div>
    </section>

    <!-- Contect Form Start -->
    <section class="contect-frm-section sgn-frm">
      <img class="reg-vtor" src="./assets/img/teen-line.svg" alt="">
      <div class="container">
        <div class="frm-cnct">
          <form id="form-data" method="post">
            @csrf
            <div class="row">
              <div class="offset-lg-4 col-lg-4 col-md-12">
                <h3>Please fill details in the below fields.</h3>
                <div class="form-group">
                  <label>Name <span class="text-danger">*</span></label>
                  <input type="text" placeholder="Enter name" name="name" />
                </div>
                <div class="form-group">
                  <label>Email <span class="text-danger">*</span></label>
                  <input type="text" name="email" placeholder="Enter Email Address" />
                </div>
                <div class="form-group">
                  <label>Mobile Number <span class="text-danger">*</span></label>
                    <input type="text" name="mobile" id="mobile_code" class="form-control" placeholder="Phone Number" >               
                </div>
                <div class="form-group position-relative">
                  <label>Password <span class="text-danger">*</span></label>
                  <div class="input-group change-passwords">
                    <span ><i toggle="#password-field" class="fa fa-eye-slash toggle-password"></i></span>
                      <input type="password" name="password" placeholder="Enter Password" id="password-field">  
                    </div>          
                </div>
                <div class="form-group position-relative">
                  <label>Confirm Password <span class="text-danger">*</span></label>
                  <div class="input-group change-passwords">
                    <span ><i toggle="#password-field1" class="fa fa-eye-slash toggle-password"></i></span>
                    <input type="password" name="confirm_password" placeholder="Re-enter Password" id="password-field1">  
                    </div>           
                </div>
                <div class="form-group tms-plc-lgn">
                  <p>
                        <label class="checkbox-cus">
                        <input class="w-auto" type="checkbox" name="check" checked="checked" required>
                        <span class="checkmark"></span>
                      </label>
                     <span class="">
                      I agree to all the <a href="{{url('/page/terms-conditions')}}">Term & Conditions</a> and 
                    <a href="{{url('/page/privacy-policy')}}">Privacy Policy</a>.
                     </span></p>  
                     <label id="check-error" class="error" for="check"></label>
                </div>

                <div class="col-md-12">
                  <button type="submit" class="pre-btn w-100 p-3">Sign Up</button>
                </div>
              </div>
              
            </div>
          </form>
        </div>
      </div>
      <img class=" left-vctor con-dtt btm-rht " src="{{url('/public/frontend/')}}/assets/img/dott-vtr.svg" alt="">
    </section>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script>
    $("#form-data").validate({

        onfocusout: function (element) {
            $(element).valid();
        },

        rules: {
            'name': {required: true},
            'email': {required: true},
            'mobile': {required: true},
            'password': {required: true},
            'confirm_password': {required: true},
            'check': {required: true},
            
        },
        messages: {
            'name': "Please Enter name.",        
            'email': "Please Enter email address.",        
            'mobile': "Please Enter mobile number.", 
            'password': "Please enter password.",    
            'confirm_password': "Please Enter confirm password.",            
            'check': "Please agree to all the Term & Conditions and Privacy Policy.",            
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
            url: "{{ route('register_save') }}",
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
                  $('#exampleModalToggle').modal('show');
                  $('.otp_mobile').val(res.wish_data);
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


  function varify_button(){
    
    if($("#otp input#first").val() == "" || $("#otp input#second").val() == "" || $("#otp input#third").val() == "" || $("#otp input#fourth").val() == ""){
      $('.error_otp').css("display", "block"); 
      return false;
    } else {
      var one = $("#otp input#one").val()
      var two = $("#otp input#two").val()
      var three = $("#otp input#three").val()
      var four = $("#otp input#four").val()
      var five = $("#otp input#five").val()
      var six = $("#otp input#six").val()
      var mobile = $("#mobile_code").val()
      $.ajax({
        url:"{{route('match_otp')}}",
        
        data:{
          one,two,three,four,five,six,mobile
        },
        beforeSend: function() {
          $("#preloader").show();
        },

        success: function (res) {
          $("#preloader").hide();
          if (res.status==1) { 
              swal({
                    title: "Success!",
                    text: res.message,
                    icon: "success",
                    dangerMode: true,
                    buttons: false,
                    timer: 1000
                })
           .then(() => {
              location.href = "{{ route('user_dashboard') }}";
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

@endsection