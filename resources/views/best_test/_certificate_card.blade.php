@php
    $payload = \App\Services\BestTestCertificateRenderer::payload($batch, $row);
    $field = function ($value, $left = 4, $right = 8) {
        $value = e(trim((string) $value));
        $html = '';
        if ($left > 0) {
            $html .= str_repeat('.', $left);
        }
        if ($value !== '') {
            $html .= '<strong>' . $value . '</strong>';
        }
        if ($right > 0) {
            $html .= str_repeat('.', $right);
        }
        return $html;
    };
@endphp
<div class="btc-sheet" id="cert-{{ $row->id }}">
    <img src="{{ $payload['blank_url'] }}" alt="Color Belt Certificate" class="btc-bg">
    <div class="btc-body">
        <div class="btc-line btc-split">
            <span>No. {!! $field($payload['no'], 5, 10) !!}</span>
            <span>Date: <strong>{{ $payload['date'] }}</strong></span>
        </div>
        <div class="btc-line">
            This is to certify that Mr./Ms. {!! $field($payload['name'], 6, 6) !!}
        </div>
        <div class="btc-line">
            Son / Daughter {!! $field($payload['father'], 3, 4) !!}
            of {!! $field($payload['district'], 2, 3) !!} District
        </div>
        <div class="btc-line">
            qualified in Color Belt Promotion Test Held on
            {!! $field($payload['exam_date'], 0, 3) !!}
            at {!! $field($payload['place'], 1, 2) !!}
        </div>
        <div class="btc-line">
            is permitted to wear {!! $field($payload['belt'], 6, 6) !!} belt
        </div>
        <div class="btc-line btc-split">
            <span>He/She is not permitted to teach taekwondo</span>
            <span>Grade: <strong>{{ $payload['grade'] }}</strong></span>
        </div>
    </div>
</div>
