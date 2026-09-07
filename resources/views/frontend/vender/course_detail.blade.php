@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')
<div class="col-md-9">
  <section class="prgrms course-section p-0">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-md-7">
          <a href="javascript:history.back()"><img src="{{url('/public/frontend/')}}/assets/images/bck-aro.svg" alt=""> Back to Courses</a>
          <div class="prgrms-lft mt-4">
            <div class="slideshow">

              <div class="slide">

                <video width="100%" controls poster="{{isset($data->image)?url($data->image):url('public/noimage.jpg')}}">
                  <source src="{{isset($data->file)?url($data->file):''}}" type="video/mp4">


                </video>
              </div>

            </div>
            <div class="p-4">
              <div class="d-md-flex justify-content-between align-items-center ">
                <h3>{{$data->title}}</h3>
                <div class="d-flex gap-2">
                  <a href="{{url('/practitioner_create_course/'.get_encrypted_value($data->id, true))}}" class="btn-primary ">Edit</a>
                  <button class="btn-primary " onclick="delete_course({{$data->id}})" data-bs-toggle="modal" role="button">Delete</button>
                </div>
              </div>
              <span>{{$data->duration}} - $ {{$data->price}}</span>
              <h5 class="total-purchesed"><span class="text-dark">{{$course_per}}</span> People Purchased</h5>
              @if(!empty($data->tags))
              @php
              $tags = $array = explode(",", $data->tags);
              $tag_data = \App\Models\Tags::whereIn('id',$tags)->where('status',1)->get();
              @endphp
              <ul>

                @foreach($tag_data as $tag)
                <a href="{{route('program',['tag'=>$tag->slug])}}">
                  <li>#{{$tag->title}}</li>
                </a>
                @endforeach
              </ul>
              @endif
              <p>{!!$data->description!!}</p>

            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="prgrms-rht">
            
            @if(!empty($data->course_pdf))
            <div class="hdng mb-2 mt-2">Course Content</div>
            <a class="pdf-down" href="{{isset($data->course_pdf)?url($data->course_pdf):''}}" download="{{isset($data->course_pdf)?url($data->course_pdf):''}}">{{$data->title}}.pdf <img src="{{url('/public/frontend/')}}/assets/images/download-pdf.svg" alt="" /></a>
            @endif
            
            @if(!empty($data->video_link)) 
          <div class="hdng mb-2 mt-2">Course Link</div>
          <a class="pdf-down" target="_blank" href="{{isset($data->video_link)?url($data->video_link):''}}"><div class="pdf_down_div">{{$data->video_link}}</div></a>
          @endif
         
            @if(count($include)>0)
            <h2>Included</h2>
            <ul class="inclds">

              @foreach($include as $in)
              <li>{{$in->title}}</li>
              @endforeach
            </ul>
            @endif
            
          </div>
        </div>
      </div>


    </div>
  </section>
</div>
</div>



</div>
</section>
<script>
  function delete_course(id) {
    swal({
      title: "Are you sure you want to delete this?",
      text: "You will not be able to recover this action!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: "{{ route('vender_course_delete') }}",
          datatType: 'json',
          type: 'GET',
          data: {
            'id': id,
          },
          success: function(res) {

            if (res.status === "1") {
              swal({
                  title: "Deleted!",
                  text: res.message,
                  icon: "success",
                  dangerMode: true,
                  buttons: false,
                  timer: 1000
                })
                .then(() => {
                  window.location = "{{ route('vender_my_course') }}"
                })

            } else {
              swal("Error", "{!!trans('language.delete_already_used') !!}", "error");
            }



          }
        });
        // swal("Deleted!", "{!! trans('language.deleted_successfully') !!}", "success");
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
  }
</script>

@endsection