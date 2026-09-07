@extends('frontend.layout.layout2')
@section('content')
<style>
  .another_image {
    width: 100%;
    background-position: top, center !important;
    background-repeat: no-repeat;
    position: relative;
    padding-bottom: 21%;
    overflow: hidden;
  }

  .why-join-img-00 {
    height: 317px !important;
  }

  .another_image img {
    min-height: inherit;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
  }
</style>
<div class="another_image why-join-img-00">
  <img class="img-fluid" src="{{isset($page->image)?url($page->image):url('/public/noimage.png')}}" alt="" width="100%">
</div>



<section class="our_modalities">
  <div class="tb-nav">
    <h2 class="text-center p-3">Why Join Telimed</h2>
    <nav>
      <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
        @php
        if(request()->segment(2) == ''){
        $active = 'active';
        }else{
        $active = '';
        }
        @endphp
        <a href="{{url('/why-join-telimed/patient')}}"><button class="nav-link {{ request()->segment(2) == 'patient' ? 'active' : $active }}" id="nav-home-tab" data-bs-toggle="tab" type="button" role="tab" aria-controls="nav-home" aria-selected="true">For Patients</button></a>

        <a href="{{url('/why-join-telimed/practitioner')}}"><button class="nav-link {{ request()->segment(2) == 'practitioner' ? 'active' : '' }}" id="nav-home-tab" data-bs-toggle="tab" type="button" role="tab" aria-controls="nav-home" aria-selected="true">For Practitioners</button></a>

      </div>
    </nav>
  </div>
  <div class="container">
    <div class="tab-content mt-5 mb-5" id="nav-tabContent">

      <div class="tab-pane fade active show " role="tabpanel" aria-labelledby="nav-home-tab">

        {!!$page->description!!}
      </div>

    </div>
</section>
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

@endsection