@extends('admin.layout.layout')
@section('content')
<!-- <style type="text/css">
    .card {
    margin-bottom: 24px;
    box-shadow: 0 0.75rem 1.5rem rgb(18 38 63 / 3%)!important;
    background-color: inherit;
}
</style> -->
            

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

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    

                                    <div class="col-xl-6">
                                        <div class="mt-4 mt-xl-3">
                                            <a href="javascript: void(0);" class="text-primary"></a>
                                            <h4 class="mt-1 mb-3"><img src="{{url(isset($user->profile)?$user->profile:'public/noimage.png')}}" alt="logo" height="50" width="50" class="rounded-circle" />&nbsp;&nbsp;{{$user->name}}</h4>

                                            
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                                

                                
                                <div class="mt-5">
                                    <!-- <h5 class="mb-3">Specifications :</h5> -->

                                    <div class="table-responsive">
                                        <table class="table mb-0 table-bordered">
                                            <tbody>
                                                
                                                
                                                <tr>
                                                    <th scope="row">Email Address</th>
                                                    <td>{{$user->email}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Phone Number</th>
                                                    <td>{{isset($user->country_code)?'+'.$user->country_code.' '.$user->mobile:$user->mobile}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Available Wallet Points</th>
                                                    <td>{{isset($user->refar_point)?$user->refar_point:'0'}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Total Referral</th>
                                                    <td>{{count($refar)}}</td>
                                                </tr>
                                                
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- end Specifications -->

                                

                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                </div>
                <!-- Total Booking -->
                <div class="row">
                    <div class="col-12">
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                   <h4 class="mb-sm-0 font-size-18">Total Booking</h4>
                                </div><br>
                                <table  class="table table-bordered dt-responsive nowrap w-100 table_data">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking ID</th>
                                        <th>Hotel</th>
                                        <th>Check in</th>
                                        <th>Check Out</th>
                                        <th>Booking Date</th>
                                        <th>Status</th>
                                        <th>View Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($user['get_booking'] as $bkey=>$booking)
                                    <tr>
                                        <td>{{$bkey+1}}</td>
                                        <td>{{$booking->booking_id}}</td>
                                        <td>{{isset($booking['get_hotel']->hotel_name)?$booking['get_hotel']->hotel_name:'N/A'}}</td>
                                        <td>{{date('d-M-Y', strtotime($booking->check_in))}}</td>
                                        <td>{{date('d-M-Y', strtotime($booking->check_out))}}</td>
                                        <td>{{date('d-M-Y h:i A', strtotime($booking->created_at))}}</td>
                                        <td>@if ($booking->booking_status==1)
                                            <span class="badge bg-warning font-size-10">Pending</span>
                                        @elseif($booking->booking_status==2)
                                            <span class="badge bg-success font-size-10">Accept</span>
                                        @elseif($booking->booking_status==3)
                                            <span class="badge bg-danger font-size-10">Reject</span>
                                        @elseif($booking->booking_status==4)
                                            <span class="badge bg-primary font-size-10">Check In</span>
                                        @else
                                            <span class="badge bg-info font-size-10">Check Out</span>
                                        @endif</td>
                                        <td><a href="{{url('/admin/booking-detail/'.$booking->id)}}"><button type="button" class="btn btn-primary btn-sm btn-rounded" >View Details</button></a></td>
                                    </tr>
                                    @empty
                                    
                                    <tr class="odd"><td valign="top" colspan="9" class="dataTables_empty">No Booking</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
                <!-- end col -->
                <!-- Total Referral -->
                <div class="row">
                    <div class="col-12">
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                   <h4 class="mb-sm-0 font-size-18">Total Referral</h4>
                                </div><br>
                                <table  class="table table-bordered dt-responsive nowrap w-100 table_data">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Phone Number</th>
                                    </tr>
                                    </thead>
                                    @forelse($refar as $key=>$refar_data)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td><img src="{{url(isset($refar_data->profile)?$refar_data->profile:'public/noimage.png')}}" height="30" width="30" class="rounded-circle"></td>
                                        <td>{{$refar_data->name}}</td>
                                        <td>{{$refar_data->email}}</td>
                                        <td>{{isset($refar_data->country_code)?'+'.$refar_data->country_code.' '.$refar_data->mobile:$refar_data->mobile}}</td>
                                    </tr>
                                    @empty
                                    <tr class="odd"><td style="text-align: center;" valign="top" colspan="9" class="dataTables_empty">No Referral</td></tr>
                                    @endforelse
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
                <!-- end col -->

                

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    
    <script>
       $(document).ready(function () {
          var table = $('.table_data').DataTable({
            "bFilter": false, 
          }); 
          
      });
    </script>
@endsection 
        