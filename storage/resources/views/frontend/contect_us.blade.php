@extends('frontend.layout.layout')
@section('content')

  
    <!-- Hero Section  -->
    <section class="contect-hero mt-110">
      <div class="container ctr-algn">
        <div class="hero-heading">
          <h1>Contact Us</h1>
          <p>Have a question ? We'd love to hear from you.</p>
        </div>
      </div>
    </section>

    <!-- Contect Form Start -->
    <section class="contect-frm-section">
      <img class="con-dtt" src="{{url('/public/frontend/')}}//assets/img/dott-vtr.svg" alt="">
      <div class="container">
        <div class="frm-cnct">
          <form id="form-data" method="post">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Your Name</label>
                  <input type="text" name="name" placeholder="Enter Name" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Your Email</label>
                  <input type="email" name="email" placeholder="Enter Email Address" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Mobile Number</label>
                  <input type="text" id="mobile_code" name="phone" placeholder="Enter Mobile Number" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Subject</label>
                  <input type="text" name="subject" placeholder="Enter Subject" />
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">Message</label>
                  <textarea name="message" id="" cols="30" rows="8" placeholder="Enter Message"></textarea>
                </div>
              </div>
              <div class="col-md-12">
                <button type="submit" class="pre-btn w-100 p-3">Send</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <img class="con-dtt btm-rht" src="{{url('/public/frontend/')}}/assets/img/dott-1.svg" alt="">
    </section>
    <!-- Address No Section Start -->
    <section class="add-section">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="con-dtl-ref">
              <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/address.svg" alt="">
              <h3>Address</h3>
              <span>for help with a current product or service</span>
              <p>{{$setting->address}}</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="con-dtl-ref call-ref">
              <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/phone-call.svg" alt="">
              <h3>Call Us</h3>
              <span>Call us to speak to a member of our team</span>
              <p><a href="#">+91 {{$setting->phone}},</a></p> 
            </div>
          </div>
          <div class="col-md-4">
            <div class="con-dtl-ref email-ref">
              <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/email.svg" alt="">
              <h3>Email Address</h3>
              <span>for help with a current product or service</span>
              <p><a href="#">{{$setting->email}}</a></p> 
            </div>
          </div>
        </div>
      </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script>
    $("#form-data").validate({

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'name': {required: true},
            'email': {required: true},
            'phone': {required: true},
            'subject': {required: true},
            'message': {required: true},  
        },
        messages: {
            'name': "Please Enter mobile number.",        
            'email': "Please Enter email address.",        
            'mobile': "Please Enter mobile number.", 
            'subject': "Please enter subject.",    
            'message': "Please Enter message.",                       
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
            url: "{{ route('contact_enquery') }}",
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
    @endsection