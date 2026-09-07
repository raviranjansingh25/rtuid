@php
    $settingdata = App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">

  

  
  <title>SIGN UP</title>

  <link rel="stylesheet" href="{{url('/public/frontend/')}}/css/bootstrap.min.css">
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="{{url($settingdata->logo)}}">
  <!-- Bootstrap CSS -->

  <!-- Fontawesome CSS -->
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/css/fontawesome-all.min.css">
  <!-- Flaticon CSS -->
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/font/flaticon.css">
  <!-- Google Web Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/style1.css">
  <link rel="stylesheet" href="{{url('/public/frontend/')}}/style.css">
</head>
<style>
  /* The Modal (background) */
  .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }

  /* Modal Content */
  .modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
  }

  /* The Close Button */
  .close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
  }


  @media only screen and (max-width: 600px) {
   .mob-img-res{
    width: 220px;
    height: 220px;
    position:relative;
    top:-340px;
    left:60px;
  }
}

</style>
<body>
      <div id="preloader" class="preloader">
        <div class='inner'>
          <div class='line1'></div>
          <div class='line2'></div>
          <div class='line3'></div>
        </div>
      </div>
      <section class="fxt-template-animation fxt-template-layout34" data-bg-image="img/elements/bg1.png">
        <div class="fxt-shape">
          <div class="fxt-transformX-L-50 fxt-transition-delay-1">
            <img src="{{url('public/frontend/')}}/img/elements/shape1.png" alt="Shape">
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-lg-8">
              <div class="fxt-column-wrap justify-content-between">
                <div class="fxt-animated-img">
                  <div class="fxt-transformX-L-50 fxt-transition-delay-10 mob-img-res">
                    <img src="{{url('public/frontend/')}}/img/figure/bg34-1.png" alt="Animated Image" width="400" height="400">
                  </div>
                </div>
                <div class="fxt-transformX-L-50 fxt-transition-delay-3">
                  <a href="index.php" class="fxt-logo"><img src="{{url($settingdata->logo)}}" alt="Logo" height="200px" width="200px"></a>
                </div>
                <div class="fxt-transformX-L-50 fxt-transition-delay-5">
                  <div class="fxt-middle-content">
                    <h1 class="fxt-main-title">Sign Up To Participate</h1>
                    <div class="fxt-switcher-description1">If you have an account You can<a href="{{route('userlogin')}}" class="fxt-switcher-text ms-2">Sign In</a></div>
                  </div>
                </div>
                <div class="fxt-transformX-L-50 fxt-transition-delay-7">

                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="fxt-column-wrap justify-content-center">
                <div class="fxt-form">


                  @if(session()->has('success'))
                      <p class="alert alert-success text-muted m-b-10 col-lg-12 col-md-12 col-sm-12 col-xs-12 font-13 green">
                          {{ session()->get('success') }}
                      </p>
                  @endif
                  @if($errors->any())
                      <p class="text-muted m-b-10 col-lg-12 col-md-12 col-sm-12 col-xs-12 font-13 alert alert-danger">{{$errors->first()}}</p>
                  @endif
                  <form method="POST" action="{{route('register_save')}}" autocomplete=""  enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                      <input type="text" id="f_name" class="form-control" name="name" value="{{old('name')}}" placeholder="FULL NAME" required >
                    </div>


                    <div class="form-group">
                      <input type="text" id="l_name" class="form-control" name="email" value="{{old('email')}}" placeholder="GMAIL"  >
                    </div>
                    <div class="form-group">
                      <input type="text" id="l_name" class="form-control" name="mob" pattern="[0-9]{10}" value="{{old('mob')}}" placeholder="Mobile Phone" title="You can enter only 10 digits..." required  >
                    </div>
                    <div class="form-group">
                      <div><span style="font-size:12px;color:red">*Password needs to be at least 8 characters</span>  </div> 
                      <input id="password" type="password" class="form-control" value="{{old('password')}}" name="password" placeholder="********" required >


                    </div>

                    <div class="form-group">
                      <input id="password" type="password" class="form-control" name="cpassword" value="{{old('cpassword')}}" placeholder="********" required >

                    </div>

                    <div class="form-group  pad-form">
                      <label>FILL YOUR D.O.B</label>
                      <input type="date"  class="form-control dob" name="dob" value="{{old('dob')}}" placeholder="D.O.B" required> 
                    </div>


                    <div class="form-group">
                      <label>Category</label>
                       <select class="form-control sub_cat" name="category" required>
                        <option value="">Please fill DOB first</option>
                      </select>
                   </div>
                   <div class="form-group">

                    <select class="form-control" name="state"  required>
                      <option value="">SELECT YOUR STATE</option> 
                      @foreach($state as $statedata)
                      <option value="{{$statedata->id}}">{{$statedata->name}}</option>
                      @endforeach

                    </select>
                  </div>




                  <div class="form-group">

                    <select  class="form-control" name="gender" required>
                      <option value="">SELECT YOUR GENDER</option>
                      <option value="1">MALE</option>
                      <option value="2">FEMALE</option>
                      <option value="3">Transgender</option>
                    </select>
                  </div>



                  <div class="form-group  pad-form">
                    <input type="text"  class="form-control" name="fathername" value="{{old('fathername')}}" placeholder="FATHER NAME" required  >
                  </div>

                  <div class="form-group  pad-form">
                    <input type="text"  class="form-control" name="mothername" value="{{old('mothername')}}" placeholder="MOTHER NAME" required  >
                  </div>


                  <div class="form-group  pad-form">
                   <label>ADDRESS <span style="color:red">*</span></label>
                   <textarea   rows="4" cols="50" class="form-control" minlength="20"   name="street" value="{{old('street')}}" placeholder="STREET ADDRESS" required   required></textarea>


                   <input type="text" style="margin-top:3px"  class="form-control" name="city" value="{{old('city')}}" placeholder="CITY"  required>   

                   <select style="margin-top:3px" class="form-control" name="statemain"  required>
                    <option value="">SELECT YOUR STATE</option>                            
                    @foreach($state as $statedata)
                      <option value="{{$statedata->code}}">{{$statedata->name}}</option>
                      @endforeach

                  </select>   

                  <input type="text" style="margin-top:3px"  class="form-control" name="pincode" value="{{old('pincode')}}" placeholder="PINCODE"    required>   
                </div>




                  <div class="form-group  pad-form">
                    <input type="number"  class="form-control"  name="adharnumber" value="{{old('adharnumber')}}" placeholder="ADHAR CARD NUMBER" required  minlength="12" maxlength = "12"  >

                  </div>


                  <div class="form-group  pad-form">
                    <label>UPLOAD ADHAR CARD FRONT IMAGE</label>
                    <input type="file"  class="form-control" name="adharcardfrontimage" value="{{old('adharcardfrontimage')}}" placeholder="ADHAR IMAGE FRONT"  ></div>
                    
                    <div class="form-group  pad-form">
                      <label>UPLOAD ADHAR CARD BACK IMAGE</label>
                      <input type="file"  class="form-control" name="adharcardbackimage" value="{{old('adharcardbackimage')}}" placeholder="ADHAR IMAGE BACK"  ></div>

                      <div class="form-group  pad-form"
                      <label>UPLOAD PASSPORT SIZE PHOTO</label>
                      <input type="file"  class="form-control" name="passport" value="{{old('passport')}}" placeholder="PASSPORT SIZE IMAGE"   >

                    </div>
                    
                    <div class="form-group  pad-form">
                      <label>UPLOAD SIGNATURE IMAGE</label>
                      <input type="file"  class="form-control" name="sign" placeholder="SIGNATURE" value="{{old('sign')}}" required>
                    </div>


                    <div class="form-group">
                      <button class="fxt-btn-fill btn btn-primary" type="submit">Submit</button>
                    </div>
                    
               </form>

             </div>
             <div class="fxt-switcher-description1">If you have an account You can<a href="{{route('userlogin')}}" class="fxt-switcher-text ms-2">Sign In</a></div>
           </div>
         </div>
       </div>
     </div>
   </section>


<!-- jquery-->
<script src="{{url('/public/frontend/')}}/js/jquery-3.5.0.min.js"></script>
<!-- Bootstrap js -->
<script src="{{url('/public/frontend/')}}/js/bootstrap.min.js"></script>
<!-- Imagesloaded js -->
<script src="{{url('/public/frontend/')}}/js/imagesloaded.pkgd.min.js"></script>
<!-- Validator js -->
<script src="{{url('/public/frontend/')}}/js/validator.min.js"></script>
<!-- Custom Js -->
<script src="{{url('/public/frontend/')}}/js/main.js"></script>
<script type="text/javascript">
  $('input[type=date]').change(function () {
    get_category();
});

$('input[type=date]').keypress(function (e) {
    $(this).off('change blur');

    $(this).blur(function () {
         get_category();
    });

    if (e.keyCode === 13) {
        get_category();
    }
});

function get_category(){
  var dob = $(".dob").val();
  $.ajax({
          url:"{{route('get_category')}}",
          
          data:{
              dob
          },
          
          success:function(res){
            if (res.status==1) {
                        
                 $(".sub_cat").html(res.sub_cat);
                 
                      
            }
            else {
                swal("Opps", "Please Select Date of birth", "warning");
            }
          },
          
      })
}
</script>

</body>


<!-- Mirrored from affixtheme.com/html/xmee/demo/register-34.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 10 Jun 2022 14:41:41 GMT -->
</html>




