@extends('coach.layout.layout')
@section('content')
@include('merit_certificate._certificate_styles')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <h4 class="mb-0">{{ $title }}</h4>
                </div>
            </div>

            @if($certificates->isEmpty())
            <div class="alert alert-info">No certificate issued yet. Please contact Admin.</div>
            @else
            <div class="row">
                @foreach($certificates as $row)
                @php $batch = $row->batch; @endphp
                <div class="col-md-6 col-lg-4 mb-4 text-center">
                    <h6 class="mb-1">{{ $batch->course_title ?? 'Certificate' }}</h6>
                    <p class="small text-muted mb-2">
                        Sr. No: {{ $row->serial_no }}
                        @if($batch && $batch->issue_date)
                        · Issued: {{ $batch->issue_date->format('d-m-Y') }}
                        @endif
                    </p>
                    <a class="btn btn-success btn-sm mb-2" href="{{ route('coach_certificate_download', $row->id) }}">Download Certificate</a>
                    @include('merit_certificate._certificate_card', ['batch' => $batch, 'row' => $row])
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
