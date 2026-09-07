@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/sidebar')
<div class="col-md-9">
  <div class="row">
    <div class="col-md-6">
      <a href="my-sessions.html" class="d-flex align-items-center gap-2"><img class="img-fluid ms-2" src="./images/back-errow.svg" alt=""> Back to Dashboard</a>
      <div class="celender-section mt-4">
        <h3>Availability</h3>
        <div class="meeting-time">
          <button class="active">1 Hour</button>
          <button>45 Min</button>
          <button>30 Min</button>
        </div>
        <form action="#" class="open-celndr">
          <div id="inline_cal" data-rome-id="0"></div>
        </form>
        <div class="actvty-type">
          <div class="row">
            <div class="col-md-6">
              <p>Indicate for not available</p>
            </div>
            <div class="col-md-6">
              <p class="avalble">Indicate for available</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="celender-section">
        <h3 class="text-start">Available Time Slots</h3>
        <div class=" meeting-time d-block">
          <div class="row">
            @foreach($calendar as $key=>$cal)
            <?php
            if ($key == 0) {
              $active = 'active';
            } else {
              $active = '';
            }
            ?>
            <div class="col-md-6 mb-3">
              <button class="calendar-button {{$active}}" data-start="{{ $cal->start_time }}" data-sec-id="{{ $cal->id }}" data-end="{{ $cal->end_time }}">
                {{ date("h:i a", strtotime($cal->start_time)) }} to {{ date("h:i a", strtotime($cal->end_time)) }}
              </button>
            </div>
            @endforeach
          </div>
        </div>
        <div class="action-btn-meeting">
          <form action="{{route('paylinksession')}}" method="post">
            @csrf
            <input type="hidden" name="month" id="month">
            <input type="hidden" name="date" id="date">
            <input type="hidden" name="session_id" id="session_id">

            <button onclick="booking_now()" class="btn-primary w-100 mt-3 text-center">Book & Pay Now</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
</div>
</div>
</section>





<!-- Jquery needed -->
<script src="{{url('/public/frontend/')}}/assets/js/jquery.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.min.js"></script>
<!-- <script src="{{url('/public/frontend/')}}/assets/js/wow.min.js"></script> -->
<script src="{{url('/public/frontend/')}}/assets/js/rome.js"></script>
<script>
  function booking_now() {
    // Concatenate classes with a dot
    var buttonElement = document.querySelector('.calendar-button.active');

    if (buttonElement) {
      var dataSecIdValue = buttonElement.getAttribute('data-sec-id');
    } else {
      swal({
        icon: 'error',
        title: 'Oops...',
        text: 'Please select Available Time Slots',
      })

    }
    var date = $('.rd-day-body.rd-day-selected').html();
    var month = $('.rd-month-label').html();

    $("#session_id").val(dataSecIdValue);
    $("#month").val(month);
    $("#date").val(date);


  }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Get all buttons with the class 'calendar-button'
    var buttons = document.querySelectorAll('.calendar-button');

    // Add a click event listener to each button
    buttons.forEach(function(button) {
      button.addEventListener('click', function() {
        // Remove the 'active' class from all buttons
        buttons.forEach(function(btn) {
          btn.classList.remove('active');
        });

        // Add the 'active' class to the clicked button
        button.classList.add('active');

      });
    });
  });
</script>
<script>
  $(".h-search-btn").click(function() {
    $(".h-srch").toggleClass("slide");
  });
  // Prepare the preview for profile picture
  $("#wizard-picture").change(function() {
    readURL(this);
  });

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
      }
      reader.readAsDataURL(input.files[0]);
    }
  }




  $(function() {

    rome(inline_cal, {
      time: false
    });

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




  (function() {
    const second = 1000,
      minute = second * 60,
      hour = minute * 60,
      day = hour * 24;

    //I'm adding this section so I don't have to keep updating this pen every year :-)
    //remove this if you don't need it
    let today = new Date(),
      dd = String(today.getDate()).padStart(2, "0"),
      mm = String(today.getMonth() + 1).padStart(2, "0"),
      yyyy = today.getFullYear(),
      nextYear = yyyy + 1,
      dayMonth = "09/30/",
      birthday = dayMonth + yyyy;

    today = mm + "/" + dd + "/" + yyyy;
    if (today > birthday) {
      birthday = dayMonth + nextYear;
    }
    //end

    const countDown = new Date(birthday).getTime(),
      x = setInterval(function() {

        const now = new Date().getTime(),
          distance = countDown - now;


        document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
          document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
          document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

        //do something later when date is reached
        if (distance < 0) {
          document.getElementById("headline").innerText = "It's my birthday!";
          document.getElementById("countdown").style.display = "none";
          document.getElementById("content").style.display = "block";
          clearInterval(x);
        }
        //seconds
      }, 0)
  }());
</script>

@endsection