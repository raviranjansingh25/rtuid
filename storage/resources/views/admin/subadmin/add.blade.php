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
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name',isset($getdata->name) ? $getdata->name : '' )}}" class="form-control" placeholder="Enter name">
                                </div>

                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="{{ old('email',isset($getdata->email) ? $getdata->email : '' )}}" class="form-control" placeholder="Enter Email Address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="phone" id="mobile_code" name="phone" value="{{ old('phone',isset($getdata->phone) ? '+'.$getdata->country_code.' '.$getdata->phone : '' )}}" class="form-control" placeholder="Enter Phone Number">
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
                                                <span ><i toggle="#password-field" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="password" value="{{ old('password')}}" class="form-control" id="password-field" placeholder="Enter password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-password-input" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="input-group change-passwords">
                                                <span ><i toggle="#password-field1" class="fa fa-eye-slash toggle-password"></i></span>
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
                                                <span ><i toggle="#e_password-field" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="e_password" value="{{ old('e_password')}}" class="form-control" id="e_password-field" placeholder="Enter password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password </label>
                                            <div class="input-group change-passwords">
                                                <span ><i toggle="#e_password-field1" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="e_confirm_password" value="{{ old('e_confirm_password')}}" class="form-control" id="e_password-field1" placeholder="Enter confirm password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 @endif

                                <div class="row">
                                    <div class="col-md-4">
                                        
                                            <div class="form-group">
                                              <label>Profile</label><br>
                                              <input type="file" id="input-file-now" accept="image/*" name="image" class="dropify" data-default-file="{{isset($getdata->image) ? url($getdata->image) : ''}}"/>
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

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'name': {required: true},
            'email': {required: true},
            'phone': {required: true},
            'password': {required: true},
            'confirm_password': {required: true,equalTo: '#password-field'},

            'e_confirm_password': {equalTo: "#e_password-field",},
            
        },
        messages: {
            'name': "Please enter name.",        
            'email': "Please enter email address.",        
            'phone': "Please enter phone number.",        
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

        if (this.valid()){
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



@endsection 
    

    
