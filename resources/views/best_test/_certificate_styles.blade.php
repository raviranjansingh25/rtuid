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
    .btc-t {
        position: absolute;
        transform: translateY(-88%);
        font-family: Arial, Helvetica, sans-serif;
        font-size: 1.54cqi;
        font-weight: 400;
        color: #333;
        line-height: 1;
        white-space: nowrap;
        pointer-events: none;
    }
    .btc-b {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15px;
    }
    .btc-d {
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
        border-bottom: 0.14cqi dotted #444;
        padding-bottom: 0.12cqi;
        box-sizing: border-box;
    }
    .btc-place2 {
        text-overflow: clip;
    }
    .btc-actions { margin: 10px 0 18px; line-height: normal; }
    @media print {
        .no-print { display: none !important; }
        .main-content, .page-content, .card, body { background: #fff !important; }
        .btc-wrap { box-shadow: none; page-break-after: always; max-width: 100%; }
        .btc-sheet { box-shadow: none; }
    }
</style>
