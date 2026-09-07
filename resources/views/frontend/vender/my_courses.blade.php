@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')

<div class="col-md-9 col-lg-9 ">
<div class="courses-section-000">
<h2 class="section-title ">My Courses
</h2>
<a href="{{route('vender_create_course')}}" class="btn-primary">+ Create Course</a>
</div>
  <div class="row mb-4 login-course-man">
    @foreach($course as $cor)
    <div class="col-md-6 col-lg-4 col-xl-3">
      <div class="course-box">
        <div class="course-img">
          <a href="{{url('/practitioner_course_detail/'.$cor->slug)}}"><img src="{{isset($cor->image)?url($cor->image):url('/public/noimage.png')}}" alt=""></a>
        </div>
        <div class="course-box-dtl">
          <h3><a href="{{url('/practitioner_course_detail/'.$cor->slug)}}">{{$cor->title}}</a></h3>
          <p>{{$cor->duration}} - $ {{$cor->price}}</p>
          <a href="{{url('/practitioner_course_detail/'.$cor->slug)}}" class="any-button"><span>Learn More <img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/button-errow.svg" alt=""></span></a>
        </div>
      </div>
    </div>
    @endforeach
  </div>


</div>
</div>



</div>
</section>

@endsection