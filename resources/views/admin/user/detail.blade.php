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
                                            <tr><th scope="row">Name</th><td>{{ $user->name ?? 'N/A' }}</td></tr>
                                             <tr><th scope="row">IT UID</th><td>{{ $user->it_uid ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Father Name</th><td>{{ $user->father_name ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Date of Birth</th><td>{{ $user->dob ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Contact Number</th><td>{{ isset($user->country_code) ? '+' . $user->country_code . ' ' . $user->contact_number : $user->contact_number }}</td></tr>
                                            <tr><th scope="row">WhatsApp Number</th><td>{{ $user->whatsapp_number ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Email</th><td>{{ $user->email ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">BELS Certificate</th><td>{{ $user->bels_certificate ?? 'N/A' }}</td></tr>
                                            <tr>
                                                <th scope="row">Gender</th>
                                                <td>
                                                    @php
                                                        $genders = [1 => 'Male', 2 => 'Female', 3 => 'Other'];
                                                    @endphp
                                                    {{ $genders[$user->gender] ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr><th scope="row">Role</th><td>{{ $user->role == 1 ? 'User' : 'Admin' }}</td></tr>
                                            <tr><th scope="row">Category</th><td>{{ $user->category_name ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">State</th><td>{{ $user->state ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">District</th><td>{{ $user->district ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">City</th><td>{{ $user->city ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Pin Code</th><td>{{ $user->pin_code ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Address</th><td>{{ $user->address ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Aadhar Number</th><td>{{ $user->aadhar_number ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Aadhar Front</th>
                                                <td>
                                                    @if($user->aadhar_front)
                                                        <!-- <a href="{{ asset('storage/'.$user->aadhar_front) }}" target="_blank">View</a> -->
                                                        <a href="{{ url($user->aadhar_front) }}" target="_blank"><img src="{{ url($user->aadhar_front) }}" alt="Photo" width="100"></a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th scope="row">Aadhar Back</th>
                                                <td>
                                                    @if($user->aadhar_back)
                                                        <!-- <a href="{{ asset('storage/'.$user->aadhar_back) }}" target="_blank">View</a> -->
                                                        <a href="{{ url($user->aadhar_back) }}" target="_blank"><img src="{{ url($user->aadhar_back) }}" alt="Photo" width="100"></a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th scope="row">DOB Certificate</th>
                                                <td>
                                                    @if($user->dob_certificate)
                                                        <!-- <a href="{{ asset('storage/'.$user->dob_certificate) }}" target="_blank">View</a> -->
                                                       <a href="{{ url($user->dob_certificate) }}" target="_blank"> <img src="{{ url($user->dob_certificate) }}" alt="Photo" width="100"></a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th scope="row">Belt Certificate</th>
                                                <td>
                                                    @if($user->belt_certificate)
                                                        <!-- <a href="{{ asset('storage/'.$user->dob_certificate) }}" target="_blank">View</a> -->
                                                       <a href="{{ url($user->belt_certificate) }}" target="_blank"> <img src="{{ url($user->belt_certificate) }}" alt="Photo" width="100"></a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th scope="row">Photo</th>
                                                <td>
                                                    @if($user->photo)
                                                        <a href="{{ url($user->photo) }}" target="_blank"><img src="{{ url($user->photo) }}" alt="Photo" width="100"></a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th scope="row">Signature</th>
                                                <td>
                                                    @if($user->signature)
                                                        <!-- <img src="{{ asset('storage/'.$user->signature) }}" alt="Signature" width="100"> -->
                                                        <a href="{{ url($user->signature) }}" target="_blank"><img src="{{ url($user->signature) }}" alt="Photo" width="100"></a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th scope="row">Coach Name</th><td>{{ $user->coach_name ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Coach Contact</th><td>{{ $user->coach_contact ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Code</th><td>{{ $user->code_id ?? 'N/A' }}</td></tr>
                                            <tr><th scope="row">Created At</th><td>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</td></tr>
                                            <tr><th scope="row">Status</th><td>
                                                @php if ($user->status == 1) {
                                                        $text = 'Approved';
                                                        $color = 'success';
                                                    } elseif ($user->status == 2) {
                                                        $text = 'Pending';
                                                        $color = 'primary';
                                                    } elseif ($user->status == 4) {
                                                        $text = 'Reject';
                                                        $color = 'danger';
                                                    }
                                                @endphp
                                    
                                                     <div class="btn-group">
                                                            <button class="btn btn-{{$color}} dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                {{ $text }}<i class="mdi mdi-chevron-down"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#" onclick="changeStatus({{$user->id}},2)">Pending</a>
                                                                <a class="dropdown-item" href="#" onclick="changeStatus({{$user->id}},1)">Approved</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" onclick="changeStatus({{$user->id}},4)" href="#">Reject</a>
                                                            </div>
                                                        </div>
                                                </td></tr>
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
            <!-- <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <h4 class="mb-sm-0 font-size-18">My Sessions</h4>
                            </div><br>
                            <table class="table table-bordered dt-responsive nowrap w-100 table_data">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Practitioner</th>
                                        <th>Specialities</th>
                                        <th>Sessions Date</th>
                                        <th>Sessions Time</th>
                                        <th>Sessions Duration</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- end col -->
            <!-- <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <h4 class="mb-sm-0 font-size-18">My Purchase Courses</h4>
                            </div><br>
                            <table class="table table-bordered dt-responsive nowrap w-100 table_data">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Course name</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Rating</th>


                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> -->


        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

</div>
<!-- end main content-->

</div>
<!-- END layout-wrapper -->

<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

<script>
    $(document).ready(function() {
        var table = $('.table_data').DataTable({
            "bFilter": false,
        });

    });
</script>
<script type="text/javascript">
     function changeStatus(id, status) {
         var token = '{!!csrf_token()!!}';

         $.ajax({
             url: "{{ url('admin/user/status') }}",
             type: 'GET',
             data: {
                 'id': id,
                 'status': status,
             },
             success: function(res) {
                 if (res == "Success") {
                     swal({
                             title: "Change Status!",
                             text: "User Status Change Sussessfully",
                             icon: "success",
                             dangerMode: true,
                             buttons: false,
                             timer: 1000
                         })
                         .then(() => {
                             window.location.href = "/admin/user";
                         })

                 }

             }
         });
     }
 </script>
@endsection