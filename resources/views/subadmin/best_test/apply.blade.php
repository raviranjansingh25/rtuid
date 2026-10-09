@extends('subadmin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $page_title }}</h4>
                        <a href="{{ route('subadmin_best_test') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
            </div>

            @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('subadmin_best_test_apply_save') }}">
                @csrf
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Exam Date <span class="text-danger">*</span></label>
                                <input type="date" name="exam_date" class="form-control" value="{{ old('exam_date') }}" required>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Place / Palace <span class="text-danger">*</span></label>
                                <input type="text" name="place" class="form-control" value="{{ old('place') }}" placeholder="Exam place" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Select Belt for each athlete</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Athlete</th>
                                        <th>Code</th>
                                        <th>Belt Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($athletes as $i => $athlete)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ trim(($athlete->name ?? '') . ' ' . ($athlete->last_name ?? '')) }}</td>
                                        <td>{{ $athlete->code ?? '-' }}</td>
                                        <td>
                                            <select name="belts[{{ $athlete->id }}]" class="form-control" required>
                                                <option value="">Select Belt</option>
                                                @foreach($beltOptions as $key => $label)
                                                <option value="{{ $key }}" {{ old('belts.'.$athlete->id) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success">Apply</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
