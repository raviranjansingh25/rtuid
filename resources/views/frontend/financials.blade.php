@extends('frontend.layout.layout2')
@section('content')
@include('frontend.layout.sidebar')
<div class="col-md-9">

  <div class="row">



    <div class="col-md-12">
      <div class="payment-history">
        <h2>Payment History</h2>
        @forelse($history as $his)
        <div class="history-dtl">
          <div class="pmt-snd" style="width: 40%;">
            <img class="mt-1" src="{{url('/public/frontend/')}}/assets/images/pmt.svg" alt="">
            <h3><a href="{{url('/practitioners-detail/'.get_encrypted_value($his['get_doctor']->id, true))}}">{{$his['get_doctor']->name}} {{$his['get_doctor']->last_name}}</a> <span>{{$his->course_name}}</span>{{$his->created_at}}</h3>
          </div>
          <div class="amout-give">
            <h5>$ {{ number_format($his->price, 2) }}</h5>
          </div>
          <div class="">
            <a target="_blank" href="{{url('/user_invoice/'.get_encrypted_value($his->id, true))}}" class="btn-primary">Invoice</a>
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