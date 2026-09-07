@php
$settingdata = App\Models\Setting::first();
@endphp
<!DOCTYPE html>
<html>

<head>
	<title>form</title>

	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<style>
    body {margin: 0px;}
.main{
    width: 700px;
    height: 980px;
    margin: auto;
    background:white;
    position: relative;
    border:1px solid black;

   }

   .logo{
    position: absolute;
    top: 14px;
    left: 14px;
   }

   .head2{
    position: absolute;
    top:30px; 
    left: 180px;
   }

   .head2 h2{
   text-align: center;
   

   }

   .parti{
    position: absolute;
    top: 100px;
    left: 281px;
   
   }

   .th19{
    position: absolute;
    top: 124px;
    /*left: 225px;*/
    width: 100%;
    color: #F27170;
    font-size: 1.25rem;
    text-align: center;
   }

   .img-border{
    position: absolute;
    
    top: 276px;
    left: 500px;

}

.img-border img{
    border-radius: 15px;
}

.fill-row{
    position: relative;top:239px;left: 96px;
}

.boldreg{
    font-weight: 700;
}


.down-para{
    position: relative;
    top: 219px;
    float: left;
    padding: 20px;
    text-align: justify;
    line-height: inherit;
}

.i-para{
    float: left;
}

.left-para{
    float: left;
}

.spc-para{
    float: left;
}

.sign{
    position: absolute;
    bottom: 50px;
    right: 40px;
}

.seal-blank{
    text-align: right;
}

.seal{
     position: absolute;
    bottom: 50px;
    left: 40px;

}


.blank-bbb{
    position:absolute;
    top:239px;
    left: 281px;
}

.blank-bbb div{
        padding: 28px;
}
/* new */

.row-box {
    width: 100%;
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 20px;
    padding: 0 0 20px;
}

.left-para p {
    font-size: 15px;
    padding: 0 50px;
}

@media only screen and (min-device-width : 320px) and (max-device-width : 576px) {
    .main {
        width: 700px;
        height: 950px;
    }
    
    .down-para {
        padding: 30px 60px;
        text-align: justify;
        line-height: 20px;
        font-size: 12px;
    }
    
    .row-box {
        padding: 0 0 10px;
    }
}
</style>
<body>

	<div class="main" id="contentToPrint">
	<!--<div class="main">-->
		<div class="logo"><img src="{{url($settingdata->logo)}}" width="150" height="150"></div>
		<div class="head2">
			<h2>JUMP ROPE FEDERATION OF INDIA<br>
			(JRFI)</h2>
		</div>
		<div class="parti">
			<h3>PARTICIPATION FORM</h3>
		</div>

		<div class="th19">
			<h5>({{$event['get_newevent']->name}})</h5>
		</div>

		<div class="img-border">
			<img src="{{url($user->passport)}}" width="130" height="130">

		</div>
		@php
		if($user->gender==1){ 
		$gender = 'Male'; 
	}
	elseif($user->gender==2){
	$gender = 'Female'; 
}
else {
$gender = 'Transgender';
}
@endphp
<div class="fill-row ">
	<div class="row-box">
		<div class="reg boldreg">Registration ID No.</div>
		<div class="register-number">{{$user->code}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">STATE</div>
		<div class="register-number">{{$user['get_state']->name}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">1. Player's Name</div>
		<div class="register-number" style="width:150px;">{{$user->name}} {{$user->middlename}} {{$user->lastname}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">2. Fathers's Name</div>
		<div class="register-number" style="width:150px;">{{$user->fathername}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">3. Date of Birth</div>
		<div class="register-number">{{$user->dob}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">4. Gender</div>
		<div class="register-number">{{$gender}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">5. Age Category</div>
		<div class="register-number">{{$user['get_cat']->name}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">6. Sub Category</div>
		<div class="register-number">{{$event['get_newevent']['get_subcat']->title}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">7. Mobile No</div>
		<div class="register-number">{{$user->mobile}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">8. Email Id.</div>
		<div class="register-number">{{$user->email}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">9. Address</div>
		<div class="register-number" style="width: 70%;
		">{{$user->street}}, {{$user->city}}</div>
	</div>
	<div class="row-box">
		<div class="reg boldreg">10. Participating</div>
		<div class="register-number">
		    <!--<span>{{$event['get_newevent']['get_event']->name}}<br></span>-->
		    @foreach($event1 as $ev1)
            <span>{{$ev1['get_newevent']['get_event']->name}}<br></span>
            @endforeach
            @foreach($event2 as $ev2)
            <span>{{$ev2['get_newevent']['get_event']->name}}<br></span>
            @endforeach
		    <!--<span>{{$event['get_newevent']['get_event']->name}}</span>-->
		 </div>
	</div>





</div>

<div class="down-para">
	I&nbsp; {{$user->name}} {{$user->middlename}} {{$user->lastname}}&nbsp;the undersigned knowingly and without any objection voluntarily submit my entry to Organizing Committee of Jump Rope Federation of India. Subject to the acceptance of my participation by the organizer which may result from or in connection to my participation is purely Organizer Cancers. I undertake to abide by the result, rules & regulation of JRFI and understand that my protest must be conducted in accordance with the rules of Arbitration.
</div>

<div class="footer-signature">

	<div class="seal">
		<div>............</div>
		<div>(Signature of Jumper)</div>
	</div>
	<div class="sign">
		<div class="sign-blank">...............</div>
		<div>(Signature of State Secretary & Seal)</div>
	</div>
</div>
</div>

</div>

</div>
</body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://demos.codexworld.com/includes/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    $(document).ready( function () { 
      window.jsPDF = window.jspdf.jsPDF;
      var doc = new jsPDF();
      // Source HTMLElement or a string containing HTML.
      var elementHTML = document.querySelector("#contentToPrint");

      doc.html(elementHTML, {
        callback: function(doc) {
          // Save the PDF
          doc.save('document-html.pdf');
        },
        margin: [10, 10, 10, 10],
        autoPaging: 'text',
        x: 0,
        y: 0,
        width: 190, // Target width in the PDF document
        windowWidth: 675 // Window width in CSS pixels


      });
      // window.history.back();


      
    });
</script> 
</html>