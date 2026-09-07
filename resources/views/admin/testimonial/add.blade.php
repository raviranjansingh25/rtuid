@extends('admin.layout.layout')
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
                                <div class="row">
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label>Image</label><br>
                                            <input type="file" id="input-file-now" accept="image/*" name="image" class="dropify" data-default-file="{{isset($getdata->image) ? url($getdata->image) : ''}}" />
                                        </div>

                                    </div>
                                </div><br>
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name',isset($getdata->name) ? $getdata->name : '' )}}" class="form-control" placeholder="Enter name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                                    <input type="text" name="designation" value="{{ old('designation',isset($getdata->designation) ? $getdata->designation : '' )}}" class="form-control" placeholder="Enter designation">
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">Review <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control" placeholder="Enter Description">{{ old('message',isset($getdata->message) ? $getdata->message : '' )}}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Rating<span class="text-danger">*</span></label>
                                    <select class="form-control" name="ratting">
                                        <option value="">Select Rating</option>

                                        <option value="1" {{ isset($getdata->ratting) ? $getdata->ratting==1 ? 'selected' : '' : '' }}>1</option>
                                        <option value="2" {{ isset($getdata->ratting) ? $getdata->ratting==2 ? 'selected' : '' : '' }}>2</option>
                                        <option value="3" {{ isset($getdata->ratting) ? $getdata->ratting==3 ? 'selected' : '' : '' }}>3</option>
                                        <option value="4" {{ isset($getdata->ratting) ? $getdata->ratting==4 ? 'selected' : '' : '' }}>4</option>
                                        <option value="5" {{ isset($getdata->ratting) ? $getdata->ratting==5 ? 'selected' : '' : '' }}>5</option>

                                    </select>
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
                'name': {
                    required: true
                },
                'designation': {
                    required: true
                },
                'message': {
                    required: true
                },
                'ratting': {
                    required: true
                },

            },
            messages: {
                'name': "Please Enter name.",
                'designation': "Please enter designation.",
                'message': "Please Enter review.",
                'ratting': "Please select ratting.",
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