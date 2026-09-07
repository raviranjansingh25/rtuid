@extends('frontend.layout.layout2')
@section('content')

<section class="prgrms course-section">
  <div class="container">
    <div class="section-heading mb-4">
      <h2>Blogs

      </h2>
    </div>
    <div class="row ">
      @foreach($blog as $blog_data)
      <div class="col-md-6 col-lg-4 col-xl-3">
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