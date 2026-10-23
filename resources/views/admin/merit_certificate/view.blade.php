@extends('admin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <div>
                        <a href="{{ route('admin_merit_certificate_list') }}" class="btn btn-secondary btn-sm">Back</a>
                        <a href="{{ route('admin_merit_certificate_certificates', $batch->id) }}" class="btn btn-success btn-sm">View / Download All</a>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card mb-3">
                <div class="card-body">
                    <p class="mb-1"><strong>Type:</strong> {{ ucfirst($batch->recipient_type) }}</p>
                    <p class="mb-1"><strong>Template:</strong> {{ $batch->template }}</p>
                    <p class="mb-1"><strong>Course:</strong> {{ $batch->course_title }}</p>
                    @if($batch->place)<p class="mb-1"><strong>Place:</strong> {{ $batch->place }}</p>@endif
                    @if($batch->date_from)<p class="mb-1"><strong>From:</strong> {{ $batch->date_from }}</p>@endif
                    @if($batch->date_on)<p class="mb-1"><strong>On:</strong> {{ $batch->date_on }}</p>@endif
                    <p class="mb-0"><strong>Issue Date:</strong> {{ $batch->issue_date ? $batch->issue_date->format('d-m-Y') : '-' }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sr. No</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->certificates as $i => $row)
                            @php $person = $people[$row->recipient_id] ?? null; @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->serial_no }}</td>
                                <td>{{ $person->name ?? ('#' . $row->recipient_id) }}</td>
                                <td>
                                    <a href="{{ route('admin_merit_certificate_download', [$batch->id, $row->id]) }}" class="btn btn-sm btn-success">Download</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
