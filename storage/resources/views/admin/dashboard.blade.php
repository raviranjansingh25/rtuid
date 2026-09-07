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
                                <!-- <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li> -->
                                <!-- <li class="breadcrumb-item">{{$title}}</li> -->
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->  
            <div class="row">
                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('booking')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Booking</p>
                                        <h4 class="mb-0">{{$total_booking}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                            <span class="avatar-title">
                                                <i class="bx bx-copy-alt font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('booking')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Revenue</p>
                                        <h4 class="mb-0">₹{{$total_revenue}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center ">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-archive-in font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('vender')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Hotels</p>
                                        <h4 class="mb-0">{{$total_hotels}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="fas fa-hotel font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('city')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Cities</p>
                                        <h4 class="mb-0">{{$total_city}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="fas fa-bed font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('user')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Customers</p>
                                        <h4 class="mb-0">{{$total_user}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="dripicons-user-group font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('subadmin')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Subadmin</p>
                                        <h4 class="mb-0">{{$total_subadmin}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bxs-user-detail font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('category')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Categories</p>
                                        <h4 class="mb-0">{{$total_categories}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-file font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('amities')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Amenities</p>
                                        <h4 class="mb-0">{{$total_amities}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-detail font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('collection')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Collections</p>
                                        <h4 class="mb-0">{{$total_collection}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-detail font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('associate')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Associate Request</p>
                                        <h4 class="mb-0">{{$total_associate}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-detail font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('promo_code')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Promo Code</p>
                                        <h4 class="mb-0">{{$total_promo_code}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="fa fa-gift font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <a href="{{route('review')}}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">User Review</p>
                                        <h4 class="mb-0">{{$total_user_review}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="fas fa-comments font-size-24"></i>
                                                <!-- <i class="fas fa-comments"></i> -->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Latest Booking</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered dt-responsive nowrap w-100 table_data dataTable no-footer">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="align-middle">Order ID</th>
                                            <th class="align-middle">Customer Name</th>
                                            <th class="align-middle">Check In</th>
                                            <th class="align-middle">Check Out</th>
                                            <th class="align-middle">Total</th>
                                            <th class="align-middle">Payment Status</th>
                                            <th class="align-middle">Payment Method</th>
                                            <th class="align-middle">View Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($letest_booking as $data)
                                        <tr>
                                            
                                            <td><a href="javascript: void(0);" class="text-body fw-bold">#{{$data->booking_id}}</a> </td>
                                            <td>{{isset($data['get_user']->name)?$data['get_user']->name:'N/A'}}</td>
                                            <td>{{date('d-M-Y', strtotime($data->check_in))}}</td>
                                            <td>{{date('d-M-Y', strtotime($data->check_out))}}</td>
                                            <td>₹{{$data->total_amount}}</td>
                                            <td>
                                                <span class="badge badge-pill badge-soft-success font-size-11">{{$data->payment_status}}</span>
                                            </td>
                                            <td>
                                                {{$data->payment_type}}
                                            </td>
                                            <td>
                                                <!-- Button trigger modal -->
                                                <a href="{{url('/admin/booking-detail/'.$data->id)}}"><button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" >
                                                    View Details
                                                </button></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr class="odd"><td valign="top" colspan="9" class="dataTables_empty">No data available in table</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection 
    

    
