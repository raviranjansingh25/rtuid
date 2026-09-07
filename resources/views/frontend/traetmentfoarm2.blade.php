<html lang="en">

<head>

</head>
<style>
    body {
        font-family: "Poppins", sans-serif;
        font-weight: 700;
        font-style: normal;
    }

    @media print {
        button#print-button {
            display: none;
        }
    }
</style>

<body>

    <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="box-shadow: -1px 1px 4px 1px #e1e1e1; border-radius: 10px; margin-top: 50px; padding: 50px 20px;">
        <button id="print-button" onclick="window.print()">Print this page</button>
        
        <tbody>

            <tr rowspan="12" style=" text-align: center; ">
                <td colspan="12">
                    <img src="img/77606.png" alt="">
                </td>
            </tr>

            <tr rowspan="12" style="text-align: center; font-size: 40px; font-weight: 600; ">
                <td colspan="12" style="padding: 50px 0px 30px;">Treatment Plan</td>
            </tr>

            <tr rowspan="12" style="text-align: start;">
                <td colspan="1"></td>
                <td colspan="1" style="font-size: 20px; font-weight: 400; line-height: 24px;">Patient Name <br>
                    <span style="font-size: 20px; font-weight: 600;">{{$data->Patient_Name}}</span>
                </td>
                <td colspan="7" style="font-size: 20px; font-weight: 400; line-height: 24px;">Consultation Type<br>
                    <span style="font-size: 20px; font-weight: 600;">{{$data->Consultation_Type}}</span>
                </td>
                <td colspan="4" style="font-size: 20px; font-weight: 400; line-height: 24px;">Consultation date<br>
                    <span style="font-size: 20px; font-weight: 600;">{{$data->Consultation_Date}}</span>
                </td>

            </tr>



            <tr rowspan="12" style="text-align: center;">
                <td colspan="12" style="font-size: 20px; font-weight: 600; line-height: 28px; padding: 50px;">General
                    note to patient: <br>
                    <span style="font-size: 15px; font-weight: 300;">{{$data->General_note_to_patient}}</span>
                </td>
            </tr>



            <tr rowspan="12" style="text-align: center; background: transparent linear-gradient(292deg, #2CFDB2 0%, #00D2D9 100%);">
                <td colspan="3" style="padding: 20px 0px; font-weight: 600; ">Recommendations/Prescriptions:</td>
                <td colspan="5" style="font-weight: 600; ">Dietary Recommendations:</td>
                <td colspan="4" style="font-weight: 600; ">Supplement:</td>
            </tr>
            <tr style="text-align: center; border: 1px ;">
                <td colspan="3" style="padding: 20px 0px; border-bottom: 1px solid #eee;"> {{$data->Recommendations}} </td>
                <td colspan="5" style="padding: 20px 0px; border-bottom: 1px solid #eee;">{{$data->Dietary_Recommendations}}</td>
                <td colspan="4" style="padding: 20px 0px; border-bottom: 1px solid #eee;">{{$data->Supplement}}</td>
            </tr>

            <tr rowspan="12">
                <td colspan="12">
                    <div style="display: flex; justify-content: space-between;">
                        <div>
                            <p style="font-size: 20px; font-weight: 600; margin-bottom: 15px; margin-top: 30px;">
                                Handouts or documents to attach:</p>
                            <img src="{{isset($data->Handouts_or_documents_to_attach)?url($data->Handouts_or_documents_to_attach):url('/public/noimage.png')}}" style="width: 100px;" alt="">
                        </div>
                        <div style="width: 50%;">
                            <p style="font-size: 20px; font-weight: 600; margin-bottom: 15px; margin-top: 30px;">Next
                                Appointment:sss</p>
                            <span>{{$data->Next_Appointment}}</span>
                        </div>
                    </div>
                </td>

            </tr>


        </tbody>
    </table>


</body>

</html>