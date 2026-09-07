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

    <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="padding: 20px; box-shadow: -1px 1px 4px 1px #e1e1e1; border-radius: 10px;">
        <button id="print-button" onclick="window.print()">Print this page</button>
        <div class="mb-3">
            <div align="center"> <img src="{{url($setting->header_logo)}}" alt="logo" border="0" height="50px" /></div>
        </div>
        <tbody>
        
            <tr rowspan="12" style=" text-align: center; ">
                <td colspan="12">
                    <img src="img/77606.png" alt="">
                </td>
            </tr>

            <tr rowspan="12" style="text-align: center; font-size: 40px; font-weight: 600; ">
                <td colspan="12" style="padding: 0px 0px 30px;">Preconsult form</td>
            </tr>

            <tr rowspan="12" style="text-align: center;">
                <td colspan="3" style="font-size: 20px; font-weight: 600; padding: 10px 0;">First Name</td>
                <td></td>
                <td colspan="3" style="font-size: 20px; font-weight: 600; padding: 10px 0;">Last Name</td>

            </tr>
            <tr rowspan="12" style="text-align: center;">
                <td colspan="3" style="font-size: 20px; font-weight: 600;    padding-bottom: 10px;">{{$data->first_name}}</td>
                <td></td>
                <td colspan="3" style="font-size: 20px; font-weight: 600;     padding-bottom: 10px;">{{$data->first_name}}</td>

            </tr>


            <tr rowspan="12" style="text-align: center; background: transparent linear-gradient(292deg, #2CFDB2 0%, #00D2D9 100%);">
                <td colspan="2" style="padding: 20px 0px; font-weight: 600; ">DOB</td>
                <td colspan="2" style="font-weight: 600; ">Email</td>
                <td colspan="2" style="font-weight: 600; ">Height</td>
                <td colspan="2" style="font-weight: 600; ">Sex</td>
                <td colspan="2" style="font-weight: 600; ">Blood type</td>
                <td colspan="2" style="font-weight: 600; ">Weight</td>


            </tr>
            <tr style="text-align: center; border: 1px ;">
                <td colspan="2" style="padding: 20px 0px;"> {{$data->dob}}</td>
                <td colspan="2" style="padding: 20px 0px;">{{$data->email}}</td>
                <td colspan="2" style="padding: 20px 0px;">{{$data->height}}</td>
                <td colspan="2" style="padding: 20px 0px;">{{$data->sex}}</td>
                <td colspan="2" style="padding: 20px 0px;">{{$data->blood_type}}</td>
                <td colspan="2" style="padding: 20px 0px;">{{$data->weight}}</td>
            </tr>

            <tr rowspan="12">
                <td colspan="6">
                    <div style=" display: flex; flex-direction: column; align-items: start; ">
                        <p style="margin-bottom: 5px; font-size: 18px; font-weight: 600;">Blood pressure</p>
                        <span style="font-size: 15px; font-weight: 400; text-align: end;">{{$data->blood_pressure}}</span>
                    </div>
                    <div style=" display: flex; flex-direction: column; align-items: start; ">
                        <p style="margin-bottom: 5px; font-size: 14px;">Previous occupations</p>
                        <span style="font-size: 18px; font-weight: 600;">{{$data->previous_occupations}}</span>
                    </div>
                    <div style=" display: flex; flex-direction: column; align-items: start; ">
                        <p style="margin-bottom: 5px; font-size: 14px">Current occupations</p>
                        <span style="font-size: 18px; font-weight: 600;">{{$data->current_occupations}}</span>
                    </div>
                    <div style=" display: flex; flex-direction: column; align-items: start; ">
                        <p style="margin-bottom: 5px; font-size: 14px">Next of Kin Name</p>
                        <span style="font-size: 18px; font-weight: 600;">{{$data->next_to_kin}}</span>
                    </div>
                    <div style=" display: flex; flex-direction: column; align-items: start; ">
                        <p style="margin-bottom: 5px; font-size: 14px">Next of Kin Contact Number</p>
                        <span style="font-size: 18px; font-weight: 600;">{{$data->next_to_kin_contact}}</span>
                    </div>
                    <div style=" display: flex; flex-direction: column; align-items: start; margin-bottom: 10px;">
                        <p style="margin-bottom: 5px; font-size: 14px">Children and Ages</p>
                        <span style="font-size: 18px; font-weight: 600;">{{$data->children_and_age}}</span>
                    </div>
                </td>
                <td colspan="6">
                    <div style=" display: flex; flex-direction: column; align-items: end; ">
                        <p style="margin-bottom: 5px; font-size: 18px; font-weight: 600;">Address</p>
                        <span style="font-size: 15px; font-weight: 400; text-align: end;">{{$data->address}}</span>
                    </div>
                    <div style=" display: flex; flex-direction: column; align-items: end; ">
                        <p style="margin-bottom: 5px; font-size: 14px;">Phone Number</p>
                        <span style="font-size: 18px; font-weight: 600;">{{$data->phone_number}}</span>
                    </div>
                    <!-- <div style=" display: flex; flex-direction: column; align-items: end; ">
                        <p style="margin-bottom: 5px; font-size: 14px">Phone Number</p>
                        <span style="font-size: 18px; font-weight: 600;">123456987488</span>
                    </div> -->
                </td>
            </tr>




            <tr rowspan="12" style="background: transparent linear-gradient(292deg, #2CFDB2 0%, #00D2D9 100%); text-align: center;">
                <td colspan="6" style="padding: 10px 0;">Question</td>
                <td colspan="6" style="padding: 10px 0;">Answer</td>
            </tr>


            <tr rowspan="12">
                <td colspan="3" style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    What other health and/or medical professionals are you currently seeing?</td>
                <td style="border-bottom: 1px solid #eee;"></td>
                <td colspan="8" style="padding: 10px 0; border-bottom: 1px solid #eee;">Lorem ipsum dolor sit amet
                    consectetur adipisicing elit.
                </td>
            </tr>
            <tr rowspan="12">
                <td colspan="3" style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    Have you had any hospitalisations, surgery, procedures, medical, test, accidents, injuries,
                    C-sections? (What, When and Why?):</td>
                <td style="border-bottom: 1px solid #eee;"></td>

                <td colspan="8" style="padding: 10px 0; border-bottom: 1px solid #eee;">Lorem ipsum dolor sit amet
                    consectetur adipisicing elit.
                </td>
            </tr>

            <tr rowspan="12">
                <td colspan="3" style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    Do you have any diagnosed conditions?:</td>
                <td style="border-bottom: 1px solid #eee;"></td>

                <td colspan="8" style="padding: 10px 0; border-bottom: 1px solid #eee;">Lorem ipsum dolor sit amet
                    consectetur adipisicing elit.
                </td>
            </tr>
            <tr rowspan="12">
                <td colspan="3" style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    Family History:</td>
                <td style="border-bottom: 1px solid #eee;"></td>

                <td colspan="8" style="padding: 10px 0; border-bottom: 1px solid #eee;">Lorem ipsum dolor sit amet
                    consectetur adipisicing elit.
                </td>
            </tr>
            <tr rowspan="12">
                <td colspan="3" style="padding: 10px 0; border-bottom: 1px solid #eee;">

                    Have you been on any overseas trips in the last 12 months?:</td>
                <td style="border-bottom: 1px solid #eee;"></td>

                <td colspan="8" style="padding: 10px 0; border-bottom: 1px solid #eee;">Lorem ipsum dolor sit amet
                    consectetur adipisicing elit.
                </td>
            </tr>
            <tr rowspan="12">
                <td colspan="3" style="padding: 10px 0; border-bottom: 1px solid #eee;">

                    What vaccinations have you had in the last 3 years?:</td>
                <td style="border-bottom: 1px solid #eee;"></td>

                <td colspan="8" style="padding: 10px 0; border-bottom: 1px solid #eee;">Lorem ipsum dolor sit amet
                    consectetur adipisicing elit.
                </td>
            </tr>

            <tr rowspan="12" style="text-align: center;">
                <td colspan="12" style="padding: 30px;">
                    Signature: <span style="font-weight: 600;">Yes</span></td>
            </tr>

        </tbody>
    </table>


</body>

</html>