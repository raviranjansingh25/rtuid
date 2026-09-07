@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/sidebar')
<div class="col-md-9">
  <div class="row">
    <div class="col-md-6">
      <div class="book-appointment-dr" style="height:inherit;">
        <div class="book-profile-img">
          <img class="profile-b" src="{{isset($doctor->profile)?url($doctor->profile):url('/public/vender.png')}}" alt="">
          <div>
            <span class="sp-clr">{{$doctor->name}}</span>
            <h3 class="">{{$doctor->category_name}} </h3>
            <p><img src="{{url('/public/frontend/')}}/assets/images/like.svg" alt=""> {{$doctor->total_review}}%</p>

          </div>
        </div>

        <div class="prices bg-white p-0">
          <h3 class="Con-info">Consultation Information</h3>
          <div class="row">
            <div class="col-md-6">
              <div class="d-flex align-items-center gap-2 mb-4">
                <img src="{{url('/public/')}}/calendar.png" height="100px;" alt="">
                <div class="avlble-time p-0 text-start">
                  <h4>{{date("h:i a", strtotime($file->user_start_time_timezone))}} </h4>
                  <!-- <h4><span>{{$doctor->address}} ({{$doctor->timezone}})</span></h4> -->
                  <p>{{ date('l, jS F', strtotime($file->booking_date)) }}</p>
                </div>
              </div>
            </div>
@if(!empty($treat))
            <div class="col-md-6">
              <div onclick="TreatmentPlanUser(1,{{$file['vender_id']}})" class="d-flex align-items-center gap-2 mb-4">
                <img src="{{url('/public/')}}/frontend/assets/images/form.svg" height="50px;" alt="">
                <div class="avlble-time p-0 text-start">
                  <h4>Treatment Plan</h4>
                </div>
              </div>
            </div>
            @endif

          </div>

          <ul class="brdr-top py-3 mt-5">
            <li><strong>Consult Prices</strong> <span>${{$file->session_price}}</span></li>
          </ul>
        </div>




        <div class="d-flex gap-2">
          <a href="{{url('/booking/'.get_encrypted_value($doctor->id, true))}}" class="btn-primary w-100 mt-3 text-center">Rebook</a>
          <?php
          $review = "";
          if (!empty(Auth::guard('web')->user())) {
            $review = \App\Models\DoctorReview::where('user_id', Auth::guard('web')->user()->id)->first();
          }

          ?>
          @if(empty($review))
          <button class="btn-primary w-100 mt-3 text-center" onclick="review_model({{$doctor->id}},1)">Give Rating</button>
          @endif
        </div>
      </div>
    </div>
  </div>

</div>
</div>
</div>
</section>



@endsection