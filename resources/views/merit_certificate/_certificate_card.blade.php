@php
    $data = \App\Services\MeritCertificateRenderer::payload($batch, $row);
    $tplClass = ((int) $data['template'] === 2) ? 'tpl-2' : 'tpl-1';
@endphp
<div class="merit-cert-wrap mb-4">
    <div class="merit-cert-frame {{ $tplClass }}" style="background-image:url('{{ $data['blank_url'] }}');">
        <div class="mc-serial">{{ $data['serial'] }}</div>
        <div class="mc-name">{{ $data['name'] }}</div>
        @if((int)$data['template'] === 1)
            <div class="mc-course tpl1">{{ $data['course'] }}</div>
            <div class="mc-place tpl1">{{ $data['place'] }}</div>
            <div class="mc-from tpl1">{{ $data['date_from'] }}</div>
        @else
            <div class="mc-course tpl2">{{ $data['course'] }}</div>
            <div class="mc-on tpl2">{{ $data['date_on'] }}</div>
        @endif
    </div>
</div>
