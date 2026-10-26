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
        left: 12.5%;
        right: 13.2%;
        display: flex;
        align-items: baseline;
        gap: 0.45cqi;
        transform: translateY(-88%);
        font-family: Arial, Helvetica, sans-serif;
        font-size: 1.65cqi;
        font-weight: 400;
        color: #333;
        line-height: 1;
        white-space: nowrap;
        pointer-events: none;
    }
    .btc-l1 { top: 60.5%; }
    .btc-l2 { top: 63.9%; }
    .btc-l3 { top: 67.3%; }
    .btc-l4 { top: 70.7%; }
    .btc-l5 { top: 74.1%; }
    .btc-l6 { top: 77.5%; }
    .btc-lbl { flex: 0 0 auto; }
    .btc-fill {
        flex: 1 1 auto;
        min-width: 8cqi;
        text-align: center;
        font-weight: 700;
        letter-spacing: 0.2px;
        text-transform: uppercase;
        border-bottom: 0.16cqi dotted #444;
        padding: 0 0.35cqi 0.12cqi;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btc-fill-sm {
        flex: 0.75 1 auto;
        min-width: 10cqi;
        max-width: 26cqi;
    }
    .btc-actions { margin: 10px 0 18px; line-height: normal; }
    @media print {
        .no-print { display: none !important; }
        .main-content, .page-content, .card, body { background: #fff !important; }
        .btc-wrap { box-shadow: none; page-break-after: always; max-width: 100%; }
        .btc-sheet { box-shadow: none; }
    }
</style>
