@extends('frontend.layout.layout2')
@section('content')

<section class="about-us">
  <div class="abt-bnr py-5 m-0" style="background-size: cover;">
    <div class="container">
      <div class="row ">
        <div class="col-md-12">
        <h2 class="text-center mt-0 mb-4">FAQs</h2>
        <div class="faq_sec">
          <div class="accordion" id="accordionExample">
            @foreach($faq as $key=>$faq_data)
            @php
            if($key==0){
            $show = 'show';
            }else{
            $show = '';
            }

            @endphp

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne_{{$key}}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                  {{$faq_data->question}}
                </button>
              </h2>
              <div id="collapseOne_{{$key}}" class="accordion-collapse collapse {{$show}}" aria-labelledby="headingOne_{{$key}}" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  {{$faq_data->answer}}
                </div>
              </div>
            </div>
            @endforeach



          </div>
        </div>
        </div>
      </div>
    </div>
  </div>


  <section class="health-fingertips ">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 wow zoomInLeft" style="visibility: visible; animation-name: zoomInLeft;">
          <h2>Holistic Health At Your <br>
            Fingertips</h2>
          <a data-bs-toggle="modal" href="#loginmdl" role="button" class="light-btn">Join Telimed</a>
        </div>
        <div class="col-md-6 wow zoomInDown" style="visibility: visible; animation-name: zoomInDown;">
          <div class="health-img text-end">
            <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/health-img.png" alt="">
          </div>
        </div>
      </div>
    </div>
  </section>

</section>




<script>
  $(".h-search-btn").click(function() {
    $(".h-srch").toggleClass("slide");
  });
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