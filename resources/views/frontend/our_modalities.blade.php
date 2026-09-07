@extends('frontend.layout.layout2')
@section('content')



<div class="another_image">
  <img class="img-fluid" src="{{isset($file->banner_image)?url($file->banner_image):url('/public/noimage.png')}}" alt="" width="100%">
</div>


<section class="our_modalities">
  <div class="tb-nav">
    <h2 class="text-center p-3">Our Modalities</h2>
    <nav>
      <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
        @foreach($category as $key1=>$cat)
        <?php
        // p($file['banner_image']);
        if ($key1 == 0) {
          $active1 = 'active';
        } else {
          $active1 = '';
        }

        ?>
        <a href="{{url('/modalities/')}}?tab={{$cat->slug}}"><button class="nav-link {{ isset($_GET['tab']) ? $_GET['tab']==$cat->slug ? 'active' : '' : $active1 }}" id="nav-home-tab" data-bs-toggle="tab" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{$cat->title}}</button></a>
        @endforeach
      </div>
    </nav>
  </div>
  <div class="container">
    <div class="tab-content mt-5 mb-5" id="nav-tabContent">

      <div class="tab-pane fade active show " role="tabpanel" aria-labelledby="nav-home-tab">
        <h4>{{$file->title}}</h4>
        {!!$file->description!!}
      </div>
      <div class="text-center">
        <a href="{{ route('practitioners', ['search' => $file->slug]) }}"><button class="btn-primary me-2">Find a {{$file->search_title}}</button></a>
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