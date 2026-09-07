@extends('admin.layout.layout')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
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
                            <form method="post" id="settingform" action="{{$saveurl}}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">

                                    <div class="form-group">
                                        <label>Image</label><br>
                                        <input type="file" id="input-file-now" accept="image/*" name="image" class="dropify" />
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Subject<span class="text-danger">*</span></label>
                                    <input type="text" name="subject" value="{{ old('subject',isset($setting->subject) ? $setting->subject : '' )}}" class="form-control" id="formrow-firstname-input" placeholder="Enter Your First Name">
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Message</label>
                                    <textarea name="message" class="form-control" placeholder="Enter Message">{{ old('message',isset($setting->message) ? $setting->message : '' )}}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Signature </label>
                                    <textarea name="signature" class="form-control" placeholder="Enter signature">{{ old('signature',isset($setting->signature) ? $setting->signature : '' )}}</textarea>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="formrow-firstname-input" class="form-label">Select User</label>
                                        <select id="states" name="user[]" class="form-control" multiple>

                                            @foreach($user as $use)
                                            <option value="{{$use->id}}">{{$use->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <br><input id="chkall" type="checkbox"> Select All
                                    </div>


                                </div>


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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#states').select2();

            $("#chkall").click(function() {
                if ($("#chkall").is(':checked')) {
                    $("#states > option").prop("selected", "selected");
                    $("#states").trigger("change");
                } else {
                    $("#states > option").removeAttr("selected");
                    $("#states").trigger("change");
                }
            });
        });
    </script>
    <script>
        $("#settingform").validate({

            onfocusout: function(element) {
                $(element).valid();
            },
            highlight: function(element, errorClass) {

            },

            rules: {
                'sitename': {
                    required: true
                },

            },
            messages: {
                'sitename': "Please Enter Sitename.",
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
                    $('.confirm-reservation-cart').attr("disabled", "disabled");

                    form.submit();
                }
            },
        });
    </script>
    @endsection