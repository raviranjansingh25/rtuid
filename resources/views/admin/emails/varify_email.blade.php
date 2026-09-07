<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/x-icon" href="{{url($setting->fav_icon)}}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Telimed</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    <link rel="stylesheet" href="./css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
</head>

<body bgcolor="black">
    <div>

        <div align="center" style="background-color:#FFFFFF; padding-left:20px; padding-right:20px; max-width:550px; margin:auto; border-radius:5px; padding-bottom:5px; text-align:left; margin-bottom:40px; width:80%">

            <h2 style="padding-top:25px; min-width:600; align:center; font-family:Roboto">
                Hi, {{ $mailData['user'] }}!
            </h2>
            <!-- <p style="max-width:500px; align:center; font-family:Roboto; padding-bottom:0px; wrap:hard; line-height:25px">
          Thanks for creating an account with PayPerView! We're so happy to have you on board. 
        </p> -->
            <p style="max-width:500px; align:center; font-family:Roboto-Bold; padding-bottom:0px; wrap:hard">
                Please verify your OTP using the code below:
            </p>
            <h1 style="font-family:Roboto-Bold; letter-spacing:5px; margin-bottom:0px">
                {{ $mailData['otp'] }}
            </h1>

            <p style="max-width:500px; align:center; font-family:Roboto; padding-bottom:0px; wrap:hard">
                Thank you,
            </p>
            <p style="max-width:500px; align:center; font-family:Roboto; padding-bottom:20px; wrap:hard">
                The Telimed Team
            </p style="color:black">
            <hr>
            </hr>
            <p style="max-width:100%; align:center; font-family:Roboto; padding-bottom:10px; wrap:hard; padding-top: 0px; font-size:10px">
                You’re receiving this email because you recently created a new Telimed account. If this wasn’t you, please ignore this email.
            </p>
        </div>
    </div>
</body>

</html>