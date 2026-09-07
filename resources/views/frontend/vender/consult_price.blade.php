@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')
<div class="col-md-9">
  <div class="row">
    <div class="col-md-12">
      <a href="javascript:history.back()" class="d-flex align-items-center gap-2"><img class="img-fluid ms-2" src="{{url('/public/frontend/')}}/assets/images/back-errow.svg" alt=""> Back</a>
      <div class="edit-profile-pt dtl-dtl mt-4">
        <h3 class="section-title">Add Consult Price</h3>
        <form id="create_price" method="post" action="{{ route('vender_price_profile_save') }}" class="cmn-frm">
          @csrf
          <div class="dynamic_field">
            @php
            $pro_image = App\Models\VenderConsultPrice::where('vender_id',$getdata->id)->get();
            @endphp
            @if(count($pro_image)>0)
            @if(isset($getdata->id)!="" && count($pro_image)>0)

            @foreach($pro_image as $imgkey=>$img_data)
            <div class="row rowdelete">
              <input type="hidden" name="consult[{{$imgkey}}][consult_id]" value="{{$img_data->id}}">

              <div class="col-md-4">
                <div class="form-group">
                  @if($imgkey == 0)
                  <label for="">Consult Type </label>
                  @endif

                  <select name="consult[{{$imgkey}}][type]" required>
                    <option value="">Select Consult Type</option>

                    <option value="1" {{ isset($img_data->type) ? $img_data->type==1 ? 'selected' : '' : '' }}>Initial</option>
                    <option value="2" {{ isset($img_data->type) ? $img_data->type==2 ? 'selected' : '' : '' }}>Follow up</option>


                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  @if($imgkey == 0)
                  <label for="">Time </label>
                  @endif

                  <select name="consult[{{$imgkey}}][consult_name]" required>
                    <option value="">Select Time</option>
                    @foreach($const_name as $name)
                    <option value="{{$name->id}}" {{ isset($img_data->consult_name_id) ? $img_data->consult_name_id==$name->id ? 'selected' : '' : '' }}>{{$name->title}}</option>
                    @endforeach

                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  @if($imgkey == 0)
                  <label for="">Consult Price </label>
                  @endif
                  <div class="d-flex gap-2">
                    <input type="number" name="consult[{{$imgkey}}][consult_price]" value="{{ isset($img_data->consult_price) ? $img_data->consult_price : '' }}" placeholder="Consult Price" required min="1">

                  </div>
                </div>
              </div>

              <div class="col-md-1">

                @if($imgkey == 0)
                <br>
                <img class="add" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                @else
                <img class="btn_remove" src="{{url('public/frontend/assets/images/remove-filds.svg ')}}" alt="">
                @endif
              </div>
            </div>
            @endforeach
            <input type="hidden" name="varkey" id="varkey" value="{{$imgkey+1}}">
            @endif
            @else
            <div class="row rowdelete">
              <input type="hidden" name="consult[0][consult_id]" value="">
              <div class="col-md-4">
                <div class="form-group">

                  <label for="">Consult Type </label>


                  <select name="consult[0][type]" required>
                    <option value="">Select Consult Type</option>

                    <option value="1">Initial</option>
                    <option value="2">Follow up</option>


                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">Consult Name </label>

                  <select name="consult[0][consult_name]" required>
                    <option value="">Select Consult Name</option>
                    @foreach($const_name as $name)
                    <option value="{{$name->id}}">{{$name->title}}</option>
                    @endforeach

                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Consult Price </label>
                  <div class="d-flex gap-2">
                    <input type="number" name="consult[0][consult_price]" id="" placeholder="Consult Price" required min="1">

                  </div>
                </div>
              </div>

              <div class="col-md-1">
                <br>
                <img class="add" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
              </div>
            </div>
            @endif

          </div>



          <h3 class="section-title my-3">Add Package Price</h3>
          <div class="package">
            @php
            $package = App\Models\VenderPackagePrice::where('vender_id',$getdata->id)->get();
            @endphp
            @if(count($package)>0)
            @if(isset($getdata->id)!="" && count($package)>0)

            @foreach($package as $imgkey1=>$pac_data)
            <input type="hidden" name="package[{{$imgkey1}}][package_id]" value="{{$pac_data->id}}">
            <div class="row package_delete">
              <div class="col-md-4">
                <div class="form-group">
                  @if($imgkey1 == 0)
                  <label for="">Package Name </label>
                  @endif
                  <input type="text" name="package[{{$imgkey1}}][title]" value="{{ isset($pac_data->title) ? $pac_data->title : '' }}" placeholder="Package Name">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  @if($imgkey1 == 0)
                  <label for="">No of Consult</label>
                  @endif
                  <div class="d-flex gap-2">
                    <input type="text" name="package[{{$imgkey1}}][no_of_consult]" value="{{ isset($pac_data->time) ? $pac_data->time : '' }}" placeholder="No of Consult">

                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  @if($imgkey1 == 0)
                  <label for="">Duration</label>
                  @endif
                  <select name="package[{{$imgkey1}}][duration]">
                    <option value="">Select Duration</option>

                    @foreach($const_name as $name)
                    <option value="{{$name->id}}" {{ isset($pac_data->time_duration) ? $pac_data->time_duration==$name->id ? 'selected' : '' : '' }}>{{$name->title}}</option>
                    @endforeach

                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  @if($imgkey1 == 0)
                  <label for="">Package Price</label>
                  @endif
                  <div class="d-flex gap-2">
                    <input type="number" name="package[{{$imgkey1}}][price]" value="{{ isset($pac_data->price) ? $pac_data->price : '' }}" placeholder="Package Price" min="1">

                  </div>
                </div>
              </div>
              <div class="col-md-1">


                @if($imgkey1 == 0)
                <br>
                <img class="add_package" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
                @else
                <img class="btn_remove_package" src="{{url('public/frontend/assets/images/remove-filds.svg ')}}" alt="">
                @endif
              </div>


            </div>
            @endforeach
            <input type="hidden" name="package_id" id="varkeypackage" value="{{$imgkey1+1}}">
            @endif
            @else
            <div class="row package_delete">
              <input type="hidden" name="package[0][package_id]" value="">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">Package Name </label>
                  <input type="text" name="package[0][title]" placeholder="Package Name">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="">No of Consult</label>
                  <div class="d-flex gap-2">
                    <input type="text" name="package[0][no_of_consult]" placeholder="No of Consult">

                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Duration</label>
                  <select name="package[0][duration]">
                    <option value="">Select Duration</option>
                    @foreach($const_name as $name)
                    <option value="{{$name->id}}">{{$name->title}}</option>
                    @endforeach

                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="">Package Price</label>
                  <div class="d-flex gap-2">
                    <input type="number" name="package[0][price]" placeholder="Package Price" min="1">

                  </div>
                </div>
              </div>
              <div class="col-md-1">
                <br>
                <img class="add_package" src="{{url('/public/frontend/')}}/assets/images/add-filds.svg" alt="">
              </div>


            </div>
            @endif
          </div>
          <div class="col-xl-3 col-lg-4 col-md-5">
            <button type="submit" class="btn-primary px-5 w-100 my-4" type="submit">Save</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
</div>
</section>







<div class="modal fade" id="SuccessfullyCreated" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
        <div class="text-center p-4 py-5">
          <h2 class="modal-title text-center mt-4 mb-4">
            Successfully Created
          </h2>
          <p>Your profile created with us.
            Admin will get back to you soon once your credentials have been checked.</p>
          <button class="btn-primary px-5" data-bs-dismiss="modal" aria-label="Close">Save</button>
        </div>
      </div>

    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@if(session()->has('success'))
<script>
  // Use window.onload to ensure the script runs after the page has fully loaded
  window.onload = function() {
    // Display your success alert (you can use a library like SweetAlert for better styling)
    swal({
        title: "Success!",
        text: 'Consultation has been saved',
        icon: "success",
        dangerMode: true,
        buttons: false,
        timer: 1000
      })
      .then(() => {
        window.location = "{{ route('complete_profile') }}"
      })
  };
</script>
@endif
<script>
  $(document).ready(function() {
    var varkey = $("#varkey").val();

    if (varkey != undefined) {
      val = varkey;
    } else {
      val = 1;
    }
    valuedata = 0;

    $(document).on("click", ".add", function() {

      var html = `<div class="row rowdelete">
              <input type="hidden" name="consult[` + val + `][consult_id]" value="">
              <div class="col-md-4">
                <div class="form-group">
                  
                  
                  <select name="consult[` + val + `][type]" required>
                    <option value="">Select Consult Type</option>
                    <option value="1">Initial</option>
                    <option value="2">Follow up</option>

                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  
                  
                  <select name="consult[` + val + `][consult_name]" required>
                    <option value="">Select Consult Name</option>
                    @foreach($const_name as $name)
                    <option value="{{$name->id}}">{{$name->title}}</option>
                    @endforeach

                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  
                  <div class="d-flex gap-2">
                    <input type="number" name="consult[` + val + `][consult_price]" id="" placeholder="Consult Price" required min="1">

                  </div>
                </div>
              </div>
              
              <div class="col-md-1">
                
                <img class="btn_remove" src="{{url('public/frontend/assets/images/remove-filds.svg ')}}" alt="">
              </div>
            </div>`;
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
  $(document).ready(function() {
    var varkeypackage = $("#varkeypackage").val();

    if (varkeypackage != undefined) {
      valpackage = varkeypackage;
    } else {
      valpackage = 1;
    }
    valuedata = 0;

    $(document).on("click", ".add_package", function() {

      var package = `<div class="row package_delete">
                <input type="hidden" name="package[` + valpackage + `][package_id]" value="">
              <div class="col-md-4">
                <div class="form-group">
                  
                  <input type="text" name="package[` + valpackage + `][title]" placeholder="Package Name" required>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  
                  <div class="d-flex gap-2">
                    <input type="text" name="package[` + valpackage + `][no_of_consult]" placeholder="No of Consult" required>

                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  
                  <select name="package[` + valpackage + `][duration]" required>
                    <option value="">Select Duration</option>
                    @foreach($const_name as $name)
                    <option value="{{$name->id}}">{{$name->title}}</option>
                    @endforeach

                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  
                  <div class="d-flex gap-2">
                    <input type="number" name="package[` + valpackage + `][price]" placeholder="Package Price" required min="1">

                  </div>
                </div>
              </div>
              <div class="col-md-1">
                
                <img class="btn_remove_package" src="{{url('public/frontend/assets/images/remove-filds.svg ')}}" alt="">
              </div>

              
            </div>`;
      $(this).parents(".package").append(package);


      val++;
      rerun();
    });

    $(document).on('click', '.btn_remove_package', function() {
      $(this).parents('.package_delete').remove();
    });
  });
</script>


<script>
  $("#create_price").validate({

    onfocusout: function(element) {
      $(element).valid();
    },

    rules: {
      'consult': {
        required: true
      },

    },
    messages: {
      'consult': "Please Enter consult price.",
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "data[Payment][phone]") {
        error.insertAfter(".error-placement");
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      var form_data = new FormData($('#create_price')[0]);
      $.ajax({
        url: "{{ route('vender_price_profile_save') }}",
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
                window.location = "{{ route('complete_profile') }}"
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