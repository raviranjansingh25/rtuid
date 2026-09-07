@extends('frontend.layout.layout2')
@section('content')

@include('frontend/layout/sidebar')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />


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

.ui-datepicker-trigger {
    max-height: 28px;
}
</style>
<div class="col-md-9">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <form id="profile_edit" class="cmn-frm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="edit-profile">
                    <div class="edit-img mb-6">
                        <div class="picture-container">
                            <div class="picture">
                                <img src="{{isset($user->photo)?url($user->photo):url('public/user.png')}}"
                                    class="img-fluid show-image picture-src" title="" />
                                <input type="file" name="profile" id="wizard-picture" aria-invalid="false" class="image"
                                    accept="image/*">
                                <input type="hidden" name="image_base64">
                                <img class="edit-icon image"
                                    src="{{url('/public/frontend/')}}/assets/images/edit-img.svg" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="">Full Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{$user->name}}" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="">Fater Name</label>
                                <input type="text" name="father_name" value="{{$user->father_name}}"
                                    placeholder="Father Name">
                            </div>
                        </div>
                        
                        <!-- <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="form-group">
                <input type="text" name="preferred_name" value="{{$user->preferred_name}}" placeholder="Preferred Name">
              </div>
            </div> -->
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="">Email<span class="text-danger">*</span></label>
                                <input type="text" name="email" value="{{$user->email}}" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group ">
                                <label for="">D.O.B<span class="text-danger">*</span></label>
                                <input type="date" name="dob" value="{{$user->dob}}" placeholder="D.O.B."
                                    placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="">Mobile Number<span class="text-danger">*</span></label>
                                <input type="text" name="contact_number" value="{{$user->contact_number}}" placeholder="Mobile Number">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="">Whats's App Number<span class="text-danger">*</span></label>
                                <input type="text" name="whatsapp_number" value="{{$user->whatsapp_number}}" placeholder="Whats's App Number">
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="">State<span class="text-danger">*</span></label>
                                <select name="state">
                                    <option value="">Select state</option>
                                    
                                    <option value="rajasthan" selected="">Rajasthan</option>
                                       
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="">District<span class="text-danger">*</span></label>
                                <select name="district" class="select2" search>
                                    <option value="">Select district</option>
                                    @foreach($district as $distr)
                                    <option value="{{$distr->id}}"
                                    {{ isset($user->district) && $user->district == $distr->id ? 'selected' : '' }}>
                                        {{$distr->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--<label id="timezone-error" class="error" for="timezone"></label>-->
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="">City<span class="text-danger">*</span></label>
                                <input type="text" name="city" value="{{$user->city}}" placeholder="City Name">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="">Pincode<span class="text-danger">*</span></label>
                                <input type="text" name="pin_code" value="{{$user->pin_code}}" placeholder="Pincode Number">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="">Address<span class="text-danger">*</span></label>
                                <textarea name="address" placeholder="Address">{{$user->address}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="">Gender: <span class="text-danger">*</span></label>
                                <div class="gender-slct border-ctm d-flex align-items-center">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input w-auto mt-3" type="radio" id="gender1"
                                            type="radio"
                                            {{ isset($user->gender) ? $user->gender==1 ? 'checked' : '' : '' }}
                                            name="gender" value="1" placeholder="Gender.">
                                        <label class="form-check-label" for="gender1">
                                            Male
                                        </label>
                                    </div>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input w-auto mt-3" type="radio" id="gender2"
                                            {{ isset($user->gender) ? $user->gender==2 ? 'checked' : '' : '' }}
                                            name="gender" value="2" placeholder="Gender.">
                                        <label class="form-check-label" for="gender2">
                                            Female
                                        </label>
                                    </div>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input w-auto mt-3" type="radio" name="gender"
                                            id="gender3"
                                            {{ isset($user->gender) ? $user->gender==3 ? 'checked' : '' : '' }}
                                            name="gender" value="3" placeholder="Gender.">
                                        <label class="form-check-label" for="gender3">
                                            Other
                                        </label>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="">Coach Name<span class="text-danger">*</span></label>
                                <input type="text" name="coach_name" value="{{$user->coach_name}}" placeholder="Coach Name">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="">Coach contact<span class="text-danger">*</span></label>
                                <input type="text" name="coach_contact" value="{{$user->coach_contact}}" placeholder="Coach contact">
                            </div>
                        </div>
                        

                    </div>
                    <button type="submit" class="btn-primary w-100">Save</button>

                </div>
            </form>
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


<script src="{{url('/public/frontend/')}}/assets/js/jquery.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/rome.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>


<script>
console.log('https://www.facebook.com/akahriman');
</script>


<script>
var $modal = $('#modal');
var image = document.getElementById('image');
var cropper;

/*------------------------------------------
--------------------------------------------
Image Change Event
--------------------------------------------
--------------------------------------------*/
$("body").on("change", ".image", function(e) {
    var files = e.target.files;
    var done = function(url) {
        image.src = url;
        $modal.modal('show');
    };

    var reader;
    var file;
    var url;

    if (files && files.length > 0) {
        file = files[0];

        if (URL) {
            done(URL.createObjectURL(file));
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function(e) {
                done(reader.result);
            };
            reader.readAsDataURL(file);
        }
    }
});








/*------------------------------------------
--------------------------------------------
Show Model Event
--------------------------------------------
--------------------------------------------*/
$modal.on('shown.bs.modal', function() {
    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 1,
        preview: '.preview'
    });

    // Calculate the appropriate width and height for the displayed image
    var originalWidth = image.naturalWidth; // Width of the original image
    var originalHeight = image.naturalHeight; // Height of the original image
    var targetHeight = 371.25; // Desired height for the displayed image

    var scaleFactor = targetHeight / originalHeight;
    var scaledWidth = originalWidth * scaleFactor;

    // Set the style of the image to adjust its size
    $(image).css({
        width: scaledWidth + 'px',
        height: targetHeight + 'px'
    });

    // Set the transform property
    var translateX = 257; // Example value for translateX
    var translateY = 356.5; // Example value for translateY
    $(image).css({
        transform: 'translateX(' + translateX + 'px) translateY(' + translateY + 'px)'
    });
}).on('hidden.bs.modal', function() {
    cropper.destroy();
    cropper = null;
});

/*------------------------------------------
--------------------------------------------
Crop Button Click Event
--------------------------------------------
--------------------------------------------*/
$("#crop").click(function() {
    canvas = cropper.getCroppedCanvas({
        width: 600,
        height: 600,
    });

    canvas.toBlob(function(blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function() {
            var base64data = reader.result;
            $("input[name='image_base64']").val(base64data);
            $(".show-image").show();
            $(".show-image").attr("src", base64data);
            $("#modal").modal('toggle');
        }
    });
});
</script>
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
<script>
$("#profile_edit").validate({

    onfocusout: function(element) {
        $(element).valid();
    },

    rules: {
        'name': {
            required: true
        },
        'last_name': {
            required: true
        },
        'email': {
            required: true
        },
        'mobile': {
            required: true
        },
        'dob': {
            required: true
        },
        'accept': {
            required: true
        },
        'timezone': {
            required: true
        },
    },
    messages: {
        'name': "Please enter  first name.",
        'last_name': "Please enter  last name.",
        'email': "Please Enter email address.",
        'mobile': "Please Enter mobile number.",
        'dob': "Please Enter date of birth.",
        'accept': "Please selct any one.",
        'timezone': "Please selct timezone.",


    },
    errorPlacement: function(error, element) {
        if (element.attr("name") == "data[Payment][phone]") {
            error.insertAfter(".error-placement");
        } else {
            error.insertAfter(element);
        }
    },

    submitHandler: function(form) {
        var form_data = new FormData($('#profile_edit')[0]);
        $.ajax({
            url: "{{ route('user_profile_edit') }}",
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

                    swal({
                            title: "Success!",
                            text: res.message,
                            icon: "success",
                            dangerMode: true,
                            buttons: false,
                            timer: 1000
                        })
                        .then(() => {
                            window.location = "{{ route('user_profile') }}"
                        })

                } else {
                    $("#preloader").hide();
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.message,
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
    },
});
</script>
@endsection