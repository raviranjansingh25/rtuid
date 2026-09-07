@php
$user_data= Auth::guard('web')->user();
@endphp
<div class="col-md-3">
	<div class="profile">
		<img src="{{url('/public/frontend/')}}/assets/img/profile_pic.png" alt="" />
		<h4>{{isset($user_data->name)?$user_data->name:''}}<span>{{isset($user_data->mobile)?$user_data->mobile:''}}</span></h4>
	</div>
    <!-- Tabs nav -->
    <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link py-4 px-0 active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
            <i class=""><img src="{{url('/public/frontend/')}}/assets/img/user2.svg" alt="" /></i>
            <span class="font-weight-bold small">My Profile <small>Your personal details</small></span></a>

        <a class="nav-link py-4 px-0" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
            <i class=""><img src="{{url('/public/frontend/')}}/assets/img/wallet_icon.svg" alt="" /></i>
            <span class="font-weight-bold small">My Wallet<small>Earn money Details</small></span></a>

        <a class="nav-link py-4 px-0" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">
            <i class=""><img src="{{url('/public/frontend/')}}/assets/img/my-boking.svg" alt="" /></i>
            <span class="font-weight-bold small">My Bookings<small>Your bookings details</small></span></a>

        <a class="nav-link py-4 px-0" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
            <i class=""><img src="{{url('/public/frontend/')}}/assets/img/like.svg" alt="" /></i>
            <span class="font-weight-bold small">Favorites<small>Your favorite property details</small></span></a>

        <a class="nav-link py-4 px-0" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-invite" role="tab" aria-controls="v-pills-settings" aria-selected="false">
            <i class=""><img src="{{url('/public/frontend/')}}/assets/img/gift_present.svg" alt="" /></i>
            <span class="font-weight-bold small">Invite & Earn<small>Send invite your friends</small></span></a>

        <a class="nav-link py-4 px-0" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-notifi" role="tab" aria-controls="v-pills-settings" aria-selected="false">
            <i class=""><img src="{{url('/public/frontend/')}}/assets/img/notifi.svg" alt="" /></i>
            <span class="font-weight-bold small">Notifications<small>Your bookings notifications</small></span></a>
        </div>
</div>