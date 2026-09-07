@extends('frontend.layout.layout2')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
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
    /*width: 80%;*/
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
<style type="text/css">
  .preview {
    /* text-align: center; */
    overflow: hidden;
    width: 160px;
    height: 160px;
    margin: 10px;
    border: 1px solid red;
  }

  .select2-selection__rendered {
    width: 100%;
    padding: 10px;
    background-color: #FFFFFF;
    border: 1px solid #CBCBCB;
    border-radius: 8px;
    margin: 0px 0 15px;
    font-size: 14px;
    color: #30304b !important;
}

.select2-container {
    margin-bottom: 32px;
}

.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: inherit;
    border-radius: 4px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 45px;
}

  .section {
    margin-top: 150px;
    background: #fff;
    padding: 50px 30px;
  }

  .modal-lg {
    max-width: 1000px !important;
  }

  .cropper-bg {
    width: 100% !important;
    /* height: 100% !important; */
  }
</style>
<div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="text-center mt-4 mb-4">Register</h2>
            <div class="edit-profile-pt">
                <form id="taekwondo_form" method="POST" class="cmn-frm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>State</label>-->
                                <select name="state" class="form-control">
                                    <option value="Rajasthan" selected>Rajasthan</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>District</label>-->
                                <select name="district" id="districtSelect" class="form-control">
                                    <option value="">Select District</option>
                                    @foreach($dist as $dis)
                                    <option value="{{$dis->id}}">{{$dis->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>Coach</label>-->
                                <select name="coach_id" id="coachSelect" class="form-control">
                                    <option value="">Select Coach</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="coach_name"  class="form-control" id="coachName">

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>Contact Number</label>-->
                                <input type="text" name="coach_contact" placeholder="Coach Contact Name" class="form-control" id="coachContact" readonly>
                            </div>
                        </div>

                        <!-- Basic Info -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>Full Name <span class="text-danger">*</span></label>-->
                                <input type="text" name="name" class="form-control" placeholder="Full Name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>IT UID</label>-->
                                <input type="text" name="u_id" class="form-control" placeholder="IT UID">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>Father's Name</label>-->
                                <input type="text" name="father_name" class="form-control" placeholder="Father's Name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>DOB</label>
                                <input type="date" name="dob" class="form-control">
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>Mobile Number</label>-->
                                <input type="text" name="contact_number" class="form-control" placeholder="Mobile Number">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>WhatsApp Number</label>-->
                                <input type="text" name="whatsapp_number" class="form-control" placeholder="WhatsApp Number">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>Email ID</label>-->
                                <input type="email" name="email" class="form-control" placeholder="Email Address">
                            </div>
                        </div>

                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Bels Certificate</label>
                                <input type="text" name="bels_certificate" class="form-control" placeholder="Confirm Password">
                            </div>
                        </div> -->

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>Password</label>-->
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>Confirm Password</label>-->
                                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                            </div>
                        </div>


                        <!-- Gender and Category -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label><br>
                                <label><input type="radio" name="gender" value="1"> Male</label>
                                <label><input type="radio" name="gender" value="2"> Female</label>
                                <label><input type="radio" name="gender" value="3"> Other</label>
                            </div>
                        </div>

                        <!--<div class="col-md-6">-->
                        <!--    <div class="form-group">-->
                        <!--        <label>Category</label>-->
                        <!--        <select name="category" class="form-control">-->
                        <!--            <option value="">Select Category</option>-->
                        <!--            @foreach($category as $cat)-->
                        <!--            <option value="{{$cat->id}}">{{$cat->title}}</option>-->
                        <!--            @endforeach-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--</div>-->

                        <!-- Location Info -->
                        

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>City</label>-->
                                <input type="text" name="city" class="form-control" placeholder="City">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>PIN Code</label>-->
                                <input type="text" name="pin_code" class="form-control" placeholder="PIN Code">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <!--<label>Address</label>-->
                                <textarea name="address" class="form-control" rows="3" placeholder="Address"></textarea>
                            </div>
                        </div>

                        <!-- ID Info -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <!--<label>Aadhar Card Number</label>-->
                                <input type="text" name="aadhar_number" class="form-control" placeholder="Aadhar Number">
                            </div>
                        </div>

                        <!-- Uploads -->
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Aadhar Front Image</label>
                                <input type="file" name="aadhar_front" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Aadhar Back Image</label>
                                <input type="file" name="aadhar_back" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>10th/Birth Certificate</label>
                                <input type="file" name="dob_certificate" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Passport Size Photo</label>
                                <input type="file" name="photo" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Signature Image</label>
                                <input type="file" name="signature" class="form-control">
                            </div>
                        </div> -->

                        <div class="form-group pad-form">
                          <label>UPLOAD ADHAR CARD FRONT IMAGE</label>
                          <input type="file" class="form-control crop-image" name="aadhar_front" accept="image/*">
                          <div class="image-preview mt-2" id="preview-aadhar_front"></div>
                          <input type="hidden" class="preview-aadhar_front" name="aadhar_frontbase">
                        </div>

                        <div class="form-group pad-form">
                          <label>UPLOAD ADHAR CARD BACK IMAGE</label>
                          <input type="file" class="form-control crop-image" name="aadhar_back" accept="image/*">
                          <div class="image-preview mt-2" id="preview-aadhar_back"></div>
                          <input type="hidden" class="preview-aadhar_back" name="aadhar_backbase">
                        </div>

                        <div class="form-group pad-form">
                          <label>UPLOAD DOB certificate (Birth/Board marksheet/ Indian Passport)</label>
                          <input type="file" class="form-control crop-image" name="dob_certificate" accept="image/*">
                          <div class="image-preview mt-2" id="preview-dob_certificate"></div>
                          <input type="hidden" class="preview-dob_certificate" name="dob_certificatebase">
                        </div>
                        
                        <div class="form-group pad-form">
                          <label>UPLOAD Belt Certificate</label>
                          <input type="file" class="form-control crop-image" name="belt_certificate" accept="image/*">
                          <div class="image-preview mt-2" id="preview-belt_certificate"></div>
                          <input type="hidden" class="preview-belt_certificate" name="belt_certificatebase">
                        </div>

                        <div class="form-group pad-form">
                          <label>UPLOAD PASSPORT SIZE PHOTO</label>
                          <input type="file" class="form-control crop-image" name="photo" accept="image/*">
                          <div class="image-preview mt-2" id="preview-photo"></div>
                          <input type="hidden" class="preview-photo" name="photobase">
                        </div>

                        <div class="form-group pad-form">
                          <label>UPLOAD SIGNATURE IMAGE</label>
                          <input type="file" class="form-control crop-image" name="signature" accept="image/*">
                          <div class="image-preview mt-2" id="preview-signature"></div>
                          <input type="hidden" class="preview-signature" name="signaturebase">
                        </div>

                        

                        

                        <!-- Submit -->
                        <div class="col-md-12 text-center mt-4 mb-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-body">
        <div class="img-container">
          <div class="row">
            <div class="col-md-8">
              <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
            </div>
            <div class="col-md-4">
              <div class="preview"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="crop">Crop</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="reg_otp_model" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
        <div class="forgot-pss">
          <h2 class="modal-title">Please verify account</h2>
          <p>A one time verification code has been sent to your registered email address. Please enter the code below and click submit.</p>
          <form id="reg_match_otp" method="post" class="cmn-frm">
            @csrf
            <div class="form-floating">
              <input type="hidden" class="form-control user_email" name="email">
              <input type="text" class="form-control" pattern="\d{4}" name="otp" placeholder="name@example.com">
              <label for="floatingInput">Enter OTP Number</label>
            </div>
            <!-- <label id="otp-error" class="error" for="otp">Please Enter OTP number.</label> -->
            <button type="submit" class="d-block btn-primary w-100">Submit</button>
          </form>
        <p>📩 Didn’t Receive Your OTP? <br>If you haven’t received the OTP in your inbox, please make sure to check your Spam, Junk, or Important folders.

Sometimes automated messages may be filtered by your email provider.


— Rajasthan Taekwondo</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="{{url('/public/frontend/')}}/assets/js/jquery.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/rome.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



<script>
  $("#taekwondo_form").validate({
    onfocusout: function(element) {
      $(element).valid();
    },
    rules: {
      'name': { required: true },
      'father_name': { required: true },
      'dob': { required: true },
      'contact_number': { required: true },
      'whatsapp_number': { required: true },
      'email': { required: true, email: true },
    //   'bels_certificate': { required: true },
      'password': { required: true },
      'confirm_password': { required: true, equalTo: '#password' },
      'gender': { required: true },
      'category': { required: true },
      'state': { required: true },
      'district': { required: true },
      'city': { required: true },
      'pin_code': { required: true },
      'address': { required: true },
      'aadhar_number': { required: true },
      'coach_name': { required: true },
      'coach_contact': { required: true }
    },
    messages: {
      'name': "Please enter full name.",
      'father_name': "Please enter father's name.",
      'dob': "Please select date of birth.",
      'contact_number': "Please enter mobile number.",
      'whatsapp_number': "Please enter WhatsApp number.",
      'email': "Please enter a valid email address.",
    //   'bels_certificate': "Please enter Bels Certificate.",
      'password': "Please enter password.",
      'confirm_password': "Passwords do not match.",
      'gender': "Please select gender.",
      'category': "Please select category.",
      'state': "Please select state.",
      'district': "Please enter district.",
      'city': "Please enter city.",
      'pin_code': "Please enter PIN code.",
      'address': "Please enter address.",
      'aadhar_number': "Please enter Aadhar number.",
      'coach_name': "Please enter coach name.",
      'coach_contact': "Please enter coach contact number."
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },
    submitHandler: function(form) {
      var form_data = new FormData($('#taekwondo_form')[0]);
      $.ajax({
        url: "{{ route('send_otp') }}",
        type: 'post',
        data: form_data,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();
          
          if (res.status == 1) {
            $('#loginmdl').modal('hide');
            Swal.fire({
              title: "Success!",
              text: res.message,
              icon: "success",
              dangerMode: true,
              buttons: false,
              timer: 2000
            }).then(() => {
                // alert(res.status);
              $('#reg_otp_model').modal('show');
              $('.user_email').val(res.email);
            });
          } else {
            $("#preloader").hide();
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            });
          }
        },
        error: function(error) {
          $("#preloader").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          });
        }
      });
    },
  });

  $("#reg_match_otp").validate({
    onfocusout: function(element) {
      $(element).valid();
    },
    rules: {
      'otp': { required: true, number: true, digits: true },
    },
    messages: {
      'otp': "Please Enter valid OTP number.",
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },
    submitHandler: function(form) {
      $.ajax({
        url: "{{ route('reg_user_match_otp') }}",
        type: 'GET',
        data: $('#reg_match_otp').serialize(),
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();
          $('#otp_model').modal('hide');
          if (res.status == 1) {
        //   alert(res.status);
            Swal.fire({
              title: "Success!",
              text: res.message,
              icon: "success",
              dangerMode: true,
              buttons: false,
              timer: 2000
            }).then(() => {
                // $('#loginmdl').modal('show');
                window.location.href = "{{ route('login_page') }}";
            });
          } else {
            $("#preloader").hide();
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: res.message,
            });
          }
        },
        error: function(error) {
          $("#preloader").hide();
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          });
        }
      });
    },
  });
</script>

<script>
    $('#districtSelect').on('change', function () {

        var districtId = $(this).val();

        if (districtId) {

            $.ajax({
                url: '{{ url("get-coaches") }}/' + districtId,
                type: 'GET',

                success: function (data) {

                    $('#coachSelect').empty().append('<option value="">Select Coach</option>');

                    $.each(data, function (key, coach) {

                        $('#coachSelect').append(
                            '<option value="' + coach.id + '" data-name="' + coach.name + '" data-contact="' + coach.phone + '" data-status="' + coach.status + '">' + coach.name + '</option>'
                        );

                    });

                    $('#coachName').val('');
                    $('#coachContact').val('');
                }
            });

        } else {

            $('#coachSelect').html('<option value="">Select Coach</option>');
            $('#coachName').val('');
            $('#coachContact').val('');

        }

    });


$('#coachSelect').on('change', function () {

    var selectedOption = $(this).find(':selected');

    var name = selectedOption.data('name');
    var contact = selectedOption.data('contact');
    var status = selectedOption.data('status');

    // inactive coach
    if(status != 1){

        $('#coachName').val('');
        $('#coachContact').val('');
        $('#coachSelect').val('');

        // form submit disable
        $('button[type="submit"]').prop('disabled', true);

        Swal.fire({
            icon: 'error',
            title: 'Opps!',
            html: 'Selected coach has not completed the Rajasthan Taekwondo Coaches Course for this year.<br><br>Please contact your coach.'
        });

        return false;
    }

    // active coach
    $('button[type="submit"]').prop('disabled', false);

    // fields auto fill
    $('#coachName').val(name || '');
    $('#coachContact').val(contact || '');

});
</script>


@endsection