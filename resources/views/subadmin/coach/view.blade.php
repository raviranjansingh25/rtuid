@extends('subadmin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('subadmin_dashboard') }}">Dashboard</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        <strong>District:</strong> {{ $assignedDistrictName }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="search-form" method="post">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Search Name</label>
                                        <input type="text" name="title" class="form-control" placeholder="Name">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="">All Type</option>
                                            <option value="1">Active</option>
                                            <option value="2">Deactive</option>
                                        </select>
                                    </div>
                                    @if($canAccessAllDistricts)
                                    <div class="col-md-3">
                                        <label>District</label>
                                        <select class="form-control" name="district">
                                            <option value="all">All Districts</option>
                                            @foreach($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    <div class="col-md-3 col1 mt-4">
                                        <button id="filter" type="button" class="btn btn-primary btn-fw">Apply Filters</button>
                                        <button type="button" class="btn btn-primary btn-fw" id="clearBtn">Reset Filters</button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered table_data mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            @if(!$canAccessAllDistricts)
                                            <th>Stop Apply Tournament</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
$(document).ready(function () {
    var table = $('.table_data').DataTable({
        bFilter: false,
        order: [],
        ajax: {
            url: "{{ url('/subadmin/coach-data') }}",
            data: function (d) {
                d.title = $('input[name="title"]').val();
                d.status = $('select[name="status"]').val();
                d.district = $('select[name="district"]').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'image', name: 'image' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'status', name: 'status' }
            @if(!$canAccessAllDistricts)
            ,{ data: 'stop_apply', name: 'stop_apply', orderable: false, searchable: false }
            @endif
        ]
    });
    $('#filter').click(function () { table.ajax.reload(); });
    $('#clearBtn').click(function () { $('#search-form')[0].reset(); table.ajax.reload(); });

    @if(!$canAccessAllDistricts)
    $(document).on('change', '.stop-apply-toggle', function () {
        var coachId = $(this).data('id');
        var stop = $(this).is(':checked') ? 1 : 0;
        $.ajax({
            url: "{{ route('subadmin_coach_toggle_stop_apply') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                coach_id: coachId,
                stop: stop
            },
            success: function (res) {
                if (res.status === 'success') {
                    table.ajax.reload(null, false);
                } else {
                    swal('Error', res.message || 'Update failed.', 'error');
                }
            },
            error: function () {
                swal('Error', 'Update failed.', 'error');
            }
        });
    });
    @endif
});
</script>
@endsection
