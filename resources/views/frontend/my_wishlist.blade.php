@extends('frontend.layout.layout2')
@section('content')

<section class="my-wishlist bg-color py-5">
  <div class="container">

    <h2 class="modal-title text-center py-5">My saved favourites</h2>
    <div class="directory-list list-type-man gride-lst-view">
      @foreach($wishlist as $wish)
      @php
      $min = App\Models\VenderConsultPrice::where('vender_id',$wish['get_doctor']->id)->min('consult_price');
      $max = App\Models\VenderConsultPrice::where('vender_id',$wish['get_doctor']->id)->max('consult_price');
      @endphp
      <div class="list-box">
        <div class="profile-di">
          <a href="{{url('/practitioners-detail/'.get_encrypted_value($wish['get_doctor']->id, true))}}">
            <img src="{{isset($wish['get_doctor']->profile)?url($wish['get_doctor']->profile):url('/public/vender.png')}}" alt="">
            <h3>{{$wish['get_doctor']->name}} <span>{{$wish['get_doctor']->category_name}}</span></h3>
          </a>
        </div>
        <div class="price-limit">
          <p>from $ {{$min}} to $ {{$max}}</p>
        </div>
        <div class="rating-dr">
          <h6>{{$wish['get_doctor']->address}} </h6>
          <span><img src="{{url('/public/frontend/')}}/assets/images/like.svg" alt=""> {{$wish['get_doctor']->total_review}}%</span>
        </div>
        <span class="show_file_{{$wish['get_doctor']->id}}">
          @if(Auth::user())
          @php
          $wish_product = App\Models\Wishlist::where(['user_id'=>Auth::user()->id,'doctor_id'=>$wish['get_doctor']->id])->first();
          @endphp
          @if(empty($wish_product))
          <div class="like-dr" onclick="add_to_wishlist({{$wish['get_doctor']->id}})">
            <img src="{{url('/public/frontend/')}}/assets/images/like-heart.svg" alt="">
          </div>
          @else
          <div class="like-dr" onclick="remove_to_wishlist({{$wish['get_doctor']->id}})">
            <img src="{{url('/public/frontend/')}}/assets/like.svg" alt="">
          </div>
          @endif
          @else
          <div class="like-dr" onclick="login_model()">
            <img src="{{url('/public/frontend/')}}/assets/images/like-heart.svg" alt="">
          </div>
          @endif
        </span>
      </div>
      @endforeach

    </div>
  </div>
</section>





<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
        <div class="rating-modal">
          <h2 class="modal-title text-center">Give Rating</h2>
          <div action="" class="cmn-frm">
            <div class="form-group">
              <input type="text" name="" id="" placeholder="How Did you find your session?">
            </div>
          </div>
          <div class="rating-like-dislike">
            <div class="course-rting">
              <input type="radio" name="1">
              <img src="{{url('/public/frontend/')}}/assets/images/like-rating.svg" alt="">
            </div>
            <div class="course-rting dis-like-rt">
              <input type="radio" name="1">
              <img src="{{url('/public/frontend/')}}/assets/images/like-rating.svg" alt="">
            </div>
          </div>
          <button class="btn-primary w-100">Submit</button>
        </div>
      </div>

    </div>
  </div>
</div>


<!-- Jquery needed -->
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
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



</body>

</html>