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
                                <li class="breadcrumb-item"><a href="{{ route('subadmin_tournament') }}">Tournament</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ $saveurl }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $getdata->title ?? '') }}" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" value="{{ old('start_date', $getdata->start_date ?? '') }}" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                                        <input type="date" name="end_date" value="{{ old('end_date', $getdata->end_date ?? '') }}" class="form-control">
                                    </div>
                                </div>

                                <label class="form-label mt-2">District Tournament Options</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input type="hidden" name="district_apply_open" value="0">
                                            <input class="form-check-input" type="checkbox" name="district_apply_open" id="district_apply_open" value="1" {{ old('district_apply_open', $getdata->district_apply_open ?? 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="district_apply_open">District Apply Tournament ON</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input type="hidden" name="district_draw_sheet_open" value="0">
                                            <input class="form-check-input" type="checkbox" name="district_draw_sheet_open" id="district_draw_sheet_open" value="1" {{ old('district_draw_sheet_open', $getdata->district_draw_sheet_open ?? 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="district_draw_sheet_open">District Draw Sheet ON</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input type="hidden" name="coach_apply_weight_open" value="0">
                                            <input class="form-check-input" type="checkbox" name="coach_apply_weight_open" id="coach_apply_weight_open" value="1" {{ old('coach_apply_weight_open', $getdata->coach_apply_weight_open ?? 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="coach_apply_weight_open">Coach Apply Tournament Weight ON</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input type="hidden" name="athlete_apply_weight_open" value="0">
                                            <input class="form-check-input" type="checkbox" name="athlete_apply_weight_open" id="athlete_apply_weight_open" value="1" {{ old('athlete_apply_weight_open', $getdata->athlete_apply_weight_open ?? 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="athlete_apply_weight_open">Athlete Login Apply Tournament ON</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary w-md">Update</button>
                                    <a href="{{ route('subadmin_tournament') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
