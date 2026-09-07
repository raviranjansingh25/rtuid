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
    <section class="rest-pss contect-hero  mt-110">
      <div class="container ctr-algn">
        <div class="hero-heading">
          <h1>Reset Password</h1>
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
                <h3>The password should have at least 6 characters.</h3>
                  
                <div class="form-group position-relative">
                  <label for="Mobile Number">Password <span class="text-danger">*</span></label>
                  <div class="input-group change-passwords">
                    <span ><i toggle="#new_password" class="fa fa-eye-slash toggle-password"></i></span>
                    <input type="password" id="new_password" name="password" placeholder="Enter Password">  
                  </div>        
                </div>
                <div class="form-group position-relative">
                  <label for="Mobile Number">Confirm Password <span class="text-danger">*</span></label>
                  <div class="input-group change-passwords">
                    <span ><i toggle="#password-field1" class="fa fa-eye-slash toggle-password"></i></span>
                    <input type="password" name="confirm_password" id="password-field1" placeholder="Re-enter Password">  
                  </div>    
                </div> 
                <input type="hidden" name="user_id" value="{{$user_id}}">

                <div class="col-md-12">
                  <button type="submit"  class="pre-btn w-100 p-3" id="man-book">Save Changes</button>
                </div>
              </div>
              
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
            'password': {required: true},  
            'confirm_password': {required: true,equalTo: "#new_password"},  
        },
        messages: {    
            'password': "Please Enter password.",        
            'confirm_password': "Please Enter confirm password.",        
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
            url: "{{ route('reset_forgor_password') }}",
            type: 'GET',
            data: $('#form-data').serialize(),
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
                        window.location="{{route('login')}}"
                    })
                    
                }else{
                  swal("OTP is invalid", "Plesae enter correct OTP.", "warning");
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
    
    @endsection