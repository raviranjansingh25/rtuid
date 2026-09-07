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
                                            <input type="text" name="question" class="form-control" placeholder="Title">
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
                                            <a href="{{route('faq_add')}}"> <button type="button" class="btn btn-success waves-effect waves-light">Add</button></a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <table  class="table table-bordered table_data mb-0 table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Category</th>
                                    <th>Status</th>
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
       $(document).ready(function () {
          var table = $('.table_data').DataTable({
            "bFilter": false,
                ajax: {
                    url: "{{ url('/admin/faq-data') }}",
                    data: function (d) {
                        d.question = $('input[name="question"]').val();
                        d.status = $('select[name="status"]').val();
                    }
                },
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'question', name: 'question'},
                  {data: 'answer', name: 'answer'},
                  {data: 'category', name: 'category'},
                  {data: 'status', name: 'status'},
                  {data: 'action', name: 'action'}
              ]
          });

          $('#search-form').on('submit', function(e) {
              table.draw();
              e.preventDefault();
          });


          $('#filter').click(function(){
              table.ajax.reload();
          });

          $('#clearBtn').click(function(){
            $('#search-form')[0].reset();
              table.ajax.reload();
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
                                url: "{{ url('admin/faq/delete') }}",
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
            url: "{{ url('admin/faq/status') }}",
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
                    text: "FAQ Status Change Sussessfully",
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
    

    
