<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">

    <title></title>


    <style>
        .logo-section {
            width: 100%;
            text-align: center;
        }

        .logo-section img {
            width: 200px;
        }

        .logo-section h3 {
            font-size: 29px;
            margin-top: 10px;
        }

        .border-box {
            border: 2px solid #000;
        }

        .inq-section {
            border-bottom: 2px solid #000;
            margin-top: 50px;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .inq-section p {
            font-size: 15px;
            font-weight: bold;
            color: #000;
            margin: 2px 0;

        }

        .mesg-section p {
            font-weight: 300;
            font-size: 14px;
            margin: 5px 0;
        }

        .it-section img {
            width: 30px;
        }

        @media print {
            button#print-button {
                display: none;
            }
        }
    </style>

</head>

<body>




    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-8">
            
                <div class="border-box p-5">
                    <button id="print-button" onclick="window.print()">Print this page</button>
                    <div class="mb-3">
            <div align="center"> <img src="{{url($setting->header_logo)}}" alt="logo" border="0" height="50px" /></div>
        </div>

                    <div class="inq-section">

                        <p>Name: {{$data->Patient_Name}}</p>
                        <p>Date: {{$data->Consultation_Date}}</p>
                        <p>Consult: {{$data->Consultation_Type}}</p>

                    </div>

                    <div class="mesg-section">
                        <p><i>Hello {{$data->Patient_Name}}</i></p>
                        <p>{{$data->General_note_to_patient}}</p>
                    </div>

                    <div class="mt-5">
                        <p><strong>Recommendations/Prescriptions:</strong></p>
                        <p>{{$data->Recommendations}}</p>

                        <p><strong>Dietary Recommendations:</strong></p>
                        <p>{{$data->Dietary_Recommendations}}</p>

                        <p><strong>Supplement:</strong></p>
                        <p>{{$data->Dietary_Recommendations}}</p>



                        <div class="it-section">
                            <p><strong>Handouts or documents to attach:</strong></p>
                            <img src="{{isset($data->Handouts_or_documents_to_attach)?url($data->Handouts_or_documents_to_attach):url('/public/noimage.png')}}" style="width: 100px;" alt="">
                        </div>

                        <p class="mt-5"><strong>Your Next Consultation:</strong> <span class="ms-5"> {{$data->Next_Appointment}}</span></p>

                    </div>


                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>