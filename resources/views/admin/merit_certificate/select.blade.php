@extends('admin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>
                        <a href="{{ route('admin_merit_certificate_list') }}" class="btn btn-secondary btn-sm">Issued Certificates</a>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form id="search-form" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Type</label>
                                <select class="form-control" name="recipient_type" id="recipient_type">
                                    <option value="coach">Coach</option>
                                    <option value="referee">Referee</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Search Name</label>
                                <input type="text" name="title" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-md-3">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">All</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
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

                    <form method="POST" action="{{ route('admin_merit_certificate_prepare') }}" id="prepare-form">
                        @csrf
                        <input type="hidden" name="recipient_type" id="prepare_type" value="coach">
                        <div id="selected-inputs"></div>
                        <div class="mb-3" id="next-wrap" style="display:none;">
                            <button type="submit" class="btn btn-success">
                                Next (<span id="selected-count">0</span> selected)
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table_data mb-0">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="check-all"></th>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>District</th>
                                    <th>Phone</th>
                                    <th>Status</th>
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
    var selected = {};

    function syncNext() {
        var ids = Object.keys(selected);
        var $box = $('#selected-inputs').empty();
        ids.forEach(function (id) {
            $box.append('<input type="hidden" name="recipient_ids[]" value="' + id + '">');
        });
        $('#selected-count').text(ids.length);
        $('#next-wrap').toggle(ids.length > 0);
        $('#prepare_type').val($('#recipient_type').val());
    }

    var table = $('.table_data').DataTable({
        bFilter: false,
        order: [],
        ajax: {
            url: "{{ route('admin_merit_certificate_recipient_data') }}",
            data: function (d) {
                d.recipient_type = $('#recipient_type').val();
                d.title = $('input[name="title"]').val();
                d.status = $('select[name="status"]').val();
                d.district = $('select[name="district"]').val();
            }
        },
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'district_name' },
            { data: 'phone_col' },
            { data: 'status_badge', orderable: false, searchable: false }
        ],
        drawCallback: function () {
            $('.recipient-check').each(function () {
                var id = String($(this).val());
                $(this).prop('checked', !!selected[id]);
            });
        }
    });

    $('#filter').on('click', function () {
        selected = {};
        syncNext();
        table.ajax.reload();
    });
    $('#clearBtn').on('click', function () {
        $('#search-form')[0].reset();
        selected = {};
        syncNext();
        table.ajax.reload();
    });
    $('#recipient_type').on('change', function () {
        selected = {};
        syncNext();
        table.ajax.reload();
    });

    $(document).on('change', '.recipient-check', function () {
        var id = String($(this).val());
        if ($(this).is(':checked')) selected[id] = true;
        else delete selected[id];
        syncNext();
    });
    $('#check-all').on('change', function () {
        var checked = $(this).is(':checked');
        $('.recipient-check').each(function () {
            $(this).prop('checked', checked).trigger('change');
        });
    });
});
</script>
@endsection
