@extends('frontend.layout.layout2')
@section('content')

<section class="prgrms course-section">
  <div class="container-fluid">
    

    <div class="row">
      @forelse($course as $course_data)
      <div class="col-lg-3 col-md-6">
        <div class="course-box">
          <div class="course-img">
            <img src="{{isset($course_data->image)?url($course_data->image):url('/public/noimage.png')}}" alt="">
          </div>
          <div class="course-box-dtl">
            <h3>{{$course_data->title}}</h3>
           
          </div>
        </div>
      </div>
      @empty
      <div class="col-lg-12 col-md-12 text-center">
        <div class="course-box">

          <div class="course-box-dtl">
            <h3>No courses yet.</h3>

          </div>
        </div>
      </div>
      @endforelse
    </div>

  </div>
</section>

@endsection