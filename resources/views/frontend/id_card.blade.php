@extends('frontend.layout.layout2')
@section('content')
@include('frontend.layout.sidebar')

<style>
.front-bgr{
  /*background-image:url('');*/
  height: 205px;
  width:326px;
  background-repeat: no-repeat;
  background-size: contain;
margin: auto;
position: relative;

font-family: 'Source Sans Pro', sans-serif;
font-size: 16px;
}


.name-id{
  position:absolute;
  top: 80px;
    left: 100px;
font-family: 'Montserrat', sans-serif;
font-size: 11px;
font-weight: 550;
} 


.father-id{
  position:absolute;
      top: 96px;
    left: 100px;
font-family: 'Montserrat', sans-serif;
font-size: 11px;
font-weight: 550;
}   


.sex-id{
  position:absolute;
  top: 114px;
    left: 100px;
font-family: 'Montserrat', sans-serif;
font-size: 11px;
font-weight: 550;
}  


.dob-id{
  position:absolute;
  top: 130px;
    left: 100px;
font-family: 'Montserrat', sans-serif;
font-size: 11px;
font-weight: 550;
}  


.id-id{
  position:absolute;
  top: 148px;
    left: 100px;
font-family: 'Montserrat', sans-serif;
font-size: 11px;
font-weight: 550;
}  

.id-dist{
  position:absolute;
  top: 166px;
  left: 100px;
  font-family: 'Montserrat', sans-serif;
  font-size: 11px;
  font-weight: 550;
}


.grade-id{
  position:absolute;
  top: 174px;
    left: 149px;
font-family: 'Montserrat', sans-serif;
font-size: 11px;
font-weight: 550;
} 

.img-id{
  width: 100px;
  height: 100px;
  /* border-radius: 50%; */
  /* border:3px solid #FFC342; */
  position: absolute;
  top: 117px;
  right: 15px;
}


.img-id img {
  border-radius: 10%;
    /* padding: 1px; */
    height: 75px;
    width: 62px;
    border: 2px solid black;
}




.back-bgr{
  /*background-image:url('{{url('/public/')}}/2.png');*/
height: 205px;width:326px;background-repeat: no-repeat;
  background-size: contain;
margin: auto;
position: relative;

font-family: 'Source Sans Pro', sans-serif;
font-size: 16px;
margin-top: 20px;

}


.mobile-id{
  position:absolute;
  top: 17px;
    left: 85px;
font-family: 'Montserrat', sans-serif;
font-size: 11px;
font-weight: 550;

}   .mobile-idw{
  position:absolute;
  top: 43px;
    left: 46px;
font-family: 'Montserrat', sans-serif;
font-size: 11px;
font-weight: 550;

}



.address-id{
  position:absolute;
  top: 66px;
    left: 85px;
    font-family: 'Montserrat', sans-serif;
    font-size: 11px;
    font-weight: 550;
    width: 66%;
}  
.email-id{
  position:absolute;
  top: 41px;
    left: 85px;
    font-family: 'Montserrat', sans-serif;
    font-size: 11px;
    font-weight: 550;
    width: 66%;
}




.for-bich{

}

    
</style>
<div class="col-md-9">

  <div class="row">



    <div class="col-md-12">
      <div class="payment-history">
        <h2>Payment History</h2>
        <div class="content_wrapper">
    <div class="contaner">
        <div class="row justify-content-center mb-none-30 ">

            
            <div class="col-lg-11 col-md-11 mb-30 col-11" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="overview-card">
              <a onclick="Convert_HTML_To_PDF()" href="#">Download Front Image</a>   
                            
          <div class="container for-bich">
          <?php
            if($user['gender']==1){
                   $gender = 'Male';
               }elseif($user['gender']==2){
                    $gender = 'Female';
               }else{
                   $gender = 'Transgender';
               }
            ?>
            

<div id="contentToPrint">
    <div class="front-bgr" >
      
    <img src="{{url('/public/')}}/id 5.jpg" height="100%" width="100%">
      <div class="name-id"><?php echo $user['name'] ?></div> 
      <div class="father-id"><?php echo $user['father_name'] ?></div> 
      <div class="sex-id"><?php echo $gender ?></div>    
      <div class="dob-id"><?php echo $user['dob'] ?></div>  
      <div class="id-id"><?php echo 'RTUID/' . $user['get_dist']->short_code . '/' . (100 + $user['id']); ?>
      </div>  
      <div class="id-dist"><?php echo $user['get_dist']->title ?></div> 


      <div class="img-id" style="height: 75px; width: 62px;">
        
        <img src="{{isset($user['photo'])?url($user['photo']):url('/uploads/user.jpg')}}" width="100%" height="100%">
      </div>

    </div>


    <div class="back-bgr"> 
      <img src="{{url('/public/')}}/id 6.jpg" height="100%" width="100%">
      <div class="mobile-id"><?php echo $user['contact_number'] ?></div> 
      <div class="email-id"><?php echo $user['email'] ?></div>

      <div class="address-id"><?php echo $user['address'] ?></div>  



    </div>
    </div>
 

  </div>
                        
                    
                </div>
            </div>
            

        </div>
    </div>





</div>
      </div>
    </div>
  </div>
</div>
</div>



</div>
</section>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://demos.codexworld.com/includes/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
 
<script>
window.jsPDF = window.jspdf.jsPDF;


function Convert_HTML_To_PDF() {
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
}

</script>
@endsection