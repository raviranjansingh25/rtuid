@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/sidebar')

<div class="col-md-9">
  <div class="row mb-4 login-course-man">
    @forelse($course as $course_data)
    <div class="col-md-3">
      <div class="course-box">
        <div class="course-img">
          <a href="{{url('/course-detail/'.$course_data['get_course']->slug)}}"><img src="{{isset($course_data['get_course']->image)?url($course_data['get_course']->image):url('/public/noimage.png')}}" alt=""></a>
        </div>
        <div class="course-box-dtl">
          <h3><a href="{{url('/course-detail/'.$course_data['get_course']->slug)}}">{{$course_data['get_course']->title}}</a></h3>
          <p>{{$course_data['get_course']->duration}} - $ {{$course_data['get_course']->price}}</p>
          <a href="{{url('/course-detail/'.$course_data['get_course']->slug)}}" class="any-button"><span>Learn More <img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/button-errow.svg" alt=""></span></a>
        </div>
      </div>
    </div>
    @empty
    <div class="course-create not-found-data ">
      <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/not-found.svg" alt="">
      <h3>No courses purchased yet</h3>

      <a href="{{route('program')}}" class="btn-primary px-5">Buy Course</a>
    </div>
    @endforelse

  </div>
</div>
</div>
</section>
@endsection