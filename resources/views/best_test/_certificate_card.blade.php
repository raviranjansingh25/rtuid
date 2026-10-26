@php
    $payload = \App\Services\BestTestCertificateRenderer::payload($batch, $row);
@endphp
<div class="btc-sheet" id="cert-{{ $row->id }}">
    <img src="{{ $payload['blank_url'] }}" alt="Color Belt Certificate" class="btc-bg">

    <span class="btc-t" style="left:12.8%;top:60.3%">No.</span>
    <span class="btc-t btc-b" style="left:19.5%;top:60.3%">{{ $payload['no'] }}</span>
    <span class="btc-t" style="left:64.8%;top:60.3%">Date:</span>
    <span class="btc-t btc-b btc-d" style="left:70.8%;width:15.0%;top:60.3%">{{ $payload['date'] }}</span>

    <span class="btc-t" style="left:12.8%;top:63.7%">This is to certify that Mr./Ms.</span>
    <span class="btc-t btc-b btc-d" style="left:37.2%;width:48.6%;top:63.7%">{{ $payload['name'] }}</span>

    <span class="btc-t" style="left:12.8%;top:67.2%">Son / Daughter</span>
    <span class="btc-t btc-b btc-d" style="left:27.0%;width:27.1%;top:67.2%">{{ $payload['father'] }}</span>
    <span class="btc-t" style="left:54.5%;top:67.2%">of</span>
    <span class="btc-t btc-b btc-d" style="left:56.5%;width:19.7%;top:67.2%">{{ $payload['district'] }}</span>
    <span class="btc-t" style="left:76.8%;top:67.2%">District, has</span>

    <span class="btc-t" style="left:12.8%;top:70.6%">qualified in Color Belt Promotion Test Held on</span>
    <span class="btc-t btc-b btc-d" style="left:53.2%;width:15.9%;top:70.6%">{{ $payload['exam_date'] }}</span>
    <span class="btc-t" style="left:69.5%;top:70.6%">at</span>
    <span class="btc-t btc-b btc-d" style="left:70.8%;width:15.0%;top:70.6%">{{ $payload['place'] }}</span>

    <span class="btc-t btc-d" style="left:14.2%;width:22.7%;top:74.1%">&nbsp;</span>
    <span class="btc-t" style="left:37.2%;top:74.1%">and is permitted to wear</span>
    <span class="btc-t btc-b btc-d" style="left:59.3%;width:22.9%;top:74.1%">{{ $payload['belt'] }}</span>
    <span class="btc-t" style="left:82.8%;top:74.1%">belt.</span>

    <span class="btc-t" style="left:12.8%;top:77.5%">He/She is not permitted to teach taekwondo</span>
    <span class="btc-t" style="left:72.5%;top:77.5%">Grade:</span>
    <span class="btc-t btc-b btc-d" style="left:77.9%;width:7.9%;top:77.5%">{{ $payload['grade'] }}</span>
</div>
