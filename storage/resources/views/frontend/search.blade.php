@extends('frontend.layout.layout')
@section('content')

<style type="text/css">


#dash label {
  text-overflow: ellipsis;
    display: -webkit-box;
    overflow: hidden;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}
.more-prty{
    color: #F03E39;
    font-size: 20px;
    font-weight: 600;
    cursor: pointer;
}
.gj-datepicker-bootstrap [role=right-icon] button {
    height: 58px;
}

</style>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="{{url('/public/frontend/')}}/assets/css/style.css" />

<section class="srch-hro mt-110">
  <div class="container">
    <form action="{{route('hotels')}}" method="post" class="srch-lst-frm">
      @csrf
      <div class="row ">
        <div class="col-md-3">
          <div class="form-group w-100 sldr-frm ">
            <input type="text" class="form-control" name="name" placeholder="Hotel/City Name">
            <img class="in-icn" src="{{url('/public/frontend/')}}/assets/img/serch.svg" alt="">
          </div>
        </div>

        <div class="col-md-3 d-flex">
          <div class="form-group w-100 sldr-frm rd-brd">
              <input type="text" class="form-control" id="input_from" placeholder="Fri, 15 Dec 22">
              <img class="in-icn clanr" src="{{url('/public/frontend/')}}/assets/img/caldr.svg" alt="" name="check_in">
            </div>
            <div class="form-group w-100 sldr-frm"> 
              <input type="text" class="form-control" id="input_to" placeholder="Sat, 16 Dec 22">
              <img class="in-icn clanr" src="{{url('/public/frontend/')}}/assets/img/caldr.svg" alt="" name="check_out">
            </div>
        </div>

        <div class="col-md-3 d-flex">
          <div class="form-group w-100 sldr-frm rd-brd"> 
            <select name="room_qty" id="">
              <option value="">Rooms</option>
              <option value="">1</option>
              <option value="">2</option>
              <option value="">3</option>
            </select>
          </div>
          <div class="form-group w-100 sldr-frm">
            <select name="guest_qty" id="">
              <option value="">Guest</option>
              <option value="">1</option>
              <option value="">2</option>
              <option value="">3</option>
            </select>
          </div>

        </div>
        <div class="col-md-1 sldr-frm">
          <button type="submit" class="flter-btn" style="padding: 15px;">Search</button>
        </div>
        <div class="col-md-2 sldr-frm">
          <button type="button" class="flter-btn" data-bs-toggle="modal" data-bs-target="#filterModalToggle"><img src="{{url('/public/frontend/')}}/assets/img/fltr-icon.svg" alt=""> All Filters</button>
        </div>
      </div>
    </form>
  </div>
</section>

<section class="lctn-htl">
  <div class="container">
    <div class="row align-items-center">
      <input type="hidden" name="post_city" id="post_city" value="{{isset($city_id)?$city_id:''}}">
      <div class="col-md-6">
        @if(!empty($city))
        <h2>{{$city}} <span>({{count($hotel)}} Hotels)</span></h2>
        @endif
      </div>
      <div class="col-md-6">
        <div class="srt-drp">
          <h3>Sort by</h3>
          <select name="shorting" id="shorting" onchange="shorting()">
            <option value="">Price</option>
            <option value="1">Low to high</option>
            <option value="2">High to low</option>
           
          </select>
          <img class="drp-dn" src="{{url('/public/frontend/')}}/assets/img/drp-dwn.svg" alt="">
        </div>
      </div>
    </div>
  </div>
</section>

<section class="citye-perty-section">
  <div class="container">
    <div class="row hotel_val content hideContent" id="trid">
      
      @foreach($hotel as $hotel_data)
      <div class="col-lg-4 col-md-6">
        <div class="prty-box">
          <span>
            @php
            $cart = '';
            $user= Auth::guard('web')->user();
            $wishlistwish =  App\Models\Favorites::where(['hotel_id'=>$hotel_data->id,'user_id'=>isset($user->id) ? $user->id : ''])->first();

            @endphp
            @if($wishlistwish!="" && $user!="")
            <img class="lke-icn1" onclick="remove_to_wish('{{$hotel_data->id}}',this)" src="{{url('/public/frontend/')}}/assets/img/union.svg"  alt="">
            @else
            <img class="lke-icn" onclick="add_to_wish('{{$hotel_data->id}}',this)" src="{{url('/public/frontend/')}}/assets/img/like-icn.svg" alt="">
            @endif
          </span>
          <div class="prty-img-sldr">
            @foreach($hotel_data['get_single_room']['get_image'] as $image)
            <div class="prty-img">
              <img class="img-fluid" src="{{url($image->image)}}" alt="" style="height:260px;">
            </div>
            @endforeach
          </div> 
          <div class="prty-dtl-pr">
            <h3>{{$hotel_data->hotel_name}}</h3>
            <h5><span>{{$hotel_data->hotel_review}}.0 <img src="{{url('/public/frontend/')}}/assets/img/Polygon.svg" alt=""></span> ({{$hotel_data->review_count}} Ratings)</h5>
            <div > 
              <p id="dash"><label>{{$hotel_data->address}}</label><span>{{$hotel_data->city_name}}</span></p>
            </div>
            <ul>
              @foreach($hotel_data['get_single_room']->amities_data as $am=>$amities)
              @if($am<4)
              <li><img style="height: 17px;" class="img-fluid" src="{{url($amities->profile)}}" alt=""></li>
              @endif
              @endforeach
              @if(count($hotel_data['get_single_room']->amities_data)>4)
              <li><a href="{{url('/detail/'.$hotel_data->slug)}}">+{{count($hotel_data['get_single_room']->amities_data)-4}} more</a></li>
              @endif
              
              
            </ul>
            <div class="htl-prce">
              <h4><img class="img-fluid" src="{{url('/public/frontend/')}}/assets/img/rupee.svg" alt=""> {{$hotel_data->discount_rent}} <span><img src="{{url('/public/frontend/')}}/assets/img/com-rupee.svg" alt=""> {{$hotel_data->room_rent}}</span></h4>
              <a class="bdr-btn" href="{{url('/detail/'.$hotel_data->slug)}}" tabindex="0">View Details</a>
            </div>
            <h6>per room per night</h6>
          </div>
        </div>
      </div>
      @endforeach
      
    </div>
    @if(count($hotel)>6)
    <div class="more-prty">
      <span class="see-more" data-page="2" data-link="{{url('/hotels')}}?page=" data-div="#trid">See More Hotels <img class="img-fluid ms-1" src="{{url('/public/frontend/')}}/assets/img/more-view.svg" alt=""></span>
    </div>
    @endif
  </div>
</section>

<div class="modal fltr-section fade" id="filterModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content"> 
      <div class="modal-body">
        <div class="flter-mdl">
          <div class="tle-fltr">
            <h2>Filters</h2>
            <div class="clr-shw">
              <button class="dt-clr-btm" data-bs-dismiss="modal" aria-label="Close">All Clear</button>
              <a href="#" data-bs-dismiss="modal" aria-label="Close" onclick="filter()">Show Results</a>
            </div>
          </div>
          <div class="tle-fltrt-dtl">
            <p class="mb-4">Price range</p>
            <div id="slider"></div>
            <hr>
            <div class="clton">
              <p>Collections</p>
              <ul>
                @foreach($collection as $coll)
                <li>
                  <label class="checkbox-cus">{{$coll->title}}
                    <input class="w-auto" type="checkbox" name="collection" value="{{$coll->id}}">
                    <span class="checkmark"></span>
                  </label>
                </li>
                @endforeach
              </ul>
            </div>
            <hr>
            <div class="clton cst-gry">
              <p>Categories</p>
              <ul>
                @foreach($room_category as $cat)
                <li>
                  <label class="checkbox-cus">{{$cat->title}}
                    <input class="w-auto" type="checkbox" name="category" value="{{$cat->id}}">
                    <span class="checkmark"></span>
                  </label>
                  <p>{{$cat->description}}</p>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
          <div class="more-prty p-0 text-start">
            <a href="#">See More <img class="img-fluid ms-1" src="./assets/img/more-view.svg" alt=""></a>
          </div>
        </div>
      </div>
      <!-- <div class="modal-footer">
        <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">Open second modal</button>
      </div> -->
    </div>
  </div>
</div>

<script src="{{url('/public/frontend/')}}/assets/js/jquery-3.3.1.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/slick.min.js"></script>
<script src="{{url('/public/frontend/')}}/assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
  $(".see-more").click(function() {
  $div = $($(this).data('div')); //div to append
  $link = $(this).data('link'); //current URL
  $page = $(this).data('page'); //get the next page #
  $href = $link + $page; //complete URL
  $.get($href, function(response) { //append data
    $html = $(response).find("#trid").html();
    $div.append($html);
    var slideContainer = $('.prty-img-sldr').not('.slick-initialized');
      slideContainer.slick({
        arrows: true,
        initialSlide:0,
        centerMode: true,
        centerPadding: '0%',
        slidesToShow: 1,
        swipeToSlide:true,
      });
  });
  
  $(this).data('page', (parseInt($page) + 1)); //update page #
});
</script>


<script type="text/javascript">
  function filter(){
      var category_data = new Array();
        $("input[name='category']:checked").each(function(i) {
            category_data.push($(this).val());
        });
        var collection_data = new Array();
        $("input[name='collection']:checked").each(function(i) {
            collection_data.push($(this).val());
        });
        var min_price = $('.price-range-min').html();
        var max_price = $('.price-range-max').html();
        var city = $('#post_city').val();
        $.ajax(
          {
          url: "{{ route('filter') }}",
          type: 'GET',
          data: {
              '_token' : '<?php echo csrf_token() ?>',
              'category_data'    : category_data,
              'collection_data'  : collection_data,
              'min_price'        : min_price,
              'max_price'        : max_price,
              'city'         : city,
          },
          beforeSend: function() {
              $("#preloader").show(); 
          },
          success: function (res)
          {
            $("#preloader").hide(); 
              if (res.status == 1)
              {
                $('.hotel_val').html(res.hotel_val);
                // $('#filterModalToggle').modal('hide');


              }else{
                
                swal({
                  icon: 'error',
                  title: 'Oops...',
                  text: res.message,
                })
              }  
              var slideContainer = $('.prty-img-sldr');
              slideContainer.slick({
                arrows: true,
                initialSlide:0,
                centerMode: true,
                centerPadding: '0%',
                slidesToShow: 1,
                swipeToSlide:true,
              }); 
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

<script type="text/javascript">
  function shorting(){
      var shorting = $('#shorting').val();
      var city = $('#post_city').val();
      $.ajax(
        {
        url: "{{ route('shorting') }}",
        type: 'GET',
        data: {
            '_token' : '<?php echo csrf_token() ?>',
            'shorting'         : shorting,
            'city'         : city,
        },
        beforeSend: function() {
            $("#preloader").show(); 
        },
        success: function (res)
        {
          $("#preloader").hide(); 
            if (res.status == 1)
            {
              $('.hotel_val').html(res.hotel_val);
            }else{
              
              swal({
                icon: 'error',
                title: 'Oops...',
                text: res.message,
              })
            }  
            var slideContainer = $('.prty-img-sldr');
            slideContainer.slick({
              arrows: true,
              initialSlide:0,
              centerMode: true,
              centerPadding: '0%',
              slidesToShow: 1,
              swipeToSlide:true,
            }); 
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
  $(function() {
    var d = new Date()
    var datestring = d.getDate()  + "-" + (d.getMonth()+1) + "-" + d.getFullYear();
    rome(input_from, {
      dateValidator: rome.val.beforeEq(input_to),
      min: datestring,
      time: false
    });

    var end_date = input_from;
    end_date.setDate(end_date.getDate() + 1);
    rome(input_to, {
      dateValidator: rome.val.afterEq(end_date),
      time: false
    });


  });
</script>

<script>
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
  function collision($div1, $div2) {
    var x1 = $div1.offset().left;
    var w1 = 40;
    var r1 = x1 + w1;
    var x2 = $div2.offset().left;
    var w2 = 40;
    var r2 = x2 + w2;

    if (r1 < x2 || x1 > r2)
      return false;
    return true;
  }
// Fetch Url value 
  var getQueryString = function (parameter) {
    var href = window.location.href;
    var reg = new RegExp('[?&]' + parameter + '=([^&#]*)', 'i');
    var string = reg.exec(href);
    return string ? string[1] : null;
  };
// End url 
// // slider call
  $('#slider').slider({
    range: true,
    min: {{$min_price}},
    max: {{$max_price}},
    step: 1,
    values: [getQueryString('minval') ? getQueryString('minval') : {{$min_price}}, getQueryString('maxval') ? getQueryString('maxval') : {{$max_price}}],

    slide: function (event, ui) {

      $('.ui-slider-handle:eq(0) .price-range-min').html('₹' + ui.values[ 0 ]);
      $('.ui-slider-handle:eq(1) .price-range-max').html('₹' + ui.values[ 1 ]);
      $('.price-range-both').html('<i>₹' + ui.values[ 0 ] + ' - </i>₹' + ui.values[ 1 ]);

        // get values of min and max
      $("#minval").val(ui.values[0]);
      $("#maxval").val(ui.values[1]);

      if (ui.values[0] == ui.values[1]) {
        $('.price-range-both i').css('display', 'none');
      } else {
        $('.price-range-both i').css('display', 'inline');
      }

      if (collision($('.price-range-min'), $('.price-range-max')) == true) {
        $('.price-range-min, .price-range-max').css('opacity', '0');
        $('.price-range-both').css('display', 'block');
      } else {
        $('.price-range-min, .price-range-max').css('opacity', '1');
        $('.price-range-both').css('display', 'none');
      }

    }
  });

  $('.ui-slider-range').append('<span class="price-range-both value"><i>₹' + $('#slider').slider('values', 0) + ' - </i>' + $('#slider').slider('values', 1) + '</span>');

  $('.ui-slider-handle:eq(0)').append('<span class="price-range-min value">₹' + $('#slider').slider('values', 0) + '</span>');

  $('.ui-slider-handle:eq(1)').append('<span class="price-range-max value">₹' + $('#slider').slider('values', 1) + '</span>');
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

@endsection