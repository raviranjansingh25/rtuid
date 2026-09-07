@extends('coach.layout.layout')
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
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session()->get('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mt-4 mt-xl-3 d-flex align-items-center justify-content-between">
                                        <h4 class="mt-1 mb-3">
                                            <img src="{{url(isset($user->profile)?$user->profile:'public/noimage.png')}}" alt="logo" height="50" width="50" class="rounded-circle" />&nbsp;&nbsp;{{$user->name}}
                                        </h4>

                                        
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <tbody>
                                            <tr><th scope="row">Name</th><td>{{ $user->name ?? 'N/A' }}</td></tr>
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
                                                        <a href="{{ url($user->aadhar_front) }}" target="_blank"><img src="{{ url($user->aadhar_front) }}" alt="Photo" width="100"></a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th scope="row">Aadhar Back</th>
                                                <td>
                                                    @if($user->aadhar_back)
                                                        <a href="{{ url($user->aadhar_back) }}" target="_blank"><img src="{{ url($user->aadhar_back) }}" alt="Photo" width="100"></a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th scope="row">DOB Certificate</th>
                                                <td>
                                                    @if($user->dob_certificate)
                                                       <a href="{{ url($user->dob_certificate) }}" target="_blank"> <img src="{{ url($user->dob_certificate) }}" alt="Photo" width="100"></a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th scope="row">Belt Certificate</th>
                                                <td>
                                                    @if($user->belt_certificate)
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
                                            
                                            <!-- Status Row -->
                                            <tr>
                                                <th scope="row">Status</th>
                                                <td>
                                                    @php 
                                                        if ($user->status == 1) {
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
                                                            {{ $text }}
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#save_it_uid_btn').click(function(e) {
            e.preventDefault();
            
            var it_uid = $('#it_uid_input').val();
            var userId = '{{ $user->id }}';

            if(it_uid.trim() === '') {
                swal("Warning", "Please enter IT UID!", "warning");
                return;
            }

            $.ajax({
                url: "{{ url('coach/user/update-it-uid') }}",
                type: "POST",
                data: {
                    '_token': '{!! csrf_token() !!}',
                    'user_id': userId,
                    'it_uid': it_uid
                },
                success: function(response) {
                    if(response.status === 'success') {
                        swal({
                            title: "Success!",
                            text: response.message || "IT UID Updated Successfully!",
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        swal("Error", "Failed to update IT UID", "error");
                    }
                },
                error: function() {
                    swal("Error", "Server request failed!", "error");
                }
            });
        });
    });
</script>

@endsection