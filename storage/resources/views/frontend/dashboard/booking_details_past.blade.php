@extends('frontend.layout.layout')
@section('content')

    <div class="boking-det">
    <!-- Hero Section  -->
    <section class="contect-hero mt-110">

    </section>

    <!-- Contect Form Start -->
    <section class="contect-frm-section">
      <img class="con-dtt" src="{{url('/public/frontend/')}}/assets/img/dott-vtr.svg" alt="">
      <div class="container">
        <div class="frm-cnct">
          <h3>Booking Details <a href="javascript:history.back()"><img src="{{url('/public/frontend/')}}/assets/img/g-bck.svg" alt=""> Go Back</a></h3>
          <div class="bknid-dt">
            <p>Booking # {{$booking_detail->booking_id}}</p>
            <p>{{date_format($booking_detail->created_at,"d M Y - h:i A");}}</p>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="prty-img">
                <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/pprty.png" alt="">
              </div>
              <h2>{{$booking_detail['get_hotel']->hotel_name}}</h2>
              <div class="prty-dtl-pr p-0">
                <p>{{$booking_detail['get_hotel']->hotel_name}},  <span>{{$booking_detail['get_hotel']->city_name}}</span></p>
                </div>
              <div class="chekin-time-out">
                <p>Check In <span>{{date_format( new DateTime($booking_detail['check_in']), 'D, d M y' )}}</span></p>
                <p>Check Out <span>{{date_format( new DateTime($booking_detail['check_out']), 'D, d M y' )}}</span></p>
                <p>Room <span>{{$booking_detail['room_qty']}}</span></p>
                <p>Guest <span>{{$booking_detail['guest_qty']}}</span></p>
              </div>
              @if(count($booking_detail['get_food'])>0)
              <div class="gt200 mb-2">Meals During Your Stay</div>

              @foreach($booking_detail['get_food'] as $food)
              <div class="meal-fast p-3">
                <div class="d-flex align-items-center">
                  <div class="img-food">
                    <img style="height: 80px;" src="{{url('/public/frontend/')}}/assets/img/food-img.png" alt="">
                  </div>
                    <div>
                      <h4 class="mb-0">{{$food->category}}</h4>
                      <p>Indian Menu <span><i>₹</i> {{$food->price}}</span></p>
                    </div>
                  </div>
                </div>
                @endforeach
                @endif
            </div>
            <div class="col-md-6">
              <div class="hotl-cntct">
                <h4>{{$booking_detail->guest_name}}</h4>
                <span>{{$booking_detail->phone}}  |  {{$booking_detail->email}}</span>
              </div>
              <div class="book-dtl">
                <ul>
                  <li>Service Charge <span>₹{{$service_charge}}</span></li>
                  <li>Meal <span>₹{{$food_charge}}</span></li>
                  <li class="totle">Total Amount <span>₹{{$booking_detail->total_amount}}</span>
                  </li>
                </ul>
              </div>
              <div class="book-dtl">
                <ul>
                  <li>Payment Status <span class="blu">{{$booking_detail->payment_type}}</span></li>
                  <li class="totlee">Booking Status <span class="grn-cl">
                  <!-- <span class="text-warning">Pending</span> -->
                  @if($booking_detail->booking_status==1)
                  <span class="text-warning">Pending</span>
                  @elseif($booking_detail->booking_status==2)
                  <span class="text-success">Completed</span>
                  @else
                  <span class="text-danger">Cancel</span>
                  @endif
                </span></li>
                </ul>
              </div>
              <div class="bkn-det-bt">
                <a class="bdr-btn ornge-bt" href="#" data-bs-toggle="modal" data-bs-target="#reviewModalToggle">Rate & Review This Hotel</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <img class="con-dtt btm-rht" src="{{url('/public/frontend/')}}/assets/img/dott-1.svg" alt="">
    </section>


  <div class="modal otp-mdl fade review-mdl" id="reviewModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content"> 
        <div class="modal-body">
          <div class="">
            <form id="ratting" method="post">
              @csrf
              <h3>Rate & Review This Hotel</h3>
              <div class="rating">
                  <input type="radio" id="star1" name="rating" value="1" />
                  <label class="star" for="star1" title="Awesome" aria-hidden="true"></label>
                  <input type="radio" id="star2" name="rating" value="2" />
                  <label class="star" for="star2" title="Great" aria-hidden="true"></label>
                  <input type="radio" id="star3" name="rating" value="3" />
                  <label class="star" for="star3" title="Very good" aria-hidden="true"></label>
                  <input type="radio" id="star4" name="rating" value="4" />
                  <label class="star" for="star4" title="Good" aria-hidden="true"></label>
                  <input type="radio" id="star5" name="rating" value="5" />
                  <label class="star" for="star5" title="Bad" aria-hidden="true"></label>
                </div>
              <div class="frm-cnct p-0 mt-5">
                <textarea name="review" id="" cols="30" rows="6" placeholder="Please write your review"></textarea>
              </div>
              <input type="hidden" name="booking_id" value="{{$booking_detail->id}}">
              <input type="hidden" name="hotel_id" value="{{$booking_detail->hotel_id}}">
              <div class="text-end">
                <button type="button" class="dis-btn-1" data-bs-dismiss="modal" aria-label="Close">Not Now</button>
                <button type="submit" class="red-btn-1" >Submit</button>
              </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>


</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script>
    $("#ratting").validate({

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'rating': {required: true},
            'review': {required: true}, 
        },
        messages: {
            'rating': "Please Select Rating.",        
            'review': "Please Enter Review.",                             
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
            url: "{{ route('rate_review') }}",
            type: 'GET',
            data: $('#ratting').serialize(),
            beforeSend: function() {
                $("#preloader").show(); 
            },
            success: function (res)
            {
              $("#preloader").hide(); 
                if (res.status == 1)
                {
                  $('#reviewModalToggle').modal('hide');
                    swal({
                    title: "Success!",
                    text: res.message,
                    icon: "success",
                    dangerMode: true,
                    buttons: false,
                    timer: 1000
                })
                .then(() => {
                    window.location=""
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
    @endsection
