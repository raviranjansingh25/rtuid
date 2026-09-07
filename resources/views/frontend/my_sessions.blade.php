@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/sidebar')

<style>
  .directory-list .list-box:hover .btn-primary {
    color: black;
    background: transparent linear-gradient(292deg, #efefef 0%, #fdfdfd 100%);
}
</style>
<div class="col-md-12 col-lg-9 my-session-00">
  <div class="">
    <div class="tabs-session">
      <nav>
        <div class="nav " id="nav-tab" role="tablist">
          <button class="nav-link active ftt-00" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">New Tournament</button>
          <button class="nav-link ftt-00" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Apply Tournament</button>
        </div>
      </nav>
    </div>
    <div class="tab-content " id="nav-tabContent">

      <div class="tab-pane fade active show" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="directory-list list-type-man">
          @forelse($new as $comming)
          <div class="list-box">
            <div class="profile-di">
              <h3>{{ $comming->title }}</h3>
              <p>{{ date('d-m-Y', strtotime($comming->start_date)) }} - {{ date('d-m-Y', strtotime($comming->end_date)) }}</p>
            </div>
            <div class="d-flex gap-2">
              <button type="button" onclick="openWeightModal({{ $comming->id }})" class="btn btn-secondary">Fill Weight</button>
              <button type="button" onclick="applyAthleteTournament({{ $comming->id }})" class="btn-primary">Apply</button>
            </div>
          </div>
          @empty
          <div class="list-heading-sec">
            <div class="profile-di text-center list-heading" style="display: block;">
              <h3>No District Tournament Open For Apply</h3>
            </div>
          </div>
          @endforelse
        </div>
      </div>

      <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="directory-list list-type-man">
          @forelse($applied as $item)
          <div class="list-box">
            <div class="profile-di">
              <h3>{{ $item->title }}</h3>
              <p>Applied</p>
            </div>
          </div>
          @empty
          <div class="list-heading-sec">
            <div class="profile-di text-center list-heading" style="display: block;">
              <h3>No Applied Tournament</h3>
            </div>
          </div>
          @endforelse
        </div>
      </div>

    </div>
  </div>
</div>
</div>
</div>
</section>

<div class="modal fade" id="weightModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Fill Weight</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="number" class="form-control" id="weightInput" placeholder="Enter weight (kg)">
        <input type="hidden" id="selectedTournamentId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="saveAthleteWeight()">Save Weight</button>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/sweetalert@1.1.3/dist/sweetalert.min.js"></script>
<script>
function openWeightModal(tournamentId) {
  $('#selectedTournamentId').val(tournamentId);
  $('#weightInput').val('{{ $user->weight ?? '' }}');
  $('#weightModal').modal('show');
}

function saveAthleteWeight() {
  var weight = $('#weightInput').val();
  if (!weight) {
    alert('Please enter weight');
    return;
  }

  $.ajax({
    url: '{{ url("athlete/update-weight") }}',
    type: 'POST',
    data: {
      _token: '{{ csrf_token() }}',
      weight: weight
    },
    success: function(res) {
      if (res.status === 'success') {
        $('#weightModal').modal('hide');
        swal('Success', 'Weight saved successfully.', 'success');
      }
    },
    error: function() {
      swal('Error', 'Could not save weight.', 'error');
    }
  });
}

function applyAthleteTournament(tournamentId) {
  $.ajax({
    url: '{{ url("athlete/apply-tournament") }}',
    type: 'POST',
    data: {
      _token: '{{ csrf_token() }}',
      tournament_id: tournamentId
    },
    success: function(res) {
      if (res.status === 'success') {
        swal('Success', 'Applied to tournament successfully.', 'success').then(function() {
          location.reload();
        });
      } else {
        swal('Error', res.message || 'Apply failed.', 'error');
      }
    },
    error: function(xhr) {
      var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Apply failed.';
      swal('Error', msg, 'error');
    }
  });
}
</script>

@endsection
