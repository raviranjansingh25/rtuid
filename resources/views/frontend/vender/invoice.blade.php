<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title> Order confirmation </title>
  <meta name="robots" content="noindex,nofollow" />
  <meta name="viewport" content="width=device-width; initial-scale=1.0;" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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

</head>

<body>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">

    <tr>
      <td height="20"></td>
    </tr>
    <tr>
      <td>
        <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 10px 10px 0 0;">

          <tr class="hiddenMobile">
            <td height="40"></td>
          </tr>

          <tr>
            <td>
              <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                <button id="print-button" onclick="window.print()">Print this page</button>
                <tbody>
                  <tr>
                    <td>
                      <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="col">
                        <tbody>
                          <tr>
                            <td align="center"> <img src="{{url($setting->header_logo)}}" alt="logo" border="0" height="40px" /></td>
                          </tr>
                          <tr class="hiddenMobile">
                            <td height="40"></td>
                          </tr>
                          <tr>
                            <td style="font-size: 18px; color: #000000; line-height: 18px; vertical-align: top; text-align: center; font-weight: 700; line-height: 28px;">
                              Thank you for supporting our telimed community!<br>
                              Please view your invoice below.
                            </td>
                          </tr>
                        </tbody>
                      </table>

                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!-- /Header -->
  <!-- Information -->
  <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tbody>
      <tr>
        <td>
          <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
            <tbody>
              <tr>
              <tr class="hiddenMobile">
                <td height="40"></td>
              </tr>
              <tr>
                <td>
                  <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                    <tbody>
                      <tr>
                        <td>
                          <table width="300" border="0" cellpadding="0" cellspacing="0" align="left" class="col">

                            <tbody>
                              <tr>
                                <td style="font-size: 16px; letter-spacing: 1px; color: black;">
                                  ISSUED TO :
                                </td>
                              </tr>
                              <tr>
                                <td width="100%" height="5"></td>
                              </tr>
                              <tr>
                                <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px;">
                                  {{$history['get_patient']->name}}
                                </td>
                              </tr>
                              <tr>
                                <td width="100%" height="5"></td>
                              </tr>
                              <tr>
                                <td width="100%" height="10">
                                  <a href="#" style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; text-decoration: none;">{{isset($history['get_patient']->email)?$history['get_patient']->email:'N/A'}}</a>
                                </td>
                              </tr>
                            </tbody>
                          </table>


                          <table width="300" border="0" cellpadding="0" cellspacing="0" align="right" class="col">
                            <tbody>
                              <tr style="text-align: right;">
                                <td style="font-size: 16px; letter-spacing: 1px; color:  black; font-weight: 400;">
                                  <strong style="color: black;">Invoice No. :</strong> #Teli0{{$history->id}}
                                </td>
                              </tr>
                              <tr>
                                <td width="100%" height="5"></td>
                              </tr>
                              <tr style="text-align: right;">
                                <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px;">
                                  <strong style="color: black;">Date : </strong>{{$history->created_at}}
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <!-- /Information -->
  <!-- Order Details -->
  <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tbody>
      <tr>
        <td>
          <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
            <tbody>
              <tr>
              <tr class="hiddenMobile">
                <td height="40"></td>
              </tr>
              <tr>
                <td>
                  <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                    <tbody>
                      <tr>
                        <td colspan="4" style="border-bottom: 1px solid black;"></td>
                      </tr>
                      <tr>
                        <th style="font-size: 16px; letter-spacing: 1px; color: black; padding: 8px 0px;" align="left" width="25%">
                          DESCRIPTION
                        </th>
                        <th style="font-size: 16px; letter-spacing: 1px; color: black; padding: 8px 0px" align="center" width="25%">
                          UNIT PRICE
                        </th>
                        <th style="font-size: 16px; letter-spacing: 1px; color: black; padding: 8px 0px" align="center" width="25%">
                          QTY
                        </th>
                        <th style="font-size: 16px; letter-spacing: 1px; color: black; padding: 8px 0px" align="right" width="25%">
                          TOTAL
                        </th>
                      </tr>
                      <tr>
                        <td colspan="4" style="border-top: 1px solid black;"></td>
                      </tr>


                      <tr style="padding: 8px 0px;">
                        <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px;padding: 8px 0px" width="25%" align="left">
                          {{$history->course_name}}
                        </td>
                        <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; padding: 8px 0px" width="25%" align="center">{{$history->price}}</td>
                        <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; padding: 8px 0px" width="25%" align="center">1</td>
                        <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; padding: 8px 0px" width="25%" align="right">{{$history->price}}</td>
                      </tr>


                      <tr>
                        <td colspan="4" style="border-top: 1px solid black;"></td>
                      </tr>

                      <tr>
                        <th style="font-size: 16px; letter-spacing: 1px; color: black; padding: 8px 0px;" colspan="2" align="left">
                          TOTAL
                        </th>
                        <th style="font-size: 16px; letter-spacing: 1px; color: black; padding: 8px 0px;" colspan="2" align="right">
                          $ {{$history->price}}
                        </th>
                      </tr>

                      <tr>
                        <td colspan="4" style="border-bottom: 1px solid black;"></td>
                      </tr>

                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td height="20"></td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <!-- /Order Details -->
  <!-- Total -->
  <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tbody>
      <tr>
        <td>
          <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
            <tbody>
              <tr>
                <td>

                  <!-- Table Total -->
                  <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                    <tbody>
                      <tr>
                        <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; padding: 4px 0px; text-align:right; ">
                          Total
                        </td>
                        <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; padding: 4px 0px; text-align:right;">
                          ${{$history->price}}
                        </td>
                      </tr>
                      @php
                          
                          $fee = $history->price*17.5/100;
                          @endphp
                      <tr></tr>
                      
                      
                        <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; padding: 4px 0px; text-align:right; ">
                        Platform fee 17.5%
                        </td>
                        <td style="font-size: 16px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; padding: 4px 0px; text-align:right; ">
                          -${{(number_format($fee,2))}}
                        </td>
                      </tr>
                      <tr>
                        <td style="font-size: 16px; letter-spacing: 1px; color: black; padding: 4px 0px; text-align:right; ">
                          Amount Due
                        </td>
                        <td style="font-size: 16px; letter-spacing: 1px; color: black;  text-align:right; ">
                          
                          $ {{ number_format($history->price - $fee, 2) }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <!-- /Table Total -->

                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <!-- /Total -->

  <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">

    <tr>
      <td>
        <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 0 0 10px 10px;">
          <tr>
            <td>
              <table width="750" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                <tbody>
                  <tr>
                    <td>
                      <img style="margin-left: 50px;" src="{{url('/public/frontend/')}}/assets/images/fav.svg">
                    </td>
                  </tr>
                  <tr>
                    <td style="font-size: 14px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; text-align: left; padding-left: 13px;">
                      ACN: 671372339
                    </td>
                  </tr>
                  <tr>
                    <td style="font-size: 14px; line-height: 20px; vertical-align: top; color: black; font-weight: 400; letter-spacing: 1px; text-align: left;">
                      ABN: 87671372339
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr class="spacer">
            <td height="40"></td>
          </tr>

        </table>
      </td>
    </tr>
    <tr>
      <td height="20"></td>
    </tr>

  </table>

</body>

</html>