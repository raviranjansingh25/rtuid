@extends('frontend.layout.layout2')
@section('content')
@include('frontend.layout.vender_sidebar')
<div class="col-md-9">
  <div class="dashboard_content_s">
    <div class="row">
      <div class="col-xl-8 col-md-12 p-0">
        <div class="dashboard_content_s_in">
          <h2 class="login-dash-title">Upcoming Consultations <a href="{{route('my_patients')}}">View All Your Patients <img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/button-errow.svg" alt=""></a></h2>
          <div class="upcoming-cont">

            @forelse($currentbooking as $key=>$booking_data)
            @php

            $conv_id = App\Models\ChatUser::select('convenience_id')
            ->whereIn('user_id', [$booking_data->user_id, $booking_data->vender_id])
            ->groupBy('convenience_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('convenience_id')
            ->toArray();


            @endphp
            <div class="list-consultations">
              <div class="profile-di ">
                <a href="{{url('/patients-detail/'.get_encrypted_value($booking_data['get_patient']->id, true))}}">
                  <img src="{{isset($booking_data['get_patient']->profile)?url($booking_data['get_patient']->profile):url('/public/user.png')}}" alt="">

                  <h3>{{$booking_data['get_patient']->name}} </h3>
                </a>
              </div>
              <div class="countdown-time">
                <div id="countdown{{$key}}" data-time="{{$booking_data->booking_date}} {{$booking_data->start_time}}" data-conv_id="{{isset($conv_id[0])?$conv_id[0]:''}}">

                  <h6>start call in</h6>
                  <ul id="time{{$key}}">

                  </ul>
                </div>
              </div>
              <script>
                // coundown_data("{{$key}}");
                countdown_data("{{$key}}", "{{$user->user_timezone}}");
              </script>
              <div class="avlble-time" data-time="2024-04-16T12:00:00">
                <h4>{{date("h:i a", strtotime($booking_data->start_time))}} </h4>
                <p>{{ date('l, jS F', strtotime($booking_data->booking_date)) }}</p>
              </div>
            </div>

            @empty
            <div class="list-consultations">
              <div class="profile-di">
                No Upcoming Consultations
              </div>


            </div>
            @endforelse

          </div>

          <div class="telimed-modalities">
            <h3>Consultation Stats this Month</h3>
            <div class="row">
              <div class="col-xl-3 col-lg-4 col-md-4 col">
                <div class="status-month-box">
                  <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/thumps-received.svg" alt="">
                  <p>Thumps up received</p>
                  <h2>{{$like_count}}</h2>
                </div>
              </div>
              <div class="col-xl-3 col-lg-4 col-md-4 col">
                <div class="status-month-box">
                  <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/income.svg" alt="">
                  <p>Generated Income</p>
                  <h2>${{ number_format($income, 2) }}</h2>
                </div>
              </div>
              <div class="col-xl-6 col-lg-4 col-md-4 col">
                <div class="grapg_sec">
                  <p>Profile Clicked</p>
                  <canvas id="myChart1" height="100px"></canvas>
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 mt-4 col">
                <div class="grapg_sec">
                  <p>See Financial Reports</p>
                  <canvas id="myChart" height="100px"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="healthcare-perof gride-lst-view">
            <h2 class="login-dash-title">My Courses <a href="{{route('vender_my_course')}}">View All <img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/button-errow.svg" alt=""></a></h2>
            <div class="row">
              @forelse($course as $cors)
              <div class="col-lg-4 col-md-6">
                <div class="course-box">
                  <div class="course-img">
                    <img src="{{isset($cors->image)?url($cors->image):url('/public/noimage.png')}}" alt="">
                  </div>
                  <div class="course-box-dtl">
                    <h3>{{$cors->title}}</h3>
                    <p>{{$cors->duration}} - $ {{$cors->price}}</p>
                    <a href="{{ url('/practitioner_course_detail/' . $cors->slug) }}" class="any-button">
                    <span>Learn More
                      <img class="img-fluid ms-2" src="https://telimed.health/public/frontend/assets/images/button-errow.svg" alt="">
                    </span>
                  </a>
                  </div>
                  
                </div>
              </div>
              @empty
              <div class="course-create not-found-data">
                <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/not-found.svg" alt="">
                <h3>No courses yet</h3>
                <p>Upload and sell your course or program.</p>
                <a href="{{route('vender_create_course')}}" class="btn-primary px-5">Create Course</a>
              </div>
              @endforelse
            </div>


          </div>
        </div>


      </div>
      <div class="col-xl-4 col-md-12">
        <div class="all-consultations-date">
          <div class="completed-content">
            <p>You have completed <br>
              <span class="sp-clr">{{$countsession}}</span> Consultations through telimed
            </p>
          </div>
          <img src="{{url('/public/')}}/calendar.png" height="100px;" alt="">
          <h3>All Consultations</h3>
          <form action="#" class="open-celndr dashboard-select-date">
            <div id="inline_cal"></div>
          </form>
          <a href="{{route('vender_my_calendar')}}" class="btn-primary w-100 mt-3">View all Consultations</a>
        </div>
      </div>
    </div>
  </div>

</div>
</div>
</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="{{ asset('public/admin/') }}/assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="{{ asset('public/admin/') }}/assets/js/pages/saas-dashboard.init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
  var labels = @json($labels);
  var users = @json($data);

  const data = {
    labels: labels,
    datasets: [{
      label: 'Income',
      backgroundColor: ' rgb(0, 210, 217)',
      borderColor: 'rgb(255, 99, 132)',
      data: users,
    }]
  };

  const config = {
    type: 'bar',
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    },
  };

  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
</script>
<script type="text/javascript">
  var labels2 = @json($labels1);
  var users2 = @json($data1);

  const data2 = {
    labels: labels2,
    datasets: [{
      label: 'Clicked',
      backgroundColor: 'rgb(0, 210, 217)',
      borderColor: 'rgb(255, 99, 132)',
      data: users2,
    }]
  };

  const config2 = {
    type: 'bar',
    data: data2,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    },
  };

  const myChart2 = new Chart(
    document.getElementById('myChart1'),
    config2
  );
</script>
@endsection



<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.40/moment-timezone-with-data.min.js"></script>
<script>
  function convertToISOFormat(dateString) {
    var timezone = "{{$user->user_timezone}}";
    // Assuming dateString is in 'YYYY-MM-DD HH:mm' format and is in local time
    return moment.tz(dateString, timezone).format();
  }

  function countdown_data(data_id) {
    var countdownElement = document.getElementById("countdown" + data_id);
    var countdownTime = convertToISOFormat(countdownElement.getAttribute("data-time"));
    var conv_id = countdownElement.getAttribute("data-conv_id");
    var update = setInterval(function() {
      var now = new Date().getTime();
      var diff = new Date(countdownTime).getTime() - now;
      if (diff < 0) {
        clearInterval(update);
        var url = '/video/' + conv_id;
        document.getElementById("time" + data_id).innerHTML = '<a href="' + url + '" class="btn-primary">Join Now</a>';
        return;
      }
      var days = Math.floor(diff / (1000 * 60 * 60 * 24));
      var hrs = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((diff % (1000 * 60)) / 1000);
      document.getElementById("time" + data_id).innerHTML = "<li><span>" + days + "</span>Days</li><li><span>" + hrs + "</span>Hr</li><li><span>" + minutes + "</span>Min</li><li><span>" + seconds + "</span>Sec</li>";
    }, 1000);
  }
</script>