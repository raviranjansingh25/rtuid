@extends('frontend.layout.layout2')
@section('content')

@include('frontend/layout/vender_sidebar')
<div class="col-md-9">
  <div class="row">
    <div class="col-md-12">
      <a href="javascript:history.back()" class="d-flex align-items-center gap-2"><img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/back-errow.svg" alt=""> Back</a>
      <div class="edit-profile-pt dtl-dtl">
        <h3 class="section-title">Verify Profile</h3>
        <form id="verify_save" method="post" class="cmn-frm">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Select Practitioner Type <span class="text-danger">*</span></label>
                <select name="category" id="">

                  <option value="">Select Type</option>
                  @foreach($category as $cat)
                  <option value="{{$cat->id}}" {{ isset($data->category) ? $data->category==$cat->id ? 'selected' : '' : '' }}>{{$cat->search_title}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="dynamic_field1">
              @if(isset($data->id)!="")
              @php
              $pro_image1 = App\Models\UserQualification::where('vender_id',$data->doctor_id)->get();
              @endphp

              @if(count($pro_image1)>0)
              @foreach($pro_image1 as $imgkey1=>$img_data1)
              <div class="row rowdelete1">
                <input type="hidden" name="qualification[{{$imgkey1}}][qualification_id]" value="{{$img_data1->id}}">
                <div class="col-md-12">
                  <h3 class="section-title mt-3">Qualifications <span class="text-danger">*</span><img data-toggle="tooltip" data-placement="bottom" title="Choose your qualification from the drop down menu. If not listed please reach out via the contact us page and we will add it. 
" height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></h3>
                  <div class=" bg-color p-3">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="">Select Qualification<span class="text-danger">*</span></label>
                          <select name="qualification[{{$imgkey1}}][qualification]" id="">
                            <option value="">Select Qualification</option>
                            @foreach($qualification as $qua)
                            <option value="{{$qua->id}}" {{ isset($img_data1->qualification_id) ? $img_data1->qualification_id==$qua->id ? 'selected' : '' : '' }}>{{$qua->title}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label for="">Year Graduated<span class="text-danger">*</span></label>
                          <input type="text" name="qualification[{{$imgkey1}}][year_graduated]" value="{{ isset($img_data1->year_graduated) ? $img_data1->year_graduated : '' }}" placeholder=" Year graduated">
                        </div>
                      </div>
                      <div class="col-1 col-md-1 col-lg-1">
                        <div class="form-group">
                          <?php
                          if ($imgkey1 == 0) { ?>
                            <br><img class="add1" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                          <?php } else { ?>

                            <img height="30px" class="btn_remove1" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt="">
                          <?php } ?>
                        </div>
                      </div>


                      <div class="col-md-12">

                        @if(!empty($img_data1->graduate_doc))

                        <div class="form-group">
                          <label for="">Qualification/Certificate File<span class="text-danger">*</span></label>
                          <input type="file" name="qualification[{{$imgkey1}}][graduate_doc]" value="{{isset($img_data1->graduate_doc)?$img_data1->graduate_doc:''}}" placeholder="Year graduated">
                        </div>

                        <a class="pdf-down" href="{{isset($img_data1->graduate_doc)?url($img_data1->graduate_doc):''}}" download="{{isset($img_data1->graduate_doc)?url($img_data1->graduate_doc):''}}">Graduate Doc.pdf <img src="{{url('/public/frontend/')}}/assets/images/download-pdf.svg" alt="" /></a>
                        @else
                        <div class="form-group">
                          <label for="">Qualification/Certificate File<span class="text-danger">*</span></label>
                          <input type="file" name="graduate_doc" value="{{isset($img_data1->graduate_doc)?$data->graduate_doc:''}}" placeholder="Year graduated" required>
                        </div>
                        @endif

                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              @endforeach
              <input type="hidden" name="varkey1" id="varkey1" value="{{$imgkey1+1}}">
              @else
              <input type="hidden" name="qualification[0][qualification_id]">
              <div class="row rowdelete1">
                <div class="col-md-12">
                  <h3 class="section-title mt-3">Qualifications <span class="text-danger">*</span><img data-toggle="tooltip" data-placement="bottom" title="Choose your qualification from the drop down menu. If not listed please reach out via the contact us page and we will add it. 
" height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></h3>

                  <div class=" bg-color p-3">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="">Select Qualification<span class="text-danger">*</span></label>
                          <select name="qualification[0][qualification]" id="">
                            <option value="">Select Qualification</option>
                            @foreach($qualification as $qua)
                            <option value="{{$qua->id}}">{{$qua->title}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label for="">Year Graduated<span class="text-danger">*</span></label>
                          <input type="text" name="qualification[0][year_graduated]" placeholder="Year graduated">
                        </div>
                      </div>
                      <div class="col-1 col-md-1 col-lg-1">
                        <div class="form-group">
                          <br><img class="add1" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="">Qualification/Certificate File<span class="text-danger">*</span></label>
                        <input type="file" name="qualification[0][graduate_doc]" placeholder="Year graduated" required>
                      </div>


                    </div>
                  </div>
                </div>
              </div>
              @endif
              @else
              <div class="row rowdelete1">
                <input type="hidden" name="qualification[0][qualification_id]">
                <div class="col-md-12">
                  <h3 class="section-title mt-3">Qualifications <span class="text-danger">*</span><img data-toggle="tooltip" data-placement="bottom" title="Choose your qualification from the drop down menu. If not listed please reach out via the contact us page and we will add it. 
" height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></h3>
                  <div class=" bg-color p-3">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="">Select Qualification<span class="text-danger">*</span></label>
                          <select name="qualification[0][qualification]" id="">
                            <option value="">Select Qualification</option>
                            @foreach($qualification as $qua)
                            <option value="{{$qua->id}}">{{$qua->title}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label for="">Year Graduated<span class="text-danger">*</span></label>
                          <input type="text" name="qualification[0][year_graduated]" placeholder="Year graduated">
                        </div>
                      </div>
                      <div class="col-1 col-md-1 col-lg-1">
                        <div class="form-group">
                          <br><img class="add1" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="">Qualification/Certificate File<span class="text-danger">*</span></label>
                        <input type="file" name="qualification[0][graduate_doc]" placeholder="Year graduated" required>
                      </div>

                      <!-- <div class="col-md-12">

                        @if(!empty($data->graduate_doc))

                        <div class="form-group">
                          <label for="">Qualification/Certificate File<span class="text-danger">*</span></label>
                          <input type="file" name="graduate_doc" value="{{isset($data->graduate_doc)?$data->graduate_doc:''}}" placeholder="Year graduated">
                        </div>

                        <a class="pdf-down" href="{{isset($data->graduate_doc)?url($data->graduate_doc):''}}" download="{{isset($data->graduate_doc)?url($data->graduate_doc):''}}">Graduate Doc.pdf <img src="{{url('/public/frontend/')}}/assets/images/download-pdf.svg" alt="" /></a>
                        @else
                        <div class="form-group">
                          <label for="">Qualification/Certificate File<span class="text-danger">*</span></label>
                          <input type="file" name="graduate_doc" value="{{isset($data->graduate_doc)?$data->graduate_doc:''}}" placeholder="Year graduated" required>
                        </div>
                        @endif

                      </div> -->
                    </div>
                  </div>
                </div>
              </div>
              @endif
            </div>


            <div class="col-md-12">
              <h3 class="section-title mt-3">Association details<img data-toggle="tooltip" data-placement="bottom" title="If your modality does not require registration with an association please write NA in this field." height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></h3>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for=""> Association organization name <span class="text-danger">*</span></label>
                <input type="text" name="organization_name" value="{{isset($data->organization_name)?$data->organization_name:''}}" placeholder=" Association organization name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for=""> Association organization membership number <span class="text-danger">*</span></label>
                <input type="text" name="organization_membership_no" value="{{isset($data->organization_membership_no)?$data->organization_membership_no:''}}" placeholder=" Association organization membership number">
              </div>
            </div>
            <div class="col-md-12" style="margin-bottom: 10px;">

              @if(!empty($data->graduate_doc))
              <div class="form-group">
                <label for=""> Membership Number Document </label>
                <input type="file" name="membership_number_doc" value="{{isset($data->membership_number_doc)?$data->membership_number_doc:''}}" placeholder=" Association organization membership number">
              </div>

              <a class="pdf-down" href="{{isset($data->membership_number_doc)?url($data->membership_number_doc):''}}" download="{{isset($data->membership_number_doc)?url($data->membership_number_doc):''}}">Membership Doc.pdf <img src="{{url('/public/frontend/')}}/assets/images/download-pdf.svg" alt="" /></a>
              @else
              <div class="form-group">
                <label for=""> Membership Number Document </label>
                <input type="file" name="membership_number_doc" value="{{isset($data->membership_number_doc)?$data->membership_number_doc:''}}" placeholder=" Association organization membership number">
              </div>

              @endif
            </div><br>


            <div class="dynamic_field">




              @if(isset($data->id)!="")
              @php
              $pro_image = App\Models\InsuranceProviderDetails::where('doctor_id',$data->doctor_id)->get();

              @endphp
              @if(count($pro_image)>0)
              @foreach($pro_image as $imgkey=>$img_data)
              <div class="row rowdelete">
                <input type="hidden" name="policy[{{$imgkey}}][policy_id]" value="{{$img_data->id}}">

                <div class="col-11 col-md-11 col-lg-11">
                  <div class="form-group">
                    <!-- <label>Policy</label> -->
                    <?php
                    if ($imgkey == 0) { ?>
                      <label for="">Indemnity Insurance Provider details <span class="text-danger">*</span><img data-toggle="tooltip" data-placement="bottom" title="If your modality does not require an Indemnity insurance provider please write NA in this field." height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></label>
                    <?php } ?>
                    <input type="text" name="policy[{{$imgkey}}][policy]" value="{{ isset($img_data->provider_name) ? $img_data->provider_name : '' }}" placeholder="Provider Name" required>
                  </div>
                </div>
                <div class="col-1 col-md-1 col-lg-1">
                  <div class="form-group">
                    <?php
                    if ($imgkey == 0) { ?>
                      <br><img class="add" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                    <?php } else { ?>

                      <img height="30px" class="btn_remove" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt="">
                    <?php } ?>

                  </div>
                </div>
              </div>
              <br>
              @endforeach
              <input type="hidden" name="varkey" id="varkey" value="{{$imgkey+1}}">
              @endif
              @else
              <div class="row rowdelete">
                <input type="hidden" name="policy[0][policy_id]" value="">


                <div class="col-11 col-md-11 col-lg-11">
                  <div class="form-group">
                    <label for="">Indemnity Insurance Provider details <span class="text-danger">*</span><img data-toggle="tooltip" data-placement="bottom" title="If your modality does not require an Indemnity insurance provider please write NA in this field." height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></label>
                    <input type="text" name="policy[0][policy]" id="" placeholder="Provider Name" required>
                  </div>
                </div>
                <div class="col-1 col-md-1 col-lg-1">
                  <div class="form-group">
                    <br><img class="add" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                  </div>
                </div>
              </div>
              <br>
              @endif
            </div>

            <div class="dynamic_field3">




              @if(isset($data->id)!="")
              @php
              $pro_image3 = App\Models\VenderOtherDocument::where('vender_id',$data->doctor_id)->get();

              @endphp
              @if(count($pro_image3)>0)
              @foreach($pro_image3 as $imgkey3=>$img_data3)
              <div class="row rowdelete3">
                <input type="hidden" name="document[{{$imgkey3}}][document_id]" value="{{$img_data3->id}}">
                <div class="col-md-11">
                  <div class="form-group">
                    <label for="">Other documents<span class="text-danger">*</span></label>
                    <select name="document[{{$imgkey3}}][upload_type]" id="">
                      <option value="">Select Documents Type</option>
                      <option value="For first aid" {{ isset($img_data3->upload_type) ? $img_data3->upload_type== "For first aid"? 'selected' : '' : '' }}>For first aid</option>
                      <option value="Police Check" {{ isset($img_data3->upload_type) ? $img_data3->upload_type== "Police Check"? 'selected' : '' : '' }}>Police Check</option>
                      <option value="Working with children check" {{ isset($img_data3->upload_type) ? $img_data3->upload_type== "Working with children check"? 'selected' : '' : '' }}>Working with children check</option>
                      <option value="Not applicable to my modality" {{ isset($img_data3->upload_type) ? $img_data3->upload_type== "Not applicable to my modality"? 'selected' : '' : '' }}>Not applicable to my modality</option>
                    </select>
                  </div>
                </div>

                <div class="col-1 col-md-1 col-lg-1">
                  <div class="form-group">
                    <?php
                    if ($imgkey3 == 0) { ?>
                      <br><img class="add3" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                    <?php } else { ?>

                      <img height="30px" style="margin-top: 30px;" class="btn_remove3" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt="">
                    <?php } ?>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Upload ID </label>
                    <input type="file" name="document[{{$imgkey3}}][upload_id]" id="" placeholder="Provider Name">
                  </div>
                  @if(!empty($img_data3->upload_id))

                  <img src="{{url($img_data3->upload_id)}}" width="100px" alt="" />
                  @endif
                </div>

              </div>
              @endforeach
              <input type="hidden" name="varkey3" id="varkey3" value="{{$imgkey3+1}}">
              @else
              <div class="row rowdelete3">
                <input type="hidden" name="document[0][document_id]">
                <div class="col-md-11">
                  <div class="form-group">
                    <label for="">Other documents<span class="text-danger">*</span></label>
                    <select name="document[0][upload_type]" id="">
                      <option value="">Select Documents Type</option>
                      <option value="For first aid">For first aid</option>
                      <option value="Police Check">Police Check</option>
                      <option value="Working with children check">Working with children check</option>
                      <option value="Not applicable to my modality">Not applicable to my modality</option>
                    </select>
                  </div>
                </div>
                <div class="col-1 col-md-1 col-lg-1">
                  <div class="form-group">
                    <br><img class="add3" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Upload ID </label>
                    <input type="file" name="document[0][upload_id]" id="" placeholder="Provider Name">
                  </div>

                </div>

              </div>
              @endif
              @else
              <div class="row rowdelete3">
                <input type="hidden" name="document[0][document_id]">
                <div class="col-md-11">
                  <div class="form-group">
                    <label for="">Other documents<span class="text-danger">*</span></label>
                    <select name="document[0][upload_type]" id="">
                      <option value="">Select Documents Type</option>
                      <option value="For first aid">For first aid</option>
                      <option value="Police Check">Police Check</option>
                      <option value="Working with children check">Working with children check</option>
                      <option value="Not applicable to my modality">Not applicable to my modality</option>
                    </select>
                  </div>
                </div>
                <div class="col-1 col-md-1 col-lg-1">
                  <div class="form-group">
                    <br><img class="add3" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Upload ID </label>
                    <input type="file" name="document[0][upload_id]" id="" placeholder="Provider Name">
                  </div>

                </div>

              </div>
              @endif
            </div>


            <div class="col-xl-3 col-lg-4 col-md-5">
              <button type="submit" class="btn-primary px-5 w-100 my-4">Save and Next</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
</div>
</div>
</section>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script>
  $(document).ready(function() {
    var varkey3 = $("#varkey3").val();

    if (varkey3 != undefined) {
      val3 = varkey3;
    } else {
      val3 = 1;
    }
    valuedata3 = 0;


    $(document).on("click", ".add3", function() {
      var html1 = `<div class="row rowdelete3">
                <input type="hidden" name="document[` + val1 + `][document_id]"">
                <div class="col-md-11">
                  <div class="form-group">
                    <label for="">Other documents<span class="text-danger">*</span></label>
                    <select name="document[` + val1 + `][upload_type]" id="">
                      <option value="">Select Documents Type</option>
                      <option value="For first aid">For first aid</option>
                      <option value="Police Check">Police Check</option>
                      <option value="Working with children check">Working with children check</option>
                      <option value="Not applicable to my modality">Not applicable to my modality</option>
                    </select>
                  </div>
                </div>
                <div class="col-1 col-md-1 col-lg-1">
                  <div class="form-group">
                    <br><img height="30px" class="btn_remove3" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt="">
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Upload ID </label>
                    <input type="file" name="document[` + val1 + `][upload_id]" id="" placeholder="Provider Name">
                  </div>

                </div>

              </div>`;
      // $pmainid = $(this).closest(".main-data").attr('attr-data');
      $(this).parents(".dynamic_field3").append(html1);
      val1++;
      rerun();



    });

    $(document).on('click', '.btn_remove3', function() {
      $(this).parents('.rowdelete3').remove();
    });
  });

  function rerun() {
    $('.dropify').dropify();


  }
</script>
<script>
  $(document).ready(function() {
    var varkey1 = $("#varkey1").val();

    if (varkey1 != undefined) {
      val1 = varkey1;
    } else {
      val1 = 1;
    }
    valuedata1 = 0;


    $(document).on("click", ".add1", function() {
      var html1 = `<div class="row rowdelete1">
                <div class="col-md-12">
                <input type="hidden" name="qualification[` + val1 + `][qualification_id]" value="">
                  <h3 class="section-title mt-3"></h3>
                  <div class=" bg-color p-3">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="">Select Qualification<span class="text-danger">*</span></label>
                          <select name=" qualification[` + val1 + `][qualification]" id="">
                            <option value="">Select Qualification</option>
                            @foreach($qualification as $qua)
                            <option value="{{$qua->id}}">{{$qua->title}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label for="">Year Graduated<span class="text-danger">*</span></label>
                          <input type="text" name="qualification[` + val1 + `][year_graduated]" placeholder="Year graduated">
                        </div>
                      </div>
                      <div class="col-1 col-md-1 col-lg-1"> 
                        <div class="form-group"> 
                          <img height="30px" class="btn_remove1" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""> 
                        </div>
                      </div>
                      <div class="form-group">
                          <label for="">Qualification/Certificate File<span class="text-danger">*</span></label>
                          <input type="file" name="qualification[` + val1 + `][graduate_doc]" placeholder="Year graduated" required>
                        </div>

                      
                    </div>
                  </div>
                </div>
              </div>`;
      // $pmainid = $(this).closest(".main-data").attr('attr-data');
      $(this).parents(".dynamic_field1").append(html1);
      val1++;
      rerun();



    });

    $(document).on('click', '.btn_remove1', function() {
      $(this).parents('.rowdelete1').remove();
    });
  });

  function rerun() {
    $('.start-date').datepicker();


  }
</script>
<script>
  $(document).ready(function() {
    var varkey = $("#varkey").val();

    if (varkey != undefined) {
      val = varkey;
    } else {
      val = 1;
    }
    valuedata = 0;
    var html = `<div class="row rowdelete"> <div class="col-12 col-md-12 col-lg-12"> <div class="row white-box"><input type="hidden" name="policy[` + val + `][policy_id]" value=""> <div class="col-11 col-md-11 col-lg-11"> <div class="form-group"> <input type="text" name="policy[` + val + `][policy]"  class="form-control" required> </div></div><div class="col-1 col-md-1 col-lg-1"> <div class="form-group"> <img height="30px" class="btn_remove" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""> </div></div></div><br>`;

    $(document).on("click", ".add", function() {

      // $pmainid = $(this).closest(".main-data").attr('attr-data');
      $(this).parents(".dynamic_field").append(html);
      val++;
      rerun();



    });

    $(document).on('click', '.btn_remove', function() {
      $(this).parents('.rowdelete').remove();
    });
  });

  function rerun() {
    $('.dropify').dropify();


  }
</script>
<script>
  $("#verify_save").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      'category': {
        required: true
      },
      'qualification': {
        required: true
      },

      'year_graduated': {
        required: true
      },

      'organization_name': {
        required: true,
      },
      'organization_membership_no': {
        required: true,
      },

      'upload_type': {
        required: true,
      },
    },
    messages: {
      'category': "Please select category.",
      'qualification': "Please select qualification.",
      'year_graduated': "Please enter year graduated.",
      'graduate_doc': "Please enter graduate doc.",
      'organization_name': "Please enter organization name.",
      'organization_membership_no': "Please enter organization membership number.",
      'membership_number_doc': "Please enter membership number document.",
      'upload_type': "Please select upload type.",

    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      var form_data = new FormData($('#verify_save')[0]);
      $.ajax({
        url: "{{ route('vender_verify_profile_save') }}",
        type: 'post',
        data: form_data,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $("#preloader").show();
        },
        success: function(res) {
          $("#preloader").hide();

          if (res.status == 1) {
            $('#signmdl').modal('hide');
            swal({
                title: "Success!",
                text: res.message,
                icon: "success",
                dangerMode: true,
                buttons: false,
                timer: 1000
              })
              .then(() => {
                window.location = "{{ route('vender_price_profile') }}"
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
    },
  });
</script>


@endsection