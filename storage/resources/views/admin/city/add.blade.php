@extends('admin.layout.layout')
@section('content')
<style type="text/css">
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
</style>
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
                                <div class="mb-3">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" name="city" value="{{ old('city',isset($getdata->city) ? $getdata->city : '' )}}" class="form-control" placeholder="Enter city name">
                                </div>
                                
                                <!-- <div class="mb-3">
                                    <label for="formrow-inputState" class="form-label">State</label>
                                    <select id="formrow-inputState" name="state" class="form-select">
                                        <option value="">Select State</option>
                                        @foreach($state as $ste)
                                        <option value="{{$ste->id}}" {{ isset($getdata->state) ? $getdata->state==$ste->id ? 'selected' : '' : '' }}>{{$ste->name}}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Recommended</label>
                                        <select name="recommended" class="form-select">
                                            <option value="0" {{ isset($getdata->recommended) ? $getdata->recommended==0 ? 'selected' : '' : '' }}>No</option>
                                            <option value="1" {{ isset($getdata->recommended) ? $getdata->recommended==1 ? 'selected' : '' : '' }}>Yes</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label  class="form-label">Order</label>
                                        <input type="number" min="1" name="order" value="{{ old('order',isset($getdata->order) ? $getdata->order : '' )}}" class="form-control"  placeholder="Enter Order Number">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        
                                            <div class="form-group">
                                              <label>Image</label><br>
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
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script>
    $("#form-data").validate({

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'city': {required: true},
            
        },
        messages: {
            'city': "Please Enter city name.",        
                      
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
    

    
