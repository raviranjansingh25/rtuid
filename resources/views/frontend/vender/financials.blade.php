@extends('frontend.layout.layout2')
@section('content')
@include('frontend.layout.vender_sidebar')
<div class="col-md-12 col-lg-9 financial-adviser-00">
  <h2 class="section-title d-md-flex justify-content-between align-items-center">My Financials</h2>
  <div class="row">

    <div class="col-md-3 col">
      <div class="box-consult-form">
        <h3>$ {{number_format($total, 2)}}</h3>
        <p>Total Earning</p>
      </div>
    </div>

    <div class="col-md-3 col">
      <div class="box-consult-form">
        <h3>$ {{number_format($lastYearTotal, 2)}}</h3>
        <p>Last Year Earning</p>
      </div>
    </div>

    <div class="col-md-3 col">
      <div class="box-consult-form">
        <h3>$ {{number_format($lastMonthTotal, 2)}}</h3>
        <p>Last Month Earning</p>
      </div>
    </div>
    <div class="col-md-3 col">
      <div class="box-consult-form">
        <h3>$ {{number_format($lastWeekTotal, 2)}}</h3>
        <p>Last Week Earning</p>
      </div>
    </div>

    <div class="col-md-12">
      <div class="payment-history">
        <h2>Payment History</h2>
        @forelse($history as $his)
        <div class="history-dtl">
          <div class="pmt-snd pmt-snd-000" >
            <img class="mt-1" src="{{url('/public/frontend/')}}/assets/images/pmt.svg" alt="">
            <h3><a href="{{url('/patients-detail/'.get_encrypted_value($his->user_id, true))}}">{{$his['get_patient']->name}} {{$his['get_patient']->middle_name}} {{$his['get_patient']->last_name}}</a> <span>{{$his->course_name}}</span>{{$his->created_at}}</h3>
          </div>
          <div class="amout-give">
            @php 
            $amount = $his->price;
            $comm2 = $amount*$user->commission/100;
            
            $amount = $amount-$comm2;
            @endphp
            <h5>${{ number_format($amount, 2) }}</h5>
          </div>
          <div class="">
            <a target="_blank" href="{{url('/invoice/'.get_encrypted_value($his->id, true))}}" class="btn-primary">Invoice</a>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
</div>



</div>
</section>
@endsection