@extends('frontend.layout.layout')
@section('content')
    <!-- Hero Section  -->
    <section class="for-bg contect-hero  mt-110">
      <div class="container ctr-algn">
        <div class="hero-heading">
          <h1>Forgot Password ?</h1>
          <!-- <p>Let's Connect with the World!</p> -->
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
                <h3>Enter the mobile number associated with 
                  your account we'll send you a link to reset
                  your password.</h3>
                <div class="form-group">
                  <label for="Mobile Number">Mobile Number <span class="text-danger">*</span></label>
                    <input type="text" id="mobile_code" name="phone" class="form-control" placeholder="Phone Number" name="name">               
                </div>
                <div class="col-md-12">
                  <button type="submit" id="man-book" class="pre-btn w-100 p-3">Send</button>
                </div>
              </div>
              <p class="lgn-proc-dir">New to Cityroom? <a href="{{route('register')}}">Create an account</a></p>
            </div>
          </form>
        </div>
      </div>
      <img class=" left-vctor con-dtt btm-rht " src="./assets/img/dott-vtr.svg" alt="">
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script>
    $("#form-data").validate({

        onfocusout: function (element) {
            $(element).valid();
        },

        rules: {
            'phone': {required: true},  
        },
        messages: {    
            'phone': "Please Enter mobile number.",        
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
            url: "{{ route('send_otp') }}",
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
                    $('.otp_mobile').html(mobile_code);
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
        url:"{{route('forgot_match_otp')}}",
        
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
              location.href = "{{ url('/reset-password/') }}/"+res.user_id;
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

    
     @endsection