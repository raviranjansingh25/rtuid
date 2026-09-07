@extends('frontend.layout.layout2')
@section('content')

<section class="hero-section home_slider">
  <div class="container">
    <div class="home_slider_in">
      <div class="row">

        <div class="col-md-7">
          <div class="form-search-hero">
            <div class="form-search-hero-in">
              <h1>Find a <span>Healthcare</span><br>
                <span>Professional</span> that's <span>specific<br>
                  to your needs</span>
              </h1>
              
            </div>
          </div>
        </div>

        <div class="col-md-5">
          <div class="slideshow">
            <div class="slider">
              @foreach($banner as $bann)
              <div class="item">
                <img src="{{isset($bann->image)?url($bann->image):url('public/noimage.png')}}" />
              </div>
              @endforeach
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>


</section>

<section class="service-type-section">
  <div class="container">
    <div class="wrapper">
      <div class="service-slide">
        @foreach($category as $cat_data)
        <div>
          <div class="category-img">
            <a href="{{url('/modalities/')}}?tab={{$cat_data->slug}}">
              <img src="{{isset($cat_data->image)?url($cat_data->image):url('/public/noimage.png')}}" alt="">
              <div class="category_title">{{$cat_data->title}}</div>
            </a>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section class="how-work-section">
  <div class="container">
    <div class="section-heading text-center">
      <span>How We Work</span>
      <h2>The RTUID Process</h2>
    </div>
    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="how-work-box">
          <h2>01</h2>
          <h3>Search</h3>
          <p>Use our advanced filters to discover the ideal health professional who is tailored to your specific needs.</p>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="how-work-box">
          <h2>02</h2>
          <h3>View Profile</h3>
          <p> Explore the practitioner's credentials, areas of expertise, professional background, client feedback, and more!</p>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="how-work-box">
          <h2>03</h2>
          <h3>Book a Session</h3>
          <p>Enjoy your streamline video consultation by selecting a date and time that suits your schedule, accessible from any location of your choice and all in one place.</p>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="how-work-box">
          <h2>04</h2>
          <h3>Leave Feedback</h3>
          <p>Your feedback helps other potential patients and supports your healthcare professional, so don’t forget that thumbs up. </p>
        </div>
      </div>
    </div>
    <!-- <div class="steps-line">
      <div class="steps-mange">
        <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/steps-dott.svg" alt="">
        <p>Step 1</p>
      </div>
      <div class="steps-mange">
        <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/steps-dott.svg" alt="">
        <p>Step 2</p>
      </div>
      <div class="steps-mange">
        <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/steps-dott.svg" alt="">
        <p>Step 3</p>
      </div>
      <div class="steps-mange">
        <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/steps-dott.svg" alt="">
        <p>Step 4</p>
      </div>
    </div> -->
  </div>
</section>

<section class="about-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <div class="img-about">
          <img class="img-fluid" src="{{isset($about->image)?url($about->image):url('/public/noimage.png')}}" alt="">
        </div>
      </div>
      <div class="col-md-6">
        <div class="section-heading">
          <h2>{{$about->title}}</h2>
          <p>{!!$about->description!!}</p>
          <a href="{{route('about')}}"><button class="btn-primary mt-4">Read More</button></a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="course-section">
  <div class="container">
    <div class="section-heading mb-5">
      <span>Gallery</span>
      <h2>Gallery for events <a href="{{route('program')}}" class="btn-primary">View All</a></h2>
    </div>
    <div class="row">
      @foreach($course as $course_data)
      <div class="col-lg-4 col-md-6">
        <div class="course-box">
          <div class="course-img">
            <a href="{{url('/programs-detail/'.$course_data->slug)}}"> <img src="{{isset($course_data->image)?url($course_data->image):url('/public/noimage.png')}}" alt=""></a>
          </div>
          <div class="course-box-dtl">
            <h3><a href="{{url('/programs-detail/'.$course_data->slug)}}">{{$course_data->title}}</a></h3>
            
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

</section>

<section class="features-section" id="features_section">
  <div class="container">
    <div class="section-heading text-center">
      <span>Features</span>
      <h2>Why Choose RTUID</h2>
    </div>
    <div class="row">
      @foreach($feature as $fet)
      <div class="col-xl-3 col-lg-4 col-md-5 col">
        <div class="features-box">
          <div class="features-box1-img"><img class="" src="{{isset($fet->image)?url($fet->image):url('/public/noimage.png')}}" alt=""></div>
          <h3>{{$fet->title}}</h3>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="video-section">
  <div class="container">
    <div class="section-heading text-center text-white">
      <span class="text-white">Video Call</span>
      <h2 class="text-white">Video Consult From Anywhere In The World</h2>
    </div>
    <div class="img-dtl text-center">
      <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/video-call-img.png" alt="">
      <button class="light-btn mt-4" data-bs-toggle="modal" href="#signmdl" role="button">Join RTUID</button>
    </div>
  </div>
</section>

<section class="tstimonial-section">
  <div class="container">
    <div class="section-heading text-center">
      <span>Testimonials</span>
      <h2>What Our Patients Say</h2>
    </div>
    <div class="testimonial-slider">
      @foreach($testimonial as $test)
      <div>
        <div class="testimonial-box">
          <p>{{$test->message}}</p>
          <div class="user-profile">
            <img src="{{isset($test->image)?url($test->image):url('/public/noimage.png')}}" alt="">
            <h5>{{$test->name}} <span>{{$test->designation}}</span></h5>
          </div>
          <img class="img-fluid left-quote-icon" src="{{url('/public/frontend/')}}/assets/images/left-quote.svg" alt="">
          <img class="img-fluid right-quote-icon" src="{{url('/public/frontend/')}}/assets/images/right-quote.svg" alt="">
        </div>
      </div>
      @endforeach
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
        <a data-bs-toggle="modal" href="#signmdl" role="button" class="light-btn">Join RTUID</a>
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