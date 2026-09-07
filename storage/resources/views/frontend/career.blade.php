@extends('frontend.layout.layout')
@section('content')

  
    <!-- Hero Section  -->
    <section class="contect-hero mt-110">
      <div class="container ctr-algn">
        <div class="hero-heading">
          <h1>Career</h1>
          <p>Vaccusantium Doloremque Totam Rem Aperiam</p>
        </div>
      </div>
    </section>

    <!-- Contect Form Start -->
    <section class="contect-frm-section">
      <img class="con-dtt" src="./assets/img/dott-vtr.svg" alt="">
      <div class="container">
        <div class="frm-cnct creer-page">
          <h2>Your Application</h2>
          <p>We are pleased that you are interested in Cityroom. Please fill out the short form below. If you have <br>
             difficulties uploading your data, please send an email to career@cityroom.in.</p>
          <form method="post" id="form-data">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">First Name *</label>
                  <input type="text" name="f_name" placeholder="Enter First Name" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Last Name *</label>
                  <input type="text" name="l_name" placeholder="Enter Last Name" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Email *</label>
                  <input type="text" name="email" placeholder="Enter Email Address" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Mobile Number</label>
                  <input type="text" id="mobile_code" name="phone" class="form-control" placeholder="Mobile Number" name="name">  
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Salary Expectations *</label>
                  <input type="text" name="salary" placeholder="Enter Annual Salary" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Available From *</label>
                  <input type="date" name="date" placeholder="Enter Subject" />
                </div>
              </div>
              <div class="col-md-12">
                <h3>Documents</h3>
                <p>Sounds good? Then we look forward to receiving your documents with your earliest starting date and salary expectations. We are more interested in what moves you, who you are and why you want to work for us than a straightforward CV.</p>
              </div>
              <div class="col-md-6">
                <div class="form-group file-input">
                  <label for="">CV *</label>
                  <input type="file" name="cv" placeholder="Enter Subject" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group write-to-ent">
                  <label for="">Write to *</label>
                  <textarea rows="3" name="message"></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <button type="submit" class="pre-btn w-100 p-3 ">Submit Application</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <img class="con-dtt btm-rht" src="{{url('/public/frontend/')}}/assets/img/dott-1.svg" alt="">
    </section>
    <!-- Address No Section Start -->
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
            'f_name': {required: true},
            'l_name': {required: true},
            'email': {required: true},
            'phone': {required: true},
            'salary': {required: true},
            'date': {required: true},
            'cv': {required: true},
            'message': {required: true},  
        },
        messages: {
            'f_name': "Please Enter first name.",        
            'l_name': "Please Enter last name.",        
            'email': "Please Enter email address.",        
            'phone': "Please Enter mobile number.", 
            'salary': "Please Enter salary expectations.", 
            'date': "Please select date.",    
            'cv': "Please select cv.",    
            'message': "Please Enter message.",                       
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "data[Payment][phone]") {
              error.insertAfter(".error-placement");
          } else {
              error.insertAfter(element);
          }
      },

      submitHandler: function(form) {

        $.ajax(
        {
            url: "{{ route('career_request') }}",
            type: 'GET',
            data: $('#form-data').serialize(),
            beforeSend: function() {
                $("#preloader").show(); 
            },
            success: function (res)
            {
              $("#preloader").hide(); 
                if (res.status == 1)
                {
                    swal({
                    title: "Success!",
                    text: res.message,
                    icon: "success",
                    dangerMode: true,
                    buttons: false,
                    timer: 1000
                })
               .then(() => {
                        window.location="{{route('home')}}"
                    })
                    
                }else{
                  $("#preloader").hide(); 
                  swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: res.message,
                  })
                }   
            },
            error: function (error) {
              $("#preloader").hide(); 
                swal({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Something went wrong!',
                })
            }
        });
    },   
});

</script>
    @endsection