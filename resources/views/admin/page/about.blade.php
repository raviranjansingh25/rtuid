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
                            <form method="post" id="settingform" action="{{$saveurl}}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ old('title',isset($about->title) ? $about->title : '' )}}" class="form-control" placeholder="Enter Title">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Sub Title <span class="text-danger">*</span></label>
                                    <textarea name="subtitle" class="form-control" placeholder="Enter Sub Title">{{ old('subtitle',isset($about->subtitle) ? $about->subtitle : '' )}}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea name="description"  class="form-control">{{ old('description',isset($about->description) ? $about->description : '' )}}</textarea>
                                </div>
                                

                                <div class="row">
                                    <div class="col-md-4">
                                        
                                            <div class="form-group">
                                              <label>Large Image</label><br>
                                              <input type="file" id="input-file-now" accept="image/*" name="large_image" class="dropify" data-default-file="{{isset($about->large_image) ? url($about->large_image) : ''}}"/>
                                            </div>
                                        
                                    </div><br>
                                    <div class="col-md-4">
                                        
                                            <div class="form-group">
                                              <label>Side Image 1</label><br>
                                              <input type="file" id="input-file-now" accept="image/*" name="side_image1" class="dropify" data-default-file="{{isset($about->side_image1) ? url($about->side_image1) : ''}}"/>
                                            </div>
                                        
                                    </div><br>
                                    <div class="col-md-4">
                                        
                                            <div class="form-group">
                                              <label>Side Image 2</label><br>
                                              <input type="file" id="input-file-now" accept="image/*" name="side_image2" class="dropify" data-default-file="{{isset($about->side_image2) ? url($about->side_image2 ) : ''}}"/>
                                            </div>
                                        
                                    </div><br>
                                    <div class="col-md-4">
                                        
                                            <div class="form-group">
                                              <label>Logo</label><br>
                                              <input type="file" id="input-file-now" accept="image/*" name="image_logo" class="dropify" data-default-file="{{isset($about->image_logo) ? url($about->image_logo) : ''}}"/>
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
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script>
    $("#settingform").validate({

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'title': {required: true},
            'subtitle': {required: true},
            'description': {required: true},
            
        },
        messages: {
            'title': "Please Enter title.",        
            'subtitle': "Please Enter subtitle.",        
            'description': "Please Enter description.",        
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "data[Payment][phone]") {
              error.insertAfter(".error-placement");
          } else {
              error.insertAfter(element);
          }
      },

      submitHandler: function(form) {

        if (this.valid()){
            $('.confirm-reservation-cart').attr("disabled", "disabled");
            
            form.submit();
        } 
    },   
});

</script>
@endsection 
    

    
