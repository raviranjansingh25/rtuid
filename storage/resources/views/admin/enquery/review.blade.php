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
                                            <label for="from_date">Hotel</label>
                                            <select class="form-control" name="hotel">
                                              <option value="">All</option>
                                              @foreach($hotel as $hotel)
                                              <option value="{{$hotel->id}}">{{$hotel->hotel_name}}</option>
                                              @endforeach
                                            </select>
                                          </div>

                                          <div class="col-md-3">
                                            <label for="from_date">Rate</label>
                                            <select class="form-control" name="rate">
                                              <option value="">All</option>
                                              <option value="1">1</option>
                                              <option value="2">2</option>
                                              <option value="3">3</option>
                                              <option value="4">4</option>
                                              <option value="5">5</option>
                                            </select>
                                          </div>

                                          

                                          <div class="col-md-4 col1 mt-4">
                                              <!-- <p for="date_to"></p><br> -->
                                              <button id="filter" class="btn btn-primary btn-fw">Apply Filters</button>
                                              <button class="btn btn-primary btn-fw" id="clearBtn">Reset Filters</button>
                                          </div>
                                        </div>
                                    </form>
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0 font-size-18"></h4>

                                        

                                    </div>
                                </div>
                            </div>
                            <table  class="table table-bordered table_data">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Hotel Name</th>
                                    <th>Rate</th>
                                    <th>Review</th>
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
                    url: "{{ url('/admin/review-data') }}",
                    data: function (d) {
                        d.hotel = $('select[name="hotel"]').val();
                        d.rate = $('select[name="rate"]').val();
                    }
                },
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'user_name', name: 'user_name'},
                  {data: 'hotel_name', name: 'hotel_name'},
                  {data: 'rate', name: 'rate'},
                  {data: 'review', name: 'review'}
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
           
    });

       
    </script>

    
    
@endsection 
    

    
