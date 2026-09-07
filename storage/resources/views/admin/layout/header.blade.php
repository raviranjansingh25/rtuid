<?php
$admin= Auth::guard('admin')->user();
?>
<!doctype html>
<html lang="en">
<head>        
        <meta charset="utf-8" />
        <title>@if($title != ""){{ $title }} | @endif {{$setting->sitename}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Cityroom admin panel" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{url($setting->fav_icon)}}">
        <link href="{{url('/public/admin/')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

        <link href="{{url('/public/admin/')}}/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('/public/admin/')}}/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('/public/admin/')}}/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
        <!-- Bootstrap Css -->
        <link href="{{url('/public/admin/')}}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('public/admin/')}}/assets/dist/css/dropify.min.css">
        <!-- DataTables -->
        
        <!-- Icons Css -->
        <link href="{{url('/public/admin/')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

        <!-- App Css-->
        <link href="{{url('/public/admin/')}}/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />


    </head>
    <style type="text/css">
        .error{
            width: 100%;
        }
        body[data-sidebar=dark] .mm-active>i {
            color: #f03e39!important;
        }
        .mt-4 {
            margin-top: 1.7rem!important;
        }

        .error {
         text-transform: lowercase;
         color: red;
        } 

        .error::first-letter {
         text-transform: uppercase;
         color: red;
        }
        .btn-fw{
            text-transform: capitalize;
        }
        /*.page-title-right{
            position: absolute;
            right: 20px;
            top: 81px;
        }*/
        .add_button{
            position: relative;
            top: 28px;
            z-index: 999;
        }
        .table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before, table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before {
            top: 41%;
        }
        .iti {
            position: relative;
            display: inherit; 
        }
        .iti--allow-dropdown .iti__flag-container, .iti--separate-dial-code .iti__flag-container {
            right: auto;
            left: 0;
            height: 37px;
        }
        
    </style>

    <body data-sidebar="dark" data-layout-mode="light">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="{{route('admin_dashboard')}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{url($setting->header_logo)}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{url($setting->footer_logo)}}" alt="" height="17">
                                </span>
                            </a>

                            <a href="{{route('admin_dashboard')}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{url($setting->fav_icon)}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{url($setting->footer_logo)}}" alt="" height="19">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

                        <!-- App Search-->
                        <!-- <form class="app-search d-none d-lg-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="bx bx-search-alt"></span>
                            </div>
                        </form> -->

                        
                    </div>

                    <div class="d-flex">
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="{{isset($admin->image) ? url($admin->image) : url('public/noimage.png')}}"
                                    alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{$admin->name}}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item" href="{{route('view_profile')}}"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>

                                <a class="dropdown-item" href="{{route('change_password')}}"><i class="bx bxs-key font-size-16 align-middle me-1"></i> <span key="t-profile">Change Password</span></a>
                                
                                <a class="dropdown-item d-block" href="{{route('setting')}}"><i class="bx bx-wrench font-size-16 align-middle me-1"></i> <span key="t-settings">Settings</span></a>
                                
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{route('adminlogout')}}"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                            </div>
                        </div>

                    </div>
                </div>
            </header>