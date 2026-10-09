@extends('admin.layout.layout')
@section('content')
@include('best_test._certificate_styles')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between no-print">
                <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>
                <div>
                    <button onclick="window.print()" class="btn btn-primary btn-sm">Print All</button>
                    <a href="{{ route('admin_best_test') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success no-print">{{ session('success') }}</div>
            @endif

            @foreach($batch->athletes as $row)
            @if($row->certificate_ready)
            <div class="btc-wrap">
                <div class="btc-actions no-print d-flex justify-content-between align-items-center">
                    <strong>{{ trim(($row->user->name ?? '') . ' ' . ($row->user->last_name ?? '')) }}</strong>
                    <a class="btn btn-success btn-sm" href="{{ route('admin_best_test_certificate_download', [$batch->id, $row->id]) }}">Download Certificate</a>
                </div>
                @include('best_test._certificate_card', ['batch' => $batch, 'row' => $row])
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
