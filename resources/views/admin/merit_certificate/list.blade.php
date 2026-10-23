@extends('admin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $page_title }}</h4>
                        <a href="{{ route('admin_merit_certificate') }}" class="btn btn-primary btn-sm">New Certificate</a>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form id="search-form" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Type</label>
                                <select class="form-control" name="recipient_type">
                                    <option value="">All</option>
                                    <option value="coach">Coach</option>
                                    <option value="referee">Referee</option>
                                </select>
                            </div>
                            <div class="col-md-3 mt-4">
                                <button id="filter" type="button" class="btn btn-primary btn-fw">Apply</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table_data mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Course</th>
                                    <th>Issue Date</th>
                                    <th>Count</th>
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
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
$(document).ready(function () {
    var table = $('.table_data').DataTable({
        bFilter: false,
        order: [],
        ajax: {
            url: "{{ route('admin_merit_certificate_list_data') }}",
            data: function (d) {
                d.recipient_type = $('select[name="recipient_type"]').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'type_label' },
            { data: 'course_title' },
            { data: 'issue_date_fmt' },
            { data: 'certificates_count' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
    $('#filter').on('click', function () { table.ajax.reload(); });
});
</script>
@endsection
