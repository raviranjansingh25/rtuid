@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')
<div class="col-md-9">
  <div class="row">
    <div class="col-xl-4 col-lg-4 col-md-6">
      <div class="profile-dr profile-dr-in">
        <div class="profile-dr-con">
          <h2 class="complete-profle-add"><span class="sp-clr">{{$complite}}%</span> Complete Profile<a class="text-btn d-inline-block ms-2" href="{{route('complete_profile')}}">Complete Profile</a></h2>
          <div class="edit-img mb-4">
            <div class="picture-container">
              <div class="picture">
                <img src="{{isset($user->profile)?url($user->profile):url('/public/vender.png')}}" class="picture-src" id="wizardPicturePreview" title="" />


                <!-- <img class="edit-icon" src="{{url('/public/frontend/')}}/assets/images/edit-img.svg" alt=""> -->
              </div>
            </div>
          </div>
          <?php
          if ($user->gender == 1) {
            $sex = 'Male';
          } elseif ($user->gender == 2) {
            $sex = 'Female';
          } elseif ($user->gender == 3) {
            $sex = 'Other';
          } else {
            $sex = 'N/A';
          }
          ?>
          <h3 class="d-flex align-items-center gap-2 justify-content-center">{{$user->name}} {{$user->middle_name}} {{$user->last_name}}, {{$sex}} </h3>
          <a href="#">{{$user->email}}</a>
          <a href="#">{{isset($user->country_code)?'+'.$user->country_code:''}} {{$user->mobile}}</a>
          <p>D.O.B.: {{ isset($user->dob)?date('d-m-y', strtotime($user->dob)):'N/A' }}</p>
        </div>
        <div class="text-center profile_sss">
          <a href="{{route('edit_vender_profile')}}" class="btn-primary">Edit Profile</a>
          <form method="post" action="{{route('account_update')}}">
            @csrf
            @if(!empty(Auth::guard('vender')->user()->stripe_account_id))

            <button type="submit" class="btn-primary">Update Bank Account</button>
            @else

            <button type="submit" class="btn-primary">Add Bank Account</button>
            @endif
          </form>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6">
      <div class="profile-verify-mdl not-found-data d-flex align-items-center">
        <div>
          <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/verify-profile.svg" alt="">
          @if($complite >= 66)
          <h3>Profile Verified</h3>
          @else
          <h3>Profile Not Verified Yet!</h3>
          @endif
          <p>Upload your qualifications and credentials to be approved.</p>
          <div class="profile_sss">
            <a href="{{route('vender_verify_profile')}}" class="btn-primary">Verify Profile</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12">
      <div class="profile-verify-mdl not-found-data d-flex align-items-center">
        <div>
          <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/not-consult.svg" alt="">
          @php
          if($vender_consult_prices > 0){
          $button = 'Update';
          }else{
          $button = 'Add';
          }
          @endphp
          <h3>{{$button}} Consult Prices</h3>


          <p>Add or update your consult prices and packages.</p>
          <div class="profile_sss">
            <a href="{{route('vender_price_profile')}}"><button class="btn-primary">{{$button}} Consult Prices</button></a>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</section>
@endsection