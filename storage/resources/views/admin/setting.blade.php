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
                                    <label for="formrow-firstname-input" class="form-label">Site Name <span class="text-danger">*</span></label>
                                    <input type="text" name="sitename" value="{{ old('sitename',isset($setting->sitename) ? $setting->sitename : '' )}}" class="form-control" id="formrow-firstname-input" placeholder="Enter Your First Name">
                                </div>

                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Email</label>
                                            <input type="email" name="email" value="{{ old('email',isset($setting->email) ? $setting->email : '' )}}" class="form-control" id="formrow-email-input" placeholder="Enter Your Email ID">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-password-input" class="form-label">Phone</label>
                                            <input type="phone" id="mobile_code" name="phone" value="{{ old('phone',isset($setting->phone) ? $setting->phone : '' )}}" class="form-control" id="formrow-password-input" placeholder="Enter Your Phone Number">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Short Description</label>
                                    <textarea name="short_description" class="form-control" placeholder="Enter Short Description">{{ old('short_description',isset($setting->short_description) ? $setting->short_description : '' )}}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Address</label>
                                    <textarea name="address"  class="form-control">{{ old('address',isset($setting->address) ? $setting->address : '' )}}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Map</label>
                                    <textarea class="form-control" name="map">{{ old('map',isset($setting->map) ? $setting->map : '' )}}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="formrow-inputCity" class="form-label">Facebook</label>
                                            <input type="text" name="facebook" value="{{ old('facebook',isset($setting->facebook) ? $setting->facebook : '' )}}" class="form-control" id="formrow-inputCity" placeholder="Enter Your Facebook Link">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="formrow-inputCity" class="form-label">Twitter</label>
                                            <input type="text" name="twitter" value="{{ old('twitter',isset($setting->twitter) ? $setting->twitter : '' )}}" class="form-control" id="formrow-inputCity" placeholder="Enter Your Twitter Link">
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="formrow-inputZip" class="form-label">YouTube</label>
                                            <input type="text" name="youtube" value="{{ old('youtube',isset($setting->youtube) ? $setting->youtube : '' )}}" class="form-control" id="formrow-inputZip" placeholder="Enter Your Youtube Link">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="formrow-inputZip" class="form-label">LinkdIn</label>
                                            <input type="text" name="linkdin" value="{{ old('linkdin',isset($setting->linkdin) ? $setting->linkdin : '' )}}" class="form-control" id="formrow-inputZip" placeholder="Enter Your Linkdin Link">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="formrow-inputZip" class="form-label">Instagram</label>
                                            <input type="text" name="instagram" value="{{ old('instagram',isset($setting->instagram) ? $setting->instagram : '' )}}" class="form-control" id="formrow-inputZip" placeholder="Enter Your Instagram Link">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="formrow-inputZip" class="form-label">Pinterest</label>
                                            <input type="text" name="Pinterest" value="{{ old('Pinterest',isset($setting->Pinterest) ? $setting->Pinterest : '' )}}" class="form-control" id="formrow-inputZip" placeholder="Enter Your Pinterest Link">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    
                                    <!-- <div class="col-md-4">
                                        <div class="card-body">
                                            <div class="form-group">
                                              <label>favicon</label><br>
                                              <input type="file" id="input-file-now" accept="image/*" name="header_logo" class="dropify" data-default-file="{{isset($setting->header_logo) ? url($setting->header_logo) : ''}}"/>
                                            </div>
                                          </div>
                                    </div> -->
                                    <div class="col-md-4">
                                        <div class="card-body">
                                            <div class="form-group">
                                              <label>Logo</label><br>
                                              <input type="file" id="input-file-now" accept="image/*" name="footer_logo" class="dropify" data-default-file="{{isset($setting->footer_logo) ? url($setting->footer_logo) : ''}}"/>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card-body">
                                            <div class="form-group">
                                              <label>favicon</label><br>
                                              <input type="file" id="input-file-now" accept="image/*" name="fav_icon" class="dropify" data-default-file="{{isset($setting->fav_icon) ? url($setting->fav_icon) : ''}}"/>
                                            </div>
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
    <script>
    $("#settingform").validate({

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'sitename': {required: true},
            
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

        if (this.valid()){
            $('.confirm-reservation-cart').attr("disabled", "disabled");
            
            form.submit();
        } 
    },   
});

</script>
@endsection 
    

    
