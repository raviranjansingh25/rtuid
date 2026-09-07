@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/sidebar')
<style>
  .swal-button--confirm {
    background: transparent linear-gradient(292deg, #00D2D9 0%, #2CFDB2 100%);
  }
</style>
<div class="col-md-9">
  <div class="row">
    <div class="col-md-6">
      <a href="my-sessions.html" class="d-flex align-items-center gap-2"><img class="img-fluid ms-2" src="./images/back-errow.svg" alt=""> Back to Dashboard</a>
      <div class="celender-section mt-4">
        <h3>Availability</h3>
        <div class="meeting-time">
        <button onclick="time_select('15',{{$user->id}})" data-min="15" id="btnFifteenMin" class="button_show <?php echo in_array(15, $allowedDurations) ? '' : 'd-none'; ?>">15 Min</button>
        <button onclick="time_select('30',{{$user->id}})" data-min="30" id="btnThirtyMin" class="button_show <?php echo in_array(30, $allowedDurations) ? '' : 'd-none'; ?>">30 Min</button>
        <button onclick="time_select('45',{{$user->id}})" data-min="45" id="btnFortyfiveMin" class="button_show <?php echo in_array(45, $allowedDurations) ? '' : 'd-none'; ?>">45 Min</button>
        <button onclick="time_select('60',{{$user->id}})" data-min="60" id="btnOneHour" class="button_show <?php echo in_array(60, $allowedDurations) ? '' : 'd-none'; ?>">1 Hour</button>
        <button onclick="time_select('90',{{$user->id}})" data-min="90" id="btnOneAndHalfHour" class="button_show <?php echo in_array(90, $allowedDurations) ? '' : 'd-none'; ?>">1.5 Hour</button>
        </div>
        <form action="#" class="open-celndr">
          <div id="inline_cal" data-rome-id="0"></div>
        </form>
        <div class="actvty-type">
          <div class="row">
            
            <div class="col-md-6">
              <p class="avalble">Indicate for not available</p>
            </div>
            <div class="col-md-6">
              <p>Indicate for available</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="celender-section">
        <h3 class="text-start">Available Time Slots</h3>
        <div class=" meeting-time d-block">
          <div class="row time_data">
            @foreach($calendar as $key=>$cal)
            <?php
            if ($key == 0) {
              $active = 'active';
            } else {
              $active = '';
            }
            ?>

            <div class="col-md-6 mb-3">
              <button class="calendar-button {{$active}}" data-start="{{ $cal['start_time'] }}" data-sec-id="{{ $cal['id'] }}" data-end="{{ $cal['end_time'] }}">
                {{ date("h:i a", strtotime($cal['start_time'])) }} to {{ date("h:i a", strtotime($cal['end_time'])) }}
              </button>
            </div>
            @endforeach
          </div>
        </div>
        <div class="action-btn-meeting">
          <form id="bookingForm" action="{{route('reschedule_save')}}" method="post">
            @csrf
            <input type="hidden" name="month" id="month">
            <input type="hidden" name="date" id="date">
            <input type="hidden" name="session_id" id="session_id">
            <input type="hidden" name="start_time" class="start_time">
            <input type="hidden" name="end_time" class="end_time">
            <input type="hidden" name="session_booking_id" value="{{$file->id}}">

            <button onclick="booking_now()" class="btn-primary w-100 mt-3 text-center">Reschedule Now</button>
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
  function time_select(time, vender_id) {

    var date = $('.rd-day-body.rd-day-selected').html();
    var month = $('.rd-month-label').html();

    $.ajax({
      url: "{{ url('/chek_availability') }}",
      datatType: 'json',
      data: {
        '_token': '<?php echo csrf_token() ?>',
        'select_time': time,
        'select_date': date,
        'select_month': month,
        'vender_id': vender_id,
      },

      success: function(res) {
        if (res.status == 1) {
          $('.time_data').html(res.data);
          addButtonEvent()
        }
      }
    });
  }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Add a click event listener to elements with the class 'rd-day-body' and 'rd-day-selected'
    var selectedDateElements = document.querySelectorAll('.rd-day-body.rd-day-selected');
    // Iterate through each selected date element
    selectedDateElements.forEach(function(selectedDateElement) {
      selectedDateElement.addEventListener('click', function() {
        // Get the selected date text content
        var selectedDateText = selectedDateElement.textContent.trim();

        // Get the month label text content
        var monthLabel = document.querySelector('.rd-month-label');
        var monthLabelText = monthLabel ? monthLabel.textContent.trim() : '';

        // Display the selected date with month value
        console.log("Selected Date: " + monthLabelText + " " + selectedDateText);
      });
    });
  });
</script>
<script>
  function booking_now() {
    event.preventDefault();
    var buttonElement = document.querySelector('.calendar-button.active');

    if (buttonElement) {
      var dataSecIdValue = buttonElement.getAttribute('data-sec-id');
      var StartTimeValue = buttonElement.getAttribute('data-start');
      var EndTimeValue = buttonElement.getAttribute('data-end');
      
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
    $(".start_time").val(StartTimeValue);
    $(".end_time").val(EndTimeValue);
    

    swal({
      text: "You are booking in for " + date + " " + month + " at " + StartTimeValue + ". If this is correct, please click ok.",
      icon: false,
      buttons: true,
      successMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        document.getElementById('bookingForm').submit();
      } else {
        swal({
          title: "Cancelled",
          text: "You cancelled your action",
          icon: "error",
          buttons: false,
          timer: 1500
        })

      }
    })
  }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    addButtonEvent()
  });

  function addButtonEvent() {

    // Get all buttons with the class 'calendar-button'
    var buttons = document.querySelectorAll('.calendar-button');

    // Add a click event listener to each button
    buttons.forEach(function(button) {
      button.addEventListener('click', function() {
        console.log("here");
        // Remove the 'active' class from all buttons
        buttons.forEach(function(btn) {
          btn.classList.remove('active');
        });

        // Add the 'active' class to the clicked button
        button.classList.add('active');

      });
    });
  }
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
    var currentDate = new Date();

    // Extract year, month, and day components from the date
    var year = currentDate.getFullYear();
    var month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed, so we add 1
    var day = String(currentDate.getDate()).padStart(2, '0');

    // Combine the components into the desired format
    var formattedDate = year + '-' + month + '-' + day;
   

    get_deactive_date(formattedDate);
  
  });

  function get_deactive_date(month){
    var vender_id = <?php echo $vender_id?>;
    alert(vender_id);
    $.ajax({
      url: "{{url('/get-disabled-dates')}}",
      datatType: 'json',
        data: {
          '_token': '<?php echo csrf_token() ?>',
          'select_month': month,
          'vender_id': vender_id,
        },
      success: function(disabledDates) {
        rome(inline_cal, {
          time: false,
          min: new Date(),
          dateValidator: rome.val.except(disabledDates)
        }).on('data', function(value) {
          // Your additional code here
          var min = document.querySelector('.meeting-time button.active').getAttribute('data-min');

          time_select(min, '{{$vender_id}}');

          
        });
      },
      error: function(xhr, status, error) {
        console.error('Error fetching disabled dates:', error);
      }
    });
  }

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
<script>
  // Use jQuery document ready
  $(document).ready(function() {
    // Find the first visible button
    var visibleButton = $('.button_show:not(.d-none)').eq(0);

    if (visibleButton.length > 0) {
      // Add the active class directly to the first visible button
      visibleButton.addClass('active');

      // Get the data-min attribute of the active button
      var min = visibleButton.attr('data-min');

      // Call the time_select function with the obtained data-min value and user ID
      time_select(min, '{{$vender_id}}');
    }
  });
</script>

@endsection