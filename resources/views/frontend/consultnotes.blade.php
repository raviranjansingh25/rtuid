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
    <button id="print-button" onclick="window.print()">Print this page</button>
    <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="padding: 20px; box-shadow: -1px 1px 4px 1px #e1e1e1; border-radius: 10px; margin-top: 170px;">
    
        <tbody>
        <div class="m-3">
            <div align="center"> <img src="{{url($setting->header_logo)}}" alt="logo" border="0" height="50px" /></div>
        </div>
            

            <tr rowspan="12" style="text-align: center; font-size: 40px; font-weight: 600; ">
                <td colspan="12" style="padding: 0px 0px 30px;">Consult Notes</td>
            </tr>
            @foreach($data as $data_get)
            <tr class="mb-2"><td colspan="6" style="font-size: 18px;font-weight: 800;line-height: 24px;"><h2>{{$data_get->vender_name}}</h2></td>
            <td colspan="6" style="font-size: 18px;font-weight: 600;line-height: 24px; text-align: end;"> Date<br>
                    <span style="font-weight: 400; font-size: 18px;">{{ $data_get->created_at->format('d M Y') }}</span>
                </td></tr><br>
            <tr rowspan="12" style="text-align: start; margin-top:10px;">
                
                <td colspan="6" style="font-size: 18px;font-weight: 600;line-height: 24px;">Patient Name <br>
                    <span style="font-weight: 400; font-size: 18px;">{{$data_get->name}}</span>
                </td>
                <td colspan="6" style="font-size: 18px;font-weight: 600;line-height: 24px; text-align: end;"> Consult Number<br>
                    <span style="font-weight: 400; font-size: 18px;">{{$data_get->consult_name}}</span>
                </td>

            </tr>

            <tr rowspan="12">
                <td colspan="12" style="font-size: 18px;line-height: 24px; font-weight: 600; padding-top: 10px;">Main Concerns <br>
                    <span style="font-weight: 400; font-size: 16px;">{{$data_get->main_concerns}}</span>
                </td>
            </tr>
            <tr rowspan="12">
                <td colspan="12" style="font-size: 18px;line-height: 24px; font-weight: 600; padding-top: 10px;">Description <br>
                    <span style="font-weight: 400; font-size: 16px;">{{$data_get->description}}</span>
                </td>
            </tr>

            <tr rowspan="12" style="text-align: left;">
                <td colspan="12" style="padding: 50px;">
                    
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>


</body>

</html>