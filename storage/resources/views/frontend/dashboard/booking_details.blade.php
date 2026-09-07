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
                <img class="img-fluid" src="{{url($booking_detail['get_hotel']->profile)}}" alt="">
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
                </ul>
              </div>
              <div class="bkn-det-bt">
                <a class="bdr-btn greye" href="{{url('/detail/'.$booking_detail['get_hotel']->slug)}}" tabindex="0">View Details</a>
                <a class="bdr-btn" href="#" tabindex="0"><img src="{{url('/public/frontend/')}}/assets/img/download_arrow.svg" alt=""> Download Invoice</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <img class="con-dtt btm-rht" src="{{url('/public/frontend/')}}/assets/img/dott-1.svg" alt="">
    </section>


    </div>
    @endsection