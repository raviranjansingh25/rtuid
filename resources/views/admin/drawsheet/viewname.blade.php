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
                                <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                                <!-- <li class="breadcrumb-item">{{$title}}</li> -->
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <form id="search-form" method="post">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="from_date">Search Title</label>
                                                <input type="text" name="title" class="form-control" placeholder="Title">
                                            </div>



                                            <div class="col-md-3">
                                                <label for="from_date">Status</label>
                                                <select class="form-control" name="status">
                                                    <option value="">All Type</option>
                                                    <option value="1">Active</option>
                                                    <option value="2">Deactive</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3 col1 mt-4">
                                                <!-- <p for="date_to"></p><br> -->
                                                <button id="filter" class="btn btn-primary btn-fw">Apply Filters</button>
                                                <button class="btn btn-primary btn-fw" id="clearBtn">Reset Filters</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="d-flex justify-content-end mb-3">
                                        <button type="button" class="btn btn-warning" onclick="verifyDrawSheetForSubadmin()">Verify Draw Sheet for Subadmin (OTP)</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table_data mb-0 table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Weight Category</th>
                                        <th>Gender</th>
                                        <th>Event</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Players</th>
                                        
                                        <th data-orderable="false">Action</th>
                                    </tr>
                                </thead>


                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('.table_data').DataTable({
                "bFilter": false,

                ajax: {
                    url: "{{ url('/admin/draw-sheet-name-data/'.$id) }}",
                    data: function(d) {
                        d.title = $('input[name="title"]').val();
                        d.status = $('select[name="status"]').val();
                    }
                },
                dom: 'Bfrtip',
                buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    title: 'Tournament Data',
                    // exportOptions: {
                    //     columns: [0, 1, 2, 3] // Exclude action column
                    // },
                    modifier: {
                        page: 'current', // ✅ This ensures only currently displayed page rows are exported
                        search: 'none',
                    }
                }
            ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'get_waight_cat',
                        name: 'get_waight_cat'
                    },
                    {
                        data: 'gender_name',
                        name: 'gender_name'
                    },
                    {
                        data: 'event',
                        name: 'event'
                    },
                    {
                        data: 'start_date',
                        name: 'startdate'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'player_count',
                        name: 'player_count'
                    },
                   
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

            $('#search-form').on('submit', function(e) {
                table.draw();
                e.preventDefault();
            });


            $('#filter').click(function() {
                table.ajax.reload();
            });

            $('#clearBtn').click(function() {
                $('#search-form')[0].reset();
                table.ajax.reload();
            });

        });

        function verifyDrawSheetForSubadmin() {
            $.ajax({
                url: "{{ route('send.otp') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    tournament_id: "{{ $id }}",
                },
                success: function(res) {
                    if (!res.success) {
                        swal("Error", res.message || "Could not send OTP.", "error");
                        return;
                    }

                    swal({
                        title: 'Enter OTP',
                        text: 'OTP sent to admin phone.',
                        input: 'text',
                        inputAttributes: { maxlength: 6 },
                        showCancelButton: true,
                        confirmButtonText: 'Verify',
                    }).then(function(result) {
                        if (!result.isConfirmed) return;

                        $.ajax({
                            url: "{{ route('verify.draw.sheet.subadmin') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                tournament_id: "{{ $id }}",
                                otp: result.value
                            },
                            success: function(res2) {
                                if (res2.success) {
                                    swal("Success", res2.message, "success");
                                } else {
                                    swal("Error", res2.message, "error");
                                }
                            }
                        });
                    });
                }
            });
        }
    </script>


    @endsection