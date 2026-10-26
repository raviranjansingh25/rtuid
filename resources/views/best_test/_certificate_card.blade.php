@php
    $payload = \App\Services\BestTestCertificateRenderer::payload($batch, $row);
@endphp
<div class="btc-sheet" id="cert-{{ $row->id }}">
    <img src="{{ $payload['blank_url'] }}" alt="Color Belt Certificate" class="btc-bg">
    <div class="btc-line btc-l1">
        <span class="btc-lbl">No.</span>
        <span class="btc-fill">{{ $payload['no'] }}</span>
        <span class="btc-lbl">Date:</span>
        <span class="btc-fill btc-fill-sm">{{ $payload['date'] }}</span>
    </div>
    <div class="btc-line btc-l2">
        <span class="btc-lbl">This is to certify that Mr./Ms.</span>
        <span class="btc-fill">{{ $payload['name'] }}</span>
    </div>
    <div class="btc-line btc-l3">
        <span class="btc-lbl">Son / Daughter</span>
        <span class="btc-fill">{{ $payload['father'] }}</span>
        <span class="btc-lbl">of</span>
        <span class="btc-fill btc-fill-sm">{{ $payload['district'] }}</span>
        <span class="btc-lbl">District, has</span>
    </div>
    <div class="btc-line btc-l4">
        <span class="btc-lbl">qualified in Color Belt Promotion Test Held on</span>
        <span class="btc-fill btc-fill-sm">{{ $payload['exam_date'] }}</span>
        <span class="btc-lbl">at</span>
        <span class="btc-fill btc-fill-sm">{{ $payload['place'] }}</span>
    </div>
    <div class="btc-line btc-l5">
        <span class="btc-lbl">is permitted to wear</span>
        <span class="btc-fill">{{ $payload['belt'] }}</span>
        <span class="btc-lbl">belt.</span>
    </div>
    <div class="btc-line btc-l6">
        <span class="btc-lbl">He/She is not permitted to teach taekwondo</span>
        <span class="btc-lbl">Grade:</span>
        <span class="btc-fill btc-fill-sm">{{ $payload['grade'] }}</span>
    </div>
</div>
