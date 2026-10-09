@extends('subadmin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>
                        <a href="{{ route('subadmin_best_test') }}" class="btn btn-primary btn-sm">New Application</a>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table_data mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exam Date</th>
                                    <th>Place</th>
                                    <th>Athletes</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
<script>
$(document).ready(function () {
    $('.table_data').DataTable({
        bFilter: false,
        order: [],
        ajax: "{{ route('subadmin_best_test_list_data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'exam_date' },
            { data: 'place' },
            { data: 'athletes_count' },
            { data: 'status_badge', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endsection
