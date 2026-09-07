@extends('admin.layout.layout')
@section('content')

      @php
        $check_in = strtotime($booking['check_in']);
        $check_out = strtotime($booking['check_out']);
        $day_diff = $check_out - $check_in;
        $day_count = $day_diff/(60*60*24);
    @endphp      

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
                            <h4 class="mb-sm-0 font-size-18">Booking Details</h4>

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
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-16">Order Id # {{$booking->booking_id}}</h4>
                                    <div class="mb-4">
                                        <h3><img src="{{url($booking['get_hotel']->profile)}}" alt="logo" height="30" width="30" class="rounded-circle" /> {{$booking['get_hotel']->hotel_name}}</h3>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <address>
                                            <strong>Address:</strong><br>
                                            {{$booking['get_hotel']->address}}, {{$booking['get_hotel']->city_name}}
                                        </address>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <address class="mt-2 mt-sm-0">
                                            <strong>Guest:</strong><br>
                                            {{$booking->guest_name}}<br>
                                            {{$booking->email}}<br>
                                            {{$booking->phone}}<br>
                                            
                                        </address>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mt-3">
                                        <address>
                                            <strong>Check In:</strong><br>
                                            {{date('D, d M Y', strtotime($booking->check_in))}}<br>
                                            
                                        </address>
                                        <address>
                                            <strong>Check Out:</strong><br>
                                            {{date('D, d M Y', strtotime($booking->check_out))}}<br>
                                            
                                        </address>
                                    </div>
                                    <div class="col-sm-6 mt-3 text-sm-end">
                                        <address>
                                            <strong>Booking Date:</strong><br>
                                            {{date('D, d M Y h:i A', strtotime($booking->created_at))}}<br><br>
                                        </address>
                                        <address>
                                            <strong>Total Guest:</strong>
                                            {{$booking->guest_qty}}<br>
                                        </address>
                                    </div>
                                </div>
                                <div class="py-2 mt-3">
                                    <h3 class="font-size-15 fw-bold">Booking summary</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th style="width: 70px;">No.</th>
                                                <th>Room</th>
                                                <th>Room Size</th>
                                                <th>Room Price</th>
                                                <th>Room Qty</th>
                                                <th>Day</th>
                                                <th class="text-end">Price Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($booking['get_room'] as $key=>$room)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$room->category}}</td>
                                                <td>{{$room->room_size}}sqft</td>
                                                <td>₹{{$room->discount_amount}}</td>
                                                <td>{{$booking->room_qty}}</td>
                                                <td>{{$day_count}}</td>
                                                <td class="text-end">₹{{$room->discount_amount*$booking->room_qty*$day_count}}</td>
                                            </tr>
                                            @endforeach
                                        @if(count($booking['get_food'])>0)
                                           <tr>
                                                <th style="width: 70px;">No.</th>
                                                <th>Food</th>
                                                <th class="text-end"></th>
                                                <th >Food Price</th>
                                                <th class="text-end"></th>
                                                <th >Day</th>
                                                <th class="text-end">Price</th>
                                            </tr>
                                            @foreach($booking['get_food'] as $key=>$food)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$food->category}}</td>
                                                <td></td>
                                                <td>₹{{$food->price}}</td>
                                                <td></td>
                                                <td>{{$day_count}}</td>
                                                <td class="text-end">₹{{$food->price*$day_count}}</td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class="border-0 text-end">
                                                    <strong>Subtotal</strong></td>
                                                <td class="border-0 text-end">₹{{$booking->total_amount}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class="border-0 text-end">
                                                    <strong>Total</strong></td>
                                                <td class="border-0 text-end"><h4 class="m-0">₹{{$booking->total_amount}}</h4></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-print-none">
                                    <div class="float-end">
                                        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- end row -->

                

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->
@endsection 
        