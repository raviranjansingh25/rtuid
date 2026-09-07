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
                                        <!-- Sabse pehle alag Column Header -->
                                        <th data-orderable="false" style="width: 120px;">Edit IT UID</th>
                                        <th>#</th>
                                        <th>District</th>
                                        <th>Code</th>
                                        <th>IT UID</th>
                                        <th>Gender</th>
                                        <th>DOB</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Phone Number</th>
                                        <th>Status</th>
                                        <th data-orderable="false">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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

    <!-- IT UID Update Modal -->
    <div class="modal fade" id="itUidModal" tabindex="-1" aria-labelledby="itUidModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="itUidModalLabel">Update IT UID</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="modalUserId">
            <div class="mb-3">
              <label for="modalItUidInput" class="form-label">IT UID</label>
              <input type="text" class="form-control" id="modalItUidInput" placeholder="Enter IT UID">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="submitItUid()">Submit</button>
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
                ajax: {
                    url: "{{ url('/coach/user-data') }}",
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
                    }
                ],
                columns: [
                   // Sabse pehla alag column yahan add kiya gaya hai
                   { data: 'edit_ituid_btn', name: 'edit_ituid_btn', orderable: false, searchable: false },
                   { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                   { data: 'district_name', name: 'district_name' },
                   { data: 'code_id', name: 'code_id' },
                   { data: 'it_uid', name: 'it_uid' },
                   { data: 'user_gender', name: 'user_gender' },
                   { data: 'dob', name: 'dob' },
                   { data: 'name', name: 'name' },
                   { data: 'email', name: 'email' },
                   { data: 'contact_number', name: 'contact_number' },
                   { data: 'status', name: 'status' },
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
                table.ajax.reload();
            });

            // delete logic
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
                            url: "{{ url('coach/user/delete') }}",
                            dataType: 'json',
                            type: 'GET',
                            data: { 'id': id },
                            success: function(res) {
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
                        swal({
                            title: "Cancelled",
                            text: "You cancelled your action",
                            icon: "error",
                            timer: 1500,
                            buttons: false
                        });
                    }
                });
            });

            // Open IT UID Modal
            $(document).on("click", ".edit-ituid-btn", function(e) {
                e.preventDefault();
                var id = $(this).attr("data-id");
                var ituid = $(this).attr("data-ituid");

                $('#modalUserId').val(id);
                $('#modalItUidInput').val(ituid);
                $('#itUidModal').modal('show');
            });
        });
    </script>

    <script type="text/javascript">
        function changeStatus(id, status) {
            $.ajax({
                url: "{{ url('coach/user/status') }}",
                type: 'GET',
                data: { 'id': id, 'status': status },
                success: function(res) {
                    if (res == "Success") {
                        swal({
                            title: "Change Status!",
                            text: "User Status Change Successfully",
                            icon: "success",
                            timer: 1000,
                            buttons: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                }
            });
        }
    </script>

    <script>
      function changeWaight(userId, value) {
        $('#weightModal').modal('show');
        $('#weightUserId').val(userId);
      }

      function submitWeight() {
        var weight = $('#weightInput').val();
        var userId = $('#weightUserId').val();

        if (weight === '') {
          alert('Please enter weight');
          return;
        }

        $.ajax({
          url: '{{ url("coach/update-weight") }}',
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
                text: "User Waight Update Successfully",
                icon: "success",
                timer: 1000,
                buttons: false
            }).then(() => {
                location.reload();
            });
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
          url: '{{ url("coach/get-tournaments") }}' + '?user_id=' + userId,
          type: 'GET',
          success: function(data) {
            let options = '<option value="">Select Tournament</option>';
            data.forEach(tournament => {
              options += `<option value="${tournament.id}">${tournament.title}</option>`;
            });
            $('#tournamentDropdown').html(options);
            $('#applyTournamentModal').modal('show');
          },
          error: function() {
            alert('Please Add waight first');
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
            $('#applyTournamentModal').modal('hide');
            if(response.status == 'success'){
                swal({
                    title: "Congratulations!",
                    text: "Tournament applied successfully",
                    icon: "success",
                    timer: 1000,
                    buttons: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert('Already Applied');
            }
          },
          error: function() {
            alert('Failed to apply for tournament');
          }
        });
      }

      // Submit IT UID Function
      function submitItUid() {
        var userId = $('#modalUserId').val();
        var itUid = $('#modalItUidInput').val();

        if (itUid.trim() === '') {
          alert('Please enter IT UID');
          return;
        }

        $.ajax({
          url: '{{ url("coach/user/update-it-uid") }}',
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            user_id: userId,
            it_uid: itUid
          },
          success: function(response) {
            $('#itUidModal').modal('hide');
            if(response.status == 'success'){
                swal({
                    title: "Success!",
                    text: response.message,
                    icon: "success",
                    timer: 1000,
                    buttons: false
                }).then(() => {
                    $('.table_data').DataTable().ajax.reload();
                });
            } else {
                alert(response.message || 'Failed to update IT UID');
            }
          },
          error: function(xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error updating IT UID';
            alert(msg);
          }
        });
      }
    </script>
@endsection