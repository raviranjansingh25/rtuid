@extends('admin.layout.layout')
@section('content')

    <!-- CSS -->
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
        margin: auto;
    margin-bottom: 20px;
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
            @php 
            $decrypted_id  = get_decrypted_value($event_id, true);
            $tournament = App\Models\Tournament::find($decrypted_id);
        
            $all = App\Models\Tournament::where('title',$tournament->title)->where('status',1)->pluck('id')->toArray();
            $apply = App\Models\ApplyTournament::whereIn('turnament_id',$all)->get();
            @endphp
            <div id="contentToPrint">
            @foreach($apply as $data)
            @php 
            
            $user = App\Models\User::find($data->user_id);
            $applytur = App\Models\Tournament::find($data->turnament_id);
            @endphp
            <div class="form-container" >
                <img src="{{ asset('storage/id_card.jpg') }}" class="form-image" alt="Form Template">
                <h2 class="field heading">{{ $applytur->title ?? '-' }}</h2>
                <div class="field player-uid">{{ $user->code_id ?? '-' }}</div>
                <!-- <div class="field it-uid">{{ $user->it_uid ?? '-' }}</div> -->
                <div class="field district">{{ $user->district_name ?? '-' }}</div>
                <div class="field full-name">{{ $user->name ?? '-' }}</div>
                <!-- <div class="field father-name">{{ $user->father_name ?? '-' }}</div> -->
                <div class="field gender">{{ ucfirst($user->user_gender ?? '-') }}</div>
                <div class="field dob">{{ \Carbon\Carbon::parse($user->dob)->format('d/m/Y') }}</div>
                <div class="field coach-name">{{ $user->coach_name ?? '-' }}</div>
                <!-- <div class="field coach-contact">{{ $user->coach_contact ?? '-' }}</div> -->
                <div class="field category">{{ $data->category ?? '-' }}</div>
                <!-- <div class="field weight">{{ $user->weight ?? '-' }} kg</div> -->
                <div class="field weight-cat">{{ $data['get_waight_cat']->title ?? '-' }}</div>
                

                @if(!empty($user->photo))
                    <div class="player-photo" style="background-image: url('{{ asset($user->photo) }}');"></div>
                @else
                    <div class="player-photo">Photo</div>
                @endif
            </div>
            @endforeach
            </div>
        </div>
    </div>
</div>

<!-- JS Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    function scaleOverlay() {
        const container = document.querySelector('.form-container');
        const overlay = document.querySelector('.form-overlay');

        if (!container || !overlay) return;

        const containerWidth = container.offsetWidth;
        const scale = containerWidth / 600; // base is 600px
        overlay.style.transform = `scale(${scale})`;
    }
    
    async function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'pt', 'a4');
        const containers = document.querySelectorAll('.form-container');

        for (let i = 0; i < containers.length; i++) {
            const container = containers[i];
            
            // Use html2canvas on each individual form
            const canvas = await html2canvas(container, {
                scale: 2 // better quality
            });

            const imgData = canvas.toDataURL('image/png');
            const imgWidth = pdf.internal.pageSize.getWidth();
            const imgHeight = canvas.height * imgWidth / canvas.width;

            if (i > 0) pdf.addPage(); // add page for every form except first

            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
        }

        pdf.save("all_forms.pdf");
    }
</script>


</body>
</html>
