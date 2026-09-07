<!doctype html>
<html lang="en">
<head>
        
        <meta charset="utf-8" />
        <title>@if($title != ""){{ $title }} | @endif {{$setting->sitename}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{url($setting->fav_icon)}}">

        <!-- Bootstrap Css -->
        <link href="{{url('/public/admin/')}}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{url('/public/admin/')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{url('/public/admin/')}}/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    </head>
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
    .error{
        width: 100%;
    }
    </style>
    <body>
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p>Sign in to continue to {{$setting->sitename}}.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{url($setting->header_logo)}}" alt="" class="img-fluid">
                                    </div>
                                </div>

                            </div>

                            <div class="card-body pt-0"> 
                                <div class="auth-logo">
                                    <a href="#" class="auth-logo-light">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{url($setting->footer_logo)}}" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>

                                    <a href="#" class="auth-logo-dark">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{url($setting->fav_icon)}}" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
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
                                <div class="p-2">
                                    <form class="form-horizontal" action="{{$saveurl}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Email Address</label>
                                            <input type="text" name="email" value="{{old('email')}}" class="form-control" id="username" placeholder="Enter Email Address">
                                        </div>
                
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group change-passwords">
                                                <span ><i toggle="#password" class="fa fa-eye-slash toggle-password"></i></span>
                                                <input type="password" name="password" value="{{old('password')}}" class="form-control" placeholder="Enter password" id="password" aria-label="Password" aria-describedby="password-addon">
                                                
                                            </div>
                                        </div>

                                        

                                        <!-- <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember-check">
                                            <label class="form-check-label" for="remember-check">
                                                Remember me
                                            </label>
                                        </div> -->
                                        
                                        <div class="mt-3 d-grid">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                        </div>
            
                                        <!-- <div class="mt-4 text-center">
                                            <h5 class="font-size-14 mb-3">Sign in with</h5>
            
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-primary text-white border-primary">
                                                        <i class="mdi mdi-facebook"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-info text-white border-info">
                                                        <i class="mdi mdi-twitter"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-danger text-white border-danger">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>
                                                </li>
                                            </ul> -->
                                        </div>

                                        <!-- <div class="mt-4 text-center">
                                            <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                        </div> -->
                                    </form>
                                </div>
            
                            </div>
                        </div>
                        <!-- <div class="mt-5 text-center">
                            
                            <div>
                                <p>Don't have an account ? <a href="auth-register.html" class="fw-medium text-primary"> Signup now </a> </p>
                                <p>© <script>document.write(new Date().getFullYear())</script> Skote. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p> -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end account-pages -->

        <!-- JAVASCRIPT -->
        <script src="{{url('/public/admin/')}}/assets/libs/jquery/jquery.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/js/pages/alerts.init.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/node-waves/waves.min.js"></script>
        <script>
        $(document).ready(function(){
         $(document).on('change', '#file', function(){
          var name = document.getElementById("file").files[0].name;
          var form_data = new FormData();
          var ext = name.split('.').pop().toLowerCase();
          if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
          {
           alert("Invalid Image File");
          }
          var oFReader = new FileReader();
          oFReader.readAsDataURL(document.getElementById("file").files[0]);
          var f = document.getElementById("file").files[0];
          var fsize = f.size||f.fileSize;
          if(fsize > 2000000)
          {
           alert("Image File Size is very big");
          }
          else
          {
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
           form_data.append("file", document.getElementById('file').files[0]);
           $.ajax({
            url:"{{route('loginsaveimage')}}",
            method:"POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend:function(){
             $('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
            },   
            success:function(data)
            {
             $('#uploaded_image').html(data);
            }
           });
          }
         });
        });
        </script>
        <!-- App js -->
        <script src="{{url('/public/admin/')}}/assets/js/app.js"></script>
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
    </body>
</html>
