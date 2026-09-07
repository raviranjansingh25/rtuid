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

            @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
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
                                                <label>Search Title</label>
                                                <input type="text" name="title" class="form-control" placeholder="Title">
                                            </div>
                                            <div class="col-md-3 col1 mt-4">
                                                <button id="filter" type="button" class="btn btn-primary btn-fw">Apply Filters</button>
                                                <button class="btn btn-primary btn-fw" id="clearBtn" type="button">Reset Filters</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="d-sm-flex align-items-center justify-content-between mt-3">
                                        <div class="alert alert-info mb-0 py-2 px-3">
                                            <strong>District:</strong> {{ $assignedDistrictName }}
                                        </div>
                                        <a href="{{ route('subadmin_tournament_add') }}">
                                            <button type="button" class="btn btn-success waves-effect waves-light">Add Tournament</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table_data mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Options</th>
                                        <th data-orderable="false">Action</th>
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

<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
$(document).ready(function() {
    var table = $('.table_data').DataTable({
        bFilter: false,
        ajax: {
            url: "{{ url('/subadmin/tournament-data') }}",
            data: function(d) {
                d.title = $('input[name="title"]').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'title', name: 'title' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'flags', name: 'flags', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#filter').click(function() { table.ajax.reload(); });
    $('#clearBtn').click(function() {
        $('#search-form')[0].reset();
        table.ajax.reload();
    });
});
</script>
@endsection
