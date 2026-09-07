@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.1/main.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
	.modal-title{
		font-size: 25px;
	}
  .available-time h4 {
    display: inherit;
    
}

h1, h2, h3, h4 {
    font-family: 'Poppins', sans-serif;
}
  .timerangepicker-container {
    display: flex;
    position: absolute;
    background-color: white;
    z-index: 999;
}
.timerangepicker-label {
  	display: block;
    line-height: 2em;
    background: transparent linear-gradient(113deg, #00D2D9 0%, #2CFDB2 100%);
    padding-left: 1em;
    /*border-bottom: 1px solid grey;*/
    margin-bottom: 0.75em;
    color: #fff;
}

.timerangepicker-from,
.timerangepicker-to {
  /*border: 1px solid grey;
  padding-bottom: 0.75em;*/
  	padding-bottom: 0.75em;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
    border-radius: 5px;
    overflow: hidden;
}
.timerangepicker-from {
  border-right: none;
}
.timerangepicker-display {
  box-sizing: border-box;
  display: inline-block;
  width: 2.5em;
  height: 2.5em;
  border: 1px solid lightgrey;
  line-height: 2.5em;
  text-align: center;
  position: relative;
  margin: 1em 0.175em;
	border-radius: 7px;
}
.timerangepicker-display .increment,
.timerangepicker-display .decrement {
  cursor: pointer;
  position: absolute;
  font-size: 1.5em;
  width: 1.5em;
  text-align: center;
  left: 0;
}

.timerangepicker-display .increment {
  margin-top: -0.25em;
  top: -1em;
}

.timerangepicker-display .decrement {
  margin-bottom: -0.25em;
  bottom: -1em;
}

.timerangepicker-display.hour {
  margin-left: 1em;
}
.timerangepicker-display.period {
  margin-right: 1em;
}
.datepicker {
  border-radius: 4px;
  direction: ltr;
  -webkit-user-select: none;
  -webkit-touch-callout: none;
}


/* basicos */
.datepicker .day{
  border-radius: 4px;
}

.datepicker-dropdown {
  top: 0;
  left: 0;
  padding: 5px;
}
.datepicker-dropdown:before {
  content: '';
    display: inline-block;
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;
    border-bottom: 7px solid #d2d2d2;
    border-top: 0;
    border-bottom-color: #d2d2d2;
    position: absolute;
}
.datepicker-dropdown:after {
  content: '';
  display: inline-block;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-bottom: 6px solid #fff;
  border-top: 0;
  position: absolute;
}
.datepicker-dropdown.datepicker-orient-left:before {
  left: 6px;
}
.datepicker-dropdown.datepicker-orient-left:after {
  left: 7px;
}
.datepicker-dropdown.datepicker-orient-right:before {
  right: 6px;
}
.datepicker-dropdown.datepicker-orient-right:after {
  right: 7px;
}
.datepicker-dropdown.datepicker-orient-bottom:before {
  top: -7px;
}
.datepicker-dropdown.datepicker-orient-bottom:after {
  top: -6px;
}
.datepicker-dropdown.datepicker-orient-top:before {
  bottom: -7px;
  border-bottom: 0;
  border-top: 7px solid red;
}
.datepicker-dropdown.datepicker-orient-top:after {
  bottom: -6px;
  border-bottom: 0;
  border-top: 6px solid red;
}




.datepicker table {
  margin: 0;
  user-select: none;
}






.datepicker td,
.datepicker th {
  text-align: center;
  width: 30px;
  height: 30px;
  border: none;
}






.datepicker .datepicker-switch,
.datepicker .prev,
.datepicker .next,
.datepicker tfoot tr th {
  cursor: pointer;
}
/*.datepicker .datepicker-switch:hover,*/
/*.datepicker .prev:hover,*/
/*.datepicker .next:hover,*/
/*.datepicker tfoot tr th:hover {*/
  /*background: red;*/
  /*border-radius: 4px;*/
/*}*/
.datepicker .prev .disabled,
.datepicker .next .disabled {
  visibility: hidden;
}




.datepicker .range-start{
  background: #337ab7 url("../images/range-bg-1.png") top right no-repeat;
  color: #fff;
}

.datepicker .range-end{
  background: #337ab7 url("../images/range-bg-2.png") top left no-repeat;
  color: #fff;
}

.datepicker  .range-start.range-end{
  background-image: none;
}


.datepicker .range{
  background: #d5e9f7;
}

/*.datepicker .disabled.day{*/
  /*color:#999;*/

/*}*/

/* Hover para dia mes y año*/

.datepicker .day:hover,
.datepicker .month:hover,
.datepicker .year:hover,
.datepicker .datepicker-switch:hover,
.datepicker .next:hover,
.datepicker .prev:hover {
      color: #fff;
    background: transparent linear-gradient(292deg, #00D2D9 0%, #2CFDB2 100%);
  border-radius: 4px;
}


.hover {
  background-color: #ff8000;
  color: white;

}


.datepicker .today {
  font-weight:bold;
  color: #1ed443;

}

.input-group-addon{
  	height: 38px;
	width: 35px;
	text-align: center;
	padding-top: 3px;
	background-color: #07d9d2;
	color: #fff;
}





/* Estilos para meses y años */


.datepicker-months, .datepicker-years{
  width: 213px;

}

.datepicker-months td, .datepicker-years td {
  width: auto;
  height: auto;

}

.datepicker-months .month, .datepicker-years .year{
  color: #fff;
  background-color: #337ab7;
  border-color: #2e6da4;
  float: left;
  display: block;
  width: 23%;
  height: 46px;
  line-height: 46px;
  margin: 1%;
  cursor: pointer;
  border-radius: 4px;
}




.day.active, .start-date-active{
  color: #fff;
  background-color: #337ab7;
  border-color: #2e6da4;
}



/* Desactivados */
.day.disabled, .month.disabled, .year.disabled, .start-date-active.disabled{
  cursor: not-allowed;
  filter: alpha(opacity=65);
  -webkit-box-shadow: none;
  box-shadow: none;
  opacity: .65;
}


a:active,
a:hover {
  outline: 0;
}

.rowdelete3 {
  border-top: 1px dashed #bbbbbb;
    /* border-bottom: 2px dashed black; */
    
}
.rowdelete2 {
  border-top: 1px dashed #bbbbbb;
    /* border-bottom: 2px dashed black; */
 }
 label{
 	font-size: 14px;
	color: #000;
 }
</style>


<div class="col-md-12 col-lg-9 my-calender-00">
  <div class="row">

    <div class="col-xl-12">
      <div class="my-consultations my-consultations-card">
        <div class="availablity_sec_h">
          <h2>My Availability</h2>
          <a class="btn-border" data-bs-toggle="modal" href="#addavailability" href="">Add Availability</a>
        </div>
        <div class="available-time">
          <div class="available-time-itms">
            @foreach($calendar as $cal)
              @php 
                $data_ava = App\Models\DoctorAvailability::where('vender_id',$user->id)->where('custom_id',$cal->custom_id)->get();

                if($cal->date_type == 1){
                  $type = 'Every Week';
                }else{
                  $type = $cal->start_date.' to '.$cal->end_date;
                }
              @endphp

              
              <div class="available-time-itm">
              
                <h4>
                <span >{{$type}}</span>
                <span class="d-flex" style="align-items: center;">
                  <div class="m-1">
                    <a href="#" onclick="editavailability({{$cal->custom_id}})" class="text-btn"><i class="fa fa-pencil"></i></a>
                    <a href="#" onclick="deleteavailability({{$cal->custom_id}})" class="text-btn "><i class="fa fa-trash-o text-danger"></i></a>
                    
                  </div><br>
                  @foreach($data_ava as $av_data)
                    {{date("h:i a", strtotime($av_data->start_time))}} to {{date("h:i a", strtotime($av_data->end_time))}} {{$av_data->weeks}} <br>
                  @endforeach
                  </span>
                </h4>
                
              </div>
              
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-12">
      <div class="my-consultations my-consultations-card">
        <div class="availablity_sec_h">
          <h2>Not Available</h2>
          <a class="btn-border" data-bs-toggle="modal" href="#addnotavailability" href=""> Add block out time</a>
        </div>
        <div class="available-time">
          <div class="available-time-itms">
            @foreach($not_available as $not)
            @php 
                $data_ava1 = App\Models\VenderNotAvailable::where('vender_id',$user->id)->where('custom_id',$not->custom_id)->get();

                if($not->date_type == 1){
                  $type = 'Every Week';
                }else{
                  $type = $not->start_date.' to '.$not->end_date;
                }
              @endphp

              
              <div class="available-time-itm">
              
                <h4>
                <span >{{$type}}</span>
                <span class="d-flex" style="align-items: center;">
                  <div class="m-1">
                    <a href="#" onclick="editnotavailability({{$not->custom_id}})" class="text-btn"><i class="fa fa-pencil"></i></a>
                    <a href="#" onclick="delete_unavailability({{$not->custom_id}})" class="text-btn"><i class="fa fa-trash-o text-danger"></i></a>
                  </div><br>
                  
                  @foreach($data_ava1 as $av_data1)
                    {{date("h:i a", strtotime($av_data1->start_time))}} to {{date("h:i a", strtotime($av_data1->end_time))}} {{$av_data1->weeks}} <br>
                  @endforeach
                  </span>
                  
                </h4>
                
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="my-consultations my-calender-box">
        <!-- <div id='calendar'></div> -->
        <div id="calendar-container">
          <div id="calendar"></div>
        </div>
      </div>

    </div> 
  </div>
</div>
</div>
</section>



<div class="modal fade" id="addavailability" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close">
          
        </button>
        <div class="add-availability p-5">
          <h2 class="modal-title mb-3 text-center">Add Availability</h2>
          
            <div class="add-availability-reslt">

              <div id="addmoresessionsection " class="dynamic_field3">
                <input type="hidden" name="varkey3" id="varkey3" value="1">
               
                <div class="row rowdelete3" style="margin:20px 0px;">
                <form action="{{route('add_availability')}}" id="create_availability" method="post" class="row ">
                @csrf
                  <div class="col-md-6 mt-2">
                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label style="font-size: 18px; color: #000;" for="monday-checkbox">Select Day</label>
                      </div>
                      <div class="col-md-7 mt-2">
                        <Span style="font-size: 18px; color: #000;">Enter Times<span>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Monday" id="monday-checkbox">
                        <label for="monday-checkbox">Monday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div class="input-group timerange">
                          <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Tuesday" id="tuesday-checkbox">
                        <label for="tuesday-checkbox">Tuesday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Wednesday" id="wednesday-checkbox">
                        <label for="wednesday-checkbox">Wednesday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input"  name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Thursday" id="thurday-checkbox">
                        <label for="thurday-checkbox">Thursday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Friday" id="friday-checkbox">
                        <label for="friday-checkbox">Friday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Saturday" id="saturday-checkbox">
                        <label for="saturday-checkbox">Saturday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Sunday" id="sunday-checkbox">
                        <label for="sunday-checkbox">Sunday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                  <div class="col-md-1 mt-2"></div>
                  <div class="col-md-5 mt-2">
                    <div class="row">
                      <div class="col-md-12 mt-2 center-checkbox">
                        <label style="font-size: 18px; color: #000;">Date Parametres</label>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 mt-2 center-checkbox">
                        <input type="radio" name="date_type" id="every_week-checkbox" value="1" checked>
                        <label for="every_week-checkbox">Every Week</label>
                        
                      </div>
                      <div class="col-md-12 mt-2 center-checkbox">
                        <input type="radio" name="date_type" id="Specific-checkbox" value="2">
                        <label for="Specific-checkbox">Specific Date Range</label>
                        
                      </div>
                      <div class="col-md-12 mt-2 center-checkbox" id="dateRangeInputs" style="display: none;">
                        <div class="input-group input-daterange col-md-4">
                            <input type="text" name="start_date" class="start-date form-control" value="">
                            <span class="input-group-addon">to</span>
                            <input type="text" name="end_date" class="end-date form-control" value="">
                        </div>
                        <label id="start_date-error" class="error" for="start_date"></label>
                        <label id="end_date-error" class="error" for="end_date"></label>
                        
                      </div>

                    </div>
                    <label id="date_type-error" class="error" for="date_type"></label>
                    <div class="col-md-4 mt-4">
                      <button type="submit" class="btn-primary w-100">Save</button>
                    </div>
                  </div>
                  </form>
                </div>

                <div class="row text-center">
                  <div class="col-md-12 mt-2">
                    <br><img class="add3" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                    <h6 class="modal-title mb-3 text-center">Add More Availability</h6>
                  </div>
                </div>
              </div>
              
            </div>
          
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addnotavailability" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close">
          <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt="" />
        </button>
        <div class="add-availability p-5">
          <h2 class="modal-title mb-3">Add Not Availability</h2>
          
            <div class="add-availability-reslt">

            <div id="addmoresessionsection " class="dynamic_field2">
                <input type="hidden" name="varkey2" id="varkey2" value="1">
               
                <div class="row rowdelete2" style="margin:20px 0px;">
                <form action="{{route('add_not_availability')}}" method="post" id="create_not_availability" class="row create_not_availability">
                @csrf
                  <div class="col-md-6 mt-2">
                  

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label style="font-size: 18px; color: #000;" for="monday-checkbox">Select Day</label>
                      </div>
                      <div class="col-md-7 mt-2">
                        <label style="font-size: 18px; color: #000;">Enter Times</label>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Monday" id="monday-checkbox">
                        <label for="monday-checkbox">Monday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div class="input-group timerange">
                          <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Tuesday" id="tuesday-checkbox">
                        <label for="tuesday-checkbox">Tuesday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Wednesday" id="wednesday-checkbox">
                        <label for="wednesday-checkbox">Wednesday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input"  name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Thursday" id="thurday-checkbox">
                        <label for="thurday-checkbox">Thursday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Friday" id="friday-checkbox">
                        <label for="friday-checkbox">Friday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Saturday" id="saturday-checkbox">
                        <label for="saturday-checkbox">Saturday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      	<input type="checkbox" name="weeks[]" value="Sunday" id="sunday-checkbox">
                        <label for="sunday-checkbox">Sunday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                  <div class="col-md-1 mt-2"></div>
                  <div class="col-md-5 mt-2">
                    <div class="row">
                      <div class="col-md-12 mt-2 center-checkbox">
                        <label style="font-size: 18px; color: #000;">Date Parametres</label>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 mt-2 center-checkbox">
                        <input type="radio" name="date_type" id="every_week-checkbox" value="1" checked>
                        <label for="every_week-checkbox">Every Week</label>
                        
                      </div>
                      <div class="col-md-12 mt-2 center-checkbox">
                        <input type="radio" name="date_type" id="Specific-checkbox" value="2">
                        <label for="Specific-checkbox">Specific Date Range</label>
                        
                      </div>
                      <div class="col-md-12 mt-2 center-checkbox" >
                        <div class="input-group input-daterange col-md-4">
                            <input type="text" name="start_date" class="start-date form-control" value="">
                            <span class="input-group-addon">to</span>
                            <input type="text" name="end_date" class="end-date form-control" value="">
                        </div>
                        
                      </div>
                      
                    </div>
                    <label id="date_type-error" class="error" for="date_type"></label>
                    <div class="col-md-4 mt-4">
                      <button type="submit" class="btn-primary w-100">Save</button>
                    </div>
                  </div>
                  </form>
                </div>

                <div class="row text-center">
                  <div class="col-md-12 mt-2">
                    <br><img class="add2" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                    <h6 class="modal-title mb-3 text-center">Add More Availability</h6>
                  </div>
                </div>
              </div>
            </div>
          
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editavailability" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close">
          <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt="" />
        </button>
        <div class="add-availability p-5">
          <h2 class="modal-title mb-3">Edit Availability</h2>
          <form action="{{route('edit_availability')}}" method="post" class="cmn-frm row">
            @csrf
            <input type="hidden" name="availability_id" id="availability_id">
              <div class="col-md-6 mt-2" id="week_set">
                
              </div>
              <div class="col-md-1 mt-2"></div>
            <div class="col-md-5 mt-2">
              <div class="row">
                <div class="col-md-12 mt-2 center-checkbox">
                  <label style="font-size: 18px; color: #000;">Date Parametres</label>
                  
                </div>
              </div>
              <div class="row" id="date_set">
                
              </div>
              <div class="col-md-4 mt-4">
                <button type="submit" class="btn-primary w-100">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editnotavailability" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close">
          <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt="" />
        </button>
        <div class="add-availability p-5">
          <h2 class="modal-title mb-3">Edit Availability</h2>
          <form action="{{route('edit_not_availability')}}" method="post" class="cmn-frm row">
            @csrf
            
            <input type="hidden" name="not_availability_id" id="not_availability_id">
              <div class="col-md-6 mt-2" id="week_set1">
                
              </div>
              <div class="col-md-1 mt-2"></div>
            <div class="col-md-5 mt-2">
              <div class="row">
                <div class="col-md-12 mt-2 center-checkbox">
                  <label style="font-size: 18px; color: #000;">Date Parametres</label>
                  
                </div>
              </div>
              <div class="row" id="date_set1">
                
              </div>
              <div class="col-md-4 mt-4">
                <button type="submit" class="btn-primary w-100">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Jquery needed -->
<script src="{{url('/public/frontend/')}}/assets/js/jquery.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/rome.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.1/main.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="{{url('/public/frontend/')}}/assets/js/timerange.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
  $(document).ready(function() {
    // Initially hide the date range inputs
    $('#dateRangeInputs').hide();

    // Listen for changes in the radio button selection
    $('input[type=radio][name=date_type]').change(function() {
        // If Specific Date Range is selected
        if (this.value == '2') {
            $('#dateRangeInputs').show(); // Show the date range inputs
        } else {
            $('#dateRangeInputs').hide(); // Otherwise, hide them
        }
    });
});
</script>

<script>
  $(document).ready(function () {
    $('.start-date').datepicker({
      templates: {
        leftArrow: '<i class="fa fa-chevron-left"></i>',
        rightArrow: '<i class="fa fa-chevron-right"></i>'
      },
      format: "dd/mm/yyyy",
      startDate: new Date(),
      keyboardNavigation: false,
      autoclose: true,
      todayHighlight: true,
      disableTouchKeyboard: true,
      orientation: "bottom auto"
    });

    $('.end-date').datepicker({
      templates: {
        leftArrow: '<i class="fa fa-chevron-left"></i>',
        rightArrow: '<i class="fa fa-chevron-right"></i>'
      },
      format: "dd/mm/yyyy",
      startDate: moment().add(1, 'days').toDate(),
      keyboardNavigation: false,
      autoclose: true,
      todayHighlight: true,
      disableTouchKeyboard: true,
      orientation: "bottom auto"
    });


    $('.start-date').datepicker().on("changeDate", function () {
      var startDate = $('.start-date').datepicker('getDate');
      var oneDayFromStartDate = moment(startDate).add(1, 'days').toDate();
      $('.end-date').datepicker('setStartDate', oneDayFromStartDate);
      $('.end-date').datepicker('setDate', oneDayFromStartDate);
    });

    $('.end-date').datepicker().on("show", function () {
      var startDate = $('.start-date').datepicker('getDate');
      $('.day.disabled').filter(function (index) {
        return $(this).text() === moment(startDate).format('D');
      }).addClass('active');
    });
  });
</script>
<script>
  $(document).ready(function() {
    var varkey3 = $("#varkey3").val();

    if (varkey3 != undefined) {
      val3 = varkey3;
    } else {
      val3 = 1;
    }
    valuedata3 = 0;


    $(document).on("click", ".add3", function() {
      
      var html = `<div class="row rowdelete3" style="margin:20px 0px;">
      
      <form action="{{route('add_availability')}}" method="post" class="row create_availability">
                @csrf
                  <div class="col-md-6 mt-2">
                  

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label style="font-size:18px;" for="monday-checkbox">Select Day</label>
                      </div>
                      <div class="col-md-7 mt-2">
                        <label style="font-size:18px;">Enter Times</label>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      <input type="checkbox" name="weeks[]" value="Monday" id="monday-checkbox">
                        <label for="monday-checkbox">Monday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div class="input-group timerange">
                          <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      <input type="checkbox" name="weeks[]" value="Tuesday" id="tuesday-checkbox">
                        <label for="tuesday-checkbox">Tuesday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      <input type="checkbox" name="weeks[]" value="Wednesday" id="wednesday-checkbox">
                        <label for="wednesday-checkbox">Wednesday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input"  name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      <input type="checkbox" name="weeks[]" value="Thursday" id="thurday-checkbox">
                        <label for="thurday-checkbox">Thursday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      <input type="checkbox" name="weeks[]" value="Friday" id="friday-checkbox">
                        <label for="friday-checkbox">Friday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      <input type="checkbox" name="weeks[]" value="Saturday" id="saturday-checkbox">
                        <label for="saturday-checkbox">Saturday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                      <input type="checkbox" name="weeks[]" value="Sunday" id="sunday-checkbox">
                        <label for="sunday-checkbox">Sunday</label>                        
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                  <div class="col-md-1 mt-2"></div>
                  <div class="col-md-5 mt-2">
                    <div class="row">
                      <div class="col-md-12 mt-2 center-checkbox">
                        <label style="font-size:18px;">Date Parametres</label>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 mt-2 center-checkbox">
                        <input type="radio" name="date_type" id="every_week-checkbox" value="1">
                        <label for="every_week-checkbox">Every Week</label>
                        
                      </div>
                      <div class="col-md-12 mt-2 center-checkbox">
                        <input type="radio" name="date_type" id="Specific-checkbox" value="2">
                        <label for="Specific-checkbox">Specific Date Range</label>
                        
                      </div>
                      <div class="col-md-12 mt-2 center-checkbox">
                        <div class="input-group input-daterange col-md-4">
                            <input type="text" name="start_date" class="start-date form-control" value="">
                            <span class="input-group-addon">to</span>
                            <input type="text" name="end_date" class="end-date form-control" value="">
                        </div>
                        
                      </div>
                    </div>
                    <div class="col-md-4 mt-4">
                      <button type="submit" class="btn-primary w-100">Save</button>
                    </div>
                  </div>
                  </form>
                  <div class="row text-center">
                  <div class="col-md-12 mt-2">
                  <img height="30px" style="margin-top: 30px;" class="btn_remove3" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt="">
                    <h6 class="modal-title mb-3 text-center">Remove More Availability</h6>
                  </div>
                </div>
                </div>
                `;
      // $pmainid = $(this).closest(".main-data").attr('attr-data');
      
      $(this).parents(".dynamic_field3").append(html);
      val3++;

      //timerange start
      $(".timerange").on("click", function (e) {
  e.stopPropagation();
  var input = $(this).find("input");

  var now = new Date();
  var hours = now.getHours();
  var period = "PM";
  if (hours < 12) {
    period = "AM";
  } else {
    hours = hours - 11;
  }
  var minutes = now.getMinutes();

  var range = {
    from: {
      hour: hours,
      minute: minutes,
      period: period
    },
    to: {
      hour: hours,
      minute: minutes,
      period: period
    }
  };

  if (input.val() !== "") {
    var timerange = input.val();
    var matches = timerange.match(
      /([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)-([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)/
    );
    if (matches.length === 7) {
      range = {
        from: {
          hour: matches[1],
          minute: matches[2],
          period: matches[3]
        },
        to: {
          hour: matches[4],
          minute: matches[5],
          period: matches[6]
        }
      };
    }
  }
  console.log(range);

  var html =
    '<div class="timerangepicker-container">' +
    '<div class="timerangepicker-from">' +
    '<label class="timerangepicker-label">From:</label>' +
    '<div class="timerangepicker-display hour">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.from.hour).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display minute">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.from.minute).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display period">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">PM</span>' +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    "</div>" +
    '<div class="timerangepicker-to">' +
    '<label class="timerangepicker-label">To:</label>' +
    '<div class="timerangepicker-display hour">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.to.hour).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display minute">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.to.minute).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display period">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">PM</span>' +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    "</div>" +
    "</div>";

  $(html).insertAfter(this);
  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.hour .increment",
    function () {
      var value = $(this).siblings(".value");
      value.text(increment(value.text(), 12, 1, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.hour .decrement",
    function () {
      var value = $(this).siblings(".value");
      value.text(decrement(value.text(), 12, 1, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.minute .increment",
    function () {
      var value = $(this).siblings(".value");
      value.text(increment(value.text(), 59, 0, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.minute .decrement",
    function () {
      var value = $(this).siblings(".value");
      value.text(decrement(value.text(), 59, 0, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.period .increment, .timerangepicker-display.period .decrement",
    function () {
      var value = $(this).siblings(".value");
      var next = value.text() == "PM" ? "AM" : "PM";
      value.text(next);
    }
  );
});

$(document).on("click", (e) => {
  if (!$(e.target).closest(".timerangepicker-container").length) {
    if ($(".timerangepicker-container").is(":visible")) {
      var timerangeContainer = $(".timerangepicker-container");
      if (timerangeContainer.length > 0) {
        var timeRange = {
          from: {
            hour: timerangeContainer.find(".value")[0].innerText,
            minute: timerangeContainer.find(".value")[1].innerText,
            period: timerangeContainer.find(".value")[2].innerText
          },
          to: {
            hour: timerangeContainer.find(".value")[3].innerText,
            minute: timerangeContainer.find(".value")[4].innerText,
            period: timerangeContainer.find(".value")[5].innerText
          }
        };

        timerangeContainer
          .parent()
          .find("input")
          .val(
            timeRange.from.hour +
              ":" +
              timeRange.from.minute +
              " " +
              timeRange.from.period +
              "-" +
              timeRange.to.hour +
              ":" +
              timeRange.to.minute +
              " " +
              timeRange.to.period
          );
        timerangeContainer.remove();
      }
    }
  }
});



function increment(value, max, min, size) {
  var intValue = parseInt(value);
  if (intValue == max) {
    return ("0" + min).substr(-size);
  } else {
    var next = intValue + 1;
    // Ensure that next is not greater than max
    next = next > max ? max : next;
    return ("0" + next).substr(-size);
  }
}

function decrement(value, max, min, size) {
  var intValue = parseInt(value);
  if (intValue == min) {
    return ("0" + max).substr(-size);
  } else {
    var next = intValue - 1;
    // Ensure that next is not less than min
    next = next < min ? min : next;
    return ("0" + next).substr(-size);
  }
}
      //time range  picker end

      //date picker start
      $('.start-date').datepicker({
        templates: {
            leftArrow: '<i class="fa fa-chevron-left"></i>',
            rightArrow: '<i class="fa fa-chevron-right"></i>'
        },
        format: "dd/mm/yyyy",
        startDate: new Date(),
        keyboardNavigation: false,
        autoclose: true,
        todayHighlight: true,
        disableTouchKeyboard: true,
        orientation: "bottom auto"
    });

    $('.end-date').datepicker({
        templates: {
            leftArrow: '<i class="fa fa-chevron-left"></i>',
            rightArrow: '<i class="fa fa-chevron-right"></i>'
        },
        format: "dd/mm/yyyy",
        startDate: moment().add(1, 'days').toDate(),
        keyboardNavigation: false,
        autoclose: true,
        todayHighlight: true,
        disableTouchKeyboard: true,
        orientation: "bottom auto"
    });
    //date picker end

      //checkbox start
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
          var timeInput = this.closest('.row').querySelector('.time-input');
          timeInput.disabled = !this.checked;
          if (!this.checked) {
            timeInput.value = null;
          }
        });
      });

      document.getElementById('submitBtn').addEventListener('click', function() {
        var data = {};
        checkboxes.forEach(function(checkbox) {
          var timeInput = checkbox.closest('.row').querySelector('.time-input');
          if (checkbox.checked) {
            data[checkbox.name] = timeInput.value;
          }
        });

        console.log(data);
      });
      //checkbox end
    });


    $(document).on('click', '.btn_remove3', function() {
      $(this).parents('.rowdelete3').remove();
    });
  });
  function rerun() {
    
    $('.start-date').datepicker();

  }
  
</script>

<script>
  $(document).ready(function() {
    var varkey2 = $("#varkey2").val();

    if (varkey2 != undefined) {
      val2 = varkey2;
    } else {
      val2 = 1;
    }
    valuedata2 = 0;


    $(document).on("click", ".add2", function() {
      
      var html = `<div class="row rowdelete2" style="margin:20px 0px;">
      
      <form action="{{route('add_not_availability')}}" method="post" class="row create_availability">
                @csrf
                  <div class="col-md-6 mt-2">
                  

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label for="monday-checkbox">Select Day</label>
                      </div>
                      <div class="col-md-7 mt-2">
                        <Span>Enter Times<span>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label for="monday-checkbox">Monday</label>
                        <input type="checkbox" name="weeks[]" value="Monday" id="monday-checkbox">
                      </div>
                      <div class="col-md-7 mt-2">
                        <div class="input-group timerange">
                          <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label for="tuesday-checkbox">Tuesday</label>
                        <input type="checkbox" name="weeks[]" value="Tuesday" id="tuesday-checkbox">
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label for="wednesday-checkbox">Wednesday</label>
                        <input type="checkbox" name="weeks[]" value="Wednesday" id="wednesday-checkbox">
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input"  name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label for="thurday-checkbox">Thursday</label>
                        <input type="checkbox" name="weeks[]" value="Thursday" id="thurday-checkbox">
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label for="friday-checkbox">Friday</label>
                        <input type="checkbox" name="weeks[]" value="Friday" id="friday-checkbox">
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label for="saturday-checkbox">Saturday</label>
                        <input type="checkbox" name="weeks[]" value="Saturday" id="saturday-checkbox">
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5 mt-2 center-checkbox">
                        <label for="sunday-checkbox">Sunday</label>
                        <input type="checkbox" name="weeks[]" value="Sunday" id="sunday-checkbox">
                      </div>
                      <div class="col-md-7 mt-2">
                        <div id="datetimepickerDate" class="input-group timerange">
                        <input class="form-control time-input" name="time[]" type="text" disabled>
                        </div>
                      </div>
                    </div>
                    
                  </div>

                  <div class="col-md-6 mt-2">
                    <div class="row">
                      <div class="col-md-12 mt-2 center-checkbox">
                        <label >Date Parametres</label>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 mt-2 center-checkbox">
                        <input type="radio" name="date_type" id="every_week-checkbox" value="1">
                        <label for="every_week-checkbox">Every Week</label>
                        
                      </div>
                      <div class="col-md-12 mt-2 center-checkbox">
                        <input type="radio" name="date_type" id="Specific-checkbox" value="2">
                        <label for="Specific-checkbox">Specific Date Range</label>
                        
                      </div>
                      <div class="col-md-12 mt-2 center-checkbox">
                        <div class="input-group input-daterange col-md-4">
                            <input type="text" name="start_date" class="start-date form-control" value="">
                            <span class="input-group-addon">to</span>
                            <input type="text" name="end_date" class="end-date form-control" value="">
                        </div>
                        
                      </div>
                    </div>
                    <div class="col-md-4 mt-4">
                      <button type="submit" class="btn-primary w-100">Save</button>
                    </div>
                  </div>
                  </form>
                  <div class="row text-center">
                  <div class="col-md-12 mt-2">
                  <img height="30px" style="margin-top: 30px;" class="btn_remove2" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt="">
                    <h6 class="modal-title mb-3 text-center">Remove More Availability</h6>
                  </div>
                </div>
                </div>
                `;
      // $pmainid = $(this).closest(".main-data").attr('attr-data');
      
      $(this).parents(".dynamic_field2").append(html);
      val2++;

      //timerange start
      $(".timerange").on("click", function (e) {
  e.stopPropagation();
  var input = $(this).find("input");

  var now = new Date();
  var hours = now.getHours();
  var period = "PM";
  if (hours < 12) {
    period = "AM";
  } else {
    hours = hours - 11;
  }
  var minutes = now.getMinutes();

  var range = {
    from: {
      hour: hours,
      minute: minutes,
      period: period
    },
    to: {
      hour: hours,
      minute: minutes,
      period: period
    }
  };

  if (input.val() !== "") {
    var timerange = input.val();
    var matches = timerange.match(
      /([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)-([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)/
    );
    if (matches.length === 7) {
      range = {
        from: {
          hour: matches[1],
          minute: matches[2],
          period: matches[3]
        },
        to: {
          hour: matches[4],
          minute: matches[5],
          period: matches[6]
        }
      };
    }
  }
  console.log(range);

  var html =
    '<div class="timerangepicker-container">' +
    '<div class="timerangepicker-from">' +
    '<label class="timerangepicker-label">From:</label>' +
    '<div class="timerangepicker-display hour">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.from.hour).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display minute">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.from.minute).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display period">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">PM</span>' +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    "</div>" +
    '<div class="timerangepicker-to">' +
    '<label class="timerangepicker-label">To:</label>' +
    '<div class="timerangepicker-display hour">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.to.hour).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display minute">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.to.minute).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display period">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">PM</span>' +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    "</div>" +
    "</div>";

  $(html).insertAfter(this);
  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.hour .increment",
    function () {
      var value = $(this).siblings(".value");
      value.text(increment(value.text(), 12, 1, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.hour .decrement",
    function () {
      var value = $(this).siblings(".value");
      value.text(decrement(value.text(), 12, 1, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.minute .increment",
    function () {
      var value = $(this).siblings(".value");
      value.text(increment(value.text(), 59, 0, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.minute .decrement",
    function () {
      var value = $(this).siblings(".value");
      value.text(decrement(value.text(), 59, 0, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.period .increment, .timerangepicker-display.period .decrement",
    function () {
      var value = $(this).siblings(".value");
      var next = value.text() == "PM" ? "AM" : "PM";
      value.text(next);
    }
  );
});

$(document).on("click", (e) => {
  if (!$(e.target).closest(".timerangepicker-container").length) {
    if ($(".timerangepicker-container").is(":visible")) {
      var timerangeContainer = $(".timerangepicker-container");
      if (timerangeContainer.length > 0) {
        var timeRange = {
          from: {
            hour: timerangeContainer.find(".value")[0].innerText,
            minute: timerangeContainer.find(".value")[1].innerText,
            period: timerangeContainer.find(".value")[2].innerText
          },
          to: {
            hour: timerangeContainer.find(".value")[3].innerText,
            minute: timerangeContainer.find(".value")[4].innerText,
            period: timerangeContainer.find(".value")[5].innerText
          }
        };

        timerangeContainer
          .parent()
          .find("input")
          .val(
            timeRange.from.hour +
              ":" +
              timeRange.from.minute +
              " " +
              timeRange.from.period +
              "-" +
              timeRange.to.hour +
              ":" +
              timeRange.to.minute +
              " " +
              timeRange.to.period
          );
        timerangeContainer.remove();
      }
    }
  }
});

function increment(value, max, min, size) {
  var intValue = parseInt(value);
  if (intValue == max) {
    return ("0" + min).substr(-size);
  } else {
    var next = intValue + 1;
    // Ensure that next is not greater than max
    next = next > max ? max : next;
    return ("0" + next).substr(-size);
  }
}

function decrement(value, max, min, size) {
  var intValue = parseInt(value);
  if (intValue == min) {
    return ("0" + max).substr(-size);
  } else {
    var next = intValue - 1;
    // Ensure that next is not less than min
    next = next < min ? min : next;
    return ("0" + next).substr(-size);
  }
}
      //time range  picker end

      //date picker start
      $('.start-date').datepicker({
        templates: {
            leftArrow: '<i class="fa fa-chevron-left"></i>',
            rightArrow: '<i class="fa fa-chevron-right"></i>'
        },
        format: "dd/mm/yyyy",
        startDate: new Date(),
        keyboardNavigation: false,
        autoclose: true,
        todayHighlight: true,
        disableTouchKeyboard: true,
        orientation: "bottom auto"
    });

    $('.end-date').datepicker({
        templates: {
            leftArrow: '<i class="fa fa-chevron-left"></i>',
            rightArrow: '<i class="fa fa-chevron-right"></i>'
        },
        format: "dd/mm/yyyy",
        startDate: moment().add(1, 'days').toDate(),
        keyboardNavigation: false,
        autoclose: true,
        todayHighlight: true,
        disableTouchKeyboard: true,
        orientation: "bottom auto"
    });
    //date picker end

      //checkbox start
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
          var timeInput = this.closest('.row').querySelector('.time-input');
          timeInput.disabled = !this.checked;
          if (!this.checked) {
            timeInput.value = null;
          }
        });
      });

      document.getElementById('submitBtn').addEventListener('click', function() {
        var data = {};
        checkboxes.forEach(function(checkbox) {
          var timeInput = checkbox.closest('.row').querySelector('.time-input');
          if (checkbox.checked) {
            data[checkbox.name] = timeInput.value;
          }
        });

        console.log(data);
      });
      //checkbox end
    });


    $(document).on('click', '.btn_remove2', function() {
      $(this).parents('.rowdelete2').remove();
    });
  });
  function rerun() {
    
    $('.start-date').datepicker();

  }
  
</script>
<script>
  // Get all checkbox inputs
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');

  // Loop through each checkbox input
  checkboxes.forEach(function(checkbox) {
    // Add event listener to each checkbox
    checkbox.addEventListener('change', function() {
      // Get the corresponding time input field
      var timeInput = this.closest('.row').querySelector('.time-input');
      // Enable/disable the time input field based on checkbox state
      timeInput.disabled = !this.checked;

      // If checkbox is unchecked, set the value of the time input field to null
      if (!this.checked) {
        timeInput.value = null;
      }
    });
  });

  document.getElementById('submitBtn').addEventListener('click', function() {
    var data = {};

    checkboxes.forEach(function(checkbox) {
      // Get the corresponding time input field
      var timeInput = checkbox.closest('.row').querySelector('.time-input');
      
      // If checkbox is checked, add its value to data object
      if (checkbox.checked) {
        data[checkbox.name] = timeInput.value;
      }
    });

    console.log(data); // Output the data to console, you can further process or send it to the server
  });
</script>
<!-- <script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: ['interaction', 'dayGrid', 'timeGrid']
      height: 'parent',
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      defaultView: 'dayGridMonth',
      defaultDate: '2020-02-12',
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: [{
          title: 'All Day Event',
          start: '2020-02-01',
        },
        {
          title: 'All Day Event',
          start: '2020-02-01',
        },
        {
          title: 'Long Event',
          start: '2020-02-07',
          end: '2020-02-10'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2020-02-09T16:00:00'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2020-02-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2020-02-11',
          end: '2020-02-13'
        },
        {
          title: 'Meeting',
          start: '2020-02-12T10:30:00',
          end: '2020-02-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2020-02-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2020-02-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2020-02-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2020-02-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2020-02-13T07:00:00'
        },
        // {
        //   title: 'Click for Google',
        //   url: 'http://google.com/',
        //   start: '2020-02-28'
        // }
      ]
    });

    calendar.render();
  });
</> -->
<script>
  $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
  });
</script>
<script>
  function editavailability(id) {
    $.ajax({
      url: "{{ route('get_availability') }}",
      datatType: 'json',
      data: {
        '_token': '<?php echo csrf_token() ?>',
        'availability_id': id,
      },

      success: function(res) {
        if (res.status == 1) {

          $("#week_set").html(res.week_set);
          $("#date_set").html(res.date_set);
          $("#availability_id").val(res.availability_id);
          $('#editavailability').modal('show');

                //timerange start
      $(".timerange").on("click", function (e) {
  e.stopPropagation();
  var input = $(this).find("input");

  var now = new Date();
  var hours = now.getHours();
  var period = "PM";
  if (hours < 12) {
    period = "AM";
  } else {
    hours = hours - 11;
  }
  var minutes = now.getMinutes();

  var range = {
    from: {
      hour: hours,
      minute: minutes,
      period: period
    },
    to: {
      hour: hours,
      minute: minutes,
      period: period
    }
  };

  if (input.val() !== "") {
    var timerange = input.val();
    var matches = timerange.match(
      /([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)-([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)/
    );
    if (matches.length === 7) {
      range = {
        from: {
          hour: matches[1],
          minute: matches[2],
          period: matches[3]
        },
        to: {
          hour: matches[4],
          minute: matches[5],
          period: matches[6]
        }
      };
    }
  }
  console.log(range);

  var html =
    '<div class="timerangepicker-container">' +
    '<div class="timerangepicker-from">' +
    '<label class="timerangepicker-label">From:</label>' +
    '<div class="timerangepicker-display hour">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.from.hour).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display minute">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.from.minute).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display period">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">PM</span>' +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    "</div>" +
    '<div class="timerangepicker-to">' +
    '<label class="timerangepicker-label">To:</label>' +
    '<div class="timerangepicker-display hour">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.to.hour).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display minute">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.to.minute).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display period">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">PM</span>' +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    "</div>" +
    "</div>";

  $(html).insertAfter(this);
  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.hour .increment",
    function () {
      var value = $(this).siblings(".value");
      value.text(increment(value.text(), 12, 1, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.hour .decrement",
    function () {
      var value = $(this).siblings(".value");
      value.text(decrement(value.text(), 12, 1, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.minute .increment",
    function () {
      var value = $(this).siblings(".value");
      value.text(increment(value.text(), 59, 0, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.minute .decrement",
    function () {
      var value = $(this).siblings(".value");
      value.text(decrement(value.text(), 59, 0, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.period .increment, .timerangepicker-display.period .decrement",
    function () {
      var value = $(this).siblings(".value");
      var next = value.text() == "PM" ? "AM" : "PM";
      value.text(next);
    }
  );
});

$(document).on("click", (e) => {
  if (!$(e.target).closest(".timerangepicker-container").length) {
    if ($(".timerangepicker-container").is(":visible")) {
      var timerangeContainer = $(".timerangepicker-container");
      if (timerangeContainer.length > 0) {
        var timeRange = {
          from: {
            hour: timerangeContainer.find(".value")[0].innerText,
            minute: timerangeContainer.find(".value")[1].innerText,
            period: timerangeContainer.find(".value")[2].innerText
          },
          to: {
            hour: timerangeContainer.find(".value")[3].innerText,
            minute: timerangeContainer.find(".value")[4].innerText,
            period: timerangeContainer.find(".value")[5].innerText
          }
        };

        timerangeContainer
          .parent()
          .find("input")
          .val(
            timeRange.from.hour +
              ":" +
              timeRange.from.minute +
              " " +
              timeRange.from.period +
              "-" +
              timeRange.to.hour +
              ":" +
              timeRange.to.minute +
              " " +
              timeRange.to.period
          );
        timerangeContainer.remove();
      }
    }
  }
});

function increment(value, max, min, size) {
  var intValue = parseInt(value);
  if (intValue == max) {
    return ("0" + min).substr(-size);
  } else {
    var next = intValue + 1;
    // Ensure that next is not greater than max
    next = next > max ? max : next;
    return ("0" + next).substr(-size);
  }
}

function decrement(value, max, min, size) {
  var intValue = parseInt(value);
  if (intValue == min) {
    return ("0" + max).substr(-size);
  } else {
    var next = intValue - 1;
    // Ensure that next is not less than min
    next = next < min ? min : next;
    return ("0" + next).substr(-size);
  }
}
      //time range  picker end

      //date picker start
      $('.start-date').datepicker({
        templates: {
            leftArrow: '<i class="fa fa-chevron-left"></i>',
            rightArrow: '<i class="fa fa-chevron-right"></i>'
        },
        format: "dd/mm/yyyy",
        startDate: new Date(),
        keyboardNavigation: false,
        autoclose: true,
        todayHighlight: true,
        disableTouchKeyboard: true,
        orientation: "bottom auto"
    });

    $('.end-date').datepicker({
        templates: {
            leftArrow: '<i class="fa fa-chevron-left"></i>',
            rightArrow: '<i class="fa fa-chevron-right"></i>'
        },
        format: "dd/mm/yyyy",
        startDate: moment().add(1, 'days').toDate(),
        keyboardNavigation: false,
        autoclose: true,
        todayHighlight: true,
        disableTouchKeyboard: true,
        orientation: "bottom auto"
    });
    //date picker end

      //checkbox start
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
          var timeInput = this.closest('.row').querySelector('.time-input');
          timeInput.disabled = !this.checked;
          if (!this.checked) {
            timeInput.value = null;
          }
        });
      });

      document.getElementById('submitBtn').addEventListener('click', function() {
        var data = {};
        checkboxes.forEach(function(checkbox) {
          var timeInput = checkbox.closest('.row').querySelector('.time-input');
          if (checkbox.checked) {
            data[checkbox.name] = timeInput.value;
          }
        });

        console.log(data);
      });
        } else {
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Plese enter email address!',
          })
        }
      }
    });
  }

  function deleteavailability(id) {
    swal({
      title: "Are you sure you want to delete this?",
      text: "You will not be able to recover this action!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: "{{ url('/delete_availability') }}",
          datatType: 'json',
          type: 'GET',
          data: {
            'id': id,
          },
          success: function(res) {

            if (res.status === "success") {
              swal({
                  title: "Deleted!",
                  text: res.message,
                  icon: "success",
                  dangerMode: true,
                  buttons: false,
                  timer: 1000
                })
                .then(() => {
                  window.location.reload();
                })

            } else {
              swal("Error", "{!!trans('language.delete_already_used') !!}", "error");
            }



          }
        });
        // swal("Deleted!", "{!! trans('language.deleted_successfully') !!}", "success");
      } else {
        swal({
          title: "Cancelled",
          text: "You cancelled your action",
          icon: "error",
          buttons: false,
          timer: 1500
        })

      }
    })
  }

  function delete_unavailability(id) {
    swal({
      title: "Are you sure you want to delete this?",
      text: "You will not be able to recover this action!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: "{{ url('/delete_unavailability') }}",
          datatType: 'json',
          type: 'GET',
          data: {
            'id': id,
          },
          success: function(res) {

            if (res.status === "success") {
              swal({
                  title: "Deleted!",
                  text: res.message,
                  icon: "success",
                  dangerMode: true,
                  buttons: false,
                  timer: 1000
                })
                .then(() => {
                  window.location.reload();
                })

            } else {
              swal("Error", "{!!trans('language.delete_already_used') !!}", "error");
            }



          }
        });
        // swal("Deleted!", "{!! trans('language.deleted_successfully') !!}", "success");
      } else {
        swal({
          title: "Cancelled",
          text: "You cancelled your action",
          icon: "error",
          buttons: false,
          timer: 1500
        })

      }
    })
  }
</script>

<script>
  function editnotavailability(id) {
    $.ajax({
      url: "{{ route('get_not_availability') }}",
      datatType: 'json',
      data: {
        '_token': '<?php echo csrf_token() ?>',
        'availability_id': id,
      },

      success: function(res) {
        if (res.status == 1) {

          $("#week_set1").html(res.week_set);
          $("#date_set1").html(res.date_set);
          $("#not_availability_id").val(res.availability_id);
          $('#editnotavailability').modal('show');

                //timerange start
      $(".timerange").on("click", function (e) {
  e.stopPropagation();
  var input = $(this).find("input");

  var now = new Date();
  var hours = now.getHours();
  var period = "PM";
  if (hours < 12) {
    period = "AM";
  } else {
    hours = hours - 11;
  }
  var minutes = now.getMinutes();

  var range = {
    from: {
      hour: hours,
      minute: minutes,
      period: period
    },
    to: {
      hour: hours,
      minute: minutes,
      period: period
    }
  };

  if (input.val() !== "") {
    var timerange = input.val();
    var matches = timerange.match(
      /([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)-([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)/
    );
    if (matches.length === 7) {
      range = {
        from: {
          hour: matches[1],
          minute: matches[2],
          period: matches[3]
        },
        to: {
          hour: matches[4],
          minute: matches[5],
          period: matches[6]
        }
      };
    }
  }
  console.log(range);

  var html =
    '<div class="timerangepicker-container">' +
    '<div class="timerangepicker-from">' +
    '<label class="timerangepicker-label">From:</label>' +
    '<div class="timerangepicker-display hour">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.from.hour).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display minute">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.from.minute).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display period">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">PM</span>' +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    "</div>" +
    '<div class="timerangepicker-to">' +
    '<label class="timerangepicker-label">To:</label>' +
    '<div class="timerangepicker-display hour">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.to.hour).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display minute">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">' +
    ("0" + range.to.minute).substr(-2) +
    "</span>" +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    ":" +
    '<div class="timerangepicker-display period">' +
    '<span class="increment fa fa-angle-up"></span>' +
    '<span class="value">PM</span>' +
    '<span class="decrement fa fa-angle-down"></span>' +
    "</div>" +
    "</div>" +
    "</div>";

  $(html).insertAfter(this);
  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.hour .increment",
    function () {
      var value = $(this).siblings(".value");
      value.text(increment(value.text(), 12, 1, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.hour .decrement",
    function () {
      var value = $(this).siblings(".value");
      value.text(decrement(value.text(), 12, 1, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.minute .increment",
    function () {
      var value = $(this).siblings(".value");
      value.text(increment(value.text(), 59, 0, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.minute .decrement",
    function () {
      var value = $(this).siblings(".value");
      value.text(decrement(value.text(), 59, 0, 2));
    }
  );

  $(".timerangepicker-container").on(
    "click",
    ".timerangepicker-display.period .increment, .timerangepicker-display.period .decrement",
    function () {
      var value = $(this).siblings(".value");
      var next = value.text() == "PM" ? "AM" : "PM";
      value.text(next);
    }
  );
});

$(document).on("click", (e) => {
  if (!$(e.target).closest(".timerangepicker-container").length) {
    if ($(".timerangepicker-container").is(":visible")) {
      var timerangeContainer = $(".timerangepicker-container");
      if (timerangeContainer.length > 0) {
        var timeRange = {
          from: {
            hour: timerangeContainer.find(".value")[0].innerText,
            minute: timerangeContainer.find(".value")[1].innerText,
            period: timerangeContainer.find(".value")[2].innerText
          },
          to: {
            hour: timerangeContainer.find(".value")[3].innerText,
            minute: timerangeContainer.find(".value")[4].innerText,
            period: timerangeContainer.find(".value")[5].innerText
          }
        };

        timerangeContainer
          .parent()
          .find("input")
          .val(
            timeRange.from.hour +
              ":" +
              timeRange.from.minute +
              " " +
              timeRange.from.period +
              "-" +
              timeRange.to.hour +
              ":" +
              timeRange.to.minute +
              " " +
              timeRange.to.period
          );
        timerangeContainer.remove();
      }
    }
  }
});

function increment(value, max, min, size) {
  var intValue = parseInt(value);
  if (intValue == max) {
    return ("0" + min).substr(-size);
  } else {
    var next = intValue + 1;
    // Ensure that next is not greater than max
    next = next > max ? max : next;
    return ("0" + next).substr(-size);
  }
}

function decrement(value, max, min, size) {
  var intValue = parseInt(value);
  if (intValue == min) {
    return ("0" + max).substr(-size);
  } else {
    var next = intValue - 1;
    // Ensure that next is not less than min
    next = next < min ? min : next;
    return ("0" + next).substr(-size);
  }
}
      //time range  picker end

      //date picker start
      $('.start-date').datepicker({
        templates: {
            leftArrow: '<i class="fa fa-chevron-left"></i>',
            rightArrow: '<i class="fa fa-chevron-right"></i>'
        },
        format: "dd/mm/yyyy",
        startDate: new Date(),
        keyboardNavigation: false,
        autoclose: true,
        todayHighlight: true,
        disableTouchKeyboard: true,
        orientation: "bottom auto"
    });

    $('.end-date').datepicker({
        templates: {
            leftArrow: '<i class="fa fa-chevron-left"></i>',
            rightArrow: '<i class="fa fa-chevron-right"></i>'
        },
        format: "dd/mm/yyyy",
        startDate: moment().add(1, 'days').toDate(),
        keyboardNavigation: false,
        autoclose: true,
        todayHighlight: true,
        disableTouchKeyboard: true,
        orientation: "bottom auto"
    });
    //date picker end

      //checkbox start
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
          var timeInput = this.closest('.row').querySelector('.time-input');
          timeInput.disabled = !this.checked;
          if (!this.checked) {
            timeInput.value = null;
          }
        });
      });

      document.getElementById('submitBtn').addEventListener('click', function() {
        var data = {};
        checkboxes.forEach(function(checkbox) {
          var timeInput = checkbox.closest('.row').querySelector('.time-input');
          if (checkbox.checked) {
            data[checkbox.name] = timeInput.value;
          }
        });

        console.log(data);
      });
        } else {
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Plese enter email address!',
          })
        }
      }
    });
  }
</script>


<script>
  $("#create_availability").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      'break': {
        required: true
      },
      'start_date': {
        required: true
      },
      'end_date': {
        required: true
      },
      'date_type': {
        required: true
      },
    },
    messages: {
      'break': "Please select any one.",
      'start_date': "Please select start date.",
      'end_date': "Please select end date.",
      'date_type': "Please select date parameter.",
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      if (this.valid()) {
        $('.confirm-reservation-cart').attr("disabled", "disabled");

        form.submit();
      }
    },
  });
</script>

<script>
  $("#create_not_availability").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {

     
      'date_type': {
        required: true
      },
    },
    messages: {
      
      'date_type': "Please select date parameter.",
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      if (this.valid()) {
        $('.confirm-reservation-cart').attr("disabled", "disabled");

        form.submit();
      }
    },
  });
</script>

<script>
  function frequency_type() {
    // alert('asd');
    var frequency_value = $('#frequency_value').val();
    if (frequency_value == 4) {
      $('#frequency').html('<div class="input-group date form-group" id="datepicker"><input type="date" class="form-control" id="Dates" name="month_date" placeholder="Select days" required /><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i><span class="count"></span></span>');
      $('#datepicker').datepicker({
        startDate: new Date(),
        multidate: true,
        format: "yyyy-mm-dd",
        daysOfWeekHighlighted: "5,6",
        datesDisabled: ['31/08/2017'],
        language: 'en'
      }).on('changeDate', function(e) {
        // `e` here contains the extra attributes
        $(this).find('.input-group-addon .count').text(' ' + e.dates.length);
      });
    } else if (frequency_value == 2) {
      $('#frequency').html('<div class="input-group date form-group" id="datepicker1"><input type="date" class="form-control" id="Dates" name="once_date" placeholder="Select days" required /><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i><span class="count"></span></span>');
      $('#datepicker1').datepicker({
        startDate: new Date(),
        multidate: false,
        format: "yyyy-mm-dd",
        daysOfWeekHighlighted: "5,6",
        datesDisabled: ['31/08/2017'],
        language: 'en'
      }).on('changeDate', function(e) {
        // `e` here contains the extra attributes
        $(this).find('.input-group-addon .count').text(' ' + e.dates.length);
      });
    } else if (frequency_value == 3) {
      $('#frequency').html('<select class="selectpicker js-example-basic-multiple" name="weeks[]" multiple data-live-search="true"><option value="Sunday">Sunday</option><option value="Monday">Monday</option><option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option><option value="Thursday">Thursday</option><option value="Friday">Friday</option><option value="Saturday">Saturday</option></select>');
      $('select').selectpicker();
    } else {
      $('#frequency').html('');
    }

  }
</script>
<script>
  $(document).on('click', '#addmoresession', function() {
    var i = $('#id_value').val();
    i++;
    var data = '<div class="row"><div class="col-md-6 mt-2"><div class="form-group"><input type="text" class="time-pickable" readonly placeholder="Start Time" /></div></div><div class="col-md-6 mt-2"><div class="form-group d-flex gap-2 align-items-center"><input type="text" class="time-pickable m-0" readonly placeholder="Start Time" /> <button type="button" class="btn btn-sm p-0 btnremoveingredient"><img  src="../images/remove-filds.svg" alt=""></button></div></div></div>';
    $('#id_value').val(i);
    $('#addmoresessionsection').append(data);
    $(document).on('click', '.btnremoveingredient', function() {
      $(this).parent().parent().remove();
    });
    $(document).on('click', '.btnremoveingredient', function() {
      $(this).parent().parent().remove();
    });
  });





  function activate() {
    document.head.insertAdjacentHTML(
      "beforeend",
      `
		<style>
			.time-picker {
				position: absolute;
				display: inline-block;
				padding: 10px;
				background: #eeeeee;
				border-radius: 6px;
        z-index:999999999;
			}

			.time-picker__select {
				-webkit-appearance: none;
				-moz-appearance: none;
				appearance: none;
				outline: none;
				text-align: center;
				border: 1px solid #dddddd;
				border-radius: 6px;
				padding: 6px 10px;
				background: #ffffff;
				cursor: pointer;
				font-family: 'Heebo', sans-serif;
			}
		</style>
	`
    );

    document.querySelectorAll(".time-pickable").forEach((timePickable) => {
      let activePicker = null;

      timePickable.addEventListener("focus", () => {
        if (activePicker) return;

        activePicker = show(timePickable);

        const onClickAway = ({
          target
        }) => {
          if (
            target === activePicker ||
            target === timePickable ||
            activePicker.contains(target)
          ) {
            return;
          }

          document.removeEventListener("mousedown", onClickAway);
          document.body.removeChild(activePicker);
          activePicker = null;
        };

        document.addEventListener("mousedown", onClickAway);
      });
    });
  }

  function show(timePickable) {
    const picker = buildPicker(timePickable);
    const {
      bottom: top,
      left
    } = timePickable.getBoundingClientRect();

    picker.style.top = `${top}px`;
    picker.style.left = `${left}px`;

    document.body.appendChild(picker);

    return picker;
  }

  function buildPicker(timePickable) {
    const picker = document.createElement("div");
    const hourOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].map(
      numberToOption
    );
    const minuteOptions = [
      0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55,
    ].map(numberToOption);

    picker.classList.add("time-picker");
    picker.innerHTML = `
		<select class="time-picker__select">
			${hourOptions.join("")}
		</select>
		:
		<select class="time-picker__select">
			${minuteOptions.join("")}
		</select>
		<select class="time-picker__select">
			<option value="am">am</option>
			<option value="pm">pm</option>
		</select>
	`;

    const selects = getSelectsFromPicker(picker);

    selects.hour.addEventListener(
      "change",
      () => (timePickable.value = getTimeStringFromPicker(picker))
    );
    selects.minute.addEventListener(
      "change",
      () => (timePickable.value = getTimeStringFromPicker(picker))
    );
    selects.meridiem.addEventListener(
      "change",
      () => (timePickable.value = getTimeStringFromPicker(picker))
    );

    if (timePickable.value) {
      const {
        hour,
        minute,
        meridiem
      } =
      getTimePartsFromPickable(timePickable);

      selects.hour.value = hour;
      selects.minute.value = minute;
      selects.meridiem.value = meridiem;
    }

    return picker;
  }

  function getTimePartsFromPickable(timePickable) {
    const pattern = /^(\d+):(\d+) (am|pm)$/;
    const [hour, minute, meridiem] = Array.from(
      timePickable.value.match(pattern)
    ).splice(1);

    return {
      hour,
      minute,
      meridiem,
    };
  }

  function getSelectsFromPicker(timePicker) {
    const [hour, minute, meridiem] = timePicker.querySelectorAll(
      ".time-picker__select"
    );

    return {
      hour,
      minute,
      meridiem,
    };
  }

  function getTimeStringFromPicker(timePicker) {
    const selects = getSelectsFromPicker(timePicker);

    return `${selects.hour.value}:${selects.minute.value} ${selects.meridiem.value}`;
  }

  function numberToOption(number) {
    const padded = number.toString().padStart(2, "0");

    return `<option value="${padded}">${padded}</option>`;
  }

  activate();

  $(".h-search-btn").click(function() {
    $(".h-srch").toggleClass("slide");
  });
  // Prepare the preview for profile picture
  $("#wizard-picture").change(function() {
    readURL(this);
  });

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $("#wizardPicturePreview")
          .attr("src", e.target.result)
          .fadeIn("slow");
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  $(function() {
    rome(inline_cal, {
      time: false
    });
  });

  $(".mobile_nav").click(function() {
    var mm = $(".mobile_menu"),
      mn = $(".mobile_nav"),
      a = "active";

    if (mm.hasClass(a) && mn.hasClass(a)) {
      mm.removeClass(a).fadeOut(200);
      mn.removeClass(a);
      $(".mobile_menu li").each(function() {
        $(this).removeClass("slide");
      });
    } else {
      mm.addClass(a).fadeIn(200);
      mn.addClass(a);
      $(".mobile_menu li").each(function(i) {
        var t = $(this);
        setTimeout(function() {
          t.addClass("slide");
        }, (i + 1) * 100);
      });
    }
  });

  // I've added annotations to make this easier to follow along at home. Good luck learning and check out my other pens if you found this useful

  // First let's set the colors of our sliders
  const settings = {
    fill: "#1abc9c",
    background: "#d7dcdf",
  };

  // First find all our sliders
  const sliders = document.querySelectorAll(".range-slider");

  // Iterate through that list of sliders
  // ... this call goes through our array of sliders [slider1,slider2,slider3] and inserts them one-by-one into the code block below with the variable name (slider). We can then access each of wthem by calling slider
  Array.prototype.forEach.call(sliders, (slider) => {
    // Look inside our slider for our input add an event listener
    //   ... the input inside addEventListener() is looking for the input action, we could change it to something like change
    slider.querySelector("input").addEventListener("input", (event) => {
      // 1. apply our value to the span
      slider.querySelector("span").innerHTML = event.target.value;
      // 2. apply our fill to the input
      applyFill(event.target);
    });
    // Don't wait for the listener, apply it now!
    applyFill(slider.querySelector("input"));
  });

  // This function applies the fill to our sliders by using a linear gradient background
  function applyFill(slider) {
    // Let's turn our value into a percentage to figure out how far it is in between the min and max of our input
    const percentage =
      (100 * (slider.value - slider.min)) / (slider.max - slider.min);
    // now we'll create a linear gradient that separates at the above point
    // Our background color will change here
    const bg = `linear-gradient(90deg, ${settings.fill} ${percentage}%, ${
          settings.background
        } ${percentage + 0.1}%)`;
    slider.style.background = bg;
  }

  (function() {
    const second = 1000,
      minute = second * 60,
      hour = minute * 60,
      day = hour * 24;

    //I'm adding this section so I don't have to keep updating this pen every year :-)
    //remove this if you don't need it
    let today = new Date(),
      dd = String(today.getDate()).padStart(2, "0"),
      mm = String(today.getMonth() + 1).padStart(2, "0"),
      yyyy = today.getFullYear(),
      nextYear = yyyy + 1,
      dayMonth = "09/30/",
      birthday = dayMonth + yyyy;

    today = mm + "/" + dd + "/" + yyyy;
    if (today > birthday) {
      birthday = dayMonth + nextYear;
    }
    //end


  })();
</script>



<script type="text/javascript">
  var booking = '<?php echo json_encode($booking); ?>';

  document.addEventListener("DOMContentLoaded", function() {
    var calendarEl = document.getElementById("calendar");
    var calendar = new FullCalendar.Calendar(calendarEl, {
      now: '{{$currentDate}}',
      scrollTime: "00:00",
      aspectRatio: 1.8,
      headerToolbar: {
        left: "today prev,next",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
      },
      initialView: "dayGridMonth",
      events: function(fetchInfo, successCallback, failureCallback) {
        successCallback(JSON.parse(booking));
      },
      eventDidMount: function(arg) {
        var cs = document.querySelectorAll(".cs");
        cs.forEach(function(v) {
          if (v.checked) {
            if (arg.event.extendedProps.cid === v.value) {
              arg.el.style.display = "block";
            }
          } else {
            if (arg.event.extendedProps.cid === v.value) {
              arg.el.style.display = "none";
            }
          }
        });
      }
    });
    calendar.render();

    var csx = document.querySelectorAll(".cs");
    csx.forEach(function(el) {
      el.addEventListener("change", function() {
        calendar.refetchEvents();
        console.log(el);
      });
    });
  });

  //Not sure for this...
  function CalendarTypeSet(fTip) {
    var x = document.getElementById('cal_tip');
    x.value = fTip;
    //$('#calendar').fullCalendar('rerenderEvents');
    calendar.rerenderEvents('#calendar');
  }
</script>
@endsection