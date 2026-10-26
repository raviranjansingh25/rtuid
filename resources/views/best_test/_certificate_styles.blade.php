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
    .btc-body {
        position: absolute;
        left: 12.5%;
        right: 12.5%;
        top: 60.2%;
        line-height: 1.85;
        color: #333;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 1.85cqi;
        font-weight: 400;
        pointer-events: none;
    }
    .btc-line {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btc-split {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }
    .btc-body strong {
        font-weight: 700;
        letter-spacing: 0.15px;
    }
    .btc-actions { margin: 10px 0 18px; line-height: normal; }
    @media print {
        .no-print { display: none !important; }
        .main-content, .page-content, .card, body { background: #fff !important; }
        .btc-wrap { box-shadow: none; page-break-after: always; max-width: 100%; }
        .btc-sheet { box-shadow: none; }
    }
</style>
