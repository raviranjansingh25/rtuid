@extends('frontend.layout.layout')
@section('content')

<style type="text/css">
  .passcode{
    display: none;
  }
  .verify{
    display: none;
  }
</style>
    <section class="dtl-perty mt-110">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="book-dtl">
              <h3>Selected Room <a href="{{url('/detail/'.$hotel->slug)}}"><img src="{{url('/public/frontend/')}}/assets/img/edit-icn.svg" alt=""> Modify Your Booking</a></h3>
              <div class="prty-img">
                <img class="img-fluid" src="{{url($hotel->profile)}}" alt="">
              <button class="type-jon-teh classic">{{$room_type}}</button>
              </div>

              <h2>{{$hotel->hotel_name}}</h2>
              <div class="chekin-time-out">
                <p>Check In <span>{{date('D, d M Y', strtotime($detail_data['check_in']))}}</span></p>
                <p>Check Out <span>{{date('D, d M Y', strtotime($detail_data['check_out']))}}</span></p>
                <p>Room <span>{{$detail_data['room_qty']}}</span></p>
                <p>Guest <span>{{$detail_data['guest_qty']}}</span></p>
              </div>
              <h5>Payment Details</h5>
              <ul>
                <li>{{$day_count}} Night X {{$detail_data['room_qty']}} Rooms <span>₹{{$detail_data['final_price']}}</span></li>
                <!-- <li>Savings Amount <span>- ₹500</span></li> -->
                <li class="totle">
                  Payble Amount <span>₹{{$detail_data['final_price']}}</span>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="frm-gust">
              <img class="img-fluid step-img" src="./assets/img/steps.svg" alt="">
              <h3>Enter Guest Details</h3>
              <p>We will use these details to share your booking information</p>
              <form method="post" class="gst-lgn" id="form-data1">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Full Name</label>
                      <input type="text" name="name"  placeholder="Enter Full Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Email</label>
                      <input type="text" name="email"  placeholder="Enter Email Address">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Mobile Number</label>
                      <input type="tel" id="mobile_code" class="form-control" placeholder="Phone Number" name="phone">   
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group passcode">
                      <label for="">4 Digit Passcode <span></span></label>
                      <input type="text" name="passcode" id="passcode"  placeholder="Enter Passcode"> 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <button type="button" onclick="send_passcode()" class=" btn btn-secondary send_passcode">Send Passcode</button>
                    <button type="submit " class="bok-hotl verify">Verify & Continue</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script>
    $("#form-data1").validate({

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'name': {required: true},
            'email': {required: true},
            'phone': {required: true},
            'passcode': {required: true},
        },
        messages: {
            'name': "Please Enter name.",        
            'email': "Please Enter email.",        
            'phone': "Please Enter phone.",        
            'passcode': "Please Enter passcode.",                     
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
            url: "{{ route('varify_save') }}",
            type: 'GET',
            data: $('#form-data1').serialize()+"&country_code="+country_code,
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
                        window.location="{{route('payment')}}"
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
      function send_passcode(){
        var phone = $('#mobile_code').val();
        $.ajax(
        {
            url: "{{ route('send_phone_otp') }}",
            type: 'GET',
            data: {
                    '_token' : '<?php echo csrf_token() ?>',
                    'phone'    : phone,
                },
            beforeSend: function() {
                $("#preloader").show(); 
            },
            success: function (res)
            {
              $("#preloader").hide(); 
                if (res.status == 1)
                {
                    swal({
                    title: "Success!",text: res.message,icon: "success",dangerMode: true,buttons: false,timer:1000
                })
               .then(() => {
                        $('.send_passcode').css("display", "none");
                        $('.passcode').css("display", "block");
                        $('.verify').css("display", "block");
                    })
                    
                }else{
                  $("#preloader").hide(); 
                  swal({
                    icon: 'error',title: 'Oops...',text: res.message,
                  })
                }   
            },
            error: function (error) {
              $("#preloader").hide(); 
                swal({
                  icon: 'error',title: 'Oops...',text: 'Something went wrong!',
                })
            }
        });
      }
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

    @endsection 