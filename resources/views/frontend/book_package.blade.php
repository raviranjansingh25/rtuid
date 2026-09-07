@extends('frontend.layout.layout2')
@section('content')


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
              <span>{{isset($user->timezone)?$user->timezone:'N/A'}}</span>
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
              @if(count($user['get_consult'])>0 || count($user['get_package'])>0)
              @foreach($user['get_consult'] as $const)
              <li>{{$const->consult_name}}: <span>${{$const->consult_price}}</span></li>
              @endforeach
              @foreach($user['get_package'] as $const)

              <li>{{$const->title}} ({{$const->package_min_name}}): <span>${{$const->price}}</span></li>
              @endforeach
              @else
              <h3 class="box-title">N/A</h3>
              @endif
            </ul>
          </div>
        </div>
      </div>
      <div class="col-xl-9 col-lg-8">
        <div class="row">

          <div class="col-xl-6 col-md-12">
            <a href="javascript:history.back()" class="align-items-center gap-2"><img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/back-errow.svg" alt=""> Back to Results</a>
            <div class="celender-section mt-4 h-auto">
              <h3 class="text-start">Packages</h3>
              <div class=" meeting-time d-block">
                <div class="row">
                  @foreach($package as $key=>$pac)
                  <?php
                  if ($key == 0) {
                    $active = 'active';
                  } else {
                    $active = '';
                  }
                  ?>
                  <div class="col-md-6 mb-3">
                    <button class="calendar-button {{$active}}" data-start="{{ $pac->start_time }}" data-sec-id="{{ $pac->id }}" data-end="{{ $pac->end_time }}">{{$pac->title}} ({{$pac->package_min_name}})</button>
                  </div>
                  @endforeach
                </div>
              </div>
              <div class="mt-5">

                <form action="{{route('paylinkpackage')}}" method="post">
                  @csrf

                  <input type="hidden" name="package_id" id="package_id">

                  <button onclick="booking_package()" class="btn-primary w-100 mt-3 text-center">Purchase Now</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>




<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="./images/close.svg" alt=""></button>
        <div class="privacy-content">
          <h2>Privacy Policy</h2>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text for purpose of using now you can use this for any time and any where. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy.
            <br><br>
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text for purpose of using now you can use this for any time and any where. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy.

          </p>
          <h3>Lorem have areas</h3>
          <ul class="inclds mt-3">
            <li>Lorem Ipsum is simply dummy text of the printing.</li>
            <li>Nutritionist have areas</li>
            <li>Lorem Ipsum is simply dummy text of the printing.</li>
            <li>Make a type specimen book. Lorem Ipsum is simply dummy.</li>
            <li>Lorem Ipsum is simply dummy text of the printing.</li>
            <li>Nutritionist have areas </li>
            <li>Lorem Ipsum is simply dummy text of the printing.</li>
          </ul>
          <div>
            <label class="d-block mb-3" for=""><input type="checkbox"> I agree</label>
            <button class="btn-primary px-5">Confirm</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function booking_package() {
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

    $("#package_id").val(dataSecIdValue);

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

@endsection