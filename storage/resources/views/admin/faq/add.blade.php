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
                                <div class="mb-3">
                                    <label class="form-label">Question <span class="text-danger">*</span></label>
                                    <input type="text" name="question" value="{{ old('question',isset($getdata->question) ? $getdata->question : '' )}}" class="form-control" placeholder="Enter Question">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea name="answer" class="form-control" placeholder="Enter Answer">{{ old('answer',isset($getdata->answer) ? $getdata->answer : '' )}}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-inputState" class="form-label">Category Type <span class="text-danger">*</span></label>
                                    <select name="faq_type" class="form-select">
                                        <option value="">Select Category</option>
                                        @foreach($category as $cat)
                                        <option value="{{$cat->id}}" {{ isset($getdata->faq_type) ? $getdata->faq_type==$cat->id ? 'selected' : '' : '' }}>{{$cat->title}}</option>
                                        @endforeach
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
    <script>
    $("#form-data").validate({

        onfocusout: function (element) {
            $(element).valid();
        },
        highlight: function(element, errorClass) {

        },

        rules: {
            'question': {required: true},
            'answer': {required: true},
            'faq_type': {required: true},
            
        },
        messages: {
            'question': "Please Enter title.",        
            'answer': "Please Enter answer.",        
            'faq_type': "Please Select faq category.",        
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
    

    
