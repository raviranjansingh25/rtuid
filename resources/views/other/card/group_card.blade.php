@extends('other.layout.layout')
@section('content')

<style type="text/css">
  .main{
    width: 226.78px;
    font-family: arial;
    /*height: 413.74px;*/
    border: 1px solid black; 
    margin: auto;
  }

  .logo_right{
    text-align: right;
    padding: 10px 0px;
    margin-left: -4px;
  }
  .logo_left{
    padding: 5px 0px;
  }
  .title{
    text-align: center;
    height: 20px;
  }
  .profile{
    text-align: right;
    /*border: 1px solid red;*/
  }
  .content_data{
    width: 100%;
    text-align: center;

  }
  .content_data{
    /*background-image: url("{{url('/public/frontend/logo.png')}}");*/
    background-size: 200px 200px;
    background-repeat: no-repeat;
    width: 100%;
    height: 180px;
    background-position: top;
  }

  .val_data{
    border-bottom: 1px dotted #1e90ff; 
    text-align: left;
    color: #271dbf;
    font-size: 7.2px;
  }
  .conte{
    color: #1e90ff;
    font-size: 12px;
    height: 230px;
  }

  label{
    text-align: right;
    float: left;
  }
  .card_data{
    color: black;
  }
  .title_data{
    text-align: left;
    font-size: 8px;
  }
  .Authorised{
    color: black;
    font-size: 12px;
    text-align: right;
    margin-top: 20px;
  }

</style>

  <body>


    <div class="content_wrapper">
      <div class="contaner">
        <a onclick="Convert_HTML_To_PDF()" href="#">Download Image</a>
          <div class="container main" style="padding: 0px 10px;" id="contentToPrint">
    <div class="row logo_header">
          <div class="col-md-2 col-2 logo_left">
            <img src="{{url('/public/frontend/')}}/logo.png"  height="50px">
          </div>
          <div class="col-md-8 col-8" style="text-align: center; padding-top: 25px">
            <h6 style="font-size: 10px;">JUMP ROPE FEDERATION OF INDIA (JRFI)</h6>
            
          </div>
          <div class="col-md-2 col-2 logo_right">
            <img src="{{url('/public/frontend/')}}/ijru.jpeg" height="40px" style="margin-left: -5px;">
          </div>
        </div>
        <div class="row" style="text-align: center; color: red; margin-top: -8px;">
          <h6 style="font-size: 8px; color: red;">({{$event['get_newevent']->title}})</h6>
        </div>
    
    <div class="row">
      <div style="text-align: right;"><img src="{{url($user['passport'] )}}" height="80px" width="70px" class="profile"></div>
    </div>
    <?php
        if($user['gender']==1){
          $gender = 'Male';
        }elseif($user['gender']==2){
          $gender = 'Female';
        }else{
          $gender = 'Transgender';
        }
    ?>
    <div class="row content_data" style="">
      <img src="{{url('/public/frontend/logo1.png')}}" height="200px" width="200px" >
      <div class="conte" style="position: absolute; width: 224px;">
        <h5 class="card_data"><u>ID CARD</u></h5>
        <div class="row">
          <div class="col-md-4 col-4 title_data">ID No:</div>
          <div class="col-md-8 col-8 val_data"><span type="text" name="">{{$user->code}}</span></div>
        </div>
        <div class="row">
          <div class="col-md-4 col-4 title_data">Name:</div>
          <div class="col-md-8 col-8 val_data"><span type="text" name="">{{$user->name}} {{$user->middlename}} {{$user->lastname}}</span></div>
        </div>
        <div class="row">
              <div class="col-md-6 col-6">
                <div class="row">
                  <div class="col-md-6 col-6 title_data">Sex:</div>
                  <div class="col-md-6 col-6 val_data"><span type="text" name="">{{$gender}}</span></div>
                </div>
              </div>
              <div class="col-md-6 col-6">
                <div class="row">
                  <div class="col-md-6 col-6 title_data">DOB:</div>
                  <div class="col-md-6 col-6 val_data"><span type="text" name="">{{$event['get_user']->dob}}</span></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-6">
                <div class="row">
                  <div class="col-md-6 col-6 title_data">Category:</div>
                  <div class="col-md-6 col-6 val_data"><span type="text" name="">{{$event['get_user']['get_cat']->name}}</span>
                </div>
            </div>
              </div>
              <div class="col-md-6 col-6">
                <div class="row">
                  <div class="col-md-8 col-8 title_data">Sub Category:</div>
                  <div class="col-md-4 col-4 val_data"><span type="text" name="">{{$event['get_newevent']['get_subcat']->title}}</span></div>
                </div>
              </div>
            </div>
        <div class="row">
          <div class="col-md-4 col-4 title_data">State:</div>
          <div class="col-md-8 col-8 val_data"><span type="text" name="">{{$event['get_user']['get_state']->name}}</span></div>
        </div>
        <div class="row">
          <div class="col-md-4 col-4 title_data">Event Name:</div>
          <div class="col-md-8 col-8 val_data">
            @foreach($event1 as $ev1)
            <span type="text" name="">{{$ev1['get_newevent']['get_event']->name}}</span><br>
            @endforeach
            @foreach($event2 as $ev2)
            <span>{{$ev2['get_newevent']['get_event']->name}}<br></span>
            @endforeach
            
        </div>
      </div>
        
        <div class="row">
          <div class="Authorised">
            Authorised Signatory
            <br/>
            (JRFI)
          </div>
        </div>
      </div>

  </div>

</div>
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
@endsection('content')