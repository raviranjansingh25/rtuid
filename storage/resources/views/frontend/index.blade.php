@extends('frontend.layout.layout')
@section('content')

<style type="text/css">
  .gj-datepicker-bootstrap [role=right-icon] button {
    width: 80%;
    height: 46px;
    
}

</style>
    <!-- Slider Section start -->
    <section class="hero-section mt-110">
        <div class="container">
            <h1>Let's The World Together!</h1>
            <p>Over 157,000 hotels and homes across 35 countries</p>
            <form action="{{route('hotels')}}" method="post" class="srch-lst-frm">
              @csrf
                <div class="row align-item-center">
                    <div class="col-md-4">
                        <div class="form-group w-100 sldr-frm">
                            <label for="input_from">Hotel/City Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Search">
                            <img class="in-icn" src="{{url('/public/frontend/')}}/assets/img/serch.svg" alt="">
                          </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="form-group w-100 sldr-frm">
                            <label for="input_from">Check-In</label>
                            <input type="text" class="form-control" id="input_from" placeholder="Fri, 15 Dec 22" name="check_in">
                            <img class="in-icn clanr" src="{{url('/public/frontend/')}}/assets/img/caldr.svg" alt="" name="check_out">
                          </div>
                          <div class="form-group w-100 sldr-frm">
                            <label for="input_from">Check-out</label>
                            <input type="text" class="form-control" id="input_to" placeholder="Sat, 16 Dec 22">
                            <img class="in-icn clanr" src="{{url('/public/frontend/')}}/assets/img/caldr.svg" alt="">
                          </div>
                    </div>
                      <div class="col-md-4 d-flex">
                        <div class="form-group w-100 mr-gr sldr-frm">
                            <label for="input_from">Rooms</label>
                            <select name="room_qty" id="">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                          </div>
                          <div class="form-group w-100 sldr-frm">
                            <label for="input_from">Guest</label>
                            <select name="guest_qty" id="">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                          </div>
                          <button class="clr-btn">Search</button>
                      </div>
                </div>
            </form>
        </div>
    </section>
     <!-- Recommended Hotels Cities Section start -->

     <section class="hotels-cities">
      <img class="fx-img" src="{{url('/public/frontend/')}}/assets/img/dott-vtr.svg" alt="">
        <div class="container">
            <div class="section-heading">
                <h2>Recommended Hotels Cities</h2>
            </div>
            <div class="row">
              @foreach($recomended_city as $key=>$city)
              @if($key<=1)
              <div class="col-md-6">
                  <div class="htl-citis">
                      <img class="img-fluid" src="{{url(isset($city->image)?$city->image:'')}}" alt="">
                      <div class="desti-nme">
                        <p>{{$city->city}}</p>
                        <a href="{{route('hotels',['city'=>$city->id])}}">View All Hotels</a>
                      </div>
                  </div>
              </div>
              @else
              <div class="col-md-3">
                  <div class="htl-citis">
                      <img class="img-fluid" src="{{url(isset($city->image)?$city->image:'')}}" alt="">
                      <div class="desti-nme">
                        <p>{{$city->city}}</p>
                        <a href="{{route('hotels', ['city'=>$city->id])}}">View All Hotels</a>
                      </div>
                  </div>
              </div>
              @endif
              @endforeach
            </div> 
        </div>
     </section>

 <!-- Special Offfers Of This Month Section start -->
     <section class="proj">
      <div class="container">
        <div class="section-heading">
          <h2>Special Offfers Of This Month</h2>
        </div>
      </div>
      <div class="slide-container">
        @foreach($hotel as $hotel_data)
        <div>
          <div class="mth-ofer">
            <div class="this-ofr">
              <img class="img-fluid" src="{{url(isset($hotel_data->profile)?$hotel_data->profile:'')}}" alt="" style="max-height: 491px;">
            </div>
            <div class="htl-card">
              <h3>{{$hotel_data->hotel_name}}</h3>
              <p>{{$hotel_data->address}} <span>{{$hotel_data->city_name}}</span></p>
              <ul>
                @foreach($hotel_data['get_single_room']->amities_data as $am=>$amities)
                @if($am<4)
                  <li><img style="height: 17px;" class="img-fluid" src="{{url($amities->profile)}}" alt="" ></li>
                @endif
                @endforeach
                @if(count($hotel_data['get_single_room']->amities_data)>4)
                <li><a href="{{url('/detail/'.$hotel_data->slug)}}">+{{count($hotel_data['get_single_room']->amities_data)-4}} more</a></li>
                @endif
              </ul>
              <div class="htl-prce">
                <h4><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/rupee.svg" alt="" > {{$hotel_data->discount_rent}} <span><img src="{{url('/public/frontend/')}}/assets/img/com-rupee.svg" alt=""> {{$hotel_data->room_rent}}</span></h4>
                <a class="pre-btn" href="{{url('/detail/'.$hotel_data->slug)}}">Book Now</a>
              </div>
              <p class="as-per">per room per night</p>
            </div>
          </div>
        </div> <!-- end wrapper -->
        @endforeach

      </div> <!-- end container -->
    </section>
<!-- App section start -->
    <section class="app-section">
      <img class="fx-img rgt-vctr" src="{{url('/public/frontend/')}}/assets/img/dott-vtr.svg" alt="">
      <div class="container">
        <div class="app-bg">
          <div class="row">
            <div class="col-md-5">
              <div class="mbl-img">
                <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/mobile_donload.svg" alt="">
              </div>
            </div>
            <div class="col-md-7">
             <div class="logo-voer">
              <h2>Download App</h2>
              <p>It is a long established fact that a reader will 
                be distracted by the readable content of a page 
                when looking at its layout. </p>
                <a href="#"><img src="{{url('/public/frontend/')}}/assets/img/google-ply.svg" alt=""></a>
             </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>

  <script>
  $(function() {
    // var current_date = new Date();
    var d = new Date()
    var datestring = d.getDate()  + "-" + (d.getMonth()+1) + "-" + d.getFullYear();
    rome(input_from, {
      dateValidator: rome.val.beforeEq(input_to),
      min: datestring,
      time: false
    });

    rome(input_to, {
      dateValidator: rome.val.afterEq(input_from),
      time: false
    });


  });
</script>  
 @endsection   
