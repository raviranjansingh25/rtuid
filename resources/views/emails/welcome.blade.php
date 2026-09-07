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

            <h2 style="padding-top:25px; min-width:600; align:center; ">
                Hi, {{ $mailData['user'] }}!
            </h2>


            {!! $mailData['message'] !!}

            <p style="max-width:500px; align:center; padding-bottom:20px; wrap:hard">
                The Telimed Team
            </p style="color:black">
            <img class="img-fluid" height="50px;" src="{{isset($setting->header_logo)?url($setting->header_logo):url('/public/noimage.png')}}" alt="">

        </div>
    </div>
</body>

</html>