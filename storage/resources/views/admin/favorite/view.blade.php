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
                                <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboards</a></li>
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
                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0 font-size-18"></h4>

                                        <div class="page-title-right add_button">
                                            <a href="{{route('category_add')}}"> <button type="button" class="btn btn-success waves-effect waves-light">Add</button></a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <table  class="table table-bordered dt-responsive nowrap w-100 table_data">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Hotel</th>
                                    <th>Created</th>
                                    
                                    <!-- <th>Action</th> -->
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
       $(document).ready(function () {
        
          var table = $('.table_data').DataTable({
            "bFilter": false,
              ajax: "{{ url('/admin/booking-data') }}",
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'user', name: 'user'},
                  {data: 'hotel', name: 'hotel'},
                  {data: 'Created', name: 'Created'},
                  // {data: 'action', name: 'action'}
              ]
          });   

          //delete
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
                        $.ajax(
                            {
                                url: "{{ url('admin/category/delete') }}",
                                datatType: 'json',
                                type: 'GET',
                                data: {
                                    'id': id,
                                },
                                success: function (res)
                                {
                                    
                                   if (res.status === "success")
                                    {
                                        swal({
                                        title: "Deleted!",text: res.message,icon: "success",dangerMode: true,buttons: false,
                                        timer: 1000
                                    })
                                   .then(() => {
                                            table.ajax.reload();
                                        })

                                    } else
                                    {
                                        swal("Error", "{!!trans('language.delete_already_used') !!}", "error");
                                    }

                                    
                                        
                                }
                            });
                        // swal("Deleted!", "{!! trans('language.deleted_successfully') !!}", "success");
                    } else {
                        swal({
                            title: "Cancelled",text: "You cancelled your action",icon: "error",buttons: false,
                            timer: 1500
                        })

                    }
                })

            });
       
      });
    </script>

    <script type="text/javascript">

      function changeStatus(id,status){
        var token = '{!!csrf_token()!!}';
          
        $.ajax(
        {
            url: "{{ url('admin/category/status') }}",
            type: 'GET',
            data: {
                'id': id,
                'status': status,
            },
            success: function (res)
            {
                if (res == "Success")
                {
                    swal({
                    title: "Change Status!",
                    text: "Category Status Change Sussessfully",
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
    

    
