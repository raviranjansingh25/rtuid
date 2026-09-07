@extends('frontend.layout.layout2')
@section('content')

<section class="about-us">
  <div class="abt-bnr py-5 m-0" style="background-size: cover;">
    <div class="container">
      <div class="row ">
        <div class="col-md-12 col-lg-6">
          <h2>Contact Us</h2>
          <p>
            Feel free to connect with us if you have any enquiries! Whether you need assistance creating your profile, submitting a course/handout, are unsure how to book a consultation or want to know more how telimed can support you, we are here to help.
          </p>
          <p>
            Thank you for considering our services to support you on your journey towards wellness and success. Your satisfaction is our priority and we will do our best to reply within 24 hours!
          </p>
        </div>
        <div class="col-md-12 col-lg-6">
          <div class="contct-form">
            <form id="contactForm" method="post" class="cmn-frm contact-box rounded ">
              <div class="form-group">
                <label for="">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="" placeholder="Full Name">
              </div>
              <div class="form-group">
                <label for="">Email <span class="text-danger">*</span></label>
                <input type="text" name="email" id="" placeholder="Email">
              </div>
              <div class="form-group">
                <label for="">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="phone" id="" placeholder="Phone Number">
              </div>
              <div class="form-group">
                <label for="">Message <span class="text-danger">*</span></label>
                <textarea name="message" id="" cols="30" rows="3" placeholder=""></textarea>
              </div>

              <label for="">Please select your role<span class="text-danger">*</span></label><br>
              <div class="d-flex justify-content-between mb-3">
                <label for="">
                  <input type="radio" name="role" value="Health professional member" id="">&nbsp;&nbsp; Health professional member
                </label>
              </div>

              <div class="d-flex justify-content-between mb-3">
                <label for="">
                  <input type="radio" name="role" value="Patient member" id="">&nbsp;&nbsp; Patient member
                </label>
              </div>

              <div class="d-flex justify-content-between mb-3">
                <label for="">
                  <input type="radio" name="role" value="Other" id="">&nbsp;&nbsp; Other
                </label>
              </div>
              <label id="type-error" class="error" for="type"></label>
              <div class="text-center my-4">
                <button placeholder="Message..." type="submit" class="btn-primary w-75 px-5">Send</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>


  <section class="health-fingertips ">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h2>Holistic <span>Health</span> At Your <br>
            <span>Fingertips</span>
          </h2>
          <a data-bs-toggle="modal" href="#signmdl" role="button" class="light-btn">Join Telimed</a>
        </div>
        <div class="col-md-6">
          <div class="health-img text-end">
            <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/health-img.png" alt="">
          </div>
        </div>
      </div>
    </div>
  </section>

</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
  $("#contactForm").validate({

    onfocusout: function(element) {
      $(element).valid();
    },
    highlight: function(element, errorClass) {

    },

    rules: {
      'name': {
        required: true
      },
      'email': {
        required: true
      },
      'phone': {
        required: true
      },

      'message': {
        required: true
      },
      'role': {
        required: true
      },

    },
    messages: {
      'name': "Please Enter name.",
      'email': "Please Enter email address.",
      'phone': "Please Enter mobile number.",
      'message': "Please Enter message.",
      'role': "Please select below option.",

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
        url: "{{ route('contact_enquery') }}",
        type: 'GET',
        data: $('#contactForm').serialize(),
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();
          if (res.status == 1) {
            swal({
                title: "Success!",
                text: res.message,
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 1000
              })
              .then(() => {
                window.location = "{{route('home')}}"
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

@endsection