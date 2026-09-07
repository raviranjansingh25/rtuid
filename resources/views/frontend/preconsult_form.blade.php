@extends('frontend.layout.layout2')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cedarville+Cursive&display=swap" rel="stylesheet">

<style>
  @import url('https://fonts.googleapis.com/css2?family=Cedarville+Cursive&display=swap');
  .signature {
    font-family: "Cedarville Cursive", cursive;
        font-size: 24px; /* Adjust font size as needed */
        color: #333; /* Color of the signature */
        /* You can add more styling properties like margin, padding, etc. */
    }
</style>

<section class="practitioner-profle-section py-4 bg-color">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-3 col-lg-4">
        <div class="book-appointment-dr">
          <div class="book-profile-img">
            <img class="profile-b" src="{{isset($user->profile)?url($user->profile):url('/public/vender.png')}}" alt="">
            <div>
              <span class="sp-clr">{{isset($form->first_name)?$form->first_name:$user->name}}</span>
              <h3 class="">{{$user->category_name}} </h3>
              <p><img src="{{url('/public/frontend/')}}/assets/images/like.svg" alt=""> {{$user->ratting}}</p>

            </div>
          </div>

          <div class="prices bg-white p-0">
            <h3 class="Con-info">Consultation Information</h3>
            <h5 class="mb-3">Time Zone
              <span>{{isset($user->timezone)?$user->timezone:'N/A'}}</span>
            </h5>
            <h6><img class="me-2" src="{{url('/public/frontend/')}}/assets/images/Pin.svg" alt=""> {{$user->address}}</h6>
            <div class="my-3 lange d-flex gap-2 align-items-start">
              <img class="mt-2" src="{{url('/public/frontend/')}}/assets/images/world.svg" alt="">
              <div>
                <p>Languages spoken by <span>{{$user->name}}</span> </p>
                @php
                $language = explode(",", $user->language);
                $lang_data = \App\Models\Language::whereIn('id',$language)->get();
                @endphp
                <h3>
                  @forelse($lang_data as $lang)
                  {{$lang->language}}
                  @if(!$loop->last)
                  ,
                  @endif
                  @empty
                  N/A
                  @endforelse
                </h3>
              </div>

            </div>
            <h3 class="box-title">Consult Prices</h3>
            <ul>
              @if(count($user['get_consult'])>0 || count($user['get_package'])>0)
              @foreach($user['get_consult'] as $const)
              <li>{{$const->consult_name}}: <span>${{$const->consult_price}}</span></li>
              @endforeach
              @foreach($user['get_package'] as $const)

              <li>{{$const->title}}: <span>${{$const->price}}</span></li>
              @endforeach
              @else
              <h3 class="box-title">N/A</h3>
              @endif
            </ul>
          </div>
        </div>
      </div>
      <div class="col-xl-9 col-lg-8">
        <div class="row">
          <div class="col-xl-12 col-md-12">
            <a href="javascript:history.back()" class="d-flex align-items-center gap-2"><img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/back-errow.svg" alt=""> Back to {{isset($form->id)?'chat':'Results'}}</a>
            <div class="celender-section h-auto mt-4">
              <h3 class="text-start">{{$edit}} Preconsult Form</h3>
              <form id="form-data" method="post" class="cmn-frm form_consult">
                <input type="hidden" name="user_id" value="{{$user->id}}">
                <input type="hidden" name="sec_id" value="{{$sec_id}}">
                <input type="hidden" name="form_id" value="{{isset($form->id)?$form->id:''}}">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>First Name <span class="text-danger">*</span></label>
                      <input type="text" name="first_name" value="{{isset($form->first_name)?$form->first_name:$file->name}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Last Name <span class="text-danger">*</span></label>
                      <input type="text" name="last_name" value="{{isset($form->last_name)?$form->last_name:$file->last_name}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>DOB <span class="text-danger">*</span></label>
                      <input type="date" name="dob" placeholder="D.O.B." value="{{isset($form->dob)?$form->dob:$file->dob}}" class="valid form-control">
                    </div>
                  </div>
                  
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Address <span class="text-danger">*</span></label>
                      <input type="text" name="address" value="{{isset($form->address)?$form->address:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Height <span class="text-danger">*</span></label>
                      <input type="text" value="{{isset($form->height)?$form->height:''}}" name="height" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Sex: </label>
                      <div class="gender-slct border-ctm d-flex align-items-center">
                        <div class="form-check d-flex align-items-center">
                          <input class="form-check-input w-auto mt-3" type="radio" {{ isset($form->sex) && $form->sex == 'Male' ? 'checked' : '' }} {{ isset($file->gender) && $file->gender == '1' ? 'checked' : '' }} id="gender1" name="sex" value="Male" placeholder="Gender.">
                          <label class="form-check-label" for="gender1">
                            Male
                          </label>
                        </div>
                        <div class="form-check d-flex align-items-center">
                          <input class="form-check-input w-auto mt-3" {{ isset($form->sex) && $form->sex == 'Female' ? 'checked' : '' }} {{ isset($file->gender) && $file->gender == '2' ? 'checked' : '' }} type="radio" id="gender2" name="sex" value="Female" placeholder="Gender.">
                          <label class="form-check-label" for="gender2">
                            Female
                          </label>
                        </div>
                        <div class="form-check d-flex align-items-center">
                          <input class="form-check-input w-auto mt-3" {{ isset($form->sex) && $form->sex == 'Other' ? 'checked' : '' }} {{ isset($file->gender) && $file->gender == '3' ? 'checked' : '' }} type="radio" name="sex" id="gender3" value="Other" placeholder="Gender.">
                          <label class="form-check-label" for="gender3">
                            Other
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <label id="sex-error" class="error" for="sex"></label>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Blood Type</label>
                      <input type="text" name="blood_type" value="{{isset($form->blood_type)?$form->blood_type:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Weight <span class="text-danger">*</span></label>
                      <input type="text" name="weight" value="{{isset($form->weight)?$form->weight:''}}" class="form-control">
                    </div>
                  </div>
                  
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Blood Pressure</label>
                      <input type="text" name="blood_pressure" value="{{isset($form->blood_pressure)?$form->blood_pressure:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Previous Occupations</label>
                      <input type="text" name="previous_occupations" value="{{isset($form->previous_occupations)?$form->previous_occupations:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Current Occupations</label>
                      <input type="text" name="current_occupations" value="{{isset($form->current_occupations)?$form->current_occupations:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Currrent Medications <span class="text-danger">*</span><img data-toggle="tooltip" data-placement="bottom" title="If you are not currently taking any medication you can just type “none”." height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></label>
                      <input type="text" name="current_medications" value="{{isset($form->current_medications)?$form->current_medications:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Next of Kin Name</label>
                      <input type="text" name="next_to_kin" value="{{isset($form->next_to_kin)?$form->next_to_kin:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Next of Kin Contact Number</label>
                      <input type="text" name="next_to_kin_contact" value="{{isset($form->next_to_kin_contact)?$form->next_to_kin_contact:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Current Supplements </label>
                      <input type="text" name="current_supplements" value="{{isset($form->current_supplements)?$form->current_supplements:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Children and Ages</label>
                      <input type="text" name="children_and_age" value="{{isset($form->children_and_age)?$form->children_and_age:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>What other health and/or medical professionals are you currently seeing?</label>
                      <input type="text" name="currently_seeing" value="{{isset($form->currently_seeing)?$form->currently_seeing:''}}" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="seperate_heading">
                      <h4 class="text-start">Health information<img data-toggle="tooltip" data-placement="bottom" title="You can type N/A if the question doesn’t apply for you or if you do not wish to answer at this time" height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></h4>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>What are your current health concerns? <span class="text-danger">*</span></label>
                      <input type="text" name="current_health_concerns" value="{{isset($form->current_health_concerns)?$form->current_health_concerns:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>What are 3 changes you would like to acheive with your health? <span class="text-danger">*</span></label>
                      <input type="text" name="acheive_your_health" value="{{isset($form->acheive_your_health)?$form->acheive_your_health:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Have you had any hospitalisations, surgery, procedures, medical, test, accidents, injuries, C-sections? (What, When and Why?) <span class="text-danger">*</span></label>
                      <input type="text" name="hospitalisations" value="{{isset($form->hospitalisations)?$form->hospitalisations:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Do you have any diagnosed conditions? <span class="text-danger">*</span></label>
                      <input type="text" name="diagnosed_conditions" value="{{isset($form->diagnosed_conditions)?$form->diagnosed_conditions:''}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Please upload any relevant pathology results here.</label>
                      <input type="file" name="image" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="seperate_heading">
                      <h4 class="text-start">Family History</h4>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="filter-chk row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Thyroid problems" id="flexCheckDefault_0" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_0">
                              Thyroid problems
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input"  type="checkbox" name="family_history[]" value="Diabetes" id="flexCheckDefault_1" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_1">
                              Diabetes
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Cancer" id="flexCheckDefault_2" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_2">
                              Cancer
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="High Blood Pressure" id="flexCheckDefault_3" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_3">
                              High Blood Pressure
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="High Cholesterol" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              High Cholesterol
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Health Disease/Attack" id="flexCheckDefault_5" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_5">
                              Health Disease/Attack
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Stroke" id="flexCheckDefault_6" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_6">
                              Stroke
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Blood Clot problems" id="flexCheckDefault_0" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_0">
                              Blood Clot
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Reproductive Issues" id="flexCheckDefault_1" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_1">
                              Reproductive Issues
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Depression" id="flexCheckDefault_2" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_2">
                              Depression
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Seizures" id="flexCheckDefault_3" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_3">
                              Seizures
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Migraine" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Migraine
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Lung Disease" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Lung Disease
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Kidney Disease" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Kidney Disease
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="UTI's" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              UTI's
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Liver/Gallbladder" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Liver/Gallbladder
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Hay Fever/Sinus" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Hay Fever/Sinus
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Allegies" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Allegies
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Asthma" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Asthma
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Eczema" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Eczema
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Coeliac" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Coeliac
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Rheumatism" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Rheumatism
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Arthritis" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Arthritis
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Auto-Innune Disease" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                            Autoimmune Disease
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="Other" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              Other
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="family_history[]" value="None" id="flexCheckDefault_4" onclick="filter()">
                            <label class="form-check-label" for="flexCheckDefault_4">
                              None
                            </label>
                          </div>
                        </div>


                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Have you been on any overseas trips in the last 12 months? </label>
                      <div class="gender-slct border-ctm d-flex align-items-center">
                        <div class="form-check d-flex align-items-center">
                          <input class="form-check-input w-auto mt-3" type="radio" id="trips1" value="Yes" name="overseas_last_month">
                          <label class="form-check-label" for="trips1">
                            Yes
                          </label>
                        </div>
                        <div class="form-check d-flex align-items-center">
                          <input class="form-check-input w-auto mt-3" type="radio" id="trips2" value="No" name="overseas_last_month">
                          <label class="form-check-label" for="trips2">
                            No
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>What vaccinations have you had in the last 3 years?</label>
                      <input type="text" name="vaccinations_3_year" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="filter-chk row">
                        <div class="col-md-12">
                          <div class="form-check">
                            <input class="form-check-input " type="checkbox" name="term_and_condition" value="Yes" id="flexCheckDefault_0" <?php echo isset($form['term_and_condition']) && $form['term_and_condition'] == 'Yes' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="flexCheckDefault_0">
                              I accept all <a href="{{route('terms_condition')}}" class="text-btn">terms &
                                condition. <span class="text-danger">*</span></a>
                            </label>
                          </div>
                        </div>
                        <label id="term_and_condition-error" class="error" for="term_and_condition"></label>
                        <div class="col-md-12">
                          <div class="form-check">
                          <input class="form-check-input " type="checkbox" name="signature" value="Yes" id="flexCheckDefault_0" onclick="sing(this.checked)" <?php echo isset($form['signature']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="flexCheckDefault_0">
                              Generate signature of consent <span class="text-danger">*</span>
                            </label>
                          </div>
                        </div>
                        <label id="signature-error" class="error" for="signature"></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Signature</label>
                      <input type="text" name="signature" readonly value="{{isset($form->signature)?$form->signature:''}}" class="form-control signature" id="usr_sig_set">
                    </div>
                  </div>
                  
                  <div class="col-md-12">
                    <div class="form-group mb-0">
                      <button type="submit" class="btn-primary">Save and Update</button>
                    </div>
                  </div>
                </div>
              </form>

              <!-- <form id="form-data" method="post" class="cmn-frm ">
                @csrf
                <div class="form-group">
                  <label for="Main Concerns">Main Concerns</label>
                  <input type="text" name="main_concerns">
                  <input type="hidden" name="user_id" value="{{$user->id}}">
                </div>
                <div class="form-group">
                  <textarea name="description" id="" cols="30" rows="10"></textarea>
                </div>
                <button type="submit" class="btn-primary w-100 mt-5">Submit</button>
              </form> -->

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
<!-- Add your modal markup -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title">Signature</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
      <div class="form-group">
        <label for="usr">Name:</label>
        <input type="text" class="form-control" id="usr_sig">
      </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick="set_sig()">Submit</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
<script>
  function sing(checked){
    var modal = document.getElementById("myModal");
    if (checked) {
      $(modal).modal('show');
    } else {
      $(modal).modal('hide');
    }
  }

  function set_sig(){
    var sigh = $('#usr_sig').val();
    $('#usr_sig_set').val(sigh);
    $('#myModal').modal('hide');
  }
</script>
<script>
  $("#form-data").validate({
    onfocusout: function(element) {
      $(element).valid();
    },
    highlight: function(element, errorClass) {

    },

    rules: {
      'first_name': {
        required: true
      },
      'last_name': {
        required: true
      },
      'dob': {
        required: true
      },
      'email': {
        required: true
      },
      'address': {
        required: true
      },
      'height': {
        required: true
      },
      'weight': {
        required: true
      },
      'current_medications': {
        required: true
      },
      'term_and_condition': {
        required: true
      },
      'signature': {
        required: true
      },
      'current_health_concerns': {
        required: true
      },
      'acheive_your_health': {
        required: true
      },
      'hospitalisations': {
        required: true
      },
      'diagnosed_conditions': {
        required: true
      },
      

    },
    messages: {
      'first_name': "Please Enter first name.",
      'last_name': "Please enter last name.",
      'dob': "Please enter dob.",
      'email': "Please enter email address.",
      'address': "Please enter address.",
      'height': "Please enter height.",
      'weight': "Please enter weight.",
      'current_medications': "Please enter current medications.",
      'term_and_condition': "Please accept terms and condition.",
      'signature': "Please accept generate signature of consent.",
      'current_health_concerns': "Please enter current health concerns.",
      'acheive_your_health': "Please enter acheive your health.",
      'hospitalisations': "Please enter Have you had any hospitalisations.",
      'diagnosed_conditions': "Please enter Do you have any diagnosed conditions?.",
    },

    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      swal({
            title: "Are you sure you want to save this form?",
            
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
              $.ajax({
                url: "{{ route('consult_form_save') }}",
                type: 'GET',
                data: $('#form-data').serialize(),
                beforeSend: function() {
                  $("#preloader").show();
                },
                success: function(res) {
                  $("#preloader").hide();

                  if (res.status == 1) {

                    swal({
                        title: "Success!",
                        text: res.message,
                        icon: "success",
                        dangerMode: true,
                        buttons: false,
                        timer: 1000
                      })
                      .then(() => {

                        window.location = "{{route('my_sessions')}}";
                      })

                  } else {
                    $("#preloader").hide();
                    swal({
                      icon: 'error',
                      title: 'Oops...',
                      text: res.message,
                    })
                  }
                },
                error: function(error) {
                  $("#preloader").hide();
                  swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                  })
                }
              });
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
    },
  });
</script>


@endsection