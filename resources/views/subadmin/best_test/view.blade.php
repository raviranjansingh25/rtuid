@extends('subadmin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">{{ $page_title }}</h4>
                <a href="{{ route('subadmin_best_test_list') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <p class="mb-1"><strong>Exam Date:</strong> {{ $batch->exam_date ? $batch->exam_date->format('d-m-Y') : '-' }}</p>
                    <p class="mb-1"><strong>Place:</strong> {{ $batch->place }}</p>
                    <p class="mb-0"><strong>Status:</strong>
                        @if($batch->status === 'graded')
                        <span class="badge bg-success">Graded / Certificate Ready</span>
                        @else
                        <span class="badge bg-warning text-dark">Pending State Grade</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Athlete</th>
                                <th>Code</th>
                                <th>Belt</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->athletes as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ trim(($row->user->name ?? '') . ' ' . ($row->user->last_name ?? '')) }}</td>
                                <td>{{ $row->user->code ?? ($row->user->code_id ?? '-') }}</td>
                                <td>{{ $row->belt_label }}</td>
                                <td>{{ $row->grade ?: 'Pending' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($batch->status === 'graded')
                    <a href="{{ route('subadmin_best_test_certificates', $batch->id) }}" class="btn btn-success">View Certificates</a>
                    @else
                    <div class="alert alert-info mb-0">Waiting for State Admin to enter grades. Certificates will appear after grading.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection