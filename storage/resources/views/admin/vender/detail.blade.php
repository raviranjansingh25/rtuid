@extends('admin.layout.layout')
@section('content')
<style type="text/css">
    .card {
    margin-bottom: 24px;
    box-shadow: 0 0.75rem 1.5rem rgb(18 38 63 / 3%)!important;
/*    background-color: inherit;*/
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
                            <h4 class="mb-sm-0 font-size-18">Vender Detail</h4>

                            <div class="page-title-right">
                                
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
                                            <h4 class="mt-1 mb-3">{{$vender->hotel_name}}</h4>

                                            <p class="text-muted float-start me-3">
                                                <?php
                                                  for($i = 1;$i <= $vender->hotel_review; $i++){
                                                ?>
                                                  <span class="bx bxs-star text-warning"></span>
                                                  <?php }
                                                      $starnot = 5-$vender->hotel_review;
                                                      for($j = 1;$j <= $starnot; $j++){ ?>
                                                                            
                                                        <span class="bx bxs-star"></span>
                                                  <?php } ?>
                                                
                                            </p>
                                            <p class="text-muted mb-4">( {{$review}} Customers Review )</p>
                                            
                                            <p class="text-muted mb-4">{!!$vender->description!!}</p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                                <div class="row">
                                    <h4>Rooms</h4>
                                        @foreach($vender['get_room'] as $room)
                                        <div class="col-lg-4">
                                            <div class="card">
                                                
                                                <div class="card-body">
                                                    <div class="float-end ms-2">
                                                        <span class="badge rounded-pill badge-soft-success font-size-12" id="task-status">{{$room['get_cat']->title}}</span>
                                                    </div>
                                                    <div>
                                                        <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark" id="task-name">{{$room['get_cat']->title}}</a></h5>
                                                        <p class="text-muted">{{$room->room_size}} sqft &nbsp;&nbsp;{{$room->no_of_room}} Rooms</p> 
                                                    </div>
        
                                                    <ul class="list-inine ps-0 mb-4">
                                                        @foreach($room['get_image'] as $image)
                                                        <li class="list-inline-item">
                                                            <a href="javascript: void(0);">
                                                                <div class="border rounded avatar-sm">
                                                                    <span class="avatar-title bg-transparent">
                                                                        <img src="{{url($image->image)}}" alt="" class="avatar-xs">
                                                                    </span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        @endforeach
                                                    </ul>

                                                    <div class="text-end">
                                                        <h5 class="font-size-15 mb-1" id="task-budget">₹{{$room->discount_amount}} <strike>₹{{$room->room_rent}}</strike> </h5>
                                                        
                                                    </div>
                                                </div>
                                                            
                                            </div>
                                        </div>
                                        @endforeach
                                    <!-- end col -->
                                </div>

                                @if(count($vender['get_food'])>0)
                                <div class="row">
                                    <h4>Foods</h4>
                                        @foreach($vender['get_food'] as $food)
                                        <div class="col-lg-4">
                                            <div class="card">
                                                
                                            <div class="card-body">
                                                <div class="float-end ms-2">
                                                    <span class="badge rounded-pill badge-soft-success font-size-12" id="task-status">{{$food['get_cat']->title}}</span>
                                                </div>
                                                <div>
                                                    <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark" id="task-name">{{$food['get_cat']->title}}</a></h5>
                                                    
                                                </div>
    
                                                <ul class="list-inine ps-0 mb-4">
                                                    <li class="list-inline-item">
                                                        <a href="javascript: void(0);">
                                                            <div class="border rounded avatar-sm">
                                                                <span class="avatar-title bg-transparent">
                                                                    <img src="{{url($food->image)}}" alt="" class="avatar-xs">
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>

                                                <div class="text-end">
                                                    <h5 class="font-size-15 mb-1" id="task-budget">{{$food->price}}</h5>
                                                    <p class="mb-0 text-muted">INR</p>
                                                </div>
                                            </div>
                                                            
                                            </div>
                                        </div>
                                        @endforeach
                                    <!-- end col -->
                                </div>
                                @endif
                                <div class="mt-5">
                                    <!-- <h5 class="mb-3">Specifications :</h5> -->

                                    <div class="table-responsive">
                                        <table class="table mb-0 table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th scope="row" style="width: 400px;">Owner Name</th>
                                                    <td>{{$vender->name}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" style="width: 400px;">Category</th>
                                                    <td>{{$vender->category_data}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Collection</th>
                                                    <td>{{$vender->collection_data}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Email</th>
                                                    <td>{{$vender->email}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Mobile</th>
                                                    <td>+{{$vender->country_code}} {{$vender->mobile}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Address</th>
                                                    <td>{{$vender->address}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">City</th>
                                                    <td>{{$vender['get_city']->city}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Description</th>
                                                    <td>{!!$vender->description!!}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Policies</th>
                                                    <td>{!!$vender->policies!!}</td>
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
                <!-- end row -->

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
                                        <th>Customer</th>
                                        <th>Check in</th>
                                        <th>Check Out</th>
                                        <th>Booking Date</th>
                                        <th>Status</th>
                                        <th>View Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($vender['vender_get_booking'] as $bkey=>$booking)
                                    <tr>
                                        <td>{{$bkey+1}}</td>
                                        <td>{{$booking->booking_id}}</td>
                                        <td>{{isset($booking['get_user']->name)?$booking['get_user']->name:'N/A'}}</td>
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

                

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->
@endsection 
        