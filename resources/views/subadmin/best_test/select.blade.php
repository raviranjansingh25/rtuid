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
                            <a href="{{ route('subadmin_best_test_list') }}" class="btn btn-secondary btn-sm">View Applications</a>
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

            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        <strong>District:</strong> {{ $assignedDistrictName }}
                        <span class="ms-2">Tick athletes, then click <strong>Next</strong>.</span>
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
                                        <label for="from_date">Search Name</label>
                                        <input type="text" name="title" class="form-control" placeholder="Name">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="from_date">Status</label>
                                        <select class="form-control" name="status">
                                            <option value="">All Type</option>
                                            <option value="2">Pending</option>
                                            <option value="1">Approved</option>
                                            <option value="4">Reject</option>
                                        </select>
                                    </div>
                                    @if($canAccessAllDistricts)
                                    <div class="col-md-3">
                                        <label for="district">District</label>
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

                            <form method="POST" action="{{ route('subadmin_best_test_prepare') }}" id="prepare-form" class="mt-3">
                                @csrf
                                <div id="selected-inputs"></div>
                                <div class="mb-3" id="next-wrap" style="display:none;">
                                    <button type="submit" class="btn btn-success">
                                        Next (<span id="selected-count">0</span> selected)
                                    </button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered table_data mb-0 table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="check-all"></th>
                                            <th>#</th>
                                            <th>District</th>
                                            <th>Code</th>
                                            <th>IT UID</th>
                                            <th>Gender</th>
                                            <th>DOB</th>
                                            <th>Name</th>
                                            <th>Email Address</th>
                                            <th>Phone Number</th>
                                            @if($canAccessAllDistricts)
                                            <th>Coach name</th>
                                            <th>Coach Permission</th>
                                            @endif
                                            @if(!$canAccessAllDistricts)
                                            <th>Subadmin Verification</th>
                                            @endif
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
            $box.append('<input type="hidden" name="athlete_ids[]" value="' + id + '">');
        });
        $('#selected-count').text(ids.length);
        $('#next-wrap').toggle(ids.length > 0);
    }

    var table = $('.table_data').DataTable({
        bFilter: false,
        order: [],
        ajax: {
            url: "{{ url('/subadmin/best-test-certificate/athlete-data') }}",
            data: function (d) {
                d.title = $('input[name="title"]').val();
                d.status = $('select[name="status"]').val();
                d.district = $('select[name="district"]').val();
            }
        },
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'district_name', name: 'district_name' },
            { data: 'code_id', name: 'code_id' },
            { data: 'it_uid', name: 'it_uid' },
            { data: 'user_gender', name: 'user_gender' },
            { data: 'dob', name: 'dob' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'contact_number', name: 'contact_number' },
            @if($canAccessAllDistricts)
            { data: 'coach_name', name: 'coach_name' },
            { data: 'coach_act', name: 'coach_act' },
            @endif
            @if(!$canAccessAllDistricts)
            { data: 'subadmin_verification', name: 'subadmin_verification' },
            @endif
            { data: 'status', name: 'status' }
        ],
        drawCallback: function () {
            $('.athlete-check').each(function () {
                var id = $(this).val();
                $(this).prop('checked', !!selected[id]);
            });
        }
    });

    $('#search-form').on('submit', function (e) {
        table.draw();
        e.preventDefault();
    });

    $(document).on('change', '.athlete-check', function () {
        var id = $(this).val();
        if (this.checked) selected[id] = true;
        else delete selected[id];
        syncNext();
    });

    $('#check-all').on('change', function () {
        var checked = this.checked;
        $('.athlete-check').each(function () {
            $(this).prop('checked', checked).trigger('change');
        });
    });

    $('#filter').on('click', function () { table.ajax.reload(); });
    $('#clearBtn').on('click', function () {
        $('#search-form')[0].reset();
        table.ajax.reload();
    });
});
</script>
@endsection
