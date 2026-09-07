@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/sidebar')
<div class="col-md-9">
  <section class="prgrms course-section p-0">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-md-7">
          <a href="javascript:history.back()"><img src="{{url('/public/frontend/')}}/assets/images/bck-aro.svg" alt=""> Back to Courses</a>
          <div class="prgrms-lft mt-4">
            <div class="slideshow">
              <div class="slide">
                <video width="100%" controls poster="{{isset($file->image)?url($file->image):url('public/noimage.png')}}">
                  <source src="{{isset($file->file)?url($file->file):''}}" type="video/mp4">
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
                <img src="{{isset($file->image)?url($file->image):url('public/noimage.png')}}" alt="" />
              </div>
              @endif
            </div>
            <div class="p-5">
              <h3>{{$file->title}}</h3>
              <span>{{$file->duration}} - ${{$file->price}}</span>
              @if(!empty($file->tags))
            @php
            $tags = $array = explode(",", $file->tags);
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
              <p>{!!$file->description!!}</p>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="prgrms-rht">
            <div class="hdng">Practitioner Details</div>
            <div class="d-flex practi-prfl">
              <img class="img-fluid profile_img" src="{{isset($doctor->profile)?url($doctor->profile):url('public/noimage.png')}}" alt="" />
              <p>{{$doctor->name}}<span>{{$doctor->category_name}}</span>
              <p>
            </div>
            <div class="hdng mb-2 mt-2">Course Content</div>
            <a class="pdf-down" href="{{isset($file->course_pdf)?url($file->course_pdf):''}}" download="{{isset($file->course_pdf)?url($file->course_pdf):''}}">{{$file->title}}.pdf <img src="{{url('/public/frontend/')}}/assets/images/download-pdf.svg" alt="" /></a>
            @if($file_buy == 1)
          <div class="hdng mb-2 mt-2">Course Link</div>
          <a class="pdf-down" target="_blank" href="{{isset($file->video_link)?url($file->video_link):''}}"><div class="pdf_down_div">{{$file->video_link}}</div></a>
          @endif
            @if(count($include)>0)
            <h2>Included</h2>
            <ul class="inclds">

              @foreach($include as $in)
              <li>{{$in->title}}</li>
              @endforeach
            </ul>
            @endif
            @if(empty($review))
            <button class="btn-primary d-block w-100 mt-5" onclick="review_model({{$file->id}},2)">Give Rating</button>
            @endif
          </div>
        </div>
      </div>


    </div>
  </section>
</div>
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


<!-- Jquery needed -->