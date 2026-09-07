@extends('coach.layout.layout')
@section('content')

<style>
    .id-card-section {
        padding: 40px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: transparent;
        min-height: calc(100vh - 150px);
        color: #333333;
    }

    .control-panel {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .btn-premium {
        background: linear-gradient(135deg, #004b87 0%, #0078d4 100%);
        color: #fff;
        font-weight: 700;
        border: none;
        padding: 12px 28px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0, 75, 135, 0.3);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        cursor: pointer;
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 75, 135, 0.5);
        color: #fff;
    }

    .cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
        width: 100%;
        perspective: 1000px;
    }

    .card-wrapper {
        width: 100%;
        max-width: 550px;
        display: flex;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ID Card Frame styling */
    .id-card-frame {
        container-type: inline-size;
        position: relative;
        width: 100%;
        aspect-ratio: 1024 / 665;
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        transition: transform 0.5s ease, box-shadow 0.5s ease;
    }

    .id-card-frame:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
    }

    .front-card {
        background-image: url("{{ asset('public/coach_card_img2.jpg') }}");
    }

    .back-card {
        background-image: url("{{ asset('public/coach_card_img1.jpg') }}");
    }

    /* Styling overlay fields */
    .card-value {
        position: absolute;
        font-family: 'Arial', sans-serif;
        font-weight: 700;
        color: #1a1a1a;
        text-transform: uppercase;
    }

    /* Name positioning above the line */
    .coach-name {
        top: 45.2%;
        left: 4.8%;
        font-size: 3.8cqw;
        color: #0c1b33;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    /* Value alignment next to labels */
    .coach-id {
        top: 55.6%;
        left: 28%;
        font-size: 3.2cqw;
        color: #1a1a1a;
    }

    .father-name {
        top: 62.6%;
        left: 28%;
        font-size: 3.2cqw;
        color: #1a1a1a;
    }

    .dob-val {
        top: 69.5%;
        left: 28%;
        font-size: 3.2cqw;
        color: #1a1a1a;
    }

    .grade-val {
        top: 76.5%;
        left: 28%;
        font-size: 3.2cqw;
        color: #1a1a1a;
    }

    .join-date {
        top: 86.8%;
        left: 17%;
        font-size: 2.8cqw;
        color: #1a1a1a;
    }

    .expired-date {
        top: 86.8%;
        left: 45%;
        font-size: 2.8cqw;
        color: #1a1a1a;
    }

    /* Right side photo frame placement */
    .photo-container {
        position: absolute;
        left: 72.1%;
        top: 39.4%;
        width: 24.3%;
        height: 47.6%;
        border-radius: 8px;
        overflow: hidden;
        background: #fdfdfd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .coach-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Back Side styling */
    .mobile-num {
        top: 25.0%;
        left: 28%;
        font-size: 3.5cqw;
        color: #1a1a1a;
    }

    .address-text {
        top: 37.0%;
        left: 28%;
        font-size: 3.2cqw;
        color: #1a1a1a;
        width: 65%;
        line-height: 1.3;
        text-transform: uppercase;
        word-break: break-word;
    }

    @media print {
        body {
            background: #fff;
            color: #000;
        }
        .id-card-section {
            background: #fff;
            padding: 0;
        }
        .control-panel {
            display: none;
        }
        .id-card-frame {
            box-shadow: none;
            border: 1px solid #ccc;
        }
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Breadcrumbs -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('coach_dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Coach ID Card</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ID Card Presentation Area -->
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="id-card-section rounded-4 shadow-lg">
                        
                        <!-- Actions Panel -->
                        <div class="control-panel">
                            <button class="btn-premium" onclick="downloadCardPDF()"><i class="fas fa-download me-2"></i> Download Card PDF</button>
                        </div>

                        <!-- Front & Back Cards wrapper -->
                        <div class="cards-container" id="printable-cards-area">
                            
                            <!-- Front Side of Card -->
                            <div class="card-wrapper">
                                <div class="id-card-frame front-card" id="card-front">
                                    <!-- Dynamic overlay values -->
                                    <div class="card-value coach-name">{{ $coach->name }}</div>
                                    <div class="card-value coach-id">CH-{{ sprintf('%05d', $coach->id) }}</div>
                                    <div class="card-value father-name">{{ $coach->father_name }}</div>
                                    <div class="card-value dob-val">{{ $coach->dob }}</div>
                                    <div class="card-value grade-val">{{ $coach->grade }}</div>
                                    
                                    <div class="card-value join-date">{{ \Carbon\Carbon::parse($coach->created_at)->format('d/m/Y') }}</div>
                                    <div class="card-value expired-date">{{ \Carbon\Carbon::parse($coach->created_at)->addYears(2)->format('d/m/Y') }}</div>
                                    
                                    <!-- Photo container -->
                                    <div class="photo-container">
                                        @if(!empty($coach->image) && file_exists(public_path($coach->image)))
                                            <img src="{{ asset($coach->image) }}" class="coach-photo" alt="Photo">
                                        @elseif(!empty($coach->image))
                                            <img src="{{ url($coach->image) }}" class="coach-photo" alt="Photo">
                                        @else
                                            <img src="{{ url('public/noimage.png') }}" class="coach-photo" alt="Photo">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Back Side of Card -->
                            <div class="card-wrapper">
                                <div class="id-card-frame back-card" id="card-back">
                                    <!-- Dynamic overlay values -->
                                    <div class="card-value mobile-num">
                                        @if(!empty($coach->phone))
                                            {{ $coach->phone }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="card-value address-text">{{ $district_name }}</div>
                                </div>
                            </div>

                        </div> <!-- /cards-container -->

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- html2canvas and jsPDF Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    async function downloadCardPDF() {
        const { jsPDF } = window.jspdf;
        const frontElement = document.getElementById("card-front");
        const backElement = document.getElementById("card-back");

        const options = {
            scale: 2,
            useCORS: true,
            allowTaint: true
        };

        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'px',
            format: [840, 600]
        });

        try {
            // Render front side
            const frontCanvas = await html2canvas(frontElement, options);
            const frontImg = frontCanvas.toDataURL("image/png");
            
            // Render back side
            const backCanvas = await html2canvas(backElement, options);
            const backImg = backCanvas.toDataURL("image/png");

            // Add Front Image with landscape aspect ratio
            pdf.addImage(frontImg, 'PNG', 30, 176, 380, 247);
            
            // Add Back Image with landscape aspect ratio
            pdf.addImage(backImg, 'PNG', 430, 176, 380, 247);

            // Save the PDF
            pdf.save("Coach_ID_Card_{{ $coach->name }}.pdf");
        } catch (error) {
            console.error("Error generating PDF:", error);
            alert("Could not generate PDF card. Please try printing or screenshotting.");
        }
    }
</script>

@endsection
