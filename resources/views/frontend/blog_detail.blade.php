@extends('frontend.layout.layout2')



@section('content')



<section class="blog-detail pt-5 mb-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12 col-md-12  ">
        <h2 class="mb-4 text-center">{{$blog->title}}</h2>
        <!-- <span class="date-post">
          December 29, 2023</span> -->
        <div class="post-img-blog mb-3">
          <img src="{{isset($blog->image)?url($blog->image):url('/public/noimage.png')}}" class="img-fluid" alt="">
        </div>
        {!!$blog->description!!}
      </div>
    </div>
  </div>
</section>
<section class="prgrms course-section">
  <div class="container">
    <div class="section-heading text-center">
      <h2>Recent Blogs</h2>
    </div>
    <div class="row mb-4">
      @foreach($blogs as $blog_data)


      <div class="col-md-3">
        <div class="course-box">
          <div class="course-img">
            <img src="{{isset($blog_data->image)?url($blog_data->image):url('/public/noimage.png')}}" alt="">
            <!-- <span class="blog-date">06 <br> Jan</span> -->
          </div>
          <div class="course-box-dtl blog-ddttl">
            <a href="{{url('/blog_detail/'.$blog_data->slug)}}">
              <h3>{{$blog_data->title}}</h3>
            </a>
            <p>{{$blog_data->short_description}}</p>
            <a href="{{url('/blog_detail/'.$blog_data->slug)}}" class="any-button"><span>Read More <img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/button-errow.svg" alt=""></span></a>
          </div>
        </div>
      </div>
      @endforeach

    </div>

  </div>
</section>


@endsection