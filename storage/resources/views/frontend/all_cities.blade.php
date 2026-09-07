@extends('frontend.layout.layout')
@section('content')
<style type="text/css">
  .alp-lst li {
    padding: 0px 6px;
}
.city:checked{
  background-color: red;
}
</style>
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
    <!-- Hero Section  -->
    <section class="contect-hero mt-110">
      <div class="container ctr-algn">
        <div class="hero-heading">
         
        </div>
      </div>
    </section>

    <!-- Contect Form Start -->
    <section class="contect-frm-section">
      <img class="con-dtt" src="./assets/img/dott-vtr.svg" alt="">
      <div class="container">
        <div class="frm-cnct all-cities-sectn">
          <h4 class="city_alpha">All Cities <span class="city_count">{{count($city)}}</span></h4>
          <ul class="alp-lst">
            
            @foreach( range('A', 'Z') as $elements)
              <li > 
                  <input type="radio" name="1" onclick="city_name('{{$elements}}')" class="city" value="{$elements{}}">
                  <span></span>
                  <b>{{$elements}}</b>
              </li>
            @endforeach
            
          </ul>

          <div class="cityes-2 all-location-man">
            <div class="row city_data" style="width:100%;">
              <div class="col-3 col-md-3">
                <i><img src="{{url('/public/frontend/')}}/assets/img/location-1.svg" alt="" /></i> Near me
              </div>
              @foreach($city as $cities)
              <div class="col-3 col-md-3" style="margin-bottom: 20px">
                <a href="{{route('hotels',['city'=>$cities->id])}}" style="color: black!important;"><i><img src="{{url('/public/frontend/')}}/assets/img/location-1.svg" alt="" /></i> {{$cities->city}}</a>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <img class="con-dtt btm-rht" src="./assets/img/dott-1.svg" alt="">
    </section>
    <!-- Address No Section Start -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
      function city_name(alpha){
          $.ajax({
          url: "{{ url('/city-filter') }}",
          datatType: 'json',
          type: 'POST',
          data: {
              '_token' : '<?php echo csrf_token() ?>',
              'alpha'    : alpha,
          },
          
          beforeSend: function() {
              $("#preloader").show(); 
          },
          success: function (res)
          {
            $("#preloader").hide(); 
            $('.city_alpha').html(alpha+ ' Cities');
              if (res.status == 1)
              {
                  $('.city_data').html(res.city_data);
                  $('.city_count').html(res.city_count);
                  
                  
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
  };
      
  </script>
    @endsection
    