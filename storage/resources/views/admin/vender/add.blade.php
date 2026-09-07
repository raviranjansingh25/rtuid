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
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name',isset($getdata->name) ? $getdata->name : '' )}}" class="form-control" placeholder="Enter Your Full Name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Hotel Name <span class="text-danger">*</span></label>
                                    <input type="text" name="hotel_name" value="{{ old('hotel_name',isset($getdata->hotel_name) ? $getdata->hotel_name : '' )}}" class="form-control" placeholder="Enter hotel name">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="{{ old('email',isset($getdata->email) ? $getdata->email : '' )}}" class="form-control" placeholder="Enter Your Email ID">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="phone" id="mobile_code" name="phone" value="{{ old('phone',isset($getdata->mobile) ? '+'.$getdata->country_code.' '.$getdata->mobile : '' )}}" class="form-control" placeholder="Enter Your Phone Number">
                                        </div>
                                    </div>
                                    <input type="hidden" name="country_code" class="country_code">
                                </div>

                                @if($getdata=="")
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Password <span class="text-danger">*</span></label>
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
                                            <label for="formrow-email-input" class="form-label">Password <span class="text-danger">*</span></label>
                                            <div class="input-group change-passwords">
                                                <span ><i toggle="#e_password-field" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="e_password" value="{{ old('e_password')}}" class="form-control" id="e_password-field" placeholder="Enter password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-password-input" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="input-group change-passwords">
                                                <span ><i toggle="#e_password-field1" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="e_confirm_password" value="{{ old('e_confirm_password')}}" class="form-control" id="e_password-field1" placeholder="Enter confirm password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 @endif

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Short Description">{{ old('description',isset($getdata->description) ? $getdata->description : '' )}}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Policies</label>
                                    <textarea name="policies"  class="form-control" id="editor">{{ old('policies',isset($getdata->policies) ? $getdata->policies : '' )}}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="address">{{ old('address',isset($getdata->address) ? $getdata->address : '' )}}</textarea>
                                </div>

                                

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-inputState" class="form-label"> Collections</label>
                                            @php
                                            $Colle = []; 
                                            if(isset($getdata['collection'])){
                                                $Colle = explode(",", $getdata['collection']);
                                            }
                                            @endphp
                                            <select name="collection[]" class="form-select select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                <option value="">Select Collections</option>
                                                @foreach($collection as $coll)
                                                <option value="{{$coll->id}}" {{ in_array($coll->id,$Colle) ? 'selected' : '' }}>{{$coll->title}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-inputState" class="form-label">Categories</label>
                                            @php
                                            $catego = []; 
                                            if(isset($getdata['category'])){
                                                $catego = explode(",", $getdata['category']);
                                            }
                                            @endphp
                                            <select  name="category[]" class="form-select select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                <option value="">Select Categories</option>
                                                @foreach($category as $cat)
                                                <option value="{{$cat->id}}" {{ in_array($cat->id,$catego) ? 'selected' : '' }}>{{$cat->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-inputState" class="form-label">City <span class="text-danger">*</span></label>
                                            <select id="formrow-inputState" name="city" class="form-select">
                                                <option value="">Select City</option>
                                                @foreach($city as $city)
                                                <option value="{{$city->id}}" {{ isset($getdata->city) ? $getdata->city==$city->id ? 'selected' : '' : '' }}>{{$city->city}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        
                                            <div class="form-group">
                                              <label>Profile</label><br>
                                              <input type="file" id="input-file-now" accept="image/*" name="profile" class="dropify" data-default-file="{{isset($getdata->profile) ? url($getdata->profile) : ''}}"/>
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

    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
            ClassicEditor
                    .create( document.querySelector( '#editor' ) )
                    .then( editor => {
                            console.log( editor );
                    } )
                    .catch( error => {
                            console.error( error );
                    } );
    </script>
    <script>
    $("#settingform").validate({

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'name': {required: true},
            'hotel_name': {required: true},
            'email': {required: true},
            'phone': {required: true},
            'password': {required: true},
            'address': {required: true},
            'city': {required: true},
            'collection': {required: true},
            'category': {required: true},
            'state': {required: true},
            'confirm_password': {required: true,equalTo: '#password-field'},
            'e_confirm_password': {equalTo: "#e_password-field",},
        },
        messages: {
            'name': "Please Enter Vender Name.",        
            'hotel_name': "Please Enter hotel name.",        
            'email': "Please Enter email address.",        
            'phone': "Please Enter phone number.",        
            'password': "Please Enter password.",        
            'address': "Please Enter address.",        
            'city': "Please select city.",        
            'collection': "Please select collection.",        
            'category': "Please select category.",        
            'state': "Please select state.",        
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
    

    
