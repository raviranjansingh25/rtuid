@extends('frontend.layout.layout2')
@section('content')
<style>
  .tag_cat {
    background: #FFFFFF;
    /* Background color */
    background-repeat: no-repeat;
    /* Background repeat */
    padding: 10px;
    /* Padding */
    border-radius: 10px;
    /* Border radius */
    font-size: 15px;
    /* Font size */
    display: inline-block;
    /* Display as inline block */
    margin-right: 10px;
    /* Right margin */
    margin-bottom: 10px;
    /* Bottom margin */
  }

  /* .form-check-label {
    white-space: nowrap;
  } */

  .filter-chk li label {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
  }
  .filter-chk li label:hover {
      white-space: inherit;
      overflow: inherit;
      text-overflow: inherit;
  }
</style>


<section class="directory-section">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-3 col-lg-4">
        <div class="search-filter">
          <h2>Search Filters</h2>
          <form action="{{route('practitioners')}}" class="cmn-frm">

            <div class="form-group">
              <input type="text" name="name_tag" id="" placeholder="Who specialises in">
              <button type="submit"><img class="img-fluid input-right-icon fltrt" src="{{url('/public/frontend/')}}/assets/images/h-search.svg" alt=""></button>

            </div>
          </form>
          
          <div class="accordion search-accrodn" id="accordionExample">

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Practitioner Type
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                <div class="accordion-body">
                  <ul class="filter-chk">
                    @foreach($category as $key=>$cat)
                    <li>
                      <div class="form-check">
                        <input class="form-check-input " type="checkbox" name="category" value="{{$cat->id}}" id="flexCheckDefault_{{$key}}" onclick="filter()">
                        <label class="form-check-label" for="flexCheckDefault_{{$key}}">
                          {{$cat->search_title}}
                        </label>
                      </div>
                    </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Consultation Price
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                <div class="accordion-body">
                  <div class="usp-price">
                    <h3>Initial Consultation Price</h3>
                    <div class="range-slider">
                      <input class="range-slider__range init_price_class" type="range" value="{{$init_price_max}}" min="{{$init_price_min}}" max="{{$init_price_max}}" >
                      <div class="min-set">
                        <div>$<span class="range-slider__value init_price">{{$init_price_min}}</span></div>
                        <p>${{$init_price_max}}</p>
                      </div>
                    </div>
                  </div>

                  <div class="usp-price">
                    <h3>Follow Up Consultation Price</h3>
                    <div class="range-slider">
                      <input class="range-slider__range follow_price_class" type="range" value="{{$follow_price_max}}" min="{{$follow_price_min}}" max="{{$follow_price_max}}" >
                      <div class="min-set">
                        <div>$<span class="range-slider__value follow_price">{{$follow_price_min}}</span></div>
                        <p>${{$follow_price_max}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne2">

                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne1">
                  Specialities
                </button>
              </h2>
              <div id="collapseOne1" class="accordion-collapse collapse" aria-labelledby="headingOne1">
                <div class="accordion-body">
                  <ul class="filter-chk">
                    @foreach($specialities as $keyspe=>$spe)
                    <li>
                      <div class="form-check">
                        <input class="form-check-input " type="checkbox" name="specialities" value="{{$spe->id}}" id="flexCheckDefault_{{$keyspe}}" onclick="filter()">
                        <label class="form-check-label" for="flexCheckDefault_{{$keyspe}}">
                          {{$spe->title}}
                        </label>
                      </div>
                    </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne2">

                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne12" aria-expanded="false" aria-controls="collapseOne12">
                  Areas of interest
                </button>
              </h2>
              <div id="collapseOne12" class="accordion-collapse collapse" aria-labelledby="headingOne1">
                <div class="accordion-body">
                  <ul class="filter-chk">
                    @foreach($aria as $keyari=>$aria_data)
                    <li>
                      <div class="form-check">
                        <input class="form-check-input " type="checkbox" name="aria" value="{{$aria_data->id}}" id="flexCheckDefault_{{$keyari}}" onclick="filter()">
                        <label class="form-check-label" for="flexCheckDefault_{{$keyari}}">
                          {{$aria_data->title}}
                        </label>
                      </div>
                    </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTworat">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTworat" aria-expanded="false" aria-controls="collapseTwo">
                  Feedback
                </button>
              </h2>
              <div id="collapseTworat" class="accordion-collapse collapse" aria-labelledby="headingTworat">
                <div class="accordion-body">
                  <div class="usp-price">

                    <div class="range-slider">
                      <input class="range-slider__range feedback_class" type="range" value="100" min="0" max="100" >
                      <div class="min-set">
                        <div><span class="range-slider__value ratting">0</span>%</div>
                        <p>100%</p>
                      </div>
                    </div>
                  </div>


                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                  Years of experience
                </button>
              </h2>
              <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour">
                <div class="accordion-body">
                  <ul class="filter-chk">
                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="experience" type="checkbox" onclick="filter()" value="0-1" id="Qualifications_1">
                        <label class="form-check-label" for="Qualifications_1">
                          0-1
                        </label>
                      </div>
                    </li>

                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="experience" type="checkbox" onclick="filter()" value="1-2" id="Qualifications_1">
                        <label class="form-check-label" for="Qualifications_1">
                          1-2
                        </label>
                      </div>
                    </li>

                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="experience" type="checkbox" onclick="filter()" value="2-3" id="Qualifications_1">
                        <label class="form-check-label" for="Qualifications_1">
                          2-3
                        </label>
                      </div>
                    </li>

                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="experience" type="checkbox" onclick="filter()" value="3-5" id="Qualifications_1">
                        <label class="form-check-label" for="Qualifications_1">
                          3-5
                        </label>
                      </div>
                    </li>

                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="experience" type="checkbox" onclick="filter()" value="5-10" id="Qualifications_1">
                        <label class="form-check-label" for="Qualifications_1">
                          5-10
                        </label>
                      </div>
                    </li>

                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="experience" type="checkbox" onclick="filter()" value="10-15" id="Qualifications_1">
                        <label class="form-check-label" for="Qualifications_1">
                          10-15
                        </label>
                      </div>
                    </li>

                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="experience" type="checkbox" onclick="filter()" value="15-100" id="Qualifications_1">
                        <label class="form-check-label" for="Qualifications_1">
                          15+
                        </label>
                      </div>
                    </li>


                  </ul>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingfive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefive" aria-expanded="false" aria-controls="collapsefive">
                  Language
                </button>
              </h2>
              <div id="collapsefive" class="accordion-collapse collapse" aria-labelledby="headingfive">
                <div class="accordion-body">
                  <ul class="filter-chk">

                    @foreach($language as $exp=>$lan_data)
                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="language" onclick="filter()" type="checkbox" value="{{$lan_data->id}}" id="language_{{$lan_data->id}}">
                        <label class="form-check-label" for="language_{{$lan_data->id}}">
                          {{$lan_data->language}}
                        </label>
                      </div>
                    </li>
                    @endforeach

                  </ul>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingfive1">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefive1" aria-expanded="false" aria-controls="collapsefive1">
                  Qualifications
                </button>
              </h2>
              <div id="collapsefive1" class="accordion-collapse collapse" aria-labelledby="headingfive1">
                <div class="accordion-body">
                  <ul class="filter-chk">

                    @foreach($experience as $exp=>$exp_data)
                    <li style="width: 98%;">
                      <div class="form-check">
                        <input class="form-check-input" name="qualifications" onclick="filter()" type="checkbox" value="{{$exp_data->id}}" id="Qualifications_{{$exp_data->id}}">
                        <label class="form-check-label" for="Qualifications_{{$exp_data->id}}">
                          {{$exp_data->title}}
                        </label>
                      </div>
                    </li>
                    @endforeach

                  </ul>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingsix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsesix" aria-expanded="false" aria-controls="collapsesix">
                  Time Zone
                </button>
              </h2>
              <div id="collapsesix" class="accordion-collapse collapse" aria-labelledby="headingsix">
                <div class="accordion-body">
                  <ul class="filter-chk">
                    @foreach($timezone as $time=>$zone)
                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="timezone" type="checkbox" value="{{$zone->timezone}}" id="time_zone_{{$time}}" onclick="filter()">
                        <label class="form-check-label" for="time_zone_{{$time}}">
                          {{$zone->timezone}}
                        </label>
                      </div>
                    </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingseven">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseseven" aria-expanded="false" aria-controls="collapseseven">
                  Length of Appointment
                </button>
              </h2>
              <div id="collapseseven" class="accordion-collapse collapse" aria-labelledby="headingseven">
                <div class="accordion-body">
                  <ul class="filter-chk">
                    @foreach($apport as $a_data)
                    <li>
                      <div class="form-check">
                        <input class="form-check-input" name="appointment" type="checkbox" onclick="filter()" value="{{$a_data->min}}" id="appointment_{{$a_data->min}}">
                        <label class="form-check-label" for="appointment1">
                          {{$a_data->title}}
                        </label>
                      </div>
                    </li>
                    @endforeach

                  </ul>
                </div>
              </div>
            </div>
            <!-- <div class="accordion-item">
                <h2 class="accordion-header" id="headingeight">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseeight" aria-expanded="false" aria-controls="collapseeight">
                    Health Insurance Cover
                  </button>
                </h2>
                <div id="collapseeight" class="accordion-collapse collapse" aria-labelledby="headingeight" data-bs-parent="#accordionExample">
                  <div class="accordion-body">

                  </div>
                </div>
              </div> -->

            <!-- <div class="chat-compy">
                <label for="">Complementary Chat <input class="mx-2" type="checkbox"> Yes</label>
              </div> -->
          </div>

        </div>
      </div>

      <div class="col-xl-9 col-lg-8">
        <div class="directory-practitioner-list ">
          <h3>Directory</h3>
          <div class="hdr-search">
            <div class="form-group w-100">
              <form action="{{route('practitioners')}}">
                <img class="input-left-icon" src="{{url('/public/frontend/')}}/assets/images/h-search.svg" alt="">
                <input type="text" name="name" value="{{$search}}" placeholder="Practitioner Name">

                @if(!empty($search))
                <a href="{{route('practitioners')}}" class="btn-primary input-right-icon">{{ $button }}</a>
                @else
                <button type="submit" class="btn-primary input-right-icon">{{ $button }}</button>
                @endif
              </form>

            </div>
            <div class="list-type">
              <button class="list-type-btn " id="list-view-1"><img src="{{url('/public/frontend/')}}/assets/images/list-view.svg" alt=""></button>
              <button class="list-type-btn list-view" id="grid-view-1"><img src="{{url('/public/frontend/')}}/assets/images/grid-view.svg" alt=""></button>
            </div>
          </div>

          <!-- <div class="categ_data">

            @if(!empty($search) || !empty($tag_search))
            <div class="search-tags">
              <h4>Search Results:</h4>

              @if(!empty($search))
              <a href="{{route('practitioners')}}">{{$search}} <img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/close-sr.svg" alt=""></a>
              @endif
              @if(!empty($tag_search))
              <a href="{{route('practitioners')}}">Intrest: {{$tag_search}} <img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/close-sr.svg" alt=""></a>
              @endif
            </div>
            @else
            <br>
            @endif
          </div> -->




          <div class="container" id="header_class"> <!-- Right Set -->
            <div class="directory-section-list-00">
              <div class="directory-section-list-box-1"> <!-- Left Set -->

                <span class="nav-link">Health Professional</span>

              </div>
              <div class="directory-section-list-box-2">

                <span class="nav-link">Price</span>

              </div>
              <div class="directory-section-list-box-3">

                <span class="nav-link">Favourite</span>

              </div>


              <div class="directory-section-list-box-4">
                <span class="nav-link text-end">Feedback</span>

              </div>


            </div>
          </div>

          <div class="directory-list list-type-man data container gride-lst-view">
            @forelse($user as $user_data)
            @php
            $price = App\Models\VenderConsultPrice::where('vender_id',$user_data->id)->get()->toArray();
            $conslt = App\Models\VenderConsultPrice::where('vender_id',$user_data->id)->count();
            $initconsult_prices = array_column($price, 'consult_price');
            if(count($price)>0){
            $init_price_min = min($initconsult_prices);
            $init_price_max = max($initconsult_prices);
            }else{
            $init_price_min = 0;
            $init_price_max = 0;
            }

            @endphp
            <div class="list-box">
              <div class="profile-d1">
                <div class="profile-di">
                  <a href="{{url('/practitioners-detail/'.get_encrypted_value($user_data->id, true))}}">
                    <img src="{{isset($user_data->profile)?url($user_data->profile):url('/public/vender.png')}}" alt="">
                    <h3>{{$user_data->name}} {{$user_data->last_name}} <span>{{$user_data->category_name}}</span></h3>
                  </a>
                </div>
              </div>
              <div class="price-limit-p1">
                <div class="price-limit">
                  @if($conslt>1)
                  <p>from ${{isset($init_price_min)?$init_price_min:0}} to ${{isset($init_price_max)?$init_price_max:0}} </p>
                  @else
                  ${{isset($init_price_min)?$init_price_min:0}}
                  @endif
                </div>
              </div>

              <div class="heart-icon">
                <span class="show_file_{{$user_data->id}}">
                  @if(Auth::user())
                  @php
                  $wish_product = App\Models\Wishlist::where(['user_id'=>Auth::user()->id,'doctor_id'=>$user_data->id])->first();
                  @endphp
                  @if(empty($wish_product))
                  <div class="like-dr" onclick="add_to_wishlist({{$user_data->id}})">
                    <img src="{{url('/public/frontend/')}}/assets/images/like-heart.svg" alt="">
                  </div>
                  @else
                  <div class="like-dr" onclick="remove_to_wishlist({{$user_data->id}})">
                    <img src="{{url('/public/frontend/')}}/assets/like-heart" alt="">
                  </div>
                  @endif
                  @else
                  <div class="like-dr" onclick="login_model()">
                    <img src="{{url('/public/frontend/')}}/assets/images/like-heart.svg" alt="">
                  </div>
                  @endif
                </span>
              </div>
              <div class="like-icon">
                <span>{{$user_data->total_review}}%<img src="{{url('/public/frontend/')}}/assets/images/like.svg" class="ms-2" alt=""></span>
              </div>
            </div>
            @empty
            No Results Found
            @endforelse


          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function toggle_Checkbox(element) {
    var category = element.getAttribute('data-category'); // Using getAttribute method

    $('input[name="category"][value="' + category + '"]').prop('checked', function(_, checked) {
      return !checked;
    });

    // Call filter function to perform any action you need when checkboxes change state
    filter();

  }
</script>
<script>
  document.getElementById("grid-view-1").onclick = function() {
    document.getElementById("header_class").style.display = "none";
  };

  document.getElementById("list-view-1").onclick = function() {
    document.getElementById("header_class").style.display = "block";
  };
</script>
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
  $('.feedback_class').on('input', function() {
      var ratting = $(this).val();
      $('.ratting').html(ratting);
      filter(ratting); // Call filter function with the updated value
  });
  $('.init_price_class').on('input', function() {
      var init_price = $(this).val();
      $('.init_price').html(init_price);
      filter('',init_price); // Call filter function with the updated value
  });
  $('.follow_price_class').on('input', function() {
      var follow_price = $(this).val();
      $('.follow_price').html(follow_price);
      filter('','',follow_price);// Call filter function with the updated value
  });

  function filter(ratting,init_price,follow_price) {
    var init_price = init_price;
    var follow_price = follow_price;
    
    var ratting = ratting;


    var checkedCategoryIds = $('input[name="category"]:checked').map(function() {
      return $(this).val();
    }).get();



    var checkedExperienceIds = $('input[name="experience"]:checked').map(function() {
      return $(this).val();
    }).get();

    var checkedTimezoneIds = $('input[name="timezone"]:checked').map(function() {
      return $(this).val();
    }).get();

    var checkedSpecialitiesIds = $('input[name="specialities"]:checked').map(function() {
      return $(this).val();
    }).get();
    var checkedAriaIds = $('input[name="aria"]:checked').map(function() {
      return $(this).val();
    }).get();
    var checkedLanguageIds = $('input[name="language"]:checked').map(function() {
      return $(this).val();
    }).get();

    var checkedAppointmentIds = $('input[name="appointment"]:checked').map(function() {
      return $(this).val();
    }).get();
    var checkedQualificationsIds = $('input[name="qualifications"]:checked').map(function() {
      return $(this).val();
    }).get();
    // alert(checkedSpecialitiesIds);
    $.ajax({
      url: "{{ url('/filter') }}",
      datatType: 'json',
      data: {
        '_token': '<?php echo csrf_token() ?>',
        'category_id': checkedCategoryIds,
        'ratting_id': ratting,
        'experience_id': checkedExperienceIds,
        'timezone_id': checkedTimezoneIds,
        'specialities_id': checkedSpecialitiesIds,
        'aria_id': checkedAriaIds,
        'language_id': checkedLanguageIds,
        'appointment_id': checkedAppointmentIds,
        'qualifications_id': checkedQualificationsIds,
        'init_price': init_price,
        'follow_price': follow_price,
      },

      success: function(res) {
        if (res.status == 1) {
          $('.data').html(res.data);
          // $('.categ_data').html(res.categ_data);
        } else {
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Somthing went wrong',
          })
        }
      }
    });
  }
</script>
@endsection