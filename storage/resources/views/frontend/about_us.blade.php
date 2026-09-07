@extends('frontend.layout.layout')
@section('content')
    <!-- Hero Section  -->
    <section class="sign-up contect-hero  mt-110">
      <div class="container ctr-algn">
        <div class="hero-heading">
          <h1>About Us</h1>
          <p>Vaccusantium Doloremque Totam Rem Aperiam</p>
        </div>
      </div>
    </section>

    <!-- Contect Form Start -->
    <section class="contect-frm-section sgn-frm">
      <div class="container">
        <div class="row">
          <div class="col-md-8 p-0">
            <div class="abt-img">
              <img class="img-fluid" src="{{url($about->large_image)}}" alt="">
            </div>
          </div>
          <div class="col-md-4 p-0">
            <div class="abt-img">
              <img class="img-fluid" src="{{url($about->side_image1)}}" alt="">
            </div>
            <div class="abt-img">
              <img class="img-fluid" src="{{url($about->side_image2)}}" alt="">
            </div>
          </div>
          <div class="col-md-4">
            <div class="abt-dtl-logo">
              <img class="img-fluid" src="{{url($about->image_logo)}}" alt="">
            </div>
          </div>
          <div class="col-md-8">
            <div class="dtl-abt">
              <h2>{{$about->title}}</h2>
              <h3>{{$about->subtitle}}</h3>
              <p>{{$about->description}}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="why-choose-section">
      <div class="section-heading text-center">
        <h2 class="m-0">Why Choose Us</h2>
        <p>Quisque aliquam condimentum vulputate. Nulla facilisi. Sed tempor dolor egestas mi placerat <br> fringilla Sed cursus pharetra eros.</p>
      </div>
      <div class="container">
        <div class="row">
          @foreach($why as $whydata)
          <div class="col-md-4">
            <div class="choose-dtl">
              <img src="{{url($whydata->image)}}" alt="">
              <h3>{{$whydata->title}}</h3>
              <p>{{$whydata->description}}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="our-tem-section why-choose-section">
      <div class="section-heading text-center">
        <h2 class="m-0">Meet Our Team</h2>
        <p>Quisque aliquam condimentum vulputate. Nulla facilisi. Sed tempor dolor egestas mi placerat <br> fringilla Sed cursus pharetra eros.</p>
      </div>
      <div class="container">
        <div class="row">
          @foreach($team as $teamdata)
          <div class="col-md-4">
            <div class="tem-sect">
              <img class="img-fluid" src="{{url($teamdata->image)}}" alt="">
              <h4>{{$teamdata->name}}</h4>
              <p>{{$teamdata->designation}}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>





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


    @endsection