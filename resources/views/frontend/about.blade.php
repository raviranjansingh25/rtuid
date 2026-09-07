@extends('frontend.layout.layout2')
@section('content')

<section class="about-us">
  <div class="abt-bnr">
    <div class="container">

      <div class="row">
        <div class="col-md-7">
          <div class="abt-data">
            <h2>{{$home->title}}</h2>
            {!!$home->description!!}
            <a class="btn-primary-w" href="{{route('practitioners')}}">Find a practitioner</a>
            <a class="btn-primary" href="{{route('contact')}}">Contact us</a>
          </div>
        </div>
        <div class="col-md-5">
          <div class="abt-rimage">
            <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/about-bnr-img.png" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="about-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="img-about">
            <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/abt3.png" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="section-heading">
            <h2>{{$about->title}}</h2>
            {!!$about->description!!}
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

@endsection