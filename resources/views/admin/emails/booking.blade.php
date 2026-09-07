 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>


  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <title>Email</title> 

    <!-- Start stylesheet -->
    <style type="text/css">
      a,a[href],a:hover, a:link, a:visited {
        /* This is the link colour */
        text-decoration: none!important;
        color: #000;
      }
      .link {
        text-decoration: underline!important;
      }
      p, p:visited {
        /* Fallback paragraph style */
        font-size:15px;
        line-height:24px; 
        font-weight:300;
        text-decoration:none;
        color: #000;
      }
      h1 {
        /* Fallback heading style */
        font-size:22px;
        line-height:24px; 
        font-weight:normal;
        text-decoration:none;
        color: #000;
      }
      .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td {line-height: 100%;}
      .ExternalClass {width: 100%;}
      h3{
        color: #000;
      }
      .brd-pss{
        background-color: #db0f00;
    color: #000;
    font-size: 24px;
    margin-bottom: 0;
    padding: 10px;
    display: inline-block;
      }
    </style>
    <!-- End stylesheet -->

</head>
@php
$check_in = strtotime($bookingData->check_in);
$check_out = strtotime($bookingData->check_out);
$day_diff = $check_out - $check_in;
$day_count = $day_diff/(60*60*24);

@endphp
  <!-- You can change background colour here -->
  <body style="text-align: center; margin: 0; padding-top: 10px; padding-bottom: 10px; padding-left: 0; padding-right: 0; -webkit-text-size-adjust: 100%;background-color: #f2f4f6; color: #000" align="center">
  
  <!-- Fallback force center content -->
  <div style="width:600px; background-color: #fff; margin:0 auto 20px; position: relative;">
    <!-- <img style="position: absolute; top: 0px;right: 0; width:600px;object-fit: cover; height: 100%;" src="https://v1.checkprojectstatus.com/flitecity/emailBG.gif" alt=""> -->
    
  <div style="text-align: center; position: relative; ">

  
    <!-- Start container for logo -->
    <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #db0f00;     backdrop-filter: blur(5px);    " width="600">
      <tbody>
        <tr>
          <td style="width: 596px; vertical-align: top; padding-left: 0; padding-right: 0; padding-top: 15px; padding-bottom: 15px; text-align: center;" width="596">

            <!-- Your logo is here -->
            <img style="width: 180px; max-width: 180px; height: 85px; max-height: 85px; text-align: left; color: #000;filter: brightness(100); !important" alt="Logo" src="https://cityroom.in/public/logo.png" align="center" width="180" height="85">

          </td>
        </tr>
      </tbody>
    </table>
    <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #db0f00;     backdrop-filter: blur(5px);    " width="600">
      <tbody>
        <tr>
          <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left;" width="596">
      
            <!-- Your logo is here -->
            <span style="color: #fff;">Thanks, {{$bookingData->guest_name}}!</span> 
            <p style="text-align: left; color: #fff;">Your booking in {{$bookingData['get_hotel']->hotel_name}}, {{$bookingData['get_hotel']->city_name}}  is confirmed.</p>
            <h3 style="text-align: left;color: #fff;"> {{$bookingData['get_hotel']->hotel_name}} is expecting you on {{date('F j, Y', strtotime($bookingData->check_in))}} 12:00 pm to till {{date('F j, Y', strtotime($bookingData->check_out))}} 11 am (Booking
              date)
              </h3> 
          </td>
        </tr>
      </tbody>
    </table>
    <table align="center" style=" border-bottom: 1px dashed #fff; text-align: center; vertical-align: top; width: 600px; max-width: 600px; backdrop-filter: blur(5px);   " width="600">
      <tbody>
     
        <tr>
          <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 0px; padding-bottom: 0px; text-align: left;" width="596">
            <p style="text-align: left;margin: 0;">Your payment will be handled by {{$bookingData['get_hotel']->hotel_name}}. The "Payment" section below has more
              details
              </p>
          </td>
        </tr>
        <tr>
          <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top:5px; padding-bottom: 15px; text-align: left;" width="596">
            <table align="center" style=" border: 1px solid #e5e5e5; text-align: center; vertical-align: top; width: 580px; max-width: 580px; backdrop-filter: blur(5px);   " width="600">
              <tbody>
                <tr>
                  <td style="width: 1%; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: center;border-right: 1px solid #e5e5e5;" width="1%">
                    <b>{{$bookingData->check_in}}</b> <span style="display: block;font-size: 12px;margin-top: 5px">12 PM Onwards</span>
                  </td>
                  <td style="width: 1%; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: center; border-right: 1px solid #e5e5e5;" width="1%">
                   <b>{{$day_count}}</b> <span style="display: block; font-size: 12px; margin-top: 5px;">Night</span>
                  </td>
                  <td style="width: 1%; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: center;border-right: 1px solid #e5e5e5;" width="1%">
                    <b>{{$bookingData->check_out}}</b> <span style="display: block;font-size: 12px; margin-top: 5px">Till 11 AM </span>
                  </td>
                  <td style="width: 1%; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: center;border-right: 1px solid #e5e5e5;" width="1%">
                    <b>{{$bookingData->guest_qty}}</b>  <span style="display: block;font-size: 12px;margin-top: 5px">Guests</span>
                  </td>
                  <td style="width: 1%; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: center;" width="1%">
                    <b>{{$bookingData->room_qty}} Room</b> <span style="display: block;font-size: 12px;margin-top: 5px">{{$bookingData->room_qty}} occupancy</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15pxpx; padding-bottom: 0px; text-align: left;" width="596">
            <p style="text-align: left;margin: 0; font-size: 20px;">Payment Details
              </p>
          </td>
        </tr>
        <tr>
          <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top:20px; padding-bottom: 15px; text-align: left;" width="596">
            <table align="center" style=" text-align: center; vertical-align: top; width: 580px; max-width: 580px; backdrop-filter: blur(5px);        border: 1px solid #e5e5e5;border-radius: 10px;" width="600">
              <tbody>
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left;border-bottom: 1px solid #e5e5e5;" width="1%">
                    <b>Your reservation </b> <span style="float: right; font-size: 14px;margin-top: 5px">{{$day_count}} night, {{$bookingData->room_qty}} room</span>
                  </td>
                </tr>
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left;border-bottom: 1px solid #e5e5e5;" width="1%">
                    <b>Check-in</b> <span style="float: right; font-size: 14px;margin-top: 5px">{{date('l, F j, Y', strtotime($bookingData->check_in))}} (12:00 PM)</span>
                  </td>
                </tr>
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left;border-bottom: 1px solid #e5e5e5;" width="1%">
                    <b>Check-out </b> <span style="float: right; font-size: 14px;margin-top: 5px">{{date('l, F j, Y', strtotime($bookingData->check_out))}} (11:00 - AM)</span>
                  </td>
                </tr>
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left;border-bottom: 1px solid #e5e5e5;" width="1%">
                    <b>Booking number</b> <span style="float: right; font-size: 14px;margin-top: 5px">{{$bookingData->booking_id}}</span>
                  </td>
                </tr>
                <!-- <tr>
                  <td style="width: 596px; vertical-align: top; padding-left:10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left;border-bottom: 1px solid #e5e5e5;" width="1%">
                    <b>PIN Code</b> <span style="float: right; font-size: 14px;margin-top: 5px">{{$bookingData->booking_id}}</span>
                  </td>
                </tr> -->
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left;border-bottom: 1px solid #e5e5e5;" width="1%">
                    <b>Booked by</b> <span style="float: right; font-size: 14px;margin-top: 5px">{{$bookingData->guest_name}} ({{$bookingData->email}})</span>
                  </td>
                </tr>
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left;border-bottom: 1px solid #e5e5e5;" width="1%">
                    <b>{{$bookingData->category}}</b> <span style="float: right; font-size: 14px;margin-top: 5px">₹ {{$bookingData->total_amount}}</span>
                  </td>
                </tr>
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left;" width="1%">
                     <span style="float: right; font-size: 18px;margin-top: 5px"><b>Price ₹ {{$bookingData->total_amount}} </b></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>

        <tr>
          <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top:0px; padding-bottom: 15px; text-align: left;" width="596">
            <table align="center" style=" text-align: center; vertical-align: top; width: 580px; max-width: 580px; backdrop-filter: blur(5px);" width="600">
              <tbody>
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 15px; padding-bottom: 15px; text-align: left; font-size: 30px;font-weight: 600;color: #db0f00;" width="1%">
                    {{$bookingData['get_hotel']->hotel_name}}
                  </td>
                </tr>
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top:0px; padding-bottom: 0px; text-align: left;line-height: 26px;" width="1%">
                    <b>Add-</b> {{$bookingData['get_hotel']->address}}, 
                      {{$bookingData['get_hotel']->city_name}}, India - <a href="#">Show directions</a>
                  </td>
                </tr>
                <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 10px; padding-bottom: 15px; text-align: left;" width="1%">
                    <b>Phone:</b> +91 {{$bookingData['get_hotel']->mobile}} (Hotel Reception contact details)
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
    <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #db0f00;  " width="600">
      <tbody>
        <tr>
          <td style="width: 596px; vertical-align: top; padding-left: 0; padding-right: 0; padding-top: 15px; padding-bottom: 15px; text-align: center;" width="596">
            <a style="color: #fff;" href="https://cityroom.in/page/privacy-policy">Privacy Policy</a>,
            <a style="color: #fff;" href="https://cityroom.in/page/terms-conditions">Terms and Conditions</a>

          </td>
        </tr>
      </tbody>
    </table>
   


 
       
  
  </div>
  </div>
  
  

  </body>

</html>