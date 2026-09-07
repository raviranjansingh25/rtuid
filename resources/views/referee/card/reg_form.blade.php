@extends('referee.layout.layout')
@section('content')

<style>
    body {
        margin: 0;
        padding: 0;
        background: #f4f4f4;
        font-family: Arial, sans-serif;
    }

    .form-container {
        position: relative;
        width: 100%;
        max-width: 600px;
        margin: 20px auto;
    }

    .form-image {
        width: 100%;
        display: block;
    }

    .form-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 600px;
        height: 850px; /* match your form image height */
        transform-origin: top left;
        pointer-events: none;
    }

    .field, .player-photo {
        position: absolute;
        font-size: 13px;
        font-weight: 100;
        color: #000;
    }
    .heading{     
        top: 116px;
        font-weight: 800;
        text-align: center;
        width: 100%;
        font-size: 22px; 
    }

    .player-uid     { top: 298px; left: 230px; }
    .it-uid         { top: 327px; left: 230px; }
    .district       { top: 358px; left: 230px; }
    .full-name      { top: 388px; left: 230px; }
    .father-name    { top: 419px; left: 230px; }
    .gender         { top: 448px; left: 230px; }
    .dob            { top: 477px; left: 230px; }
    .coach-name     { top: 507px; left: 230px; }
    .coach-contact  { top: 537px; left: 230px; }
    .category       { top: 568px; left: 230px; }
    .weight         { top: 598px; left: 230px; }
    .weight-cat     { top: 629px; left: 230px; }
    .address        { top: 657px; left: 230px; }

    .player-photo {
        top: 260px;
        left: 454px;
        width: 100px;
        height: 130px;
        border: 1px solid #000;
        background-size: cover;
        background-position: center;
        text-align: center;
        font-size: 12px;
        line-height: 130px;
    }

    .btn-wrapper {
        text-align: center;
        margin-bottom: 20px;
    }

    button {
        padding: 10px 20px;
        font-size: 16px;
        background: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    button:hover {
        background: #0056b3;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="btn-wrapper">
                <button onclick="downloadPDF()">Download PDF</button>
            </div>

            <div class="form-container" id="contentToPrint">
                <img src="{{ asset('storage/form.jpeg') }}" class="form-image" alt="Form Template">

                <div class="form-overlay">
                    <h2 class="field heading">{{ $tur->title ?? '-' }}</h2>
                    <div class="field player-uid">{{ $user->code_id ?? '-' }}</div>
                    <div class="field it-uid">{{ $user->it_uid ?? '-' }}</div>
                    <div class="field district">{{ $user->district_name ?? '-' }}</div>
                    <div class="field full-name">{{ $user->name ?? '-' }}</div>
                    <div class="field father-name">{{ $user->father_name ?? '-' }}</div>
                    <div class="field gender">{{ ucfirst($user->user_gender ?? '-') }}</div>
                    <div class="field dob">{{ \Carbon\Carbon::parse($user->dob)->format('d/m/Y') }}</div>
                    <div class="field coach-name">{{ $user->coach_name ?? '-' }}</div>
                    <div class="field coach-contact">{{ $user->coach_contact ?? '-' }}</div>
                    <div class="field category">{{ $event->category ?? '-' }}</div>
                    <div class="field weight">{{ $user->weight ?? '-' }} kg</div>
                    <div class="field weight-cat">{{ $event['get_waight_cat']->title ?? '-' }}</div>
                    <div class="field address">{{ $user->address ?? '-' }}</div>

                    @if(!empty($user->photo))
                        <div class="player-photo" style="background-image: url('{{ asset($user->photo) }}');"></div>
                    @else
                        <div class="player-photo">Photo</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS for PDF and Scaling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    // Scale the overlay based on container width
    function scaleOverlay() {
        const container = document.querySelector('.form-container');
        const overlay = document.querySelector('.form-overlay');

        if (!container || !overlay) return;

        const containerWidth = container.offsetWidth;
        const scale = containerWidth / 600; // base is 600px
        overlay.style.transform = `scale(${scale})`;
    }

    window.addEventListener('load', scaleOverlay);
    window.addEventListener('resize', scaleOverlay);

    async function downloadPDF() {
        const { jsPDF } = window.jspdf;

        const element = document.getElementById("contentToPrint");

        await html2canvas(element).then((canvas) => {
            const imgData = canvas.toDataURL("image/png");
            const pdf = new jsPDF("p", "pt", "a4");

            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = canvas.height * pdfWidth / canvas.width;

            pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
            pdf.save("championship_form.pdf");
        });
    }
</script>
@endsection
