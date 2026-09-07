<!-- Footer section start -->
@php
$city = App\Models\City::where('status',1)->limit(27)->get();
@endphp
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="con-ftr">
          <h3>Connect with Us</h3>
          <div class="call-no">
            <img src="{{url('/public/frontend/')}}/assets/img/call_icon.svg" alt="">
            <div class="cal-log">
              <p>Got Questions? Call us 24/7 <a href="#">+ 91 {{$setting->phone}}</a></p>
            </div>
          </div>
          <p>{{$setting->address}}</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="con-ftr">
          <h3>Connect with Us</h3>
          <ul>
            <li><a href="{{route('about_us')}}">About Us</a></li>
            <li><a href="{{url('/page/guest-policies')}}"> Guest Policies</a></li>
            <li><a href="{{route('career')}}">Career </a></li>
            <li><a href="{{url('/page/privacy-policy')}}"> Privacy Policy </a></li>
            <li><a href="{{route('contact_us')}}">Contact</a></li>
            <li><a href="{{url('/page/trust-and-safety')}}">Trust And Safety  </a></li>
            <li><a href="{{route('web_faq')}}"> FAQ </a></li> 
            <li><a href="{{route('associated')}}"> Associated </a></li> 
          </ul>
        </div>
      </div>
      <div class="col-md-4">
        <div class="ns-ltr">
          <h3>Get access to exclusive deals</h3>
          <p>Only the best deals reach your inbox</p>
          <form action="" class=" position-relative">
            <input type="email" name="email" placeholder="Your Email" id="newsletter">
            <button type="button" onclick="newsletter_data()" class="pre-btn">Notify me</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="btm-ftr"> 
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <p>© 2022 <a href="{{url('/')}}">cityroom.in</a>. All rights reserved.</p>
        </div>
        <div class="col-md-4 text-center">
          <img class="img-fluid" src="{{url($setting->footer_logo)}}" alt="">
        </div>
        <div class="col-md-4 text-end">
          <ul>
            <li><a href="#"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/youtube-f.svg" alt=""></a></li>
            <li><a href="#"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/whatsapp-f.svg" alt=""></a></li>
            <li><a href="#"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/twitter-f.svg" alt=""></a></li>
            <li><a href="#"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/facebbok-f.svg" alt=""></a></li>
            <li><a href="#"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/insta-f.svg" alt=""></a></li>
          </ul>
        </div>
      </div>
    </div> 
  </div>
  <div class="text-center top-win-btn">
    <a href="#"><img src="{{url('/public/frontend/')}}/assets/img/top-icon.svg" alt=""></a>
  </div>
  <div class="cityes-1">
    <div class="container">
      <h3>Our Hotels</h3>
   <div class="row cityes-2">
      @if(!empty($cityname))
      <div class="col-6 col-md-3" style="margin-bottom: 20px;">
        <a href="{{route('hotels',['city'=>$cityname->id])}}" style="color: white!important;">
        <i><img src="{{url('/public/frontend/')}}/assets/img/location-1.svg" alt=""></i>
         Near me</a>
       </div>
       @endif
       @foreach($city as $cities)
        <div class="col-6 col-md-3" style="margin-bottom: 20px;">
          <a href="{{route('hotels',['city'=>$cities->id])}}" style="color: white!important;">
          <i><img src="{{url('/public/frontend/')}}/assets/img/location-1.svg" alt=""></i> {{$cities->city}}</a>
        </div>
      @endforeach
      
   </div>
    </div>
  </div>
</footer>

<div class="modal otp-mdl fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"> 
          <div class="modal-body">
            <div class="otp-verfication">
              <button type="button" class="mdl-cls" data-bs-dismiss="modal" aria-label="Close"><img src="./assets/img/cls-login.svg" alt=""></button>
            <h3>OTP Verification</h3>
            <p>Enter your 6 digit code we have sent on your registered
              mobile number <a href="#" id="mobile_number" class="otp_mobile"></a></p>
              <form class="otp-ver-box">
                <div  class="otp-flds" id="otp">
                  <input type="number" pattern="[0-9]*" class="otp_field" inputmode="numeric" id="one" maxlength="1">
                  <input type="number" pattern="[0-9]*" class="otp_field" inputmode="numeric" id="two" maxlength="1">
                  <input type="number" pattern="[0-9]*" class="otp_field" inputmode="numeric" id="three" maxlength="1">
                  <input type="number" pattern="[0-9]*" class="otp_field" inputmode="numeric" id="four" maxlength="1">
                  <input type="number" pattern="[0-9]*" class="otp_field" inputmode="numeric" id="five" maxlength="1">
                  <input type="number" pattern="[0-9]*" class="otp_field" inputmode="numeric" id="six" maxlength="1">
                </div>
                <span class="error_otp">Please enter valid otp.</span>
                <h5> You will receive OTP within <span id="countdowntimer"></span> Sec</h5>
                <div class="ver-resnd">
                  <p class="resend_otp">Did not get Verification Code? <span style="cursor: pointer;" onclick="resend_otp()">Resend OTP</span></p>
                  <button type="button" class="pre-btn px-5" onclick="varify_button()">Verify</button>
                </div>
              </form>
              
            </div>
          </div>
          
        </div>
      </div>
    </div>
    

    <script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>
    <script src="{{url('/public/frontend/')}}/assets/js/rome.js"></script>
    <script src="{{url('/public/frontend/')}}/assets/js/main.js"></script>
    <script src="{{url('/public/frontend/')}}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('/public/frontend/')}}/assets/js/slick.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <script type="text/javascript">
      function newsletter_data(){
        var email = $('#newsletter').val();
        if (email != '') {
            $.ajax({
              url: "{{ url('/newsletter') }}",
              datatType: 'json',
              data: {
                  '_token' : '<?php echo csrf_token() ?>',
                  'email'    : email,
              },
              
              success: function (res)
              {
                  if (res.status==1) {
                      swal({
                          title: "Success!",
                          text: res.message,
                          icon: "success",
                          dangerMode: true,
                          buttons: false,
                          timer: 1000
                      })
                      $("#newsletter").val("");
                  }
                  else {
                      swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Plese enter email address!',
                      })
                  }
              }
          });
          }else{
            swal({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Plese enter email address!',
                })
          }
        
      }
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
      (function() {
  
  var slideContainer = $('.slide-container');
  slideContainer.slick({
    arrows: true,
    initialSlide:0,
    centerMode: true,
    centerPadding: '20%',
    slidesToShow: 1,
    swipeToSlide:true,
    responsive: [
      {
        breakpoint: 1334,
        settings: {
          slidesToShow: 1,
          centerPadding: '10%'
        }
      },
      {
        breakpoint: 1050,
        settings: {
          slidesToShow: 1,
          centerPadding: '0%'
        }
      },
      {
        breakpoint: 700,
        settings: {
          slidesToShow: 1,
          centerPadding: '0%'
        }
      }
    ]
  });
})();
</script>
<script type="text/javascript">
        function add_to_wish(hotel_id,c) {
            var hotel_id = hotel_id;
            // alert(product_id);
            $.ajax({
                url: "{{ url('/add-wishlist') }}",
                datatType: 'json',
                type: 'POST',
                data: {
                    '_token' : '<?php echo csrf_token() ?>',
                    'hotel_id'    : hotel_id,
                },
                
                success: function (res)
                {
                    if (res.status==1) {
                        $(c).parent().html(res.wish_data);
                    }
                    else {
                        location.href = "{{ route('login') }}";
                    }
                }
            });   
        }

        function remove_to_wish(hotel_id,c) {
            var hotel_id = hotel_id;
            // alert(product_id);
            $.ajax({
                url: "{{ url('/remove-wishlist') }}",
                datatType: 'json',
                type: 'POST',
                data: {
                    '_token' : '<?php echo csrf_token() ?>',
                    'hotel_id'    : hotel_id,
                },
                
                success: function (res)
                {
                    if (res.status==1) { 
                        $(c).parent().html(res.wish_data);
                    }
                    else {
                        // location.reload();
                    }
                }
            });   
        }

</script>


<script type="text/javascript">
    function OTPInput() {
    const inputs = document.querySelectorAll('#otp > *[id]');
    for (let i = 0; i < inputs.length; i++) {
      inputs[i].addEventListener('keydown', function(event) {
        if (event.key === "Backspace") {
          inputs[i].value = '';
          if (i !== 0)
            inputs[i - 1].focus();
        } else {
          if (i === inputs.length - 1 && inputs[i].value !== '') {
            return true;
          } else if (event.keyCode >= 47 && event.keyCode <= 58) {
            inputs[i].value = event.key;
            if (i !== inputs.length - 1)
              inputs[i + 1].focus();
            event.preventDefault();
          } else if (event.keyCode >= 96 && event.keyCode <= 105) {
            inputs[i].value = event.key;
            if (i !== inputs.length - 1)
              inputs[i + 1].focus();
            event.preventDefault();
          }
        }
      });
    }
  }
  OTPInput();

</script>

<script type="text/javascript">
  function resend_otp() {
      var mobile_number = $('.otp_mobile').html();
      $.ajax({
          url: "{{ url('/resend_otp') }}",
          datatType: 'json',
          type: 'POST',
          data: {
              '_token' : '<?php echo csrf_token() ?>',
              'mobile_number'    : mobile_number,
          },
          
          success: function (res)
          {
              $('.resend_otp').css('display','none');
              var timeleft = 60;
             var downloadTimer = setInterval(function(){
              timeleft--;
              if(timeleft==0){
                $('.resend_otp').css('display','block');
              }
              document.getElementById("countdowntimer").textContent = timeleft;
              if(timeleft <= 0)
                clearInterval(downloadTimer);
            },1000);
          }
      });   
  }
</script>

<script type="text/javascript">
  $(".otp_field").keyup(function () {
    if (this.value.length == this.maxLength) {
      $(this).next('.otp_field').focus();
    }
});

$(document).ready(function() {
  $('.prfl-img').click(function() {
    $('.prfle-act-dropdwn').slideToggle("slow"); 
  }); 
});
</script>
    
  </body>
</html>
