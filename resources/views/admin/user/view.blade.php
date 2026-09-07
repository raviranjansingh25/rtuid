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
                                                 <label for="from_date">Search Name</label>
                                                 <input type="text" name="title" class="form-control" placeholder="Name">
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
                                     <div class="d-sm-flex align-items-center justify-content-between">
                                         <h4 class="mb-sm-0 font-size-18"></h4>

                                        @php 
                                        $admin = Auth::guard('admin')->user();
                                        @endphp
                                        @if($admin->id == 1)
                                         <div class="page-title-right add_button">
                                             <a href="{{route('user_add')}}"> <button type="button" class="btn btn-success waves-effect waves-light">Add</button></a>
                                         </div>
                                        @endif
                                     </div>
                                 </div>
                             </div>
                             <div class="table-responsive">
                                <table class="table table-bordered table_data mb-0 table-bordered">
    <thead>
        <tr>
            <th><input type="checkbox" id="select_all"></th>
            <th>#</th>
            <th>District</th>
            <th>Code</th>
            <th>IT UID</th>
            <th>Gender</th>
            <th>DOB</th>
            <th>Name</th>
            <!--<th>Email Address</th>-->
            <th>Phone Number</th>
            <th>Coach name</th>
            <th>Coach Permission</th>
            <th>Status</th>
            <th>Subadmin Verified</th>
            <th data-orderable="false">Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Bulk apply button -->
<button class="btn btn-primary mt-2" id="applyCoachPermission">Apply Coach Permission</button>
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
    $(document).ready(function () {
        var table = $('.table_data').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            stateDuration: -1,
            lengthMenu: [
                  [10, 25, 50, 100, 500, 1000, -1],
                  [10, 25, 50, 100, 500, 1000, "All"]
                ],
            "bFilter": false,
            ajax: {
                url: "{{ url('/admin/user-data') }}",
                data: function (d) {
                    d.title = $('input[name="title"]').val();
                    d.status = $('select[name="status"]').val();
                }
            },
            dom: "<'row mb-3'<'col-sm-2'l><'col-sm-6 text-end'B>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>", // Needed to display buttons
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    title: 'Tournament Data',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10] // Exclude action column
                    },
                    modifier: {
                        page: 'current', // ✅ This ensures only currently displayed page rows are exported
                        search: 'none',
                    }
                }
            ],
            columns: [
    {
        data: 'checkbox',
        orderable: false,
        searchable: false
    },
    {
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data: 'district_name',
        name: 'district'
    },
    {
        data: 'code_id',
        name: 'code_id'
    },
    {
        data: 'it_uid',
        name: 'it_uid'
    },
{
    data: 'user_gender',
    name: 'gender'
},
    {
        data: 'dob',
        name: 'dob'
    },
    {
        data: 'name',
        name: 'name'
    },
    {
        data: 'contact_number',
        name: 'contact_number'
    },
    {
        data: 'coach_name',
        name: 'coach_name'
    },
    {
        data: 'coach_act',
        orderable: false,
        searchable: false
    },
    {
        data: 'status',
        name: 'status'
    },
    {
        data: 'subadmin_verified_by',
        orderable: false,
        searchable: false
    },
    {
        data: 'action',
        orderable: false,
        searchable: false
    }
]
        });

        $('#search-form').on('submit', function (e) {
            table.draw();
            e.preventDefault();
        });

        $('#filter').click(function () {
            table.ajax.reload();
        });

        $('#clearBtn').click(function () {
            $('#search-form')[0].reset();
            table.ajax.reload();
        });

        // select all checkbox
        $(document).on('click', '#select_all', function () {
            $('.row_checkbox').prop('checked', this.checked);
        });

        // Bulk Apply Coach Permission
        $('#applyCoachPermission').click(function () {
            var selectedIds = [];
            $('.row_checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                swal("Error", "Please select at least one user.", "error");
                return;
            }

            swal({
                title: "Apply Coach Permission?",
                text: "This will update permission for selected users.",
                icon: "info",
                buttons: true,
                dangerMode: false,
            }).then((confirm) => {
                if (confirm) {
                    $.ajax({
                        url: "{{ url('/admin/users/bulk-coach-permission') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: selectedIds
                        },
                        success: function (response) {
                            if (response.status == 'success') {
                                swal("Success", response.message, "success");
                                table.ajax.reload();
                            } else {
                                swal("Error", response.message, "error");
                            }
                        }
                    });
                }
            });
        });

        // delete action (unchanged)
        $(document).on("click", ".delete-button", function (e) {
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
                        url: "{{ url('admin/user/delete') }}",
                        type: 'GET',
                        data: { 'id': id },
                        success: function (res) {
                            if (res.status === "success") {
                                swal({
                                    title: "Deleted!",
                                    text: res.message,
                                    icon: "success",
                                    timer: 1000,
                                    buttons: false
                                }).then(() => {
                                    table.ajax.reload();
                                });
                            } else {
                                swal("Error", "{!!trans('language.delete_already_used') !!}", "error");
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "You cancelled your action", "error");
                }
            });
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
                                 $('.table_data').DataTable().ajax.reload();
                             })

                     }

                 }
             });
         }
     </script>
     
          <script type="text/javascript">
         function coach_act(id, status) {
             var token = '{!!csrf_token()!!}';

             $.ajax({
                 url: "{{ url('admin/user/coach_act') }}",
                 type: 'GET',
                 data: {
                     'id': id,
                     'status': status,
                 },
                 success: function(res) {
                     if (res == "Success") {
                         swal({
                                 title: "Change Status!",
                                 text: "Permission Status Change Sussessfully",
                                 icon: "success",
                                 dangerMode: true,
                                 buttons: false,
                                 timer: 1000
                             })
                             .then(() => {
                                 $('.table_data').DataTable().ajax.reload();
                             })

                     }

                 }
             });
         }
     </script>

     @endsection