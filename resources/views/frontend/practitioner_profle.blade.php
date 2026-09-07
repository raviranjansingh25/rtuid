@extends('frontend.layout.layout2')
@section('content')


<section class="practitioner-profle-section py-4 bg-color">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-3 col-lg-4">
        <div class="profile-dr">
          <div class="profile-img">
            <img src="{{isset($user->profile)?url($user->profile):url('/public/vender.png')}}" alt="">
          </div>
          <?php
          if ($user->gender == 1) {
            $sex = 'Male';
          } elseif ($user->gender == 2) {
            $sex = 'Female';
          } elseif ($user->gender == 3) {
            $sex = 'Other';
          } else {
            $sex = 'N/A';
          }
          ?>
          <h3 class="d-flex align-items-center gap-2 justify-content-center">{{$user->name}} {{$user->last_name}}, {{$sex}} <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/varify.svg" alt=""></h3>
          <!-- <a href="#">{{$user->email}}</a>
          <a href="#">{{$user->mobile}}</a> -->

          <div class="detail-dr">
            <h4><span>{{$const}} consults</span> performed through
              telimed</h4>
            <h5><span class="d-block mb-2">Qualifications</span>
              @foreach($user['multi_qulafication'] as $qua)
                {{isset($qua['get_quali']->title)?$qua['get_quali']->title:'N/A'}} in {{isset($qua->year_graduated)?$qua->year_graduated:'N/A'}} <br>
              @endforeach
              <!-- MS in 2005 -->
            </h5>
            <h4><span>{{isset($user->experience)?$user->experience:'N/A'}} </span> years of experience</h4>
          </div>
        </div>
      </div>
      <div class="col-xl-9 col-lg-8">
        <a href="javascript:history.back()" class="align-items-center gap-2"><img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/back-errow.svg" alt=""> Back to Results</a>
        <div class="dtl-dtl">
          <div class="row">
            <div class="{{isset($user->video)?'col-lg-8':'col-lg-12'}} {{isset($user->video)?'col-md-6':'col-lg-12'}}">
              <div class="profile-dtl">
                <h3><span>Bio</span>
                <?php echo nl2br(htmlspecialchars($user->bio)); ?>
                </h3>
                @if(!empty($user->specialities))
                @php
                $specialities = explode(",", $user->specialities);
                $spe_data = \App\Models\Specialities::whereIn('id',$specialities)->get();

                @endphp
                <div class="intresres">
                  <h4>Specialities</h4>
                  @foreach($spe_data as $spe)
                  <a href="{{ route('practitioners', ['specialities' => $spe->slug]) }}">
                    {{$spe->title}}

                  </a>
                  @endforeach

                </div>
                @endif


                @if(!empty($user->areas_of_intresres))
                @php
                $areas_of_intresres = explode(",", $user->areas_of_intresres);
                $tag_data = \App\Models\AriaIntrest::whereIn('id',$areas_of_intresres)->get();

                @endphp
                <div class="intresres">
                  <h4>Areas of interests</h4>
                  @foreach($tag_data as $tag)
                  <a href="{{ route('practitioners', ['tag' => $tag->slug]) }}">
                    {{$tag->title}}

                  </a>
                  @endforeach

                </div>
                @endif




                <div class="lange d-flex gap-2 align-items-start">
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
                <div class="row">

                  <div class="col-md-12 col-lg-8 col-xl-5">
                    <h3 class="box-title">Consult Prices</h3>
                    <div class="prices">
                      <ul>
                        @if(count($user['get_consult'])>0 || count($user['get_package'])>0)
                        
                        @foreach($const_data as $const)
                        <li>{{$const->consult_name2}}: <span>${{$const->consult_price}}</span></li>
                        @endforeach
                        @foreach($user['get_package'] as $const)

                        <li>{{$const->title}} {{$const->package_min_name}}: <span>${{$const->price}}</span></li>
                        @endforeach
                        @else
                        <h3 class="box-title">N/A</h3>
                        @endif
                      </ul>
                    </div>
                  </div>

                  <div class=" col-md-12 col-lg-8 col-xl-5">
                    <h3 class="box-title">Booking Information</h3>
                    <div class="prices">
                      <h5>Time Zone
                        <span>{{isset($user->timezone)?$user->timezone:'N/A'}}</span>
                      </h5>
                      <h6><img class="me-2" src="{{url('/public/frontend/')}}/assets/images/Pin.svg" alt=""> {{$user->address}}</h6>
                    </div>
                  </div>

                </div>
                @if(!empty(Auth::guard('web')->user()))

                @if(count($user['get_consult'])>0 && count($user['get_availabilities'])>0)
                <div class="lange d-flex gap-2 align-items-start">
                  <a href="{{url('/booking/'.get_encrypted_value($user->id, true))}}" class="btn-primary">Book a consultation with {{$user->name}} <img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/white-right-arrow.svg" alt=""></a>
                  @if(count($user['get_package']) >0)
                  <a href="{{url('/book-package/'.get_encrypted_value($user->id, true))}}" class="btn-primary  text-center">Purchase Package<img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/white-right-arrow.svg" alt=""></a>
                  @endif
                </div>

                @endif

                @else
                <button onclick="login_model()" class="btn-primary">Book a consultation with {{$user->name}} <img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/white-right-arrow.svg" alt=""></button>

                @if(count($user['get_package']) >0)
                <button onclick="login_model()" class="btn-primary">Purchase Package<img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/white-right-arrow.svg" alt=""></button>
                @endif
                @endif
              </div>
            </div>
            @if(!empty($user->video))
            <div class="col-lg-4 col-md-6">
              <!-- <div class="pr-video">
                <img src="{{url('/public/frontend/')}}/assets/images/video-thum.png" alt="">
                <img class="ply-btn" src="{{url('/public/frontend/')}}/assets/images/play-button.svg" alt="">
              </div> -->
              <div class="practitioner-profle-video">
                <video class="pr-video" controls banner="{{url('/public/frontend/')}}/assets/images/video-thum.png">
                  <source src="{{isset($user->video)?url($user->video):''}}" type="video/mp4">
                </video>
              </div>
            </div>
            @endif
          </div>
        </div>

        <div class="pr-courses-programs">
          <h3>Courses and Programs offered by <span>{{$user->name}}</span></h3>
          <div class="row">
            @foreach($courses as $course)
            <div class="col-lg-3 col-md-6">
              <div class="course-box">
                <div class="course-img">
                  <img src="{{isset($course->image)?url($course->image):url('public/noimage.png')}}" alt="">
                </div>
                <div class="course-box-dtl">
                  <h3>{{$course->title}}</h3>
                  <p>{{$course->duration}} - $ {{$course->price}}</p>
                  <a href="{{url('/programs-detail/'.$course->slug)}}" class="any-button"><span>Learn More <img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/button-errow.svg" alt=""></span></a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  $(".h-search-btn").click(function() {
    $(".h-srch").toggleClass("slide");
  });
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
    var buttons = $('.button_show');
    var firstButton = buttons.eq(0);

    // Check if the first button should be shown
    if (firstButton.hasClass('d-none')) {
      // If the first button is hidden, find the next visible button
      var visibleButton = $('.button_show:not(.d-none)').eq(0);
      if (visibleButton.length > 0) {
        // Add the active class directly to the first visible button
        visibleButton.addClass('active');

        // Get the data-min attribute of the active button
        var min = $('.meeting-time button.active').attr('data-min');

        // Call the time_select function with the obtained data-min value and user ID
        time_select(min, '{{$user->id}}');
      }
    }
  });
</script>
@endsection