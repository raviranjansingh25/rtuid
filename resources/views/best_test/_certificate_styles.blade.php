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
    .btc-line {
        position: absolute;
        left: 11.2%;
        width: 77.4%;
        display: flex;
        flex-wrap: nowrap;
        align-items: baseline;
        column-gap: 0.7cqi;
        transform: translateY(-88%);
        font-family: Arial, Helvetica, sans-serif;
        font-size: 1.58cqi;
        font-weight: 400;
        color: #333;
        line-height: 1;
        white-space: nowrap;
        pointer-events: none;
    }
    .btc-grow { flex: 1 1 auto; min-width: 0; }
    .btc-pair {
        display: inline-flex;
        align-items: baseline;
        column-gap: 0.55cqi;
        flex: 0 0 auto;
    }
    .btc-date { flex: 0 0 36%; }
    .btc-grade { flex: 0 0 26%; }
    .btc-slot {
        flex: 0 1 14cqi;
        min-width: 12cqi;
        max-width: 18cqi;
    }
    .btc-b {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15px;
    }
    .btc-val {
        flex: 1 1 0;
        min-width: 0;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15px;
        text-align: center;
        border-bottom: 0.14cqi dotted #444;
        padding: 0 0.35cqi 0.12cqi;
        box-sizing: border-box;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btc-date .btc-val,
    .btc-grade .btc-val {
        flex: 1 1 auto;
    }
    .btc-place1 {
        overflow: visible;
        text-overflow: clip;
    }
    .btc-place {
        flex: 0 0 auto;
        overflow: visible;
        text-overflow: clip;
        min-width: 0;
        max-width: none;
    }
    .btc-actions { margin: 10px 0 18px; line-height: normal; }
    @media print {
        .no-print { display: none !important; }
        .main-content, .page-content, .card, body { background: #fff !important; }
        .btc-wrap { box-shadow: none; page-break-after: always; max-width: 100%; }
        .btc-sheet { box-shadow: none; }
    }
</style>
