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
                                                <button id="filter" class="btn btn-primary btn-fw">Apply Filters</button>
                                                <button class="btn btn-primary btn-fw" id="clearBtn">Reset Filters</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0 font-size-18"></h4>

                                        <div class="page-title-right add_button">
                                            <a href="{{url('/admin/apply-tournament-name_add/'.$id)}}"> 
                                                <button type="button" class="btn btn-success waves-effect waves-light">Add</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-bordered table_data mb-0 table-bordered">
                                    <thead>
                                        <tr>
                                            <!-- Select All Checkbox -->
                                            <th data-orderable="false" style="width: 40px; text-align: center;">
                                                <input type="checkbox" id="select_all">
                                            </th>
                                            <th>#</th>
                                            <th>District</th>
                                            <th>Code</th>
                                            <th>It Uid</th>
                                            <th>Gender</th>
                                            <th>DOB</th>
                                            <th>Name</th>
                                            <th>Phone Number</th>
                                            <th>Coach Name</th>
                                            <th>Category</th>
                                            <th>Event</th>
                                            <th>Weight Category</th>
                                            <th>Actual Weight</th>
                                            <th data-orderable="false">Id Card</th>
                                            <th data-orderable="false">Form</th>
                                            <th data-orderable="false">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <a href="{{ url('/admin/all_single_reg_form/'.$id) }}" class="btn btn-success">Download All Form</a>
                                <a href="{{ url('/admin/all_single_card/'.$id) }}" class="btn btn-success">Download All Card</a>
                                <!-- Edit UID Update Button -->
                                <button type="button" id="update_edit_uid_btn" class="btn btn-warning">Set Edit UID (1)</button>
                            </div>

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
            // Stores selected row IDs persistently across pagination and filter reloads
            var selectedRows = [];

            var table = $('.table_data').DataTable({
                "bFilter": false,
                lengthMenu: [
                  [10, 25, 50, 100, 500, 1000, -1],
                  [10, 25, 50, 100, 500, 1000, "All"]
                ],
                ajax: {
                    url: "{{ url('/admin/apply-tournament-name-data/'.$id) }}",
                    data: function(d) {
                        d.title = $('input[name="title"]').val();
                        d.status = $('select[name="status"]').val();
                    }
                },
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: 'Tournament Data',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                        }
                    }
                ],
                columns: [
    {
        data: 'user_id',
        name: 'checkbox',
        orderable: false,
        searchable: false,
        render: function(data, type, row) {
            var userId = (row.user_id || row.id).toString();

            // Check if edit_uid is 1 in DB or already selected by user
            var isDbChecked = (row.edit_uid == 1);

            if (isDbChecked && !selectedRows.includes(userId)) {
                selectedRows.push(userId);
            }

            var isChecked = selectedRows.includes(userId) ? 'checked' : '';

            return '<div class="text-center"><input type="checkbox" class="user_checkbox" value="' + userId + '" ' + isChecked + '></div>';
        }
    },
    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
    { data: 'district', name: 'district' },
    { data: 'code', name: 'code' },
    { data: 'it_uid', name: 'it_uid' },
    { data: 'gender', name: 'gender' },
    { data: 'dob', name: 'dob' },
    { data: 'name', name: 'name' },
    { data: 'phone', name: 'phone' },
    { data: 'coach', name: 'coach' },
    { data: 'category', name: 'category' },
    { data: 'event', name: 'event' },
    { data: 'waight_category', name: 'waight_category' },
    { data: 'actul_waight', name: 'actul_waight' },
    { data: 'id_card', name: 'id_card' },
    { data: 'form', name: 'form' },
    { data: 'action', name: 'action' }
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
                selectedRows = [];
                $('#select_all').prop('checked', false);
                table.ajax.reload();
            });

            // 1. Select All Checkbox Logic
            $('#select_all').on('click', function() {
                var isChecked = this.checked;
                $('.user_checkbox').each(function() {
                    var id = $(this).val().toString();
                    $(this).prop('checked', isChecked);
                    
                    if (isChecked) {
                        if (!selectedRows.includes(id)) {
                            selectedRows.push(id);
                        }
                    } else {
                        selectedRows = selectedRows.filter(function(itemId) {
                            return itemId !== id;
                        });
                    }
                });
            });

            // 2. Individual Checkbox Change Event
            $(document).on('change', '.user_checkbox', function() {
                var id = $(this).val().toString();

                if (this.checked) {
                    if (!selectedRows.includes(id)) {
                        selectedRows.push(id);
                    }
                } else {
                    selectedRows = selectedRows.filter(function(itemId) {
                        return itemId !== id;
                    });
                }

                updateSelectAllCheckbox();
            });

            // 3. Keep "Select All" state accurate when moving through pages/redraws
            table.on('draw', function() {
                updateSelectAllCheckbox();
            });

            function updateSelectAllCheckbox() {
                var totalVisible = $('.user_checkbox').length;
                var totalCheckedVisible = $('.user_checkbox:checked').length;

                if (totalVisible > 0 && totalVisible === totalCheckedVisible) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }
            }

            // 4. Update edit_uid to 1 Button Click Handler
            $('#update_edit_uid_btn').click(function(e) {
                e.preventDefault();

                if (selectedRows.length === 0) {
                    swal("Warning", "Please select at least one user!", "warning");
                    return;
                }

                swal({
                    title: "Are you sure?",
                    text: "The edit_uid for " + selectedRows.length + " selected user(s) will be set to 1!",
                    icon: "info",
                    buttons: true,
                    dangerMode: false,
                }).then((willUpdate) => {
                    if (willUpdate) {
                        $.ajax({
                            url: "{{ url('admin/update-edit-uid') }}",
                            type: 'POST',
                            data: {
                                '_token': '{!! csrf_token() !!}',
                                'ids': selectedRows,
                                'edit_uid': 1
                            },
                            success: function(res) {
                                if (res.status === "success") {
                                    swal({
                                        title: "Success!",
                                        text: res.message || "Edit UID updated successfully!",
                                        icon: "success",
                                        timer: 1500,
                                        buttons: false
                                    }).then(() => {
                                        selectedRows = []; // Clear array after update
                                        $('#select_all').prop('checked', false);
                                        table.ajax.reload();
                                    });
                                } else {
                                    swal("Error", "Something went wrong!", "error");
                                }
                            },
                            error: function() {
                                swal("Error", "Server request failed!", "error");
                            }
                        });
                    }
                });
            });

            // Delete Logic
            $(document).on("click", ".delete-button", function(e) {
                e.preventDefault();
                var id = $(this).attr("data-id");

                swal({
                    title: "Are you sure you want to delete this?",
                    text: "You will not be able to recover this action!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ url('admin/apply-tournament-name_delete') }}",
                            dataType: 'json',
                            type: 'GET',
                            data: { 'id': id },
                            success: function(res) {
                                if (res.status === "success") {
                                    swal({
                                        title: "Deleted!",
                                        text: res.message,
                                        icon: "success",
                                        buttons: false,
                                        timer: 1000
                                    }).then(() => {
                                        selectedRows = selectedRows.filter(function(itemId) {
                                            return itemId !== id.toString();
                                        });
                                        table.ajax.reload();
                                    });
                                } else {
                                    swal("Error", "{!! trans('language.delete_already_used') !!}", "error");
                                }
                            }
                        });
                    } else {
                        swal({
                            title: "Cancelled",
                            text: "You cancelled your action",
                            icon: "error",
                            buttons: false,
                            timer: 1500
                        });
                    }
                });
            });

        });
    </script>

    <script type="text/javascript">
        function changeStatus(id, status) {
            $.ajax({
                url: "{{ url('admin/tournament/status') }}",
                type: 'GET',
                data: {
                    'id': id,
                    'status': status,
                },
                success: function(res) {
                    if (res == "Success") {
                        swal({
                            title: "Change Status!",
                            text: "Event status changed successfully",
                            icon: "success",
                            buttons: false,
                            timer: 1000
                        }).then(() => {
                            $('.table_data').DataTable().ajax.reload();
                        });
                    }
                }
            });
        }
    </script>

@endsection