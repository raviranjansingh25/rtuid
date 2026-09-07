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
                            <form method="post" id="form-data" action="{{$saveurl}}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @php
                                                $fullName = '';
                                                if (!empty($getdata)) {
                                                    $fullName = trim(implode(' ', array_filter([
                                                        $getdata->name ?? '',
                                                        $getdata->middle_name ?? '',
                                                        $getdata->last_name ?? '',
                                                    ])));
                                                }
                                            @endphp
                                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" value="{{ old('name', $fullName) }}" class="form-control" placeholder="Enter full name">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
    <label class="form-label">
        District <span class="text-danger">*</span>
    </label>

    <select class="form-select" name="district" id="districtSelect">
        <option value="">Select district</option>

        <option value="all" {{ ($getdata->district ?? '') == 'all' ? 'selected' : '' }}>
            All
        </option>

        @foreach($dist as $district)
            <option value="{{ $district->id }}"
                {{ ($getdata->district ?? '') == $district->id ? 'selected' : '' }}>
                {{ $district->title }}
            </option>
        @endforeach
    </select>
</div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="{{ old('email',isset($getdata->email) ? $getdata->email : '' )}}" class="form-control" placeholder="Enter Email Address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="phone" id="mobile_code" name="phone" value="{{ old('phone', $phoneDisplay ?? '') }}" class="form-control" placeholder="Enter Phone Number">
                                        </div>
                                    </div>
                                    <input type="hidden" name="country_code" class="country_code">
                                </div>
                                @if($getdata=="")
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
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
                                            <label class="form-label">Password </label>
                                            <div class="input-group change-passwords">
                                                <span><i toggle="#e_password-field" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="e_password" value="{{ old('e_password')}}" class="form-control" id="e_password-field" placeholder="Enter password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password </label>
                                            <div class="input-group change-passwords">
                                                <span><i toggle="#e_password-field1" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="e_confirm_password" value="{{ old('e_confirm_password')}}" class="form-control" id="e_password-field1" placeholder="Enter confirm password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label class="form-label">Sub Admin Permissions</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="select_all_district" value="0">
                                                    <input class="form-check-input" type="checkbox" name="select_all_district" id="select_all_district" value="1" {{ old('select_all_district', isset($getdata->select_all_district) ? $getdata->select_all_district : 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="select_all_district">Select All District</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="add_weight" value="0">
                                                    <input class="form-check-input" type="checkbox" name="add_weight" id="add_weight" value="1" {{ old('add_weight', isset($getdata->add_weight) ? $getdata->add_weight : 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="add_weight">Add Weight</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="can_apply" value="0">
                                                    <input class="form-check-input" type="checkbox" name="can_apply" id="can_apply" value="1" {{ old('can_apply', isset($getdata->can_apply) ? $getdata->can_apply : 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="can_apply">Apply</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="athlete_detail" value="0">
                                                    <input class="form-check-input" type="checkbox" name="athlete_detail" id="athlete_detail" value="1" {{ old('athlete_detail', isset($getdata->athlete_detail) ? $getdata->athlete_detail : 1) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="athlete_detail">Athlete Detail</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="athlete_edit" value="0">
                                                    <input class="form-check-input" type="checkbox" name="athlete_edit" id="athlete_edit" value="1" {{ old('athlete_edit', isset($getdata->athlete_edit) ? $getdata->athlete_edit : 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="athlete_edit">Athlete Edit</label>
                                                </div>
                                            </div>
                                        </div>

                                        <label class="form-label mt-3">Sub Admin Modules</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="apply_tournament" value="0">
                                                    <input class="form-check-input" type="checkbox" name="apply_tournament" id="apply_tournament" value="1" {{ old('apply_tournament', isset($getdata->apply_tournament) ? $getdata->apply_tournament : 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="apply_tournament">Apply Tournament</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="can_coach" value="0">
                                                    <input class="form-check-input" type="checkbox" name="can_coach" id="can_coach" value="1" {{ old('can_coach', isset($getdata->can_coach) ? $getdata->can_coach : 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="can_coach">Coach</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="can_referee" value="0">
                                                    <input class="form-check-input" type="checkbox" name="can_referee" id="can_referee" value="1" {{ old('can_referee', isset($getdata->can_referee) ? $getdata->can_referee : 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="can_referee">Referee</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="can_draw_sheet" value="0">
                                                    <input class="form-check-input" type="checkbox" name="can_draw_sheet" id="can_draw_sheet" value="1" {{ old('can_draw_sheet', isset($getdata->can_draw_sheet) ? $getdata->can_draw_sheet : 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="can_draw_sheet">Draw Sheet</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="can_create_tournament" value="0">
                                                    <input class="form-check-input" type="checkbox" name="can_create_tournament" id="can_create_tournament" value="1" {{ old('can_create_tournament', isset($getdata->can_create_tournament) ? $getdata->can_create_tournament : 0) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="can_create_tournament">Create Tournament (District)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label>Profile</label><br>
                                            <input type="file" id="input-file-now" accept="image/*" name="image" class="dropify" data-default-file="{{isset($getdata->image) ? url($getdata->image) : ''}}" />
                                        </div>

                                    </div>
                                </div><br>

                                <div>
                                    <button type="submit" class="btn btn-primary w-md">Submit</button>
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
    <script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>   -->
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
                'phone': {
                    required: true
                },
                'district': {
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
                'name': "Please enter full name.",
                'email': "Please enter email address.",
                'phone': "Please enter phone number.",
                'district': "Please select district.",
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
                    var itiData = $("#mobile_code").intlTelInput("getSelectedCountryData");
                    var dialCode = itiData.dialCode;
                    var phone = $("#mobile_code").val().replace(/\D/g, '');

                    if (phone.indexOf(dialCode) === 0) {
                        phone = phone.substring(dialCode.length);
                    }

                    phone = phone.replace(/^0+/, '');
                    $('.country_code').val(dialCode);
                    $("#mobile_code").val(phone);
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

        $('#districtSelect').on('change', function () {
            if ($(this).val() === 'all') {
                $('#select_all_district').prop('checked', true);
            } else {
                $('#select_all_district').prop('checked', false);
            }
        });
        // Keep checkbox in sync on page load
        if ($('#districtSelect').val() && $('#districtSelect').val() !== 'all') {
            $('#select_all_district').prop('checked', false);
        }
    </script>

@push('js')
<script>
    $(document).ready(function () {
        @if(!empty($phoneDisplay))
            $("#mobile_code").intlTelInput("setNumber", "+{{ $dialCode }}{{ $phoneDisplay }}");
        @endif
    });
</script>
@endpush

    @endsection