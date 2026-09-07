@extends('frontend.layout.layout2')
@section('content')
@include('frontend.layout.vender_sidebar')

<div class="col-md-12 col-lg-9 My-Patients-section-00">
  <h2 class="section-title d-md-flex justify-content-between align-items-center my-patient-grid">My Patients</h2>
  <div class="hdr-search">
    <div class="form-group w-100">
      <img class="input-left-icon" src="{{url('/public/frontend/')}}/assets/images/h-search.svg" alt="">
      <form action="{{route('my_patients')}}">
        <input type="text" name="search" id="searchInput" value="{{$search}}" class="search_val" placeholder="Patients Name">

      </form>
    </div>
    <div class="list-type">
      <button class="list-type-btn list-view" id="list-view-2"><img src="{{url('/public/frontend/')}}/assets/images/list-view.svg" alt=""></button>
      <!-- <button class="list-type-btn" id="grid-view-2"><img src="{{url('/public/frontend/')}}/assets/images/grid-view.svg" alt=""></button> -->
    </div>
  </div>
  <div class="table-responsive list-pt bg-white rounded p-4 mt-4">
    <table>
      <thead>
        <tr>
          <th>Patient Name</th>
          <th>Age</th>
          <th>Date of last consult</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="searchResults">
        @forelse($patients as $pas)
        <tr>
          <td>
            <div class="profile-di">
              <a href="{{url('/patients-detail/'.get_encrypted_value($pas['get_patient']->id, true))}}">
                <img src="{{isset($pas['get_patient']->profile)?url($pas['get_patient']->profile):url('/public/user.png')}}" alt="">
                <h3>{{$pas['get_patient']->name}} <span></span></h3>
              </a>
            </div>
          </td>
          @php

          $birthdate = new DateTime($pas['get_patient']->dob);
          $today = new DateTime();
          $age = $today->diff($birthdate)->y;
          @endphp
          <td>{{$age}} y</td>
          <td>{{ date('d M Y', strtotime($pas->booking_date)) }}</td>
          <td>
            <div class="form-lst">
              <button onclick="pre_form({{$pas['get_patient']->id}})"><img src="{{url('/public/frontend/')}}/assets/images/form.svg" alt=""> Preconsult form</button>
              <button onclick="ConsultNotes(1,{{$pas['get_patient']->id}})" type="button"><img src="{{url('/public/frontend/')}}/assets/images/form.svg" alt=""> Consult Notes</button>
              <button onclick="TreatmentPlan(1,{{$pas['get_patient']->id}})"><img src="{{url('/public/frontend/')}}/assets/images/form.svg" alt=""> Treatment Plan</button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4">
            <div class="profile-di">

              <h3>Patient not available</h3>

            </div>
          </td>

        </tr>
        @endforelse
      </tbody>
    </table>
  </div>


  <div class="patients-grid gride-lst-view">
    <div class="list-box">
      <div class="profile-di">
        <a href="patient-detail.html">
          <img src="{{url('/public/frontend/')}}/assets/images/team-1.png" alt="">
          <h3>Lisa Parker <span>Psychologist</span></h3>
        </a>
      </div>

      <div class="rating-dr cont-age-tst">
        <h6><span>Last consult:</span> 22 Oct 2023 </h6>
        <h6><span>Age:</span> 37 y </h6>
      </div>
      <div class="like-dr more-button" id="more-btn">
        <img src="{{url('/public/frontend/')}}/assets/images/more.svg" alt="">
      </div>
      <ul class="more-filds">
        <li>
          <button><img src="{{url('/public/frontend/')}}/assets/images/form.svg" alt=""> Preconsult form</button>

        </li>
        <li>
          <button data-bs-toggle="modal" href="#ConsultNotes" type="button"><img src="{{url('/public/frontend/')}}/assets/images/form.svg" alt=""> Consult Notes</button>

        </li>
        <li>

          <button onclick="TreatmentPlan()"><img src="{{url('/public/frontend/')}}/assets/images/form.svg" alt=""> Treatment Plan</button>
        </li>
      </ul>
    </div>


  </div>

</div>
</div>



</div>
</section>

<div class="modal fade my-patient-cons-form" id="pre_form" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
        <div class="rating-modal">
          <h2 class="modal-title text-center"> Preconsult form </h2>
          <div class="row pre_form">

          </div>


        </div>
      </div>
    </div>
  </div>
</div>

<script src="{{url('/public/frontend/')}}/assets/js/jquery.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.min.js"></script>
<!-- <script src="{{url('/public/frontend/')}}/assets/js/wow.min.js"></script> -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#searchInput').on('keyup', function() {
      var query = $(this).val();

      $.ajax({
        url: "{{ route('autocomplete') }}",
        method: 'GET',
        data: {
          query: query
        },
        success: function(response) {
          $("#searchResults").html(response);
          $("#searchResults").show();
          attachClickEvent();
        },
        error: function(xhr) {
          console.log(xhr.responseText);
        }
      });
    });
  });

  function attachClickEvent() {
    $('.name-label').click(function() {
      var name = $(this).data('name');
      name_tap(name);
    });
  }

  function name_tap(val) {
    $(".search_val").val(val);
    $("#searchResults").hide();

  }
</script>
<script>
  function pre_form(id) {


    $.ajax({
      url: "{{ route('get_consult_form') }}",
      type: 'GET',
      data: {
        'id': id,
      },
      beforeSend: function() {
        $("#preloader").show();
      },
      success: function(res) {
        $("#preloader").hide();

        if (res.status == 1) {
          $(".pre_form").html(res.pre_form);

          $('#pre_form').modal('show');
        } else {
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Pre consult form not available!',
          })
        }

      },
      error: function(error) {
        $("#preloader").hide();
        swal({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong!',
        })
      }
    });
  }
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

@endsection