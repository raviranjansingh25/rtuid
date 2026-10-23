@extends('admin.layout.layout')
@section('content')
@include('merit_certificate._certificate_styles')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <a href="{{ route('admin_merit_certificate_view', $batch->id) }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
            </div>

            <div class="row">
                @foreach($batch->certificates as $row)
                @php $person = $people[$row->recipient_id] ?? null; @endphp
                <div class="col-md-6 col-lg-4 mb-4 text-center">
                    <h6 class="mb-2">{{ $person->name ?? ('#' . $row->recipient_id) }} ({{ $row->serial_no }})</h6>
                    <a class="btn btn-success btn-sm mb-2" href="{{ route('admin_merit_certificate_download', [$batch->id, $row->id]) }}">Download</a>
                    @include('merit_certificate._certificate_card', ['batch' => $batch, 'row' => $row])
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
