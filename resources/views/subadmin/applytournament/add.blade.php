@extends('subadmin.layout.layout')
@section('content')
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
                            <form method="post" id="form-data" action="{{$saveurl}}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="user_id" value="{{ $getdata->user_id ?? '' }}">
                                <div class="mb-3">
                                    <label class="form-label">Athlete Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name',isset($getdata->name) ? $getdata->name : '' )}}" class="form-control" readonly placeholder="Enter title">
                                </div>

                                <div class="row mt-4">

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Weight <span class="text-danger">*</span></label>
                                        <input type="number" name="actul_waight" value="{{ old('actul_waight',isset($getdata->actul_waight) ? $getdata->actul_waight : '' )}}" id="actul_waight" class="form-control" placeholder="Enter Weight">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tournament <span class="text-danger">*</span></label>
                                        <select class="form-control" id="tournament_dropdown" name="tournament_id">
                                            <option value="">Select Tournament</option>
                                        </select>
                                    </div>
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
    <script src="https://cdn.ckeditor.com/4.20.1/full/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor1');
    </script>
    <script>
        $("#form-data").validate({
            onfocusout: function(element) {
                $(element).valid();
            },
            highlight: function(element, errorClass) {

            },

            rules: {
                'title': {
                    required: true
                },
                'min': {
                    required: true
                },
                'max': {
                    required: true
                },
                'category_type': {
                    required: true
                },

            },
            messages: {
                'title': "Please Enter title.",
                'min': "Please Enter min age.",
                'max': "Please Enter max age.",
                'category_type': "Please Select category Type.",
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

<script>
    $('#actul_waight').on('input', function () {
        let weight = $(this).val();
        let user_id = $('#user_id').val(); // Get user_id

        if (weight && user_id) {
            $.ajax({
                url: '{{ route("get.tournaments.by.weight") }}',
                method: 'GET',
                data: {
                    weight: weight,
                    user_id: user_id
                },
                success: function (response) {
                    let dropdown = $('#tournament_dropdown');
                    dropdown.empty().append('<option value="">Select Tournament</option>');
                    if (response.length > 0) {
                        response.forEach(function (item) {
                            dropdown.append(`<option value="${item.id}">${item.title}</option>`);
                        });
                    } else {
                        dropdown.append('<option value="">No matching tournaments</option>');
                    }
                }
            });
        }
    });
</script>
    @endsection