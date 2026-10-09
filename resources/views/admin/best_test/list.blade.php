@extends('admin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $page_title }}</h4>
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
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">All</option>
                                    <option value="applied">Pending Grade</option>
                                    <option value="graded">Graded</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>District</label>
                                <select class="form-control" name="district">
                                    <option value="all">All Districts</option>
                                    @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mt-4">
                                <button id="filter" type="button" class="btn btn-primary btn-fw">Apply Filters</button>
                                <button type="button" class="btn btn-primary btn-fw" id="clearBtn">Reset</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table_data mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>District</th>
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
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
$(document).ready(function () {
    var table = $('.table_data').DataTable({
        bFilter: false,
        order: [],
        ajax: {
            url: "{{ url('/admin/best-test-certificate/list-data') }}",
            data: function (d) {
                d.status = $('select[name="status"]').val();
                d.district = $('select[name="district"]').val();
            },
            error: function (xhr) {
                console.error('Best Test list error', xhr.status, xhr.responseText);
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'district_name', name: 'district_name' },
            { data: 'exam_date', name: 'exam_date' },
            { data: 'place', name: 'place' },
            { data: 'athletes_count', name: 'athletes_count' },
            { data: 'status_badge', name: 'status_badge', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#filter').on('click', function () { table.ajax.reload(); });
    $('#clearBtn').on('click', function () {
        $('#search-form')[0].reset();
        table.ajax.reload();
    });
});
</script>
@endsection
