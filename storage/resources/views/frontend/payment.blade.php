@extends('frontend.layout.layout')
@section('content')
<style type="text/css">
  #payment_type_error{
    color: red;
  }
  .pay_at_hotel{
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
            <div class="frm-gust steps-frm-2">
              <img class="img-fluid step-img" src="{{url('/public/frontend/')}}/assets/img/2-steps.svg" alt="">
              <h3>Payment</h3>
              <p>Select payment options here.</p>
              <span id="payment_type_error"></span>
              <form id="payment" method="post" class="pmt-optn-check">
                <div class="row"> 
                   <div class="col-md-12">
                    <ul>
                      <li>
                        <label class="checkbox-cus">
                          <input class="w-auto" type="checkbox" checked="checked">
                          <span class="checkmark"></span>
                        </label>
                        <h4>My Wallet <span>Balance ₹{{isset(Auth::guard('web')->user()->wallet)?isset(Auth::guard('web')->user()->wallet):0}}</span> </h4>
                      </li>
                      <li> 
                          <input class="w-auto me-3" type="radio" onclick="pay_hotel()" name="payment_type" value="Pay at Hotel"> 
                        <h4>Pay at Hotel </h4>
                      </li>
                      <li> 
                        <input class="w-auto me-3" type="radio" value="Advance Amount" name="payment_type"> 
                      <h4>Advance Amount 
                        <span><input type="number" name="advance_amount" id="" placeholder="Enter Amount" >
                        <b>₹</b></span> 
                      </h4>
                    </li>
                  </ul>
                  <div class="amt-py">
                    <h5>Pay Remaining Amount with  <span>₹{{$detail_data['final_price']}}</span></h5>
                  </div>
                  <ul>
                      <!-- <li> 
                        <input class="w-auto me-3" type="radio" name="payment_type" value="Paytm"> 
                      <div class="dbit-crd">
                        <h4>Paytm<img src="{{url('/public/frontend/')}}/assets/img/paytm-logo.svg" alt="">
                        </h4>
                        <input class="mt-2" type="number" name="paytm_amount" id="" placeholder="Enter Amount">
                      </div>
                    </li> -->
                    
                    <li> 
                      <input class="w-auto me-3" checked type="radio" name="payment_type" value="Razorpay"> 
                    <div class="dbit-crd">
                      <h4>Razorpay<img class="m-0" src="{{url('/public/frontend/')}}/assets/img/razorpay.svg" alt="">
                      </h4>
                       
                    </div>
                  </li>
                  <!-- <li> 
                    <input class="w-auto me-3" type="radio" name="payment_type" value="CC Avenue"> 
                  <div class="dbit-crd">
                    <h4>CC Avenue<img class="m-0" src="{{url('/public/frontend/')}}/assets/img/pay-3.svg" alt="">
                    </h4>
                     
                  </div>
                </li> -->
                  </ul>
                   
                    
                   </div>
                  <div class="col-md-6">
                    
                    <button type="button" onclick="payment()" class="vrfy-btns pay_at_hotel">Pay  ₹{{$detail_data['final_price']}}</button>

                    <button type="button" class="vrfy-btns buy_now">Pay  ₹{{$detail_data['final_price']}}</button>
                  </div>
                </div>
              </form>
              
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
  $('body').on('click', '.buy_now', function(e){
  var payment = document.getElementsByName("payment_type");
  for(i = 0; i < payment.length; i++) {
      if(payment[i].checked) 
      var payment_type = payment[i].value;
    
    }
  var totalAmount = {{$detail_data['final_price']}};
  var options = {
  "key": "rzp_test_82rDfBRzFhkxzu",
  "amount": (totalAmount*100), // 2000 paise = INR 20
  "name": "{{$setting->sitename}}",
  "description": "Payment",
  "image": "{{url($setting->fav_icon)}}",
  "handler": function (response){
  $.ajax({
  url: "{{ route('payment_save') }}",
  type: 'post',
  dataType: 'json',
  data: {
    '_token' : '<?php echo csrf_token() ?>',
    'payment_type'    : payment_type,
    'razorpay_payment_id': response.razorpay_payment_id,
    'totalAmount' : totalAmount,
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
              title: "Success!",
              text: res.message,
              icon: "success",
              dangerMode: true,
              buttons: false,
              timer: 1000
          })
         .then(() => {
                  window.location="{{route('user_dashboard')}}"
              })

        }else{
          
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
  "theme": {
  "color": "#F03E39"
  }
  };
  var rzp1 = new Razorpay(options);
  rzp1.open();
  e.preventDefault();
  });
  </script>
  <script>
    function payment(){
      var payment = document.getElementsByName("payment_type");
      for(i = 0; i < payment.length; i++) {
        if(payment[i].checked) 
        var payment_type = payment[i].value;
      
      }
      if (payment_type === undefined){
        $('#payment_type_error').html("Please Select any payment")
        return false;
      }else{

        $.ajax(
          {
          url: "{{ route('payment_save') }}",
          type: 'GET',
          data: {
              '_token' : '<?php echo csrf_token() ?>',
              'payment_type'    : payment_type,
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
                    title: "Success!",
                    text: res.message,
                    icon: "success",
                    dangerMode: true,
                    buttons: false,
                    timer: 1000
                })
               .then(() => {
                        window.location="{{route('user_dashboard')}}"
                    })

              }else{
                
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
      }

    }
</script>
<script type="text/javascript">
  function pay_hotel(){
    $(".pay_at_hotel").css("display","block")
    $(".buy_now").css("display","none")
  }
</script>
    @endsection