@extends('frontend.layout.layout2')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/animate.min.css" />
<link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/style.css" />

<section class="prgrms course-section">
  <div class="container-fluid">
    <div class="row mb-4">
      <div class="col-md-12 col-lg-7">
        <a href="javascript:history.back()"><img src="{{url('/public/frontend/')}}/assets/images/bck-aro.svg" alt=""> Back to Courses</a>
        <div class="prgrms-lft">
          @if($file_buy != 1)
            <div class="slideshow">
            <div class="slide">
                <video width="100%" controls poster="{{isset($data->image)?url($data->image):url('public/noimage.png')}}">
                  <source src="{{isset($data->file)?url($data->file):''}}" type="video/mp4">


                </video>
            </div>
              @if(count($multi_image) >0)
              @foreach($multi_image as $multi)
              <div class="slide">
                <img src="{{isset($multi->image)?url($multi->image):url('public/noimage.png')}}" alt="" />
              </div>
              @endforeach
              @else
              <div class="slide">
                <img src="{{isset($data->image)?url($data->image):url('public/noimage.png')}}" alt="" />
              </div>
              @endif
              
            </div>
          @else
            <div class="slideshow">
              <div class="slide">
                <video width="100%" controls poster="{{isset($data->image)?url($data->image):url('public/noimage.png')}}">
                  <source src="{{isset($data->file)?url($data->file):''}}" type="video/mp4">


                </video>
            </div>
            @if(count($multi_image) >0)
            @foreach($multi_image as $multi)
            <div class="slide">
              <img src="{{isset($multi->image)?url($multi->image):url('public/noimage.png')}}" alt="" />
            </div>
            @endforeach
            @else
            <div class="slide">
              <img src="{{isset($data->image)?url($data->image):url('public/noimage.png')}}" alt="" />
            </div>
            @endif
          </div>
          @endif


          <div class="p-5">
            <h3>{{$data->title}}</h3>
            <span>{{$data->duration}} - $ {{$data->price}}</span>
            <p>{!!$data->description!!}</p>
            @if(!empty($data->tags))
            @php
            $tags = $array = explode(",", $data->tags);
            $tag_data = \App\Models\Tags::whereIn('id',$tags)->where('status',1)->get();
            @endphp
            <ul>

              @foreach($tag_data as $tag)
              <a href="{{route('program',['tag'=>$tag->slug])}}">
                <li>#{{$tag->title}}</li>
              </a>
              @endforeach
            </ul>
            @endif
            
          </div>

        </div>
      </div>
      <div class="col-md-12 col-lg-5">
        <div class="prgrms-rht">
          <div class="hdng">Practitioner Details</div>
          <div class="d-flex practi-prfl">
            <img class="img-fluid profile_img" src="{{isset($user->profile)?url($user->profile):url('public/vender.png')}}" alt="" />
            <p>{{$user->name}}<span>{{$user->category_name}}</span>
            <p>
          </div>
          @if($file_buy == 1)
          @if(!empty($data->course_pdf))
          <div class="hdng mb-2 mt-2">Course Content</div>
          <a class="pdf-down" href="{{isset($data->course_pdf)?url($data->course_pdf):''}}" download="{{isset($data->course_pdf)?url($data->course_pdf):''}}">{{$data->title}}.pdf <img src="{{url('/public/frontend/')}}/assets/images/download-pdf.svg" alt="" /></a>
          @endif
          @endif

          @if($file_buy == 1)
          <div class="hdng mb-2 mt-2">Course Link</div>
          <a class="pdf-down" target="_blank" href="{{isset($data->video_link)?url($data->video_link):''}}"><div class="pdf_down_div">{{$data->video_link}}</div></a>
          @endif

          @if(count($include)>0)
          <h2>Included</h2>
          <ul class="inclds">

            @foreach($include as $in)
            <li>{{$in->title}}</li>
            @endforeach
          </ul>
          @endif
          @if($file_buy != 1)
          @if(!empty(Auth::guard('web')->user()))
          @php 
          $tax = ($data->price*2.5)/100;
          
          $total_amount = $data->price+$tax;
          
          @endphp
          <form action="{{route('paylink')}}" method="post">
            @csrf
            <input type="hidden" name="final_amount" value="{{$total_amount}}">
            <input type="hidden" name="course_id" value="{{$data->id}}">
            <button type="submit" class="btn-primary d-block w-100 mt-5">Buy Now</button>
          </form>
          @else
          <button type="button" onclick="login_model()" class="btn-primary d-block w-100 mt-5">Buy Now</button>
          @endif
          @endif
        </div>
      </div>
    </div>
    <h4>Similar Courses</h4>
    <div class="row">
      @forelse($similar as $sim)
      <div class="col-lg-3">
        <div class="course-box">
          <div class="course-img">
            <img src="{{isset($sim->image)?url($sim->image):url('/public/noimage.png')}}" alt="">
          </div>
          <div class="course-box-dtl">
            <h3>{{$sim->title}}</h3>
            <p>{{$sim->duration}} - $ {{$sim->price}}</p>
            <a href="{{url('/programs-detail/'.$sim->slug)}}" class="any-button"><span>Learn More <img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/button-errow.svg" alt=""></span></a>
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
<script src="{{url('/public/frontend/')}}/assets/js/jquery.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
<script type="text/javascript">
  $(".h-search-btn").click(function() {
    $(".h-srch").toggleClass("slide");
  });
  $(".slideshow").slick({
    infinite: true,
    autoplay: false,
    dots: false,
    arrows: true,
    autoplaySpeed: 4000
  });
</script>

<script>
  $(".mobile_nav").click(function() {

    var mm = $(".mobile_menu"),
      mn = $(".mobile_nav"),
      a = "active";

    if (mm.hasClass(a) && mn.hasClass(a)) {
      mm.removeClass(a).fadeOut(200);
      mn.removeClass(a);
      $('.mobile_menu li').each(function() {
        $(this).removeClass('slide');
      });
    } else {
      mm.addClass(a).fadeIn(200);
      mn.addClass(a);
      $('.mobile_menu li').each(function(i) {
        var t = $(this);
        setTimeout(function() {
          t.addClass('slide');
        }, (i + 1) * 100);
      });
    }

  });



  // I've added annotations to make this easier to follow along at home. Good luck learning and check out my other pens if you found this useful


  // First let's set the colors of our sliders
  const settings = {
    fill: '#1abc9c',
    background: '#d7dcdf'
  }

  // First find all our sliders
  const sliders = document.querySelectorAll('.range-slider');

  // Iterate through that list of sliders
  // ... this call goes through our array of sliders [slider1,slider2,slider3] and inserts them one-by-one into the code block below with the variable name (slider). We can then access each of wthem by calling slider
  Array.prototype.forEach.call(sliders, (slider) => {
    // Look inside our slider for our input add an event listener
    //   ... the input inside addEventListener() is looking for the input action, we could change it to something like change
    slider.querySelector('input').addEventListener('input', (event) => {
      // 1. apply our value to the span
      slider.querySelector('span').innerHTML = event.target.value;
      // 2. apply our fill to the input
      applyFill(event.target);
    });
    // Don't wait for the listener, apply it now!
    applyFill(slider.querySelector('input'));
  });

  // This function applies the fill to our sliders by using a linear gradient background
  function applyFill(slider) {
    // Let's turn our value into a percentage to figure out how far it is in between the min and max of our input
    const percentage = 100 * (slider.value - slider.min) / (slider.max - slider.min);
    // now we'll create a linear gradient that separates at the above point
    // Our background color will change here
    const bg = `linear-gradient(90deg, ${settings.fill} ${percentage}%, ${settings.background} ${percentage+0.1}%)`;
    slider.style.background = bg;
  }
</script>
@endsection