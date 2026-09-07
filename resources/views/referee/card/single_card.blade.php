@extends('referee.layout.layout')
@section('content')

<style>
    body {
        margin: 0;
        padding: 0;
        background: #f4f4f4;
        font-family: Arial, sans-serif;
    }

    .form-wrapper {
        display: flex;
        justify-content: center;
        margin: 20px 0;
    }

    .form-container {
        position: relative;
        width: 100%;
        max-width: 350px;
    }

    .form-image {
        width: 100%;
        display: block;
    }

    .form-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .field, .player-photo {
        position: absolute;
        font-size: 10px;
        font-weight: 500;
        color: #000;
    }

    /* Position your fields here based on the image layout */
    .heading { top: 265px;
    width: 61%;
    /* font-size: 24px; */
    font-size: 12px; 
    left: 144px;}

    .player-uid     { top: 306px; left: 144px; }

.district       { top: 329px; left: 144px; }
.full-name      { top: 353px; left: 144px; }
.category    { top: 422px; left: 144px; }
.gender         { top: 376px; left: 144px; }
.dob            { top: 398px; left: 144px; }
.coach-name     { top: 467px; left: 144px; }
.weight-cat     { top: 445px; left: 144px; }
.address        { top: 667px; left: 144px; }


    .player-photo {
      top: 98px;
    left: 115px;
    width: 121px;
    height: 146px;
    border-radius: 10px;
    border: 1px solid #000;
    background-size: cover;
    background-position: center;
    text-align: center;
    font-size: 9px;
    line-height: 80px;
    }

    .btn-wrapper {
        text-align: center;
        margin-bottom: 20px;
    }

    button {
        padding: 8px 16px;
        font-size: 14px;
        background: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 4px;
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

            <div class="form-wrapper">
                <div class="form-container" id="contentToPrint">
                    <!-- Card Background Image -->
                    <img src="{{ asset('storage/id_card.jpg') }}" class="form-image" alt="Form Template">

                    <!-- Overlay Details -->
                    <div class="form-overlay">
                        <h2 class="field heading">{{ $tur->title ?? '-' }}</h2>
                        <div class="field player-uid">{{ $user->code_id ?? '-' }}</div>
                        <div class="field district">{{ $user->district_name ?? '-' }}</div>
                        <div class="field full-name">{{ $user->name ?? '-' }}</div>
                        <div class="field category">{{ $apply->category ?? '-' }}</div>
                        <div class="field gender">{{ ucfirst($user->user_gender ?? '-') }}</div>
                        <div class="field dob">{{ \Carbon\Carbon::parse($user->dob)->format('d/m/Y') }}</div>
                        <div class="field coach-name">{{ $user->coach_name ?? '-' }}</div>
                        
                        <div class="field weight-cat">{{ $apply['get_waight_cat']->title ?? '-' }}</div>
                        

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
</div>

<!-- JS for PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
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
