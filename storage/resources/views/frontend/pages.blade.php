@extends('frontend.layout.layout')
@section('content')

  
    <!-- Hero Section  -->
    <section class="contect-hero mt-110">
      <div class="container ctr-algn">
        <div class="hero-heading">
          <h1>{{$title}}</h1>
          <p>Our {{$title}}</p>
        </div>
      </div>
    </section>

    <!-- Contect Form Start -->
    <section class="contect-frm-section">
      <img class="con-dtt" src="./assets/img/dott-vtr.svg" alt="">
      <div class="container">
        <div class="frm-cnct">
          {!!$page->description!!}
        </div>
      </div>
      <img class="con-dtt btm-rht" src="{{url('/public/frontend/')}}/assets/img/dott-1.svg" alt="">
    </section>
    <!-- Address No Section Start -->
    
    @endsection