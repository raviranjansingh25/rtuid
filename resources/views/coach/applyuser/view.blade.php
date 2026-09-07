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
                                 <li class="breadcrumb-item"><a href="{{route('coach_dashboard')}}">Dashboard</a></li>
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

                                         

                                     </div>
                                 </div>
                             </div>
                             <div class="table-responsive">
                             <table class="table table-bordered table_data mb-0 table-bordered">
                                 <thead>
                                     <tr>
                                         <th>#</th>
                                         <th>District</th>
                                         <th>Code</th>
                                         <th>IT UID</th>
                                         <!--<th data-orderable="false">Profile</th>-->
                                         <th>Name</th>
                                         <!--<th>Email Address</th>-->
                                         <th>Phone Number</th>
                                         <th>Gender</th>
                                         <th>DOB</th>
                                        <th>Coach name</th>
                                        <th>Weight</th>
                                        <th>Fill Weight</th>
                                        <th>Apply</th>
                                        
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

     <!-- Weight Modal -->
<div class="modal fade" id="weightModal" tabindex="-1" aria-labelledby="weightModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enter New Weight</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="number" class="form-control" id="weightInput" placeholder="Enter weight">
        <input type="hidden" id="weightUserId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="submitWeight()">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Apply Tournament Modal -->
<div class="modal fade" id="applyTournamentModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Apply for Tournament</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="userId">
        <select id="tournamentDropdown" class="form-control">
          <option value="">Select Tournament</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="submitTournament()">Submit</button>
      </div>
    </div>
  </div>
</div>
     <!-- End Page-content -->
     <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

     <script>
         $(document).ready(function() {
             var table = $('.table_data').DataTable({
                 "bFilter": false,
                 lengthMenu: [
        [10, 25, 50, 100, 500, 1000, -1], 
        [10, 25, 50, 100, 500, 1000, "All"]
    ],
    pageLength: 25,
                 ajax: {
                     url: "{{ url('/coach/applyuser-data') }}",
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
                }
            ],
                 columns: [
                     {
                         data: 'DT_RowIndex',
                         name: 'DT_RowIndex'
                     },
                     {
                         data: 'district_name',
                         name: 'district_name'
                     },
                     {
                         data: 'code_id',
                         name: 'code_id'
                     },
                     {
                         data: 'it_uid',
                         name: 'it_uid'
                     },
                     
                    //  {
                    //      data: 'profile',
                    //      name: 'profile'
                    //  },
                     {
                         data: 'name',
                         name: 'name'
                     },
                    //  {
                    //      data: 'email',
                    //      name: 'email'
                    //  },
                     {
                         data: 'contact_number',
                         name: 'contact_number'
                     },
                     {
                        data: 'user_gender',
                        name: 'user_gender'
                     },
                     {
                        data: 'dob',
                        name: 'dob'
                     },
                     {
                         data: 'coach_name',
                         name: 'coach_name'
                     },
                     {
                         data: 'weight',
                         name: 'waight'
                     },
                     {
                         data: 'waight_button',
                         name: 'waight_button'
                     },
                     {
                         data: 'apply',
                         name: 'apply'
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
                             url: "{{ url('coach/applyuser/delete') }}",
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

         });
     </script>

     <script type="text/javascript">
         function changeStatus(id, status) {
             var token = '{!!csrf_token()!!}';

             $.ajax({
                 url: "{{ url('coach/applyuser/status') }}",
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
                                location.reload();
                             })

                     }

                 }
             });
         }
     </script>

<script>
  function changeWaight(userId, value) {
    // Open the modal
    if(value == 1){
         $('#weightModal').modal('show');

        // Store user ID in hidden input
        $('#weightUserId').val(userId);
    }else{
        swal({
            title: "Opps!",
            text: "Contact to State Admin for further process",
            icon: "warning",
            dangerMode: true,
            buttons: true,
        })
    }
    
  }

  function submitWeight() {
    var weight = $('#weightInput').val();
    var userId = $('#weightUserId').val();

    if (weight === '') {
      alert('Please enter weight');
      return;
    }

    $.ajax({
      url: '{{ url("coach/apply_update-weight") }}', // your route
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        user_id: userId,
        weight: weight
      },
      success: function(response) {
        
        $('#weightModal').modal('hide');
        swal({
            title: "Waight Update!",
            text: "User Waight Update Sussessfully",
            icon: "success",
            dangerMode: true,
            buttons: false,
            timer: 1000
        })
        .then(() => {
        location.reload();
        })
        // Optionally refresh table or update DOM
      },
      error: function(xhr) {
        alert('Error updating weight');
      }
    });
  }
</script>

<script>
  function apply(userId) {
    $('#userId').val(userId);
    $('#tournamentDropdown').html('<option value="">Loading...</option>');
    
    $.ajax({
      url: '{{ url("coach/get-tournaments") }}' + '?user_id=' + userId, // get Tournament data
      type: 'GET',
      success: function(data) {
        let options = '<option value="">Select Tournament</option>';
        data.forEach(tournament => {
          options += `<option value="${tournament.id}">
                ${tournament.get_waight_cat?.title ?? ''} - ${tournament.title}
            </option>`;
        });
        $('#tournamentDropdown').html(options);
        $('#applyTournamentModal').modal('show');
      },
      error: function(xhr) {
          if (xhr.responseJSON && xhr.responseJSON.message) {
            alert(xhr.responseJSON.message);
          } else {
            alert('An unexpected error occurred.');
          }
        }
    });
  }

  function submitTournament() {
    let userId = $('#userId').val();
    let tournamentId = $('#tournamentDropdown').val();

    if (!tournamentId) {
      alert('Please select a tournament');
      return;
    }

    $.ajax({
      url: '{{ url("coach/data/apply-tournament") }}',
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        user_id: userId,
        tournament_id: tournamentId
      },
      success: function(response) {
        // alert('Tournament applied successfully');
        $('#applyTournamentModal').modal('hide');
            if(response.status == 'success'){
                swal({
                title: "Congratulations!",
                text: "Tournament applied successfully",
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 1000
            })
            .then(() => {
                location.reload();
            })
        }else{
            alert('Already Applied');
        }
        
      },
      error: function() {
        alert('Failed to apply for tournament');
      }
    });
  }
</script>
@endsection