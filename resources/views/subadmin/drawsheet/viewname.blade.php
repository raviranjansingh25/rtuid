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
                                <li class="breadcrumb-item"><a href="{{ route('subadmin_draw_sheet') }}">Draw Sheet</a></li>
                            </ol>
                        </div>
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
                                        <label>Search Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="Title">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="">All Type</option>
                                            <option value="1">Active</option>
                                            <option value="2">Deactive</option>
                                        </select>
                                    </div>
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
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Weight Category</th>
                                            <th>Gender</th>
                                            <th>Event</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Players</th>
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
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
$(document).ready(function () {
    var table = $('.table_data').DataTable({
        bFilter: false,
        order: [],
        ajax: {
            url: "{{ url('/subadmin/draw-sheet-name-data/' . $id) }}",
            data: function (d) {
                d.title = $('input[name="title"]').val();
                d.status = $('select[name="status"]').val();
            }
        },
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Tournament Data',
            modifier: { page: 'current', search: 'none' }
        }],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'title', name: 'title' },
            { data: 'category', name: 'category' },
            { data: 'get_waight_cat', name: 'get_waight_cat' },
            { data: 'gender_name', name: 'gender_name' },
            { data: 'event', name: 'event' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'player_count', name: 'player_count' },
            { data: 'action', name: 'action' }
        ]
    });
    $('#filter').click(function () { table.ajax.reload(); });
    $('#clearBtn').click(function () { $('#search-form')[0].reset(); table.ajax.reload(); });
});
</script>
@endsection
