@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')
<style>
  .prices {
    padding: 20px;
    background: #F1F9FE;
    border-radius: 14px;
    margin-bottom: 20px;
    height: inherit;
    word-break: break-all;
  }

  .pr-video img {
    width: 100%;
    height: inherit;
    object-fit: cover;
  }
</style>
<div class="col-md-9">
  <div class="row">
    <div class="col-xl-3 col-lg-12">
      <div class="profile-dr">
        <h2 class="complete-profle-add"><span class="sp-clr">{{$complite}}%</span> Complete Profile</h2>
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

        <h3 class="d-flex align-items-center gap-2 justify-content-center">{{$user->name}}{{$user->middle_name}} {{$user->last_name}}, {{$sex}}
          @if($user->status == 1)
          <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/varify.svg" alt="">
          @endif
        </h3>
        <a href="#">{{$user->email}}</a>
        <a href="#">{{isset($user->country_code)?'+'.$user->country_code:''}} {{$user->mobile}}</a>
        <p>D.O.B.: {{ date('d-m-Y', strtotime($user->dob)) }}</p>
        <div class="detail-dr">
          <h4><span>{{$countsession}} consults</span> performed through
            telimed</h4>
          <h5><span class="d-block mb-2">Qualifications</span>
            {{isset($user['get_quali']->title)?$user['get_quali']->title:'N/A'}} in {{isset($user['get_detail']->year_graduated)?$user['get_detail']->year_graduated:'N/A'}}
            <h4><span>{{isset($user->experience)?$user->experience:'N/A'}}y </span> Experience</h4>
        </div>
        <div class="profile-action-btn text-center">
          <a href="{{route('vender_profile')}}" class="btn-primary mb-2">Edit Profile</a>
          <form method="post" action="{{route('account_update')}}">
            @csrf
            @if(!empty(Auth::guard('vender')->user()->stripe_account_id))

            <button type="submit" class="btn-primary">Update Setting</button>
            @else

            <button type="submit" class="btn-primary">Setting</button>
            @endif
          </form>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-lg-12">

      <div class="dtl-dtl">

        <div class="profile-dtl">
          <h3><span>Bio</span>
            {!!$user->bio!!}
          </h3>
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
                {{$lang->language}},
                @empty
                N/A
                @endforelse
              </h3>
            </div>

          </div>
          <div class="prices">
            <h3 class="box-title">Consult Prices</h3>
            <ul>
              @if(count($user['get_consult'])>0 || count($user['get_package'])>0)
              @foreach($user['get_consult'] as $const)
              <li>{{$const->consult_name}}: <span>${{$const->consult_price}}</span></li>
              @endforeach
              @foreach($user['get_package'] as $const)

              <li>{{$const->title}}: <span>${{$const->price}}</span></li>
              @endforeach
              @else
              <h3 class="box-title">N/A</h3>
              @endif
            </ul>
          </div>
          <h3 class="box-title">Booking Information</h3>
          <div class="row">
            <div class="col-md-6">
              <div class="prices bg-white p-0 m-0">
                <h5>Time Zone
                  <span>{{isset($user->timezone)?$user->timezone:'N/A'}}</span>
                </h5>

              </div>
            </div>
            <div class="col-md-6">
              <div class="prices bg-white p-0 m-0 d-flex">
              <div>
              <img class="me-2" src="{{url('/public/frontend/')}}/assets/images/Pin.svg" alt="">
              </div>
                <div>
                <h6 class="mt-1">{{$user->address}}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-12 col-md-12">
      <div class="all-consultations-date">
        <h3 class="text-start mt-0">Promotional Video</h3>

       
        <video class="w-100" controls>
          <source src="{{isset($user->video)?url($user->video):''}}" type="video/mp4">
        </video>
     

        <h3>All Consultations</h3>
        <form action="#" class="open-celndr dashboard-select-date">
          <div id="inline_cal" data-rome-id="0"></div>
        </form>
        <a href="dashboard-calender.html" class="btn-primary w-100 mt-3">View all Consultations</a>
      </div>
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