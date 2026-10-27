@php
    $payload = \App\Services\BestTestCertificateRenderer::payload($batch, $row);
@endphp
<div class="btc-sheet" id="cert-{{ $row->id }}">
    <img src="{{ $payload['blank_url'] }}" alt="Color Belt Certificate" class="btc-bg">

    <div class="btc-line" style="top:60.0%">
        <span class="btc-pair">
            <span>No.</span>
            <span class="btc-b">{{ $payload['no'] }}</span>
        </span>
        <span class="btc-grow"></span>
        <span class="btc-pair btc-date">
            <span>Date:</span>
            <span class="btc-val">{{ $payload['date'] }}</span>
        </span>
    </div>

    <div class="btc-line" style="top:63.4%">
        <span>This is to certify that Mr./Ms.</span>
        <span class="btc-val">{{ $payload['name'] }}</span>
    </div>

    <div class="btc-line" style="top:66.8%">
        <span>Son / Daughter</span>
        <span class="btc-val">{{ $payload['father'] }}</span>
        <span>of</span>
        <span class="btc-val">{{ $payload['district'] }}</span>
        <span>District, has</span>
    </div>

    <div class="btc-line" style="top:70.2%">
        <span>qualified in Color Belt Promotion Test Held on</span>
        <span class="btc-val btc-slot">{{ $payload['exam_date'] }}</span>
        <span>at</span>
        <span class="btc-val">{{ $payload['place1'] }}</span>
    </div>

    <div class="btc-line" style="top:73.6%">
        @if($payload['place2'] !== '')
            <span class="btc-val">{{ $payload['place2'] }}</span>
        @endif
        <span>and is permitted to wear</span>
        <span class="btc-val">{{ $payload['belt'] }}</span>
        <span>belt.</span>
    </div>

    <div class="btc-line" style="top:77.0%">
        <span>He/She is not permitted to teach taekwondo</span>
        <span class="btc-grow"></span>
        <span class="btc-pair btc-grade">
            <span>Grade:</span>
            <span class="btc-val">{{ $payload['grade'] }}</span>
        </span>
    </div>
</div>
