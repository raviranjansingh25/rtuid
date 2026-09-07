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
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0 font-size-18"></h4>



                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table_data mb-0 table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email Address</th>
                                        <th>Role</th>

                                        <th>Message</th>
                                        <th>Date</th>
                                        <th data-orderable="false">Action</th>
                                    </tr>
                                </thead>


                                <tbody>

                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- container-fluid -->
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <p class="view_mess">
                    </p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Page-content -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('.table_data').DataTable({
                "bFilter": false,
                ajax: "{{ url('/admin/contact-data') }}",
                dom: 'Bfrtip',
                buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    title: 'Tournament Data',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5] // Exclude action column
                    },
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },

                    {
                        data: 'view',
                        name: 'view'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

        });

        //delete
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
                        url: "{{ url('admin/delete-enquery') }}",
                        datatType: 'json',
                        type: 'GET',
                        data: {
                            'id': id,
                        },
                        success: function(res) {

                            if (res.status === "success") {
                                swal({
                                        title: "Deleted!",
                                        text: res.message,
                                        icon: "success",
                                        dangerMode: true,
                                        buttons: false,
                                        timer: 1000
                                    })
                                    .then(() => {
                                        table.ajax.reload();
                                    })

                            } else {
                                swal("Error", "{!!trans('language.delete_already_used') !!}", "error");
                            }



                        }
                    });
                    // swal("Deleted!", "{!! trans('language.deleted_successfully') !!}", "success");
                } else {
                    swal({
                        title: "Cancelled",
                        text: "You cancelled your action",
                        icon: "error",
                        buttons: false,
                        timer: 1500
                    })

                }
            })

        });
    </script>
    <script>
        function viewdata(id) {
            $.ajax({
                url: "{{ url('/admin/contact-data/view_message') }}",
                type: 'GET',
                data: {
                    'id': id,

                },
                success: function(res) {
                    if (res.status == "success") {
                        $('.view_mess').html(res.message);
                        $('#myModal').modal('show');
                    }

                }
            });

        }
    </script>



    @endsection