@extends('frontend.layout.layout2')
@section('content')
@include('frontend.layout.vender_sidebar')
<style>
  .emergency_services {
    position: fixed;
    bottom: 0px;
    right: 0px;
    z-index: 999;
  }
</style>
<div class="col-md-9 profile-doctor-img">
  <a href="javascript:history.back()" class="align-items-center gap-2"><img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/back-errow.svg" alt=""> Back to My Patients</a>
  <div class="row mt-4 ">
    <div class="col-md-12 col-lg-7">
      <div class="profile-dr  h-auto">
        <div class="profile-img">
          <img src="{{isset($patients->profile)?url($patients->profile):url('/public/user.png')}}" alt="">
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
        <h3 class="d-flex align-items-center gap-2 justify-content-center">{{$patients->name}}, {{$sex}} </h3>

        <a href="#">{{$age}} y</a>

      </div>

      <div class="row mt-4">
        <div class="col-md-12 col-sm-12">
          <div class="bg-white p-4 rounded">
            <div class="row">
              <div class="col-xl-4 col-lg-6 col-md-6 border-end">
                <div class="date-consult">
                  <h5>Date of last consult <span>{{isset($lastsession->booking_date)?$lastsession->booking_date:'No Session'}}</span></h5>
                </div>
              </div>
              <div class="col-xl-8 col-lg-6 col-md-6 border-end">
                <div class="date-consult">
                  <h5>Pending Sessions <span>{{$countsession}}</span></h5>
                </div>
              </div>
            </div>
            <h3 class="section-title my-2">Other practitioners seen on Telimed</h3>
            @forelse($doctor as $sec)
            <div class="list-box">
              <div class="profile-di">
                <a href="{{url('/practitioners-detail/'.get_encrypted_value($sec['get_doctor']->id, true))}}">
                  <img src="{{isset($sec['get_doctor']->profile)?url($sec['get_doctor']->profile):url('/public/vender.png')}}" alt="">
                  <h3>{{$sec['get_doctor']->name}} <span>{{$sec['get_doctor']->category_name}}</span></h3>
                </a>
              </div>

              <div class="d-flex gap-2">
                <a href="{{url('/my-chat/'.$sec['get_doctor']->id)}}" class="btn-primary">Start Chat</a>
              </div>

            </div>
            @empty
            <div class="list-box">
              <div class="profile-di">
                <h3>Consulted Practitioners not available</h3>
              </div>
            </div>
            @endforelse

          </div>
        </div>
      </div>

    </div>

    <div class="col-lg-5 col-md-12">
      <div class="row">
        <div class="col-md-12 mt-5">
          <div class="d-flex justify-content-center gap-2">

            <a href="{{url('/my-chat/'.$patients->id)}}" class="btn-primary">Start Chat with Patient </a>
          </div>
        </div>
        @if(!empty($ConsultForm))
        <div class="col-md-12">
          <div class="box-consult-form">
            <img class="frm-icon" src="{{url('/public/frontend/')}}/assets/images/form.svg" alt="">
            <p>Pre-consult form</p>
            <a target="_blank" href="{{url('/pre-consult-form/'.get_encrypted_value($patients->id, true))}}"><img src="{{url('/public/frontend/')}}/assets/images/download-to-storage-drive.svg" alt=""></a>
          </div>
        </div>
        @endif

        @if(!empty($ConsultNote))
        <div class="col-md-12">
          <div class="box-consult-form">
            <img class="frm-icon" src="{{url('/public/frontend/')}}/assets/images/form.svg" alt="">
            <p>Consult Notes</p>
            <a target="_blank" href="{{url('/consult-Notes/'.get_encrypted_value($patients->id, true))}}"><img src="{{url('/public/frontend/')}}/assets/images/download-to-storage-drive.svg" alt=""></a>
          </div>
        </div>
        @endif

        @if(count($TreatmentPlan) > 0)
        <div class="col-md-12">
          <div class="box-consult-form">
            <img class="frm-icon" src="{{url('/public/frontend/')}}/assets/images/form.svg" alt="">
            <p>Treatment Plan</p>
            <!-- <a target="_blank" href="{{url('/treatment-plan/'.get_encrypted_value($patients->id, true))}}"><img src="{{url('/public/frontend/')}}/assets/images/download-to-storage-drive.svg" alt=""></a> -->
            <a href="#" data-toggle="modal" data-target="#listmood">
              <img src="{{ url('/public/frontend/assets/images/download-to-storage-drive.svg') }}" alt="">
          </a>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>


</div>
</div>



</div>
</section>
<div class="emergency_services"><img data-toggle="tooltip" data-placement="bottom" title="In the case of an emergency or fear the patient is in a life-threatening situation, please contact us via enquiries@telimed.health for the patient's details. Then call 000 if necessary." class="custom-tooltip" src="{{url('/public/emergency_services.jpg')}}" height="70px" width="70px"></div>

<div class="modal fade" id="listmood" tabindex="-1" aria-labelledby="OtpFormModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="Login-heading">
                    <h1 class="modal-title mb-4">Treatment Plan</h1>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="m5.00073 17.5864c-.3905.3906-.39044 1.0237.00012 1.4142s1.02372.3905 1.41421-.0001l5.58524-5.5862 5.5857 5.5857c.3905.3905 1.0237.3905 1.4142 0s.3905-1.0237 0-1.4142l-5.5858-5.5858 5.5854-5.58638c.3904-.39056.3904-1.02372-.0002-1.41421-.3905-.3905-1.0237-.39044-1.4142.00012l-5.5853 5.58627-5.58572-5.58579c-.39052-.39052-1.02369-.39052-1.41421 0-.39053.39053-.39053 1.02369 0 1.41422l5.58593 5.58587z" fill="rgb(0,0,0)" fill-rule="evenodd" />
                    </svg></button>
            </div>
            <div class="modal-body">
               
                <div class="list_data">
                    @forelse($TreatmentPlan as $plan)
                    <div class="box-consult-form">
                      <img class="frm-icon" src="{{url('/public/frontend/')}}/assets/images/form.svg" alt="">
                      <p>Treatment Plan</p>
                      <a target="_blank" href="{{url('/treatment-plan/'.get_encrypted_value($plan->id, true))}}"><img src="{{url('/public/frontend/')}}/assets/images/download-to-storage-drive.svg" alt=""></a>
                      
                    </div>
                    @empty
                    <p style="text-align: center;padding-top: 100px;">No clients available</p>
                    @endforelse
                </div>
    
                
            </div>
        </div>
    </div>
</div>


@endsection