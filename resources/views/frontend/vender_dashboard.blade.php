@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')

<div class="col-md-9">
  <div class="dashboard_content_s">
  <div class="row">
    <div class="col-xl-8 col-md-12">
      <div class="dashboard_content_s_in">
      <h2 class="login-dash-title">Upcoming Consultations <a href="">View All Your Patients <img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/imagesbutton-errow.svg" alt=""></a></h2>
      <div class="upcoming-cont">
        <div class="list-consultations">
          <div class="profile-di">
            <a href="practitioner_profle.html">
              <img src="{{url('/public/frontend/')}}/assets/images/imagesteam-1.png" alt="">
              <h3>Lisa Parker <span>Psychologist</span></h3>
            </a>
          </div>

          <div class="avlble-time">
            <h4>4 PM <span>(AST)</span></h4>
            <p>Thursday, 3rd March</p>
          </div>
        </div>
        <div class="list-consultations">
          <div class="profile-di">
            <a href="practitioner_profle.html">
              <img src="{{url('/public/frontend/')}}/assets/images/imagesteam-1.png" alt="">
              <h3>Lisa Parker <span>Psychologist</span></h3>
            </a>
          </div>

          <div class="avlble-time">
            <h4>4 PM <span>(AST)</span></h4>
            <p>Thursday, 3rd March</p>
          </div>
        </div>
        <div class="list-consultations">
          <div class="profile-di">
            <a href="practitioner_profle.html">
              <img src="{{url('/public/frontend/')}}/assets/images/imagesteam-1.png" alt="">
              <h3>Lisa Parker <span>Psychologist</span></h3>
            </a>
          </div>

          <div class="avlble-time">
            <h4>4 PM <span>(AST)</span></h4>
            <p>Thursday, 3rd March</p>
          </div>
        </div>
      </div>

      <div class="telimed-modalities">
        <h3>Consultation Stats in this Month</h3>
        <div class="row">
          <div class="col-xl-3 col-lg-4 col-md-4">
            <div class="status-month-box">
              <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/imagesthumps-received.svg" alt="">
              <p>Thumps up received</p>
              <h2>98</h2>
            </div>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-4">
            <div class="status-month-box">
              <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/imagesincome.svg" alt="">
              <p>Generated Income</p>
              <h2>$ 1,000</h2>
            </div>
          </div>
          <div class="col-xl-6 col-lg-4 col-md-4">
            <img class="w-100" src="{{url('/public/frontend/')}}/assets/images/imagesgraf-chart.svg" alt="">
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
            <img class="w-100" src="{{url('/public/frontend/')}}/assets/images/imagesgraf-2.svg" alt="">
          </div>
        </div>
      </div>
      <div class="healthcare-perof gride-lst-view">
        <h2 class="login-dash-title">Telimed Learning Hub <a href="">View All <img class="ms-2" src="{{url('/public/frontend/')}}/assets/images/imagesbutton-errow.svg" alt=""></a></h2>
        <div class="course-cr">

        </div>

      </div>
    </div>


    </div>
    <div class="col-xl-4 col-md-12">
      <div class="all-consultations-date">
        <div class="completed-content">
          <p>You have completed <br>
            <span class="sp-clr">12</span> Consultations with telimed
          </p>
        </div>
        <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/imagescalendar.svg" alt="">
        <h3>All Consultations</h3>
        <form action="#" class="open-celndr dashboard-select-date">
          <div id="inline_cal"></div>
        </form>
        <a href="dashboard-calender.html" class="btn-primary w-100 mt-3">View all Consultations</a>
      </div>
    </div>
  </div>
</div>

</div>
</div>
</div>
</section>

@endsection