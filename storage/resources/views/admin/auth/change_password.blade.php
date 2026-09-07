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
                            <form method="post" id="settingform" action="{{$saveurl}}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 form-group">
                                    <label for="formrow-firstname-input" class="form-label">Current Password <span class="text-danger">*</span></label>
                                    <div class="input-group change-passwords">
                                        <span ><i toggle="#password-field" class="fa fa-eye-slash toggle-password"></i></span>
                                        <input type="password" name="current-password" value="{{ old('current-password')}}" class="form-control password_data" id="password-field" placeholder="Enter Current Password" >
                                    </div>
                                </div>

                                <div class="mb-3 form-group">
                                    <label for="formrow-firstname-input" class="form-label">New Password <span class="text-danger">*</span></label>
                                    <div class="input-group change-passwords">
                                        <span ><i toggle="#newpasword" class="fa fa-eye-slash toggle-password"></i></span>
                                        <input type="password" name="new-password" value="{{ old('new-password')}}" class="form-control" id="newpasword" placeholder="Enter New Password">
                                    </div>
                                </div>

                                <div class="mb-3 form-group">
                                    <label for="formrow-firstname-input" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group change-passwords">
                                        <span><i toggle="#password-field2" class="fa fa-eye-slash toggle-password"></i></span>
                                        <input type="password" name="new-password_confirmation" value="{{ old('new-password_confirmation')}}" id="password-field2" class="form-control"  placeholder="Enter New Confirm Password">
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

        rules: {
            'current-password': {required: true},
            'new-password': {required: true},
            'new-password_confirmation': {required: true,equalTo: "#newpasword",},
            
        },
        messages: {
            'current-password': "Please Enter Current Password.",        
            'new-password': "Please Enter New Password.",        
            'new-password_confirmation': "Confirm password doesn't match New password.",        
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
    

    
