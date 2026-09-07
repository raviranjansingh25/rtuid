@php
    $settingdata = App\Models\Setting::first();
@endphp<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">




  <title>SIGN UP</title>

  <link rel="stylesheet" href="{{url('/public/frontend/')}}/css/bootstrap.min.css">
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="{{url('/public/frontend/')}}/img/favicon.png">
  <!-- Bootstrap CSS -->

  <!-- Fontawesome CSS -->
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/css/fontawesome-all.min.css">
  <!-- Flaticon CSS -->
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/font/flaticon.css">
  <!-- Google Web Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/style1.css">
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/style.css">
</head>
<style>
  /* The Modal (background) */
  .modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    padding-top: 100px;
    /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
  }

  /* Modal Content */
  .modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
  }

  /* The Close Button */
  .close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
  }


  @media only screen and (max-width: 600px) {
    .mob-img-res {
      width: 220px;
      height: 220px;
      position: relative;
      top: -340px;
      left: 60px;
    }
  }
  .modal {
    z-index: 1050; /* Bootstrap default */
  }
  .modal-backdrop {
    z-index: 1040; /* Should be lower than the modal */
  }
  .img-container img {
    max-width: 100%; /* Ensure the image fits properly */
  }
</style>

<body>
  <div id="preloader" class="preloader">
    <div class='inner'>
      <div class='line1'></div>
      <div class='line2'></div>
      <div class='line3'></div>
    </div>
  </div>
  <section class="fxt-template-animation fxt-template-layout34" data-bg-image="img/elements/bg1.png">
    <div class="fxt-shape">
      <div class="fxt-transformX-L-50 fxt-transition-delay-1">
        <img src="{{url('public/frontend/')}}/img/elements/shape1.png" alt="Shape">
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="fxt-column-wrap justify-content-between">
            <div class="fxt-animated-img">
              <div class="fxt-transformX-L-50 fxt-transition-delay-10 mob-img-res">
                <!--<a href="{{url('/')}}" class="fxt-logo"><img src="{{url($settingdata->logo)}}" alt="Logo" height="200px" width="200px"></a>-->
              </div>
            </div>
            <div class="fxt-transformX-L-50 fxt-transition-delay-3">
              <a href="{{url('/')}}" class="fxt-logo"><img src="{{url($settingdata->logo)}}" alt="Logo" height="200px" width="200px"></a>
            </div>
            <div class="fxt-transformX-L-50 fxt-transition-delay-5">
              <div class="fxt-middle-content">
                <h1 class="fxt-main-title">Sign Up To Participate</h1>
                <div class="fxt-switcher-description1">If you have an account You can<a href="{{route('userlogin')}}" class="fxt-switcher-text ms-2">Sign In</a></div>
              </div>
            </div>
            <div class="fxt-transformX-L-50 fxt-transition-delay-7">

            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="fxt-column-wrap justify-content-center">
            <div class="fxt-form">


              @if(session()->has('success'))
              <p class="alert alert-success text-muted m-b-10 col-lg-12 col-md-12 col-sm-12 col-xs-12 font-13 green">
                {{ session()->get('success') }}
              </p>
              @endif
              @if($errors->any())
              <p class="text-muted m-b-10 col-lg-12 col-md-12 col-sm-12 col-xs-12 font-13 alert alert-danger">{{$errors->first()}}</p>
              @endif
              <form method="POST" action="{{route('register_save')}}" autocomplete="" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <input type="text" id="f_name" class="form-control" name="name" value="{{old('name')}}" placeholder="FULL NAME" required>
                </div>


                <div class="form-group">
                  <input type="text" id="l_name" class="form-control" name="email" value="{{old('email')}}" placeholder="GMAIL">
                </div>
                <div class="form-group">
                  <input type="text" id="l_name" class="form-control" name="mob" pattern="[0-9]{10}" value="{{old('mob')}}" placeholder="Mobile Phone" title="You can enter only 10 digits..." required>
                </div>
                <div class="form-group">
                  <input type="text" id="l_name" class="form-control" name="whatsapp" pattern="[0-9]{10}" value="{{old('whatsapp')}}" placeholder="Whats App Number" title="You can enter only 10 digits..." required>
                </div>
                <div class="form-group">
                  <div><span style="font-size:12px;color:red">*Password needs to be at least 8 characters</span> </div>
                  <input id="password" type="password" class="form-control" value="{{old('password')}}" name="password" placeholder="********" required>


                </div>

                <div class="form-group">
                  <input id="password" type="password" class="form-control" name="cpassword" value="{{old('cpassword')}}" placeholder="********" required>

                </div>

                <div class="form-group  pad-form">
                  <label>FILL YOUR D.O.B</label>
                  <input type="date" class="form-control dob" name="dob" value="{{old('dob')}}" placeholder="D.O.B" required>
                </div>


                <div class="form-group">
                  <label>Category</label>
                  <select class="form-control sub_cat" name="category" required>
                    <option value="">Please fill DOB first</option>
                  </select>
                </div>
                <div class="form-group">

                  <select class="form-control" name="state" required>
                    <option value="">SELECT YOUR STATE</option>
                    @foreach($state as $statedata)
                    <option value="{{$statedata->id}}">{{$statedata->name}}</option>
                    @endforeach

                  </select>
                </div>




                <div class="form-group">

                  <select class="form-control" name="gender" required>
                    <option value="">SELECT YOUR GENDER</option>
                    <option value="1">MALE</option>
                    <option value="2">FEMALE</option>
                    <option value="3">Transgender</option>
                  </select>
                </div>



                <div class="form-group  pad-form">
                  <input type="text" class="form-control" name="fathername" value="{{old('fathername')}}" placeholder="FATHER NAME" required>
                </div>

                <div class="form-group  pad-form">
                  <input type="text" class="form-control" name="mothername" value="{{old('mothername')}}" placeholder="MOTHER NAME" required>
                </div>


                <div class="form-group  pad-form">
                  <label>ADDRESS <span style="color:red">*</span></label>
                  <textarea rows="4" cols="50" class="form-control" minlength="20" name="street" value="{{old('street')}}" placeholder="STREET ADDRESS" required required></textarea>


                  <input type="text" style="margin-top:3px" class="form-control" name="city" value="{{old('city')}}" placeholder="CITY" required>



                  <input type="text" style="margin-top:3px" class="form-control" name="pincode" value="{{old('pincode')}}" placeholder="PINCODE" required>
                </div>
                <div class="form-group  pad-form">
                    <input type="number"  class="form-control"  name="adharnumber" value="{{old('adharnumber')}}" placeholder="ADHAR CARD NUMBER" required  minlength="12" maxlength = "12"  >

                  </div>




                <div class="form-group pad-form">
                  <label>UPLOAD ADHAR CARD FRONT IMAGE</label>
                  <input type="file" class="form-control crop-image" name="adharcardfrontimage" accept="image/*">
                  <div class="image-preview mt-2" id="preview-adharcardfrontimage"></div>
                  <input type="hidden" class="preview-adharcardfrontimage" name="adharcardfrontimagebase">
                </div>

                <div class="form-group pad-form">
                  <label>UPLOAD ADHAR CARD BACK IMAGE</label>
                  <input type="file" class="form-control crop-image" name="adharcardbackimage" accept="image/*">
                  <div class="image-preview mt-2" id="preview-adharcardbackimage"></div>
                  <input type="hidden" class="preview-adharcardbackimage" name="adharcardbackimagebase">
                </div>

                <div class="form-group pad-form">
                  <label>UPLOAD PASSPORT SIZE PHOTO</label>
                  <input type="file" class="form-control crop-image" name="passport" accept="image/*">
                  <div class="image-preview mt-2" id="preview-passport"></div>
                  <input type="hidden" class="preview-passport" name="passportbase">
                </div>

                <div class="form-group pad-form">
                  <label>UPLOAD SIGNATURE IMAGE</label>
                  <input type="file" class="form-control crop-image" name="sign" accept="image/*">
                  <div class="image-preview mt-2" id="preview-sign"></div>
                  <input type="hidden" class="preview-sign" name="signbase">
                </div>


                <div class="form-group">
                  <button class="fxt-btn-fill btn btn-primary" type="submit">Submit</button>
                </div>

              </form>

            </div>
            <div class="fxt-switcher-description1">If you have an account You can<a href="{{route('userlogin')}}" class="fxt-switcher-text ms-2">Sign In</a></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="img-container">
          <img id="image" src="">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="crop">Crop</button>
      </div>
    </div>
  </div>
</div>

  <!-- jquery-->
  <script src="{{url('/public/frontend/')}}/js/jquery-3.5.0.min.js"></script>
  <!-- Bootstrap js -->
  <script src="{{url('/public/frontend/')}}/js/bootstrap.min.js"></script>
  <!-- Imagesloaded js -->
  <script src="{{url('/public/frontend/')}}/js/imagesloaded.pkgd.min.js"></script>
  <!-- Validator js -->
  <script src="{{url('/public/frontend/')}}/js/validator.min.js"></script>
  <!-- Custom Js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
  <script src="{{url('/public/frontend/')}}/js/main.js"></script>
   <script>
 $(document).ready(function () {
  var $modal = $('#modal');
  var image = document.getElementById('image');
  var cropper;
  var currentInput; // Track the input that triggered the event

  // File Input Change Event
  $("body").on("change", ".crop-image", function (e) {
    var files = e.target.files;
    currentInput = this; // Save reference to the current input

    if (files && files.length > 0) {
      var file = files[0];
      var reader = new FileReader();
      reader.onload = function (e) {
        image.src = e.target.result; // Set image source
        $modal.modal('show'); // Show the modal
      };
      reader.readAsDataURL(file);
    }
  });

  // Modal Show Event
  $modal.on('shown.bs.modal', function () {
    cropper = new Cropper(image, {
      aspectRatio: NaN, // Adjust as needed
      viewMode: 2,
      preview: '.preview',
    });
  }).on('hidden.bs.modal', function () {
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
  });

  // Crop Button Click Event
  $("#crop").click(function () {
    if (cropper) {
        var canvas = cropper.getCroppedCanvas({
            width: 300, // Adjust the width as needed
            height: 300, // Adjust the height as needed
        });

        // Compress the image by setting quality (range: 0 to 1)
        canvas.toBlob(
            function (blob) {
                // Ensure the image size is under 100KB by checking the Blob size
                if (blob.size > 100 * 1024) {
                    // Reduce quality further if necessary
                    canvas.toBlob(
                        function (compressedBlob) {
                            readAndPreviewBlob(compressedBlob);
                        },
                        "image/jpeg", // Set desired image type (e.g., "image/jpeg")
                        0.7 // Lower quality further (adjust between 0.1 and 1)
                    );
                } else {
                    readAndPreviewBlob(blob);
                }
            },
            "image/jpeg", // Set desired image type (e.g., "image/jpeg")
            0.9 // Initial quality factor (adjust between 0.1 and 1)
        );
    }

    function readAndPreviewBlob(blob) {
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function () {
            var base64data = reader.result;

            // Set the preview below the corresponding input
            var inputName = $(currentInput).attr("name"); // Get the input name
            $("#preview-" + inputName).html(
                '<img src="' + base64data + '" class="img-thumbnail" width="150">'
            );

            $modal.modal("hide"); // Hide the modal
        };
    }
});


  // Preview uncropped images immediately
  $("body").on("change", ".crop-image", function (e) {
    var files = e.target.files;
    var inputName = $(this).attr('name'); // Get the input name

    if (files && files.length > 0) {
      var file = files[0];
      var reader = new FileReader();
      reader.onload = function (e) {
        // Set the preview below the corresponding input
        $("#preview-" + inputName).html(
          '<img src="' + e.target.result + '" class="img-thumbnail" width="150">'
        );
        $(".preview-" + inputName).val(e.target.result);
      };
      reader.readAsDataURL(file);
    }
  });
});

</script>
  <script type="text/javascript">
    $('input[type=date]').change(function() {
      get_category();
    });

    $('input[type=date]').keypress(function(e) {
      $(this).off('change blur');

      $(this).blur(function() {
        get_category();
      });

      if (e.keyCode === 13) {
        get_category();
      }
    });

    function get_category() {
      var dob = $(".dob").val();
      $.ajax({
        url: "{{route('get_category')}}",

        data: {
          dob
        },

        success: function(res) {
          if (res.status == 1) {

            $(".sub_cat").html(res.sub_cat);


          } else {
            swal("Opps", "Please Select Date of birth", "warning");
          }
        },

      })
    }
  </script>

</body>


<!-- Mirrored from affixtheme.com/html/xmee/demo/register-34.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 10 Jun 2022 14:41:41 GMT -->

</html>