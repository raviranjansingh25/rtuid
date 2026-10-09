<style>
    .btc-wrap { max-width: 820px; margin: 0 auto 28px; }
    .btc-sheet {
        position: relative;
        width: 100%;
        line-height: 0;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,.12);
    }
    .btc-bg {
        width: 100%;
        height: auto;
        display: block;
    }
    .btc-field {
        position: absolute;
        color: #111;
        font-family: "Times New Roman", Times, serif;
        font-weight: 700;
        font-size: clamp(12px, 1.65vw, 14px);
        line-height: 1;
        white-space: nowrap;
        text-transform: uppercase;
        pointer-events: none;
        letter-spacing: 0.15px;
        /* Sit on dotted line (top = line Y) */
        transform: translateY(-72%);
    }
    /*
      Blank dotted lines (1152px):
      724=No/Date, 765=Name, 808=Father/District,
      849=Exam/Place, 891=Belt, 925=Grade
    */
    .btc-no { left: 19%; top: 62.85%; }
    .btc-date { left: 71%; top: 62.85%; }
    .btc-name { left: 46.5%; top: 66.41%; max-width: 46%; overflow: hidden; text-overflow: ellipsis; }
    .btc-father { left: 29%; top: 70.14%; max-width: 22%; overflow: hidden; text-overflow: ellipsis; }
    .btc-district { left: 58.5%; top: 70.14%; max-width: 14%; overflow: hidden; text-overflow: ellipsis; }
    .btc-exam { left: 53%; top: 73.70%; }
    .btc-place { left: 74%; top: 73.70%; max-width: 18%; overflow: hidden; text-overflow: ellipsis; } /* after "at" */
    .btc-belt { left: 40%; top: 77.34%; }
    .btc-grade { left: 76%; top: 80.30%; } /* keep on Grade line, not clipped */
    .btc-actions { margin: 10px 0 18px; line-height: normal; }
    @media print {
        .no-print { display: none !important; }
        .main-content, .page-content, .card, body { background: #fff !important; }
        .btc-wrap { box-shadow: none; page-break-after: always; max-width: 100%; }
        .btc-sheet { box-shadow: none; }
    }
</style>
