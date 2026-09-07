@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')
<div class="col-md-9">
  <h2 class="section-title d-md-flex justify-content-between align-items-center">My Courses</h2>
  <div class="d-flex align-items-center justify-content-center h-100 bg-white rounded">
    <div class="course-create not-found-data ">
      <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/not-found.svg" alt="">
      <h3>No courses yet</h3>
      <p>Upload and sell your course or program.</p>
      <a href="{{route('vender_create_course')}}" class="btn-primary px-5">Create Course</a>
    </div>
  </div>

</div>
</div>



</div>
</section>
@endsection