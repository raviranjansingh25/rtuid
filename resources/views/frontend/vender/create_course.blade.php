@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropify/dist/css/dropify.min.css">
<style>
  .preloader1 {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #333333b8;
    z-index: 99999;
    display: none;
  }

  .progress {
    position: absolute;
    top: 50%;
    left: 30%;
    margin-top: -60px;
    margin-left: -60px;
    height: 30px;
    width: 50%;
    display: none;
  }

  .progress-bar {
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow: hidden;
    color: var(--bs-progress-bar-color);
    text-align: center;
    white-space: nowrap;
    background: transparent linear-gradient(292deg, #2CFDB2 0%, #00D2D9 100%);
    transition: var(--bs-progress-bar-transition);
    /* padding: 10px 0px; */
    z-index: 999;
  }

  .custom-tooltip .tooltip-inner {
    background-color: #ff0000;
    /* Change to your desired color */
    color: #ffffff;
    /* Change text color if needed */
  }

  /* Custom tooltip arrow color */
  .custom-tooltip .arrow::before {
    border-bottom-color: #ff0000;
    /* Match tooltip background color */
  }

  @media only screen and (max-width: 600px) {
    .progress {
      left: 25%;
      width: 75%;
    }
  }
</style>
<div class="preloader1" id="preloader1">
  <div class="progress">
    <div class="progress-bar"></div>
  </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-9a7JhKkUP5VgecmFfBx7Aw8QFtDnbj/XmluSMJz9bxBLF+OJ8l/7lqJTMw05PQ1z4b6Q86PL8fsMX6TeKukAfw==" crossorigin="anonymous" />
<div class="col-md-9">
  <a href="javascript:history.back()" class="d-flex align-items-center gap-2"><img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/back-errow.svg" alt=""> Back to Results</a>
  <div class="row ">
    <div class="col-xl-8 col-lg-12">
      <div class=" bg-white rounded p-4">
        <h3 class="section-title">Create Course</h3>
        <form id="create_course" method="post" class="cmn-frm" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="course_id" value="{{isset($getdata->id)?$getdata->id:''}}">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group btn-tooltip">
                <div class="upload-img">
                <input type="file" name="file" class="form-control" id="videoInput" accept=".mp4, .webm, .avi, .mpeg, .mov, .ogg"  data-default-file="{{ isset($getdata->file) ? url($getdata->file) : '' }}"><br>
                  <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/upload-photo.svg" alt="" id="icon_image">
                  <p></p>
                  <div class="videoPreview" style="display:none;">
                    <video id="videoPreview" controls width="100%"></video> 
                    
                </div>
                  <div class="tooltip-button">
                    <!-- <button class="btn-primary px-5">Upload</button> -->

                    <!-- <img data-toggle="tooltip" data-placement="bottom" title="This video will be shown to patients interested in buying the course, the purpose is to describe the benefits and promote it." src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt="" class="custom-tooltip"> -->

                  </div>
                </div>
                
              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <input type="text" placeholder="Course Title" name="title" value="{{old('title',isset($getdata->title)?$getdata->title:'')}}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <input type="number" placeholder="Course Price" name="price" value="{{old('price',isset($getdata->price)?$getdata->price:'')}}">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <input type="text" placeholder="Duration" name="duration" value="{{old('duration',isset($getdata->duration)?$getdata->duration:'')}}">
              </div>
            </div>
            @php
            $tags_data = [];
            if(isset($getdata['tags'])){
            $tags_data = explode(",", $getdata['tags']);
            }
            @endphp
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Tag<img data-toggle="tooltip" data-placement="bottom" title="List keywords that relate to your course, this will help members find your course." height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></label>
                <select class="select2" multiple="multiple" name="tags[]">
                  <option value="">Select Tag</option>
                  @foreach($tags as $tag)
                  <option value="{{$tag->id}}" {{$tag->id}}" {{ in_array($tag->id,$tags_data) ? 'selected' : '' }}>{{$tag->title}}</option>
                  @endforeach
                </select>
              </div>
            </div></br></br></br>
            <div class="col-md-12">
              <div class="form-group">
                <textarea id="summernote" name="description" cols="30" rows="5" placeholder="Description" required>{{old('description',isset($getdata->description)?$getdata->description:'')}}</textarea>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Upload supporting documents</label>
                <img data-toggle="tooltip" data-placement="bottom" title="Place any PDF documents or MP4 video content into a zip folder and upload." height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt="">
                <input type="file" name="course_pdf" class="dropify" accept=".doc, .docx, .pdf" data-default-file="{{isset($getdata->course_pdf) ? url($getdata->course_pdf) : ''}}"><br>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Upload Thumbnail Image</label>
                <img data-toggle="tooltip" data-placement="bottom" title="This image will be shown in the search listings." height="15px;" style="margin-top: -20px;" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt="">
                <input type="file" name="image" class="dropify" accept="image/*" data-default-file="{{ isset($getdata->image) ? url($getdata->image) : '' }}" /><br>

              </div>
            </div><br>
            <!-- <div class="col-md-12">
              <div class="form-group">
                <label for="">Upload Multiple Image</label>
                <input type="file" name="multiimage" multiple accept="image/*">
              </div>
            </div> -->
            <div class="col-md-12">
              <label for="">External link to course</label>
              <img data-toggle="tooltip" data-placement="bottom" title="Insert the direct link to your course that is housed on another platform. We recommend providing a link to a free version of the course or providing a 100% off discount code. The member will purchase the course through Telimed and then access the course on the external platform (ie. Thinkific, Kajabi etc.)" height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt="">
              <div class="form-group">
                <input type="text" name="video_link" placeholder="Add a Link" value="{{old('video_link',isset($getdata->video_link)?$getdata->video_link:'')}}">
              </div>
            </div>

            <div class="dynamic_field">

              @if(isset($getdata->id)!="")
              @php
              $pro_image = App\Models\CourseInclude::where('course_id',$getdata->id)->get();
              @endphp
              @if(count($pro_image)>0)


              @foreach($pro_image as $imgkey=>$img_data)
              <div class="row rowdelete">
                <input type="hidden" name="policy[{{$imgkey}}][policy_id]" value="{{$img_data->id}}">


                <div class="col-11 col-md-11 col-lg-11">
                  <div class="form-group">
                    @if($imgkey == 0)
                    <label for="">Included</label>
                    <img data-toggle="tooltip" data-placement="bottom" title="These will be displayed under the description as dot points of what is included in your Course. 
" height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt="">
                    @endif

                    <input type="text" name="policy[{{$imgkey}}][policy]" value="{{ isset($img_data->title) ? $img_data->title : '' }}" placeholder="Included" required>
                  </div>
                </div>
                <div class="col-1 col-md-1 col-lg-1">
                  <div class="form-group">
                    <?php
                    if ($imgkey == 0) { ?>
                      <img class="add" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                    <?php } else { ?>
                      <img class="btn_remove" src="{{url('public/frontend/assets/images/remove-filds.svg ')}}" alt="">
                    <?php } ?>
                  </div>
                </div>
              </div>
              @endforeach
              <input type="hidden" name="varkey" id="varkey" value="{{$imgkey+1}}">
              @endif

              @else
              <div class="row rowdelete">
                <input type="hidden" name="policy[0][policy_id]" value="">


                <div class="col-11 col-md-11 col-lg-11">
                  <div class="form-group">

                    <label for="">Included</label>
                    <img data-toggle="tooltip" data-placement="bottom" title="These will be displayed under the description as dot points of what is included in your Course. 
" height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt="">
                    <input type="text" name="policy[0][policy]" placeholder="Included" required>

                  </div>
                </div>
                <div class="col-1 col-md-1 col-lg-1">
                  <div class="form-group">

                    <img class="add" style="margin-top: 30px;" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                  </div>
                </div>
              </div>
              @endif

              <br>

            </div>

            <div class="col-md-12 my-4">
              <div class="text-center">
                <button type="submit" class="btn-primary px-5">Save</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
</div>



</div>
</section>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<!-- <script src="{{ url('/public/admin/') }}/assets/libs/select2/js/select2.full.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/dropify/dist/js/dropify.min.js"></script>
<script>
    const videoInput = document.getElementById('videoInput');
    const videoPreview = document.getElementById('videoPreview');
    const startTimeInput = document.getElementById('startTime');
    const endTimeInput = document.getElementById('endTime');
    let selectedFile; 
	function formatTime(seconds) {
	    const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
	    const secs = Math.floor(seconds % 60).toString().padStart(2, '0');
	    return `${mins}.${secs}`;
	}
 
    videoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) { 
	        const allowedTypes = ['video/mp4', 'video/webm', 'video/avi', 'video/mpeg', 'video/quicktime', 'video/ogg'];
	        if (!allowedTypes.includes(file.type)) {
	            $('#upload_video_error').text('Please upload a valid video file (MP4, OGG, WEBM, AVI, MPEG, MOV)').show();
	            videoInput.value = '';  
	            return;
	        }
          $('#icon_image').hide();
        	$(".videoPreview").show();
            selectedFile = file;
            const videoURL = URL.createObjectURL(file);
            videoPreview.src = videoURL; 
            videoPreview.onloadedmetadata = function() {  
	            const durationInSeconds = videoPreview.duration;
            	endTimeInput.value = formatTime(durationInSeconds);
            };
        }
    });
</script>
<!-- Initialize Select2 on your multiselect element -->
<script>
    // Initialize Dropify
    $('.dropify').dropify();

    // Preview video on file selection
    document.getElementById('video').addEventListener('change', function(event) {
        const file = event.target.files[0]; // Get the selected file

        if (file) {
            const videoPreview = document.getElementById('videoPreview');
            const videoSource = document.getElementById('videoSource');

            // Create a URL for the file and set it as the source of the video
            videoSource.src = URL.createObjectURL(file);
            videoPreview.style.display = 'block'; // Show the video element
            videoPreview.load(); // Load the video file
        } else {
            // Hide the video preview if no file is selected
            videoPreview.style.display = 'none';
        }
    });
</script>
<script>
  $(document).ready(function() {
    $('#summernote').summernote();
  });
</script>
<script>
  $(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>

<script>
  $(document).ready(function() {
    var varkey = $("#varkey").val();

    if (varkey != undefined) {
      val = varkey;
    } else {
      val = 1;
    }
    valuedata = 0;

    $(document).on("click", ".add", function() {

      var html = `<div class="row rowdelete"> <div class="col-12 col-md-12 col-lg-12"> <div class="row white-box"><input type="hidden" name="policy[` + val + `][policy_id]" value=""> <div class="col-11 col-md-11 col-lg-11"> <div class="form-group"> <input type="text" name="policy[` + val + `][policy]" placeholder="Included" required> </div></div><div class="col-1 col-md-1 col-lg-1"> <div class="form-group"> <img class="btn_remove" src="{{url('public/frontend/assets/images/remove-filds.svg ')}}" alt=""></div></div></div><br>`;
      $(this).parents(".dynamic_field").append(html);


      val++;
      rerun();
    });

    $(document).on('click', '.btn_remove', function() {
      $(this).parents('.rowdelete').remove();
    });
  });

  function rerun() {
    $('.dropify').dropify();


  }
</script>
<script>
  
  $(document).ready(function() {
    $.validator.addMethod("maxWords", function(value, element, params) {
    if (value) {
      var words = value.trim().split(/\s+/);
      return words.length <= params;
    }
    return true;
  }, "Please enter no more than {0} words.");

  $("#create_course").validate({
    
    

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      'title': {
        required: true
      },
      'description': {
        required: true,
        maxWords: 200
      },

      'duration': {
        required: true
      },
      'price': {
        required: true,
      },


    },
    messages: {
      'title': "Please Enter title.",
      'description': {
        required: "Please Enter description.",
        maxWords: "Please enter no more than 200 characters."
      },
      'duration': "Please enter duration.",
      'price': "Please enter price.",

    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      var form_data = new FormData($('#create_course')[0]);
      $.ajax({
        xhr: function() {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
              var percentComplete = parseInt((evt.loaded / evt.total) * 100);
              $(".progress-bar").width(percentComplete + '%');
              $(".progress-bar").html(percentComplete + '%');
            }
          }, false);
          return xhr;
        },
        url: "{{ route('vender_save_course') }}",
        type: 'post',
        data: form_data,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $(".progress").show();
          $(".preloader1").show();
          $(".progress-bar").width('0%');
        },
        success: function(res) {
          $(".progress").hide();
          $(".preloader1").hide();

          if (res.status == 1) {
            $('#signmdl').modal('hide');
            swal({
                title: "Success!",
                text: res.message,
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 1000
              })
              .then(() => {
                window.location = "{{ route('vender_my_course') }}"
              })

          } else {
            $(".progress").hide();
            $(".preloader1").hide();
            swal({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            })
          }
        },
        error: function(error) {
          $(".progress").hide();
          $(".preloader1").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          })
        }
      });
    },
  });
});
</script>
<!-- Jquery needed -->
@endsection