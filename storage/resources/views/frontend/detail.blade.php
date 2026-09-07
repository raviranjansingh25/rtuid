@extends('frontend.layout.layout')
@section('content')
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
  .crt-dtl .dte input {
    padding: 16px 0px 16px 5px;
}
.limit_text{
    text-overflow: ellipsis;
    display: -webkit-box;
    overflow: hidden;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
.limit_text1 {
    text-overflow: ellipsis;
    display: -webkit-box;
    overflow: hidden;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
.prty-dtl-pr {
    padding: 20px;
    background-color: #fff;
    margin-bottom: 28px;
    position: relative;
}
.ofer-off {
    float: right;
    width: 51px;
    height: 53px;
    background: #0095FF;
    font-size: 16px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-top: -50px;
}
.quantity-input1 {
    outline: none;
    user-select: none;
    text-align: center;
    width: 47px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
}
.quantity-btn1 {
    background: #fff;
    margin: 0;
    padding: 0px 8px;
    cursor: pointer;
    font-size: 18px;
    border: 1px solid #707070;
    line-height: 20px;
}
.in-icn {
    position: absolute;
    top: 68px;
    right: 10px!important;
    right: inherit;
}
.in-icn {
    position: absolute;
    top: 68px;
    left: inherit;
     right: 10px; 
}
</style>
<section class="dtl-perty mt-110">
  <div class="container">
    <div class="prty-dtl-pr p-0">
      <a class="bck-icon" href="javascript:history.back()"><img src="{{url('/public/frontend/')}}/assets/img/bck-icon.svg" alt=""> Go Back</a>
      <h3>{{$hotel->hotel_name}} 
        <span class="float-end" style="position:relative;">
          @php
              $cart = '';
              $user= Auth::guard('web')->user();
              $wishlistwish =  App\Models\Favorites::where(['hotel_id'=>$hotel->id,'user_id'=>isset($user->id) ? $user->id : ''])->first();
          @endphp
          @if($wishlistwish!="" && $user!="")
          <img class="lke-icn1" onclick="remove_to_wish('{{$hotel->id}}',this)" src="{{url('/public/frontend/')}}/assets/img/union.svg" alt="">
          @else
          <img class="lke-icn" onclick="add_to_wish('{{$hotel->id}}',this)" src="{{url('/public/frontend/')}}/assets/img/like-icn.svg" alt="">
          @endif
        </span>
      </h3>
      <h5><span>{{$hotel->hotel_review}}.0 <img src="{{url('/public/frontend/')}}/assets/img/Polygon.svg" alt=""></span> ({{$hotel->review_count}} Ratings)</h5> 
      <p>{{$hotel->address}}<span>{{$hotel->city_name}}</span>
        <button class="float-end shr-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">Share <img class="img-fluid ms-1" src="{{url('/public/frontend/')}}/assets/img/shre-icon.svg" alt=""></button>
      </p>
    </div>
  </div>
</section>

<section class="prty-imgs">
  <div class="container">
    <div class="row room_image">
      
      <div class="mbl-none col-md-3">
        @foreach($hotel['get_single_room']['get_image'] as $key=>$image)
          @if($key<=1)
          <div class="prty-mnimg side-img">
            <img class="img-fluid" src="{{url($image->image)}}" alt="">
          </div>
          @endif
        @endforeach
      </div>

      <div class="col-md-6">
        @foreach($hotel['get_single_room']['get_image'] as $key=>$image)
          @if($key==2)
            <div class="prty-mnimg main-img-pr">
              <img class="img-fluid" src="{{url($image->image)}}" alt="">
              <button type="button" data-bs-toggle="modal" data-bs-target="#allphotos"><img class="img-fluid w-auto me-1" src="{{url('/public/frontend/')}}/assets/img/img-icon.svg" alt=""> View All Photos</button>
            </div>
          @endif
        @endforeach
      </div>

      <div class="mbl-none col-md-3">
        @foreach($hotel['get_single_room']['get_image'] as $key=>$image)
          @if($key>=3 && $key<5)
            <div class="prty-mnimg side-img">
              <img class="img-fluid" src="{{url($image->image)}}" alt="">
            </div>
          @endif
        @endforeach
      </div>

    </div>
  </div>
</section>

<section class="prty-dtl-main">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-12">
        <div class="abt-prty">
          <h3>Description</h3>
          <p class="detl-uni limit_text" id="limit_text0">{{$hotel->description}}</p>
          <p class="detl-uni text0" id="text" style="display: none;">
            {{$hotel->description}}
          </p>
          <button class="text-btn "><span onclick="readmore(0)" id="toggle0">See More</span> <img class="img-fluid ms-1" src="{{url('/public/frontend/')}}/assets/img/more-view.svg" alt=""></button>
        </div>
        <hr>
        <div class="avle-srve">
          <h3>Amenities</h3>
          <ul>
            @foreach($hotel['get_single_room']->amities_data as $amities)
            <li><i><img style="height: 17px;" class="img-fluid" src="{{url($amities->profile)}}" alt=""></i> {!!$amities->title!!}</li>
            @endforeach
          </ul>
        </div>
        <div class="plcy" >
          <h3>Hotel Policies</h3>
          <span class="detl-uni limit_text1" id="limit_text1">{!!$hotel->policies !!}</span>
          <span class="detl-uni text1" id="text1" style="display: none;">{!!$hotel->policies !!}</span>

          <button class="text-btn" ><span onclick="readmore1(1)" id="toggle1">See More</span> <img id="ero-roat1" class="img-fluid ms-1" src="{{url('/public/frontend/')}}/assets/img/more-view.svg" alt=""></button>
        </div>
        
            <h3>Ratings & Reviews</h3>
            <div class="clnt-review">
              <div class="row">
                <div class="col-md-6">
                  <h4>{{$hotel->hotel_review}}.0 <span><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/polygon-1.svg " alt=""></span></h4>
                  <p>{{$hotel->review_count}} Ratings <span>Good</span></p>
                </div>
                <div class="col-md-6">
                  <div class="well well-sm"> 
                    <div class="col-xs-12 col-md-12">
                      <div class="row rating-desc">  
                        <div class="col-xs-12 col-md-12">
                          <div class="rate-ove">
                            <span class="d-flex align-items-center me-2">5 <i class="fa fa-star ms-1"></i></span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20"
                              aria-valuemin="0" aria-valuemax="100" style="width: {!!$fivestar!!}%">
                            </div>
                          </div>
                          <span class="perce-value ms-3">{!!$fivestar!!}%</span>
                        </div>
                      </div>
                      <div class="col-xs-12 col-md-12">
                        <div class="rate-ove">
                          <span class="d-flex align-items-center me-2">4 <i class="fa fa-star ms-1"></i></span>
                          <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100" style="width: {!!$forestar!!}%">
                          </div>
                        </div>
                        <span class="perce-value ms-3">{!!$forestar!!}%</span>
                      </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                      <div class="rate-ove">
                        <span class="d-flex align-items-center me-2">3 <i class="fa fa-star ms-1"></i></span>
                        <div class="progress">
                          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20"
                          aria-valuemin="0" aria-valuemax="100" style="width: {!!$threestar!!}%">
                        </div>
                      </div>
                      <span class="perce-value ms-3">{!!$threestar!!}%</span>
                    </div>
                  </div>
                  <div class="col-xs-12 col-md-12">
                    <div class="rate-ove">
                      <span class="d-flex align-items-center me-2">2 <i class="fa fa-star ms-1"></i></span>
                      <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20"
                        aria-valuemin="0" aria-valuemax="100" style="width: {!!$twostar!!}%">
                      </div>
                    </div>
                    <span class="perce-value ms-3">{!!$twostar!!}%</span>
                  </div>
                </div>
                <div class="col-xs-12 col-md-12">
                  <div class="rate-ove">
                    <span class="d-flex align-items-center me-2">1 <i class="fa fa-star ms-1"></i></span>
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20"
                      aria-valuemin="0" aria-valuemax="100" style="width: {!!$onestar!!}%">
                    </div>
                  </div>
                  <span class="perce-value ms-3">{!!$onestar!!}%</span>
                </div>
              </div>
            </div>
            <!-- end row -->
          </div>  
        </div>
      </div>
    </div>
  </div>
  @if(count($review)>0)
  <div class="clnt-review limit_text" id="limit_text2">
    @foreach($review as $key=>$rev)
      @if($key==0)
      <div class="row">
        <div class="col-md-1">
          <div class="user-prfle">
            <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/usr-prle.svg" alt="">
          </div>
        </div>
        <div class="col-md-11">
          <div class="review-smt">
            <h5>{{$rev['get_user']->name}} <span>{{ $rev->created_at->diffForHumans() }}</span></h5>
            <p>- London, USA</p>
            <hr>
            <h6>{{$rev->review}}</h6>
            <ul>
              <?php
                for($i = 1;$i <= $rev->rate; $i++){
              ?>
              <li><i class="fa fa-star clr"></i></li>
              <?php }
                  $starnot = 5-$rev->rate;
                  for($j = 1;$j <= $starnot; $j++){ ?>
                                        
                    <li><i class="fa fa-star  "></i></li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
      @endif
    @endforeach
  </div>
  <div class="clnt-review detl-uni text2" style="display: none;">
    @foreach($review as $rev)
    <div class="row">
      <div class="col-md-1">
        <div class="user-prfle">
          <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/usr-prle.svg" alt="">
        </div>
      </div>
      <div class="col-md-11">
        <div class="review-smt">
          
          <p>- London, USA</p>
          <hr>
          <h6>{{$rev->review}}</h6>
          <ul>
            <?php
                for($i = 1;$i <= $rev->rate; $i++){
              ?>
              <li><i class="fa fa-star clr"></i></li>
              <?php }
                  $starnot = 5-$rev->rate;
                  for($j = 1;$j <= $starnot; $j++){ ?>
                                        
                    <li><i class="fa fa-star  "></i></li>
              <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    @endforeach
  </div>


  <button class="text-btn show-mre" ><span onclick="readmore(2)" id="toggle2">See More</span> <img id="ero-roat1" class="img-fluid ms-1" src="{{url('/public/frontend/')}}/assets/img/more-view.svg" alt=""></button>
  @endif
</div>
<div class="col-lg-4 col-md-12">
  <div class="prty-cart">
    <h2><i style="float:left;"><img src="{{url('/public/frontend/')}}/assets/img/rupee-3.svg" alt=""></i> <div class="room_price" style="float:left;">{{$hotel['get_single_room']->discount_amount}}</div> <span>per room per night</span></h2>
    <form method="post" id="form-data" class="crt-dtl">
      @csrf
      
      <div class="d-flex mb-3">
        <div class="form-group position-relative">
          <label for="">Check In</label>
          <input type="text" id="startDate" class="form-control check_in" name="check_in" placeholder="Check In">
        </div>
        <div class="form-group ms-3 position-relative">
          <label for="input_from">Check-out</label>
          <input type="text" id="endDate" class="form-control check_out" onchange="handler();" name="check_out" placeholder="Check Out">
        </div>
      </div>
      <div class="d-flex mb-3">
        <div class="w-100">
          <label for="Room">Room</label>
          <div class="quantity-control" data-quantity="">
            <button type="button" class="quantity-btn" data-quantity-minus="">-</button>
            <input type="number" class="quantity-input" data-quantity-target="" value="1" name="room_qty">
            <button type="button" class="quantity-btn" data-quantity-plus="">+
            </button>
          </div>
        </div>
        <input type="hidden" name="room_qty_value" class="room_qty_value" value="1">
        
        <div class="w-100 ms-3">
          <label for="Room">Guest</label>
          <div class="quantity-control" data-quantity1="">
            <button type="button" class="quantity-btn1" data-quantity-minus1="">-</button>
            <input type="number" class="quantity-input1" data-quantity-target1="" value="1"  name="guest_qty">
            <button type="button" class="quantity-btn1" data-quantity-plus1="">+
            </button>
          </div>
        </div>
      </div>
      <div class="room-status-change">
        <h3><span><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/room-icn.svg" alt=""></span> <span class="room_type">{{$hotel['get_single_room']['get_cat']->title}}</span></h3>
        <button type="button" data-bs-toggle="modal" data-bs-target="#roomModalToggle"><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/edit.svg" alt=""> Change</button>
      </div>
      <div class="menu-food" id="CourseMenu">
        <h4>Enjoy Meals During Your Stay</h4>
        @foreach($hotel['get_food'] as $food)
        <div class="meal-fast">
          <div class="d-flex align-items-center">
            <div class="img-food">
              <img src="{{url($food->image)}}" alt="">
            </div>
            <div>
              <h3>{{$food['get_cat']->title}}</h3>
              <p>Indian Menu <span><i><img src="{{url('/public/frontend/')}}/assets/img/rupee-3.svg" alt=""></i> {{$food->price}}</span></p>
            </div>
          </div>
          <div class="">
            <label class="checkbox-cus">
              <input class="w-auto additional" value="{{$food->id}}" id="custom-checkbox{{$food->id}}" onclick="handler()" name="food[]" data-price="{{$food->price}}" type="checkbox">
              <span class="checkmark"></span>
            </label>
          </div>
        </div>
        @endforeach
      </div>
      <button type="submit" class="bok-hotl">Continue to Book</button>
      <input type="hidden" id="room_amount" class="room_amount"  value="{{$hotel['get_single_room']->discount_amount}}">
      <input type="hidden" id="total_amount" class="total_amount1"  value="{{$hotel['get_single_room']->discount_amount}}">
      <input type="hidden" name="final_amount" id="final_amount" class="total_amount_value" value="{{$hotel['get_single_room']->discount_amount}}">
      <input type="hidden" name="hotel_id" id="hotel_id" class="hotel_id" value="{{$hotel->id}}">
      <input type="hidden" name="room_id" id="room_id" class="room_id" value="{{$hotel['get_single_room']->id}}">
      <div class="totle-amt">
        <h2>Total Price <span class="total_amount">₹{{$hotel['get_single_room']->discount_amount}}</span></h2>
        <p>(incl. of all taxes)</p>
      </div>
    </form>
  </div>
  <div class="alrt-notifctn">
    <ul>
      <li>8 people booked this hotel in last 6 hours</li>

      <a href="{{url('/page/cancellation-policy')}}"><li><span>Cancellation Policy</span></li></a>
      
      <li>Follow safety measures advised at the hotel</li> 
      
      <li>By proceeding, you agree to our <a href="{{url('/page/guest-policies')}}"><span>Guest Policies.</span></a></li>
    </ul>
  </div>
</div>
</div>
</div>
</section>

<section class="other-prty-rel">
  <div class="container">
    <div class="section-heading">
      <h2>Similar Cityroom</h2>
    </div>
    <div class="similr-roms">

      @foreach($similar_hotel as $similar)
      <div class="prty-box">
        <span>
              @php
              $cart = '';
              $user= Auth::guard('web')->user();
              $wishlistwish =  App\Models\Favorites::where(['hotel_id'=>$similar->id,'user_id'=>isset($user->id) ? $user->id : ''])->first();

              @endphp
              @if($wishlistwish!="" && $user!="")
              <img class="lke-icn1" onclick="remove_to_wish('{{$similar->id}}',this)" src="{{url('/public/frontend/')}}/assets/img/union.svg"  alt="">
              @else
              <img class="lke-icn" onclick="add_to_wish('{{$similar->id}}',this)" src="{{url('/public/frontend/')}}/assets/img/like-icn.svg" alt="">
              @endif
        </span>
        <div class="prty-img-sldr prty-img-sldrs">
          @if(!empty($similar['get_single_room']['get_image']))
            @foreach($similar['get_single_room']['get_image'] as $image1)
              <div class="prty-img">
                <img class="img-fluid" src="{{url($image1->image)}}" alt="" style="height: 260px;">
              </div>
            @endforeach
          @endif
        </div> 
        @php
        $percent = ($similar['get_single_room']->discount_amount*100)/$similar['get_single_room']->room_rent;
        $total_per = 100-$percent;
        
        @endphp
        <div class="prty-dtl-pr">
          <span class="ofer-off">{{round($total_per)}}% Off</span>
          <h3>{{$similar->hotel_name}}</h3>
          <h5><span>{{$similar->hotel_review}}.0 <img src="{{url('/public/frontend/')}}/assets/img/Polygon.svg" alt=""></span> ({{$similar->review_count}} Ratings) <b>Good</b></h5> 
          <p>{{$similar->address}}, {{$similar->city_name}} ...</p>
          
          <div class="htl-prce">
            <h4><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/rupee.svg" alt=""> {{$similar->discount_rent}} <span><img src="{{url('/public/frontend/')}}/assets/img/com-rupee.svg" alt=""> {{$similar->room_rent}}</span></h4>
            <a class="bdr-btn" href="{{url('/detail/'.$similar->slug)}}" tabindex="0">View Details</a>
          </div>
          <h6>per room per night</h6>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>

<div class="modal otp-mdl fade" id="roomModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"> 
          <div class="modal-body">
            <div class="">
            <h3>Choose Your Room</h3>
            
            @foreach($hotel['get_room'] as $room)

            <div class="clsc-prty">
              <div class="img-4">
                <img class="img-fluid" src="{{url(isset($room['get_image'][0]->image)?$room['get_image'][0]->image:'/public/noimage.png')}}" alt="">
              </div>
              <div class="dtl-clsic">
                <h4>{{$room['get_cat']->title}}</h4>
                <p>Room size: {{$room->room_size}} sqft</p>
                <ul>
                  @foreach($room->amities_data as $am1=>$amities1)
                    @if($am1<4)
                    <li><img style="height: 17px;" class="img-fluid" src="{{url($amities1->profile)}}" alt=""></li>
                  @endif
                  @endforeach
                  @if(count($room->amities_data)>4)
                  <li>&nbsp;+{{count($room->amities_data)-4}} more</li>
                  @endif
                </ul>
                <h5><img src="{{url('/public/frontend/')}}/assets/img/rupee-4.svg" alt=""> {{$room->discount_amount}} </h5>
              </div>
              <div class="slct-rdo">
                <input type="radio" name="room" value="{{$room->id}}" data-id="{{$room->id}}" {{ isset($hotel->get_single_room->id) ? $hotel['get_single_room']->id==$room->id ? 'checked' : '' : ''}} class="room_id_data">
                <span>Selected</span>
              </div>
            </div>
            @endforeach

            <div class="text-end">
              <button type="button" class="dis-btn-1" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            <button type="button" onclick="select_room()" class="red-btn-1" >Apply</button>
            </div>
            </div>
          </div>
          <!-- <div class="modal-footer">
            <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">Open second modal</button>
          </div> -->
        </div>
      </div>
    </div>


<div class="modal fade all-ptos-mdl" id="allphotos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <button type="button" class="photos-close" data-bs-dismiss="modal"> <img src="{{url('/public/frontend/')}}/assets/img/cls-icon.svg"></button>
  <div class="modal-dialog">
    <div class="modal-content"> 
        
      
      <div class="modal-body">
        <div class="slider-photos">
          @foreach($hotel['get_single_room']['get_image'] as $img)
          <div class="tle-imgs">
            <img src="{{url($img->image)}}" />
          </div>
          @endforeach
      </div>
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
      </div> -->
    </div>
  </div>
</div>


    <div class="modal fade share-mdl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"> 
       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Share</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul>
          <!-- <li> <a href="#"> <img src="https://v1.checkprojectstatus.com/kyles-klub/images/email.svg"> <span>Mail</span></a>  </li> -->
          <li> <a href="https://api.whatsapp.com/send?text={{url('/detail/'.$hotel->slug)}}"> <img src="https://v1.checkprojectstatus.com/kyles-klub/images/whatsapp.svg"> <span>WhatsApp</span></a>  </li>
          <li> <a href="http://www.facebook.com/sharer.php?u={{url('/detail/'.$hotel->slug)}}"> <img src="https://v1.checkprojectstatus.com/kyles-klub/images/facebbok-4.svg"> <span>Facebook</span></a>  </li>
          <li> <a href="http://twitter.com/share?url={{url('/detail/'.$hotel->slug)}}"> <img src="https://v1.checkprojectstatus.com/kyles-klub/images/twitter.svg"> <span>Twitter</span></a>  </li>
          <li> <a href="http://instagram.com/?u={{url('/detail/'.$hotel->slug)}}"> <img src="https://v1.checkprojectstatus.com/kyles-klub/images/insta.svg"> <span>Instagram</span></a>  </li>
        </ul>
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
      </div> -->
    </div>
  </div>
</div>
<script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
<script src="{{url('/public/frontend/')}}/assets/js/gijgo.min.js" type="text/javascript"></script>
<script>
    var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    $('#startDate').datepicker({
        uiLibrary: 'bootstrap4',
        dateFormat: 'dd/mm/yyyy',
        iconsLibrary: 'fontawesome',
        minDate: today,
        maxDate: function () {
            return $('#endDate').val();
        }
    });

    
    $('#endDate').datepicker({
        uiLibrary: 'bootstrap4',
        dateFormat: 'dd/mm/yyyy',
        iconsLibrary: 'fontawesome',
        minDate: function (days) {
            var start_date = $('#startDate').val();
            return start_date;
        }
    });

</script>
<script>
  function select_room(){
    var ele = document.getElementsByName('room');
    for(i = 0; i < ele.length; i++) {
      if(ele[i].checked)
        
      var room_id = ele[i].value;

    }
    
    $.ajax(
      {
          url: "{{ route('select_room') }}",
          type: 'GET',
          data: {
              '_token' : '<?php echo csrf_token() ?>',
              'room_id'    : room_id,
          },
          beforeSend: function() {
              $("#preloader").show(); 
          },
          success: function (res)
          {
            $("#preloader").hide(); 
              if (res.status == 1)
              {
                $(".room_image").html(res.image);
                $(".room_price").html(res.price);
                $(".total_amount").html('₹'+res.price);
                $(".room_amount").val(res.price);
                $(".room_type").html(res.room_type); 
                $('#roomModalToggle').modal('hide');
                $('.room_id').val(res.room_id);
                // $(".quantity-input").val(1);
                handler(1);
                
              }
          },
          error: function (error) {
            $("#preloader").hide(); 
              swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
              })
          }
      });
  }

</script>
<script>
    $("#form-data").validate({

      onfocusout: function (element) {
          $(element).valid();
      },

      rules: {
          'check_in': {required: true},
          'check_out': {required: true},
      },
      messages: {
          'check_in': "Please select check in date.",        
          'check_out': "Please select check out date.",                        
      },
      errorPlacement: function(error, element) {
          if (element.attr("name") == "data[Payment][phone]") {
            error.insertAfter(".error-placement");
        } else {
            error.insertAfter(element);
        }
    },

    submitHandler: function(form) {

      $.ajax(
      {
          url: "{{ route('booking_room') }}",
          type: 'GET',
          data: $('#form-data').serialize(),
          beforeSend: function() {
              $("#preloader").show(); 
          },
          success: function (res)
          {
            $("#preloader").hide(); 
              if (res.status == 1)
              {
                window.location="{{route('room_booking')}}"

              }else{
                $("#preloader").hide(); 
                swal({
                  icon: 'error',
                  title: 'Oops...',
                  text: res.message,
                })
              }   
          },
          error: function (error) {
            $("#preloader").hide(); 
              swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
              })
          }
      });
  },   
});

</script>
<script type="text/javascript">
  function handler(room_qty = 1){
    var total_amt=$('#room_amount').val();
    var room_qty=$('.room_qty_value').val();
    var diffDays = 1;
    var check_in=$('.check_in').val();
    var check_out=$('.check_out').val();
    var date1 = new Date(check_in.split('-')[2],check_in.split('-')[1]-1,check_in.split('-')[0]);
    var date2 = new Date(check_out.split('-')[2],check_out.split('-')[1]-1,check_out.split('-')[0]);
    var diffDays = parseInt((date2 - date1) / (1000 * 60 * 60 * 24), 10);
    var additional = [];
    var food_amount = 0;
    $(".additional:checked").each(function(){
        additional.push(this.value);
        var price=$(this).data('price');
        food_amount=parseInt(food_amount)+parseInt(price); 
    });
    if(isNaN(diffDays)) {
      var room_rent = ((parseInt(total_amt)*parseInt(room_qty))+parseInt(food_amount));
    }else{
      var room_rent = ((parseInt(total_amt)*parseInt(room_qty))+parseInt(food_amount))*diffDays;
    }
    
    $(".total_amount").html('₹'+room_rent);
    $(".total_amount_value").val(room_rent); 
    // $(".quantity-input").val(room_qty); 
  }
</script>

<script>
  (function () {
    "use strict";
    var jQueryPlugin = (window.jQueryPlugin = function (ident, func) {
      return function (arg) {
        if (this.length > 1) {
          this.each(function () {
            var $this = $(this);

            if (!$this.data(ident)) {
              $this.data(ident, func($this, arg));
            }
          });

          return this;
        } else if (this.length === 1) {
          if (!this.data(ident)) {
            this.data(ident, func(this, arg));
          }

          return this.data(ident);
        }
      };
    });
  })();

  (function () {
    "use strict";
    function Guantity($root) {
      const element = $root;
      const quantity = $root.first("data-quantity");
      const quantity_target = $root.find("[data-quantity-target]");
      const quantity_minus = $root.find("[data-quantity-minus]");
      const quantity_plus = $root.find("[data-quantity-plus]");
      var quantity_ = quantity_target.val();
      $(quantity_minus).click(function () {
        if (quantity_>1) {
          quantity_target.val(--quantity_);
          $(".room_qty_value").val(quantity_);
          handler(quantity_)
        }
        
      });
      $(quantity_plus).click(function () {
        quantity_target.val(++quantity_);
        $(".room_qty_value").val(quantity_);
        handler(quantity_)
      });
    }

    $.fn.Guantity = jQueryPlugin("Guantity", Guantity);
    $("[data-quantity]").Guantity();

  })();

  (function () {
    "use strict";
    function Guantity1($root1) {
      const element1 = $root1;
      const quantity1 = $root1.first("data-quantity1");
      const quantity_target1 = $root1.find("[data-quantity-target1]");
      const quantity_minus1 = $root1.find("[data-quantity-minus1]");
      const quantity_plus1 = $root1.find("[data-quantity-plus1]");
      var quantity1_ = quantity_target1.val();
      $(quantity_minus1).click(function () {
        if (quantity1_>1) {
          quantity_target1.val(--quantity1_);
        }
        
      });
      $(quantity_plus1).click(function () {
        quantity_target1.val(++quantity1_);
      });
    }

    $.fn.Guantity1 = jQueryPlugin("Guantity1", Guantity1);
    $("[data-quantity1]").Guantity1();

  })();


  const slider = document.querySelector('.items');
  let isDown = false;
  let startX;
  let scrollLeft;

  slider.addEventListener('mousedown', (e) => {
    isDown = true;
    slider.classList.add('active');
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });
  slider.addEventListener('mouseleave', () => {
    isDown = false;
    slider.classList.remove('active');
  });
  slider.addEventListener('mouseup', () => {
    isDown = false;
    slider.classList.remove('active');
  });
  slider.addEventListener('mousemove', (e) => {
    if(!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
const walk = (x - startX) * 3; //scroll-fast
slider.scrollLeft = scrollLeft - walk;
console.log(walk);
});
  function slidefunction(position)
  {

    if(position=="left")
    {
      slider.scrollLeft = 0
    }
    else{
      slider.scrollLeft = 900
    }

  }
</script>
<script>
  $('.navTrigger').click(function () {
    $(this).toggleClass('active');
    console.log("Clicked menu");
    $("#mainListDiv").toggleClass("show_list");
    $("#mainListDiv").fadeIn();

  });

</script>

<script>
  
  function readmore(id)
  {
    var elem = $("#toggle" + id).text();
    if (elem == "See More") {
                //Stuff to do when btn is in the read more state
      $("#toggle" + id).text("See Less");
      $("#ero-roat" + id).attr("src","{{url('/public/frontend/')}}/assets/img/upr-errow.svg");
      $("#limit_text" + id).hide();
      $(".text" + id).slideDown();
    } else {
                //Stuff to do when btn is in the read less state
      $("#toggle" + id).text("See More");
      $("#ero-roat" + id).attr("src","{{url('/public/frontend/')}}/assets/img/more-view.svg");
      $("#limit_text" + id).show();
      $(".text" + id).slideUp();
    }
  }

  function readmore1(id)
  {
    var elem = $("#toggle" + id).text();
    if (elem == "See More") {
                //Stuff to do when btn is in the read more state
      $("#toggle" + id).text("See Less");
      $("#ero-roat" + id).attr("src","{{url('/public/frontend/')}}/assets/img/upr-errow.svg");
      $("#limit_text"+ id).hide();
      $(".text" + id).slideDown();
    } else {
      $("#toggle" + id).text("See More");
      $("#ero-roat" + id).attr("src","{{url('/public/frontend/')}}/assets/img/more-view.svg");
      $("#limit_text"+ id).show();
      $(".text" + id).slideUp();
    }
  }
</script>
<script>
  (function() {
    
    var slideContainer = $('.prty-img-sldr');
    slideContainer.slick({
      arrows: true,
      initialSlide:0,
      centerMode: true,
      centerPadding: '0%',
      slidesToShow: 1,
      swipeToSlide:true,
    });
  })();
</script>
<script>
  (function() {
    
    var slideContainer = $('.similr-roms');
    slideContainer.slick({
      arrows: true,
      initialSlide:0,
      centerMode: true,
      centerPadding: '0%',
      slidesToShow: 3,
      swipeToSlide:true,
      swipe:false,
      responsive: [
      {
        breakpoint: 1600,
        settings: {
          slidesToShow: 2,
          centerPadding: '10%'
        }
      },
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 1,
          centerPadding: '0%'
        }
      },
      {
        breakpoint: 700,
        settings: {
          slidesToShow: 1,
          centerPadding: '0%'
        }
      }
      ]
    });
  })();
</script>
<script>
    (function() {
      
      var slideContainer = $('.slider-photos');
      slideContainer.slick({
        arrows: true,
        initialSlide:1,
        centerMode: true,
        centerPadding: '0%',
        slidesToShow: 1,
        swipeToSlide:true,
         
      });
    })();
        </script>
@endsection  

