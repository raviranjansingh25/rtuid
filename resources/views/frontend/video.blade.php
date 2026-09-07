@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/sidebar')
<style>
  .video-call {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 0;
    height: 100vh;
  }

  .video-call-frame {
    height: 70%;
    width: 50%;
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 0 20px #00c2c54b;
  }

  .video-call-frame .call-bg {
    width: 100%;
  }

  .my-video {
    position: absolute;
    top: 30px;
    right: 20px;
    width: 140px;
    height: 170px;
    border-radius: 10px;
    background-color: #fff;
    padding: 5px;
  }

  .my-video img,
  .my-video video {
    width: 100%;
    height: 100%;
  }

  .call-action-button {
    display: flex;
    align-items: center;
    gap: 15px;
    justify-content: center;
    position: absolute;
    width: 100%;
    left: 0;
    bottom: 20px;
  }

  .call-action-button button.other {
    width: 40px;
    position: relative;
    height: 40px;
  }

  .call-action-button button img {
    position: relative;
    z-index: 1;
  }

  .call-action-button button.other::after {
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    background-color: #ffffff87;
    position: absolute;
    content: "";
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    border-radius: 50px;
  }

  @media only screen and (max-width:992px) {
    .video-call-frame {
      height: 80%;
      width: 90%;
    }
  }


  .player-local {
    width: 140px;
    height: 170px;
  }

  #remote-playerlist {
    width: 100%;
    height: 100%;
    background-color: #000;
  }

  .player {
    width: 100%;
    height: 480px;

  }

  @media (max-width: 640px) {
    .player-local {
      width: 140px;
      height: 170px;
    }

    .player {
      width: 100%;
      height: 480px;
    }

    #remote-playerlist {
      width: 100%;
      height: 100%;;
      background-color: #000;
    }

  }
</style>



<div class="col-md-9">





  <section class="video-call" id="video-call">
    <div class="video-call-frame">

      <div id="remote-playerlist"></div>

      <div class="my-video player-local" id="local-player">

      </div>
      <div class="call-action-button">
        <button class="full-zoom" style="display:none; "><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/full-zoom.svg" alt=""></button>
        <button class="full-zoom-off" style="display:none; filter:invert(1);"><img class="img-fluid" src="{{url('/public/')}}/arrows-minimize.svg" alt=""></button>

        <button class="microphone_on" style="display:none; filter:invert(1);"><img class="img-fluid" src="{{url('/public/')}}/microphone.svg" alt=""></button>
        <button class="microphone_off" style="display:none; filter:invert(1);"><img class="img-fluid" src="{{url('/public/')}}/microphone-off.svg" alt=""></button>

        <button class="start-call" style=" filter:invert(1);"><img class="img-fluid" src="{{url('/public/')}}/phone-call.svg" alt=""></button>
        <button class="end-call" style="display:none;"><img class="img-fluid" src="{{url('/public/')}}/phone-off.svg" alt="" onclick="review_model({{$user->id}},1,1)"></button>

        <button class="camara_on" style="display:none; filter:invert(1);"><img class="img-fluid" src="{{url('/public/')}}/video.svg" alt=""></button>
        <button class="camara_off" style="display:none; filter:invert(1);"><img class="img-fluid" src="{{url('/public/')}}/video-off.svg" alt=""></button>

        <!--button class="other"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/setting.svg" alt=""></button-->
      </div>
    </div>
  </section>

  <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
        <div class="rating-modal">
          <h2 class="modal-title text-center">Give Rating</h2>
          <form id="review" method="post">
            @csrf
            <div action="" class="cmn-frm">
              <div class="form-group">
                <input type="text" name="review" placeholder="How did you find your session?">
                <input type="hidden" name="review_id" class="review_id">
                <input type="hidden" name="review_type" class="review_type">
              </div>
            </div>
            <div class="rating-like-dislike">
              <div class="course-rting">
                <input type="radio" name="ratting" value="1">
                <img src="{{url('/public/frontend/')}}/assets/images/like-rating.svg" alt="">
              </div>
              <div class="course-rting dis-like-rt">
                <input type="radio" name="ratting" value="2">
                <img src="{{url('/public/frontend/')}}/assets/images/like-rating.svg" alt="">
              </div>
            </div>
            <!-- <label id="ratting-error" class="error" for="ratting"></label> -->
            <button class="btn-primary w-100">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>



<script src="{{url('/public/frontend/')}}/assets/js/jquery.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.min.js"></script>
<!-- <script src="{{url('/public/frontend/')}}/assets/js/wow.min.js"></script> -->
<script src="{{url('/public/frontend/')}}/assets/js/rome.js"></script>
<script src="{{url('/public/')}}/AgoraRTC_N-4.19.3.js"></script>
<script>
  var options = {
    appid: '31283a4d9bad49faa774f551ea1c815d',
    channel: 'CHA{{$room_id}}',
    uid: null,
    token: null
  };



  $(document).on('click', '.start-call', async function() {
    await join();
    $('.full-zoom').show();
    $('.microphone_on').show();
    $('.end-call').show();
    $('.camara_on').show();

    $('.start-call').hide();

  });

  $(document).on('click', '.end-call', function() {
    leave();
    $('.full-zoom').hide();
    $('.microphone_on').hide();
    $('.microphone_off').hide();
    $('.end-call').hide();
    $('.camara_on').hide();
    $('.camara_off').hide();
    $('.start-call').show();
    // $('#exampleModalToggle').show();
    
  });

  $(document).on('click', '.microphone_on', async function() {
    var result = await microphone_on();
    $('.microphone_on').hide();
    $('.microphone_off').show();
  });

  $(document).on('click', '.microphone_off', async function() {
    var result = await microphone_off();
    $('.microphone_off').hide();
    $('.microphone_on').show();
  });

  $(document).on('click', '.camara_on', async function() {
    var result = await camara_on();
    $('.camara_on').hide();
    $('.camara_off').show();
  });
  $(document).on('click', '.camara_off', async function() {
    var result = await camara_off();
    $('.camara_off').hide();
    $('.camara_on').show();
  });

  $(document).on('click', '.full-zoom', function() {
    var elem = document.getElementById("video-call");
    openFullscreen(elem)
    $('.full-zoom-off').show();
    $('.full-zoom').hide()
  });

  $(document).on('click', '.full-zoom-off', function() {
    var elem = document.getElementById("video-call");
    closeFullscreen(elem)
    $('.full-zoom-off').hide();
    $('.full-zoom').show()
  });

  function openFullscreen(elem) {
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) {
      /* Safari */
      elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) {
      /* IE11 */
      elem.msRequestFullscreen();
    }
  }

  function closeFullscreen() {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
      /* Safari */
      document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
      /* IE11 */
      document.msExitFullscreen();
    }
  }
</script>
<script src="{{url('/public/')}}/AgoraUser.js"></script>
<script>
  function pre_const_form(){
    $('#preConsultNotes').modal('show');
  }
</script>


@endsection