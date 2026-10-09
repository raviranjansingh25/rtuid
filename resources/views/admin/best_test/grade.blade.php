@extends('admin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">{{ $page_title }}</h4>
                <a href="{{ route('admin_best_test') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>

            @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="card mb-3">
                <div class="card-body">
                    <p class="mb-1"><strong>District:</strong> {{ $batch->districtTag->title ?? ($batch->district ?? '-') }}</p>
                    <p class="mb-1"><strong>Exam Date:</strong> {{ $batch->exam_date ? $batch->exam_date->format('d-m-Y') : '-' }}</p>
                    <p class="mb-0"><strong>Place:</strong> {{ $batch->place }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin_best_test_grade_save', $batch->id) }}">
                @csrf
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
                                    <td style="min-width:180px;">
                                        <select name="grades[{{ $row->id }}]" class="form-control">
                                            <option value="">Select Grade</option>
                                            <option value="Pass" {{ old('grades.'.$row->id, $row->grade) == 'Pass' ? 'selected' : '' }}>Pass</option>
                                            <option value="Fail" {{ old('grades.'.$row->id, $row->grade) == 'Fail' ? 'selected' : '' }}>Fail</option>
                                            <option value="A" {{ old('grades.'.$row->id, $row->grade) == 'A' ? 'selected' : '' }}>A</option>
                                            <option value="B" {{ old('grades.'.$row->id, $row->grade) == 'B' ? 'selected' : '' }}>B</option>
                                            <option value="C" {{ old('grades.'.$row->id, $row->grade) == 'C' ? 'selected' : '' }}>C</option>
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-success">Submit Grades &amp; Show Certificates</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
