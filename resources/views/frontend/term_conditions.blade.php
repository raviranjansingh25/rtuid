@extends('frontend.layout.layout2')
@section('content')
<section class="about-us">
  <div class="abt-bnr py-5 m-0" style="background-size: cover;">
    <div class="container">
      <div class="row ">
        <h2 class="text-center mt-0 mb-4">{{$page->title}}</h2>

      </div>
    </div>
  </div>




</section>


<section class="pricy-content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12 col-md-12">
        <div class="content-box mt-5">
          {!!$page->description!!}
        </div>
      </div>
    </div>
  </div>
</section>

@endsection