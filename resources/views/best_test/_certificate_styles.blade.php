<style>
    .btc-wrap { max-width: 820px; margin: 0 auto 28px; }
    .btc-sheet {
        position: relative;
        width: 100%;
        line-height: 0;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,.12);
        container-type: inline-size;
    }
    .btc-bg {
        width: 100%;
        height: auto;
        display: block;
    }
    .btc-field {
        position: absolute;
        color: #333;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 700;
        font-size: 1.7cqi;
        line-height: 1;
        white-space: nowrap;
        text-transform: uppercase;
        pointer-events: none;
        letter-spacing: 0.15px;
        transform: translateY(-72%);
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /* Live blank already has labels + dotted lines. Only values sit on those lines. */
    .btc-no { left: 16.8%; top: 57.6%; max-width: 12%; }
    .btc-date { left: 73.5%; top: 57.6%; max-width: 16%; }
    .btc-name { left: 43.2%; top: 60.9%; max-width: 42%; }
    .btc-father { left: 28.8%; top: 64.1%; max-width: 22%; }
    .btc-district { left: 54.5%; top: 64.1%; max-width: 16%; }
    .btc-exam { left: 60.2%; top: 67.2%; max-width: 14%; }
    .btc-place { left: 76%; top: 67.2%; max-width: 12%; }
    .btc-belt { left: 33.8%; top: 70.3%; max-width: 28%; }
    .btc-grade { left: 74%; top: 73.5%; max-width: 12%; }
    .btc-actions { margin: 10px 0 18px; line-height: normal; }
    @media print {
        .no-print { display: none !important; }
        .main-content, .page-content, .card, body { background: #fff !important; }
        .btc-wrap { box-shadow: none; page-break-after: always; max-width: 100%; }
        .btc-sheet { box-shadow: none; }
    }
</style>
