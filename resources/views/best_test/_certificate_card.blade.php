@php
    $payload = \App\Services\BestTestCertificateRenderer::payload($batch, $row);
@endphp
<div class="btc-sheet" id="cert-{{ $row->id }}">
    <img src="{{ $payload['blank_url'] }}" alt="Color Belt Certificate" class="btc-bg">
    <div class="btc-field btc-no">{{ $payload['no'] }}</div>
    <div class="btc-field btc-date">{{ $payload['date'] }}</div>
    <div class="btc-field btc-name">{{ $payload['name'] }}</div>
    <div class="btc-field btc-father">{{ $payload['father'] }}</div>
    <div class="btc-field btc-district">{{ $payload['district'] }}</div>
    <div class="btc-field btc-exam">{{ $payload['exam_date'] }}</div>
    <div class="btc-field btc-place">{{ $payload['place'] }}</div>
    <div class="btc-field btc-belt">{{ $payload['belt'] }}</div>
    <div class="btc-field btc-grade">{{ $payload['grade'] }}</div>
</div>
