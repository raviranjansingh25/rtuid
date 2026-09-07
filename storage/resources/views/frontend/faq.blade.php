
@extends('frontend.layout.layout')
@section('content')

    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
    <!-- Hero Section  -->
    <section class="contect-hero mt-110">
      <div class="container ctr-algn">
        <div class="hero-heading">
          <h1>FAQ's</h1>
          <p>Frequently Asked Question</p>
        </div>
      </div>
    </section>

    <!-- Contect Form Start -->
    <section class="contect-frm-section">
      <div class="container">
        <div class="frm-cnct">
          <div class="accordion">
            @foreach($category as $cat)
            <div class="acnt-faq">
              <h3>{{$cat->title}}</h3>
            </div>
              @foreach($cat['get_faq'] as $faq)
              <div class="accordion-item">
                <a>{{$faq->question}}</a>
                <div class="content">
                  <p>{{$faq->answer}}</p>
                </div>
              </div>
              @endforeach
            @endforeach
          </div>
        </div>
      </div>
    </section>
    <!-- Address No Section Start -->
  
    <!-- Footer section start -->
@endsection

    <script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>
    <script>
      $(document).ready(function() {
  $('.accordion a').click(function(){
    $(this).toggleClass('active');
    $(this).next('.content').slideToggle(400);
   });
});
    </script>
    <script>
      const slider = document.querySelector('.items');
let isDown = false;
let startX;
let scrollLeft;

slider.addEventListener('mousedown', (e) => {
isDown = true;
slider.classList.add('active');
startX = e.pageX - slider.offsetLeft;
scrollLeft = slider.scrollLeft;
});
slider.addEventListener('mouseleave', () => {
isDown = false;
slider.classList.remove('active');
});
slider.addEventListener('mouseup', () => {
isDown = false;
slider.classList.remove('active');
});
slider.addEventListener('mousemove', (e) => {
if(!isDown) return;
e.preventDefault();
const x = e.pageX - slider.offsetLeft;
const walk = (x - startX) * 3; //scroll-fast
slider.scrollLeft = scrollLeft - walk;
console.log(walk);
});
function slidefunction(position)
{

if(position=="left")
{
  slider.scrollLeft = 0
}
else{
  slider.scrollLeft = 900
}

}
</script>
    <script>
      $('.navTrigger').click(function () {
    $(this).toggleClass('active');
    console.log("Clicked menu");
    $("#mainListDiv").toggleClass("show_list");
    $("#mainListDiv").fadeIn();

});

    </script>
    <script>
      (function () {
        var slideContainer = $(".slide-container");
        slideContainer.slick({
          arrows: true,
          initialSlide: 0,
          centerMode: true,
          centerPadding: "20%",
          slidesToShow: 1,
          swipeToSlide: true,
          responsive: [
            {
              breakpoint: 1334,
              settings: {
                slidesToShow: 1,
                centerPadding: "10%",
              },
            },
            {
              breakpoint: 1050,
              settings: {
                slidesToShow: 1,
              },
            },
            {
              breakpoint: 700,
              settings: {
                slidesToShow: 1,
              },
            },
          ],
        });
      })();
    </script>
    <script>
      $(function () {
        rome(input_from, {
          dateValidator: rome.val.beforeEq(input_to),
          time: false,
        });

        rome(input_to, {
          dateValidator: rome.val.afterEq(input_from),
          time: false,
        });
      });
    </script>
    
  </body>
</html>
