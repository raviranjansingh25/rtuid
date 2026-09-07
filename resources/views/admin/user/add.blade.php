@extends('admin.layout.layout')
@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<style type="text/css">
    .input-group.change-passwords span {
        position: absolute;
        right: 0px;
        z-index: 9;
        padding: 10px;
    }

    .input-group.change-passwords {
        position: relative;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{$title}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                                <!-- <li class="breadcrumb-item">{{$title}}</li> -->
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{$errors->first()}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                        <form method="post" id="form-data" action="{{ $saveurl }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $getdata->name ?? '') }}" class="form-control" placeholder="Full Name">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">IT UID <span class="text-danger">*</span></label>
                                    <input type="text" name="it_uid" value="{{ old('it_uid', $getdata->it_uid ?? '') }}" class="form-control" placeholder="IT UID" required>
                                </div>

                                <!-- Father's Name -->
                                <div class="col-md-6">
                                    <label class="form-label">Father's Name</label>
                                    <input type="text" name="father_name" value="{{ old('father_name', $getdata->father_name ?? '') }}" class="form-control" placeholder="Father's Name">
                                </div>

                                <!-- DOB -->
                                <div class="col-md-6">
                                    <label class="form-label">DOB</label>
                                    <input type="date" name="dob" value="{{ old('dob', $getdata->dob ?? '') }}" class="form-control">
                                </div>

                                <!-- Contact Info -->
                                <div class="col-md-6">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" name="contact_number" value="{{ old('contact_number', $getdata->contact_number ?? '') }}" class="form-control" placeholder="Mobile Number">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">WhatsApp Number</label>
                                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $getdata->whatsapp_number ?? '') }}" class="form-control" placeholder="WhatsApp Number">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email ID</label>
                                    <input type="email" name="email" value="{{ old('email', $getdata->email ?? '') }}" class="form-control" placeholder="Email Address">
                                </div>

                                <!-- Password -->
                                @if($getdata=="")
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Password <span class="text-danger">*</span></label>
                                            <div class="input-group change-passwords">
                                                <span><i toggle="#password-field" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="password" value="{{ old('password')}}" class="form-control" id="password-field" placeholder="Enter password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-password-input" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="input-group change-passwords">
                                                <span><i toggle="#password-field1" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="confirm_password" value="{{ old('confirm_password')}}" class="form-control" id="password-field1" placeholder="Enter confirm password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Password</label>
                                            <div class="input-group change-passwords">
                                                <span><i toggle="#e_password-field" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="e_password" value="{{ old('e_password')}}" class="form-control" id="e_password-field" placeholder="Enter password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-password-input" class="form-label">Confirm Password</label>
                                            <div class="input-group change-passwords">
                                                <span><i toggle="#e_password-field1" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="e_confirm_password" value="{{ old('e_confirm_password')}}" class="form-control" id="e_password-field1" placeholder="Enter confirm password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label><br>
                                    <label><input type="radio" name="gender" value="1" {{ old('gender', $getdata->gender ?? '') == '1' ? 'checked' : '' }}> Male</label>
                                    <label><input type="radio" name="gender" value="2" {{ old('gender', $getdata->gender ?? '') == '2' ? 'checked' : '' }}> Female</label>
                                    <label><input type="radio" name="gender" value="3" {{ old('gender', $getdata->gender ?? '') == '3' ? 'checked' : '' }}> Other</label>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-control">
                                        <option value="">Select Category</option>
                                         @foreach($category as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category', $getdata->category ?? '') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- State and District -->
                                <div class="col-md-6">
                                    <label class="form-label">State</label>
                                    <select name="state" class="form-control">
                                        <option value="Rajasthan" selected>Rajasthan</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">District</label>
                                    <select name="district" class="form-control" id="districtSelect">
                                        <option value="">Select District</option>
                                        @foreach($dist as $dis)
                                            <option value="{{ $dis->id }}" {{ old('district', $getdata->district ?? '') == $dis->id ? 'selected' : '' }}>{{ $dis->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Coach -->
                                <div class="col-md-6">
                                    <label class="form-label">Coach</label>
                                    <select name="coach_id" id="coachSelect" class="form-control">
                                        <option value="">Select Coach</option>
                                        @foreach($coaches as $coach)
                                            <option value="{{ $coach->id }}" {{ old('coach_id', $getdata->coach_id ?? '') == $coach->id ? 'selected' : '' }}>
                                                {{ $coach->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Coach Name</label>
                                        <input type="text" name="coach_name" value="{{ old('coach_name', $getdata->coach_name ?? '') }}" class="form-control" id="coachName" readonly>
                                    </div>
                                </div>
        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" name="coach_contact" value="{{ old('coach_contact', $getdata->coach_contact ?? '') }}" class="form-control" id="coachContact" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" value="{{ old('city', $getdata->city ?? '') }}" class="form-control" placeholder="City">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">PIN Code</label>
                                    <input type="text" name="pin_code" value="{{ old('pin_code', $getdata->pin_code ?? '') }}" class="form-control" placeholder="PIN Code">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3" placeholder="Address">{{ old('address', $getdata->address ?? '') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Aadhar Number</label>
                                    <input type="text" name="aadhar_number" value="{{ old('aadhar_number', $getdata->aadhar_number ?? '') }}" class="form-control" placeholder="Aadhar Number">
                                </div>
                                <div class="row">

                                <!-- File Uploads -->
                                @php
                                    $imageFields = ['aadhar_front', 'aadhar_back', 'dob_certificate', 'photo', 'signature', 'belt_certificate'];
                                @endphp

                                @foreach($imageFields as $field)
                                <!--<div class="col-md-6">-->
                                <!--    <label class="form-label text-uppercase">{{ str_replace('_', ' ', $field) }}</label>-->
                                <!--    <input type="file" name="{{ $field }}" class="form-control" accept="image/*">-->
                                <!--    @if(isset($getdata->$field))-->
                                <!--        <div class="mt-2">-->
                                <!--            <img src="{{ url($getdata->$field) }}" alt="{{ $field }}" style="max-height: 100px;">-->
                                <!--        </div>-->
                                <!--    @endif-->
                                <!--</div>-->
                                
                                
                                <div class="col-md-4">

                                        <div class="form-group">
                                            <label>{{ str_replace('_', ' ', $field) }}</label><br>
                                            <input type="file" id="input-file-now" accept="image/*" name="{{ $field }}" class="dropify" data-default-file="{{isset($getdata->$field) ? url($getdata->$field) : ''}}" />
                                        </div>

                                    </div>
                                @endforeach
                                </div>

                                

                                <!-- Submit -->
                                <div class="col-md-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>

                            </div>
                        </form>

                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script>
        $("#form-data").validate({

            onfocusout: function(element) {
                $(element).valid();
            },
            highlight: function(element, errorClass) {

            },

            rules: {
                'name': {
                    required: true
                },
                'email': {
                    required: true
                },
                'mobile': {
                    required: true
                },
                'password': {
                    required: true
                },
                'confirm_password': {
                    required: true,
                    equalTo: '#password-field'
                },

                'e_confirm_password': {
                    equalTo: "#e_password-field",
                },
            },
            messages: {
                'name': "Please Enter state name.",
                'email': "Please Enter email address.",
                'mobile': "Please Enter mobile number.",
                'password': "Please enter password.",
                'confirm_password': {
                    required: "Please enter confirm password.",
                    equalTo: "Confirm password and password are not same."
                },
                'e_confirm_password': "Please enter Valid Password.",
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "data[Payment][phone]") {
                    error.insertAfter(".error-placement");
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {

                if (this.valid()) {
                    var country_code = $('.iti__selected-dial-code').html();
                    $('.country_code').val(country_code);
                    $('.confirm-reservation-cart').attr("disabled", "disabled");

                    form.submit();
                }
            },
        });
    </script>
    <script type="text/javascript">
        $(".toggle-password").click(function() {

            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
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
                        $('#coachSelect').append('<option value="' + coach.id + '" data-name="' + coach.name + '" data-contact="' + coach.phone + '">' + coach.name + '</option>');
                    });

                    // Reset fields
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
        var name = $(this).find(':selected').data('name');
        var contact = $(this).find(':selected').data('contact');

        $('#coachName').val(name || '');
        $('#coachContact').val(contact || '');
    });
</script>
    @endsection