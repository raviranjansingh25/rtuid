@extends('frontend.layout.layout2')
@section('content')
<style>
  .meeting-time1 button {
    width: 100%;
    text-align: center;
    padding: 10px;
    color: #00C2C5;
    font-weight: 500;
    font-size: 15px;
    background: #FFFFFF;
    border: 1px solid #00E4B9;
    border-radius: 8px;
  }

  .meeting-time1 button.active {
    color: #FFFFFF;
    background: #00C2C5;
  }

  .button_not_pay {
    display: none;
  }
  .inpt_dis {
    display: none;
  }

  .discount_div{
    display: none;
  }

  .swal-button--confirm {
    background: transparent linear-gradient(292deg, #00D2D9 0%, #2CFDB2 100%);
  }
</style>
<link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/style.css" />


<section class="practitioner-profle-section py-4 bg-color">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-3 col-lg-4">
        <div class="book-appointment-dr">
          <div class="book-profile-img">
            <img class="profile-b" src="{{isset($user->profile)?url($user->profile):url('/public/vender.png')}}" alt="">
            <div>
              <span class="sp-clr">{{$user->name}} {{$user->last_name}}</span>
              <h3 class="">{{$user->category_name}} </h3>
              <p><img src="{{url('/public/frontend/')}}/assets/images/like.svg" alt=""> {{$user->ratting}}%</p>

            </div>
          </div>

          <div class="prices bg-white p-0">
            <h3 class="Con-info">Consultation Information</h3>
            <h5 class="mb-3">Time Zone
              <span>{{$user->timezone}}</span>
            </h5>
            <h6><img class="me-2" src="{{url('/public/frontend/')}}/assets/images/Pin.svg" alt=""> {{$user->address}}</h6>
            <div class="my-3 lange d-flex gap-2 align-items-start">
              <img class="mt-2" src="{{url('/public/frontend/')}}/assets/images/world.svg" alt="">
              <div>
                <p>Languages spoken by <span>{{$user->name}}</span> </p>
                @php
                $language = explode(",", $user->language);
                $lang_data = \App\Models\Language::whereIn('id',$language)->get();
                @endphp
                <h3>
                  @forelse($lang_data as $lang)
                  {{$lang->language}}
                  @if(!$loop->last)
                  ,
                  @endif
                  @empty
                  N/A
                  @endforelse
                </h3>
              </div>
            </div>
            <h3 class="box-title">Consult Prices</h3>
            <ul>
              @foreach($user['get_consult'] as $const)
              <li>{{$const->consult_name}}: <span>${{$const->consult_price}}</span></li>
              @endforeach
              @foreach($user['get_package'] as $const)

              <li>{{$const->title}} {{$const->package_min_name}}: <span>${{$const->price}}</span></li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>

      <div class="col-xl-9 col-lg-8">
        <div class="row">
          <div class="col-md-6">
            <a href="javascript:history.back()" class="align-items-center gap-2"><img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/back-errow.svg" alt=""> Back to Results</a>
            <div class="celender-section mt-4">
              @if(count($my_package)>0)
              <h3>Package</h3>
              <div class="meeting-time1">
                @foreach($my_package as $pac)
                <button onclick="select_package({{$pac->package_min}},{{$user->id}},{{$pac->id}})" data-pacmin="{{$pac->package_min}}">{{$pac->package_name}} ({{$pac->package_min_name}})</button>
                @endforeach
              </div>
              @endif

              <h3 class="mt-3">Availability</h3>
              <div class="meeting-time">
                <button onclick="time_select('15',{{$user->id}})" data-min="15" id="btnFifteenMin" class="button_show <?php echo in_array(15, $allowedDurations) ? '' : 'd-none'; ?>">15 Min</button>
                <button onclick="time_select('30',{{$user->id}})" data-min="30" id="btnThirtyMin" class="button_show <?php echo in_array(30, $allowedDurations) ? '' : 'd-none'; ?>">30 Min</button>
                <button onclick="time_select('45',{{$user->id}})" data-min="45" id="btnFortyfiveMin" class="button_show <?php echo in_array(45, $allowedDurations) ? '' : 'd-none'; ?>">45 Min</button>
                <button onclick="time_select('60',{{$user->id}})" data-min="60" id="btnOneHour" class="button_show <?php echo in_array(60, $allowedDurations) ? '' : 'd-none'; ?>">1 Hour</button>
                <button onclick="time_select('90',{{$user->id}})" data-min="90" id="btnOneAndHalfHour" class="button_show <?php echo in_array(90, $allowedDurations) ? '' : 'd-none'; ?>">1.5 Hour</button>
              </div>
              <form action="#" class="open-celndr">
                <div id="inline_cal"></div>
              </form>
              <div class="actvty-type">
                <div class="row">
                  
                  <div class="col-md-6">
                    <p class="avalble">Indicate for not available </p>
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

                </div>
              </div>
              <div class="action-btn-meeting">

                <div class="button_pay">

                  <form id="bookingForm" action="{{ route('paylinksession') }}" method="post">
                    @csrf
                    <input type="hidden" name="month" class="month">
                    <input type="hidden" name="date" class="date">
                    <input type="hidden" name="session_id" class="session_id">
                    <input type="hidden" name="start_time" class="start_time">
                    <input type="hidden" name="end_time" class="end_time">
                    <input type="hidden" name="discount_code" class="discount_code">

                    <div class="col-md-12 discount_div">
                      <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="signature" value="Yes" id="flexCheckDefault_0" onclick="discount_inbox(this.checked)">
                        <label class="form-check-label" for="flexCheckDefault_0">
                          Use coupon code
                        </label>
                      </div>
                    </div>

                    <div class="form-group inpt_dis">
                      <input type="text" id="usr_code" class="form-control">
                    </div>
                    <button type="button" class="btn-primary w-100 mt-3 text-center" onclick="booking_now()">Book & Pay Now</button>
                  </form>
                </div>
                <div class="button_not_pay">
                  <form action="{{route('buy_package_session')}}" method="post">
                    @csrf
                    <input type="hidden" name="month" class="month">
                    <input type="hidden" name="date" class="date">
                    <input type="hidden" name="session_id" class="session_id">
                    <input type="hidden" name="start_time" class="start_time">
                    <input type="hidden" name="end_time" class="end_time">
                    <input type="hidden" name="package_id" class="package_id">

                    <button onclick="booking_now()" class="btn-primary w-100 mt-3 text-center">Book Now</button>
                  </form>
                </div>


              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Add your modal markup -->


<!-- Jquery needed -->
<script src="{{url('/public/frontend/')}}/assets/js/jquery.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.min.js"></script>
<!-- <script src="{{url('/public/frontend/')}}/assets/js/wow.min.js"></script> -->
<script src="{{url('/public/frontend/')}}/assets/js/rome.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
  function set_discount(coupon_code){
    var dis_code = coupon_code;
    $.ajax({
      url: "{{ url('/chek_discount') }}",
      datatType: 'json',
      data: {
        '_token': '<?php echo csrf_token() ?>',
        'discount_code': dis_code,
      },
      success: function(res) {
        if (res.status == 1) {
          $('.discount_code').val(dis_code);
          // document.getElementById('bookingForm').submit();
          $('#usr_code').val('');
          booking_now();
        } else{
            swal({
              title: "Oops...",
              text: 'Coupon Code not avalable',
              icon: "error",
              buttons: false,
              timer: 3000
            })
          }
      }
    });
  }

  function discount_inbox(checked) {
      if (checked) {
          $('.inpt_dis').show();
      } else {
          $('.inpt_dis').hide();
      }
  }
</script>
<script>
  function select_package(packageMin, vender_id, pac_id) {
    // Remove 'active' class from all package buttons
    var packageButtons = document.querySelectorAll('.meeting-time button');
    packageButtons.forEach(function(button) {
      button.classList.remove('active');
    });

    // Add 'active' class to the clicked package button
    var clickedButton = document.querySelector('[data-pacmin="' + packageMin + '"]');
    if (clickedButton) {
      clickedButton.classList.add('active');
    }

    // Show/hide meeting time buttons based on the selected package's duration
    var meetingTimeButtons = document.querySelectorAll('.meeting-time button[data-min]');
    meetingTimeButtons.forEach(function(timeButton) {
      var min = timeButton.getAttribute('data-min');
      var isVisible = min == packageMin;

      timeButton.style.display = isVisible ? 'inline-block' : 'none';
      timeButton.classList.toggle('active', isVisible);

      if (isVisible) {
        time_select(packageMin, vender_id);
      }
    });

    $(".package_id").val(clickedButton ? pac_id : '');
    var buttonPay = document.querySelector('.button_pay');
    var buttonNotPay = document.querySelector('.button_not_pay');
    buttonPay.style.display = clickedButton ? 'none' : 'block';
    buttonNotPay.style.display = clickedButton ? 'block' : 'none';
  }
</script>

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
          $('.discount_div').show();
          addButtonEvent()
        } else{
          $('.time_data').html('');
          $('.discount_div').hide();
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
    var coupon_code = $('#usr_code').val();
    if(coupon_code!=''){
      set_discount(coupon_code);
    }


    event.preventDefault();
    var buttonElement = document.querySelector('.calendar-button.active');
    if(buttonElement){
      if (buttonElement) {
        var dataSecIdValue = buttonElement.getAttribute('data-sec-id');
        var StartTimeValue = buttonElement.getAttribute('data-start');
        var StartTimeZoneValue = buttonElement.getAttribute('data-timezonestart');
        
        var EndTimeValue = buttonElement.getAttribute('data-end');
        var timezone = "{{isset($user_p->user_timezone)?$user_p->user_timezone:'Australia/Sydney'}}";
      } else {
        swal({
          icon: 'error',
          title: 'Oops...',
          text: 'Please select Available Time Slots',
        })

      }
      var date = $('.rd-day-body.rd-day-selected').html();
      var month = $('.rd-month-label').html();

      $(".session_id").val(dataSecIdValue);
      $(".month").val(month);
      $(".date").val(date);
      $(".start_time").val(StartTimeValue);
      $(".end_time").val(EndTimeValue);

    

      swal({

        text: "You are booking in for " + date + " " + month + " at " + StartTimeZoneValue + " (" + timezone + "). If this is correct, please click ok.",
        icon: false,
        buttons: true,
        successMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          document.getElementById('bookingForm').submit();
          // $('#discount').modal('show');
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
  }else{
    swal({
        icon: 'error',
        title: 'Oops...',
        text: 'Not selected any Available Time Slots',
      })
  }




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
  // Get references to the buttons
  const btnOneHour = document.getElementById('btnOneHour');
  const btnOneAndHalfHour = document.getElementById('btnOneAndHalfHour');
  const btnFifteenMin = document.getElementById('btnFifteenMin');
  const btnThirtyMin = document.getElementById('btnThirtyMin');
  const btnFortyfiveMin = document.getElementById('btnFortyfiveMin');

  // Add click event listeners to the buttons
  btnOneHour.addEventListener('click', function() {
    handleButtonClick('1 Hour');
  });

  btnOneAndHalfHour.addEventListener('click', function() {
    handleButtonClick('1.5 Hour');
  });

  btnFifteenMin.addEventListener('click', function() {
    handleButtonClick('15 Min');
  });

  btnThirtyMin.addEventListener('click', function() {
    handleButtonClick('30 Min');
  });

  btnFortyfiveMin.addEventListener('click', function() {
    handleButtonClick('45 Min');
  });

  // Function to handle button click
  function handleButtonClick(selectedTime) {
    // Remove 'active' class from all buttons
    btnOneHour.classList.remove('active');
    btnOneAndHalfHour.classList.remove('active');
    btnFifteenMin.classList.remove('active');
    btnThirtyMin.classList.remove('active');
    btnFortyfiveMin.classList.remove('active');

    // Add 'active' class to the clicked button
    event.target.classList.add('active');

    // Perform additional actions based on the selected time
    console.log('Selected Time:', selectedTime);
  }
</script>
<script>
  $(".h-search-btn").click(function() {
    $(".h-srch").toggleClass("slide");
  });
  

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
    var vender_id = <?php echo $user->id ?>;

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
          
          dateValidator: function (d) {
        // Combine rome.val.except(disabledDates) and rome.val.after(new Date())
        // return rome.val.after(new Date())(d) && rome.val.except(disabledDates)(d);
        return rome.val.except(disabledDates)(d);
    }
        }).on('data', function(value) {
          // Your additional code here
          var min = document.querySelector('.meeting-time button.active').getAttribute('data-min');

          time_select(min, '{{$user->id}}');

          
        });
      },
      error: function(xhr, status, error) {
        console.error('Error fetching disabled dates:', error);
      }
    });
  }



  // new WOW().init();

  $("#list-view-1").on('click', function(e) {
    $("#grid-view-1").removeClass("list-view");
    $("#list-view-1").addClass("list-view");
    $(".directory-list").removeClass("gride-lst-view");
  });
  $("#grid-view-1").on('click', function(e) {
    $("#grid-view-1").addClass("list-view");
    $(".directory-list").addClass("gride-lst-view");
    $("#list-view-1").removeClass("list-view");
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
      time_select(min, '{{$user->id}}');
    }
  });
</script>
@endsection