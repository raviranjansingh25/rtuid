@extends('frontend.layout.layout2')
@section('content')
@include('frontend/layout/vender_sidebar')
<style>
  /* .type-msg {
    display: none;
  } */
  .date-header {
    text-align: center;
  }

  .scrollable-container {
    overflow-y: scroll;
    scrollbar-color: darkgray white;
  }

  .scrollable-container::-webkit-scrollbar {
    width: 4px;
  }

  .scrollable-container::-webkit-scrollbar-thumb {
    background-color: darkgray;
  }

  .scrollable-container::-webkit-scrollbar-track {
    background-color: white;
  }

  .type-msg {
    position: absolute;
    background-color: #ffffff;

  }
</style>
<div class="col-md-9">
  <div class="chat-msg">
    <div class="row h-100">
      <div class="col-xl-4 col-md-4 border-end h-100" style="padding-right: 0px;">
        <div class="srch-hdr cmn-frm ">
          <div class="form-group">
            <input type="text" name="" placeholder="Search" id="" class="searchThread">
          </div>
        </div>
        <div class="list-chat ">
          <ul class="chat-list">

          </ul>
        </div>
      </div>
      <div class="col-xl-8 col-md-8 ps-0">

        <div class="user_profile_show">
          @if(!empty($user_data))
          <div class="user-chat-hdr">
            <div class="profile-chat">
              <img src="{{isset($user_data->profile)?url($user_data->profile):url('/public/vender.png')}}" alt="">
              <h3>{{$user_data->name}}</h3>
            </div>


            <div class="d-flex gap-4">
              @if($user_chat == 1)
              <a class="btn-border" href="{{url('/video/'.$ROOM_ID)}}">Join Call Now</a>
              </a>
              @endif
              <a class="btn-border" onclick="pre_form({{$user_data->id}})" href="#" type="button">Preconsult form</a>

            </div>
          </div>
          @endif
        </div>


        <div class="chat-body mt-5 px-2" style="height:600px;">
          <div class="chat-history-body  scrollable-container" id="chat-details" style="height: 100%; overflow-y: scroll;">
            @if(!empty($item))
            @php
            // Group chat messages by date
            $groupedChatList = $chat_list->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
            });
            @endphp

            @forelse($groupedChatList as $date => $messages)
            <!-- Display the date header -->
            <div class="date-header">
              <strong>{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</strong>
            </div>

            @foreach($messages as $list)
            @if($list->from_id == $user_data['id'])
            <div class="left-msg">
              <div class="send-msg">
                <p>{{ $list->message }}</p>
              </div>
              <span>{{ date("h:i A", strtotime($list->created_at)) }}</span>
            </div>
            @else
            <div class="right-msg">
              <div class="send-msg">
                <p>{{ $list->message }}</p>
              </div>
              <span>{{ date("h:i A", strtotime($list->created_at)) }}</span>
            </div>
            @endif
            @endforeach
            @empty
            <p>Chat not found</p>
            @endforelse
            @endif


          </div>

          <div class="type-msg">

            <input type="hidden" class="file_type" value="TEXT" />
            <input type="hidden" class="message_file" value="" />
            <input type="hidden" class="message_for" value="" />


            <div id="preview-image_show" style="display: none; width:100px;" class="send-msg">
              <p id="preview-image1"><img id="preview-image" height="50px" style="display: none;"></p>
              <p id="preview-icon1"><img id="preview-icon" height="50px" style="display: none;"></p>
            </div>
            <input type="text" name="" placeholder="Message..." class=" message-input" id="message-input">

            <button class="msg-send-icon sendChat sendMessage" data-type="Chat" type="button"><img src="{{url('/public/frontend/')}}/assets/images/send.svg" alt=""><img data-toggle="tooltip" data-placement="bottom" title="This chat will be opened 2 days prior and up to 5 days after the consultation has taken place." height="15px;" style="margin-top: -20px;" class="custom-tooltip" src="https://telimed.health/public//frontend/assets/images/tooltip.svg" alt=""></button>
            <div class="choose-img">
              <label for="attach-doc">
                <img src="{{url('/public/frontend/')}}/assets/images/paper-pin.svg" alt="Paper Pin">
              </label>
              <input type="file" id="attach-doc" style="display: none;">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</section>
<div class="modal fade my-patient-cons-form" id="pre_form" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="mdl-close" data-bs-dismiss="modal" aria-label="Close"> <img class="img-fluid" src="{{url('/public/frontend/')}}/assets/images/close.svg" alt=""></button>
        <div class="rating-modal">
          <h2 class="modal-title text-center"> Preconsult form </h2>
          <div class="row pre_form">

          </div>


        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{asset('public/socket.io.min.js')}}"></script>
<script>
  const fileInput = document.getElementById('attach-doc');
  const previewImage = document.getElementById('preview-image');
  const previewIcon = document.getElementById('preview-icon');
  const previewImage1 = document.getElementById('preview-image1');
  const previewIcon1 = document.getElementById('preview-icon1');
  const previewIimage_show = document.getElementById('preview-image_show');
  const previewContainer = document.getElementById('preview-container'); // Add a container to display the preview

  fileInput.addEventListener('change', function() {
    const selectedFile = this.files[0];

    if (!selectedFile) {
      return;
    }

    const reader = new FileReader();
    reader.onload = function(event) {
      // Hide both preview elements initially
      previewImage.style.display = 'none';
      previewIcon.style.display = 'none';
      previewImage1.style.display = 'none';
      previewIcon1.style.display = 'none';

      // Determine the file type and show the appropriate preview
      if (selectedFile.type.startsWith('image/')) {
        previewImage.src = event.target.result;
        previewImage.style.display = 'block';
        previewImage1.style.display = 'block';
      } else if (selectedFile.type === 'application/pdf') {
        previewIcon.src = "{{url('/public/')}}/pdf.png"; // Replace with your PDF icon path
        previewIcon.style.display = 'block';
        previewIcon1.style.display = 'block';
      } else if (selectedFile.type === 'application/msword' || selectedFile.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
        previewIcon.src = "{{url('/public/')}}/word.png"; // Replace with your Word icon path
        previewIcon.style.display = 'block';
        previewIcon1.style.display = 'block';
      } else {
        previewIcon.src = "{{url('/public/')}}/file.png"; // Replace with your generic file icon path
        previewIcon.style.display = 'block';
        previewIcon1.style.display = 'block';
      }
      previewIimage_show.style.display = 'block';
    };

    // Read the file as a data URL
    reader.readAsDataURL(selectedFile);

    // Reset the file input value to ensure change event triggers on re-selection
    // fileInput.value = '';
  });
</script>
<script>
  $(document).ready(function() {
    $(".chat-history-body").animate({
      scrollTop: $('.chat-history-body').prop("scrollHeight")
    }, 1000);
  });
</script>
<script>
  //  const socket = io("http://localhost:3120");
  const socket = io("https://telimed.health:3120");

  const SENDER_ID = parseInt("{{auth()->guard('vender')->user()->id}}");
  var RECEIVER_ID = '35';
  var ROOM_ID = '1';

  var page = 1;
  var video_call_url = "{{route('vender_video')}}";
  /* Connect */
  socket.emit("CONNECT", {
    senderId: SENDER_ID
  });
  socket.on('CONNECT_RESPONSE', (res) => {
    // console.log(res, '-----------------');
  });

  /* End Connect */


  /* Thread list */

  function threadList(SENDER_ID) {
    socket.emit("THREADS_LIST", {
      senderId: SENDER_ID
    });
  }

  // var ROOM_ID = "{{ $RECEIVER_ID }}";

  // if (ROOM_ID != '') {
  //   $('#chat-details').html('');
  //   mesageList = '';
  //   ROOM_ID = "{{ $ROOM_ID }}"; // Use Blade syntax to echo Laravel variable
  //   RECEIVER_ID = "{{ $RECEIVER_ID }}";
  //   socket.emit("CHAT_LIST", {
  //     senderId: SENDER_ID,
  //     roomId: ROOM_ID,
  //     page: page
  //   });
  // }

  threadList(SENDER_ID);

  $(document).on('keyup', '.searchThread', function() {
    let search = $(this).val();
    socket.emit("THREADS_LIST", {
      senderId: SENDER_ID,
      search: search
    });
  });

  socket.on('THREADS_LIST_RESPONSE', (res) => {
    let result = JSON.parse(res);
    console.log(result, '-------------');
    if (result.status == true) {
      let html = ``;
      result.data.data.map(function(data) {


        var active = '';
        if (parseInt(ROOM_ID) == parseInt(data.id)) {
          active = 'active';
        }
        var receiver_id = '';
        var full_name = '';
        var user_name = '';
        var name = '';
        var profile = '';
        var is_online = 'offline';
        var is_read = '';
        if (parseInt(data.total_unread) > 0) {
          is_read = 'active';
        }

        if (data.type == "SINGLE") {
          if (parseInt(data.chatuser[0].user_id) != parseInt(SENDER_ID)) {
            receiver_id = data.chatuser[0].get_user.id ? data.chatuser[0].get_user.id : data.chatuser[0].get_user.id;
            user_name = data.chatuser[0].get_user.name ? data.chatuser[0].get_user.name : data.chatuser[0].get_user.email;
            //name = data.chatuser[0].get_user.name ? data.chatuser[0].get_user.name : data.chatuser[0].get_user.email;
            //user_name = data.chatuser[0].get_user.user_name ? data.chatuser[0].get_user.user_name : data.chatuser[0].get_user.email;
            // user_name = '@' + user_name;
            profile = data.chatuser[0].get_user.profile_image ? data.chatuser[0].get_user.profile_image : data.chatuser[0].get_user.profile_image;
            if (parseInt(data.chatuser[0].get_user.is_online) == 1) {
              is_online = 'online'
            }
          }
          if (parseInt(data.chatuser[1].user_id) != parseInt(SENDER_ID)) {
            receiver_id = data.chatuser[1].get_user.id ? data.chatuser[1].get_user.id : data.chatuser[1].get_user.id;
            user_name = data.chatuser[1].get_user.name ? data.chatuser[1].get_user.name : data.chatuser[1].get_user.email;
            //user_name = data.chatuser[1].get_user.user_name ? data.chatuser[1].get_user.user_name : data.chatuser[1].get_user.email;
            // user_name = '@' + full_name;
            name = data.chatuser[1].get_user.name ? data.chatuser[1].get_user.name : data.chatuser[1].get_user.email;
            profile = data.chatuser[1].get_user.profile_image ? data.chatuser[1].get_user.profile_image : data.chatuser[1].get_user.profile_image;
            if (parseInt(data.chatuser[1].get_user.is_online) == 1) {
              is_online = 'online'
            }
          }
        }
        html += `
        
        <li class="thread_details ${active}" data-room_id="${data.id}" data-receiver_id="${receiver_id}"  data-type="${data.type}" data-name="${user_name}" data-user_name="${user_name}" data-user_profile="${profile ? profile: "{{url('/public/vender.png')}}"}">
        
        <img src="${profile ? profile: "{{url('/public/vender.png')}}"}" alt="">
              <h3>${user_name}<span>${data.last_message}</span><span>${data.updated_at}</span></h3>
            </li>
        `;


        user_name = '';
        full_name = '';
        name = '';
        profile = '';
        is_online = 'offline';

      })
      //  console.log(html);
      $('.chat-list').html(html);
    } else {
      let html = `<h6 class="text-muted mb-0 not_found">${result.message}</h6>`;

      $('.chat-list').html(html);
    }
  });
  /* end of tread */
  var timezone = "{{$timezone}}";
  var setDate = '';
  var mesageList = '';

  function dateFormate(date) {
    const day = new Date(date);
    const m = ["January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];
    const str_op = day.getDate() + ' ' + m[day.getMonth()] + ' ' + day.getFullYear();
    return str_op;
  }

  function convertTo12HourFormat(time) {
    const [hourStr, minute] = time.split(':');
    let hour = parseInt(hourStr, 10);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12 || 12; // Convert hour from 24-hour to 12-hour format
    return `${hour}:${minute} ${ampm}`;
  }


  $(document).on('click', '.thread_details', function() {
    RECEIVER_ID = $(this).data('receiver_id');
    TYPE = $(this).data('type');
    ROOM_ID = $(this).data('room_id');
    var user_name = $(this).data('user_name');
    var user_profile = $(this).data('user_profile');
    var name = $(this).data('name');

    $('.thread_details').removeClass('active');

    // Add the 'active' class to the clicked element
    $(this).addClass('active');

    $('#chat-details').html('');
    mesageList = '';
    socket.emit("CHAT_LIST", {
      senderId: SENDER_ID,
      roomId: ROOM_ID,
      page: page
    });

    // Make an AJAX request to check if the "Join Call Now" button should be shown
    $.ajax({
      url: '/check-chat-availability',
      type: 'GET',
      data: {
        user_id: RECEIVER_ID,
        vender_id: SENDER_ID
      },
      success: function(response) {
        var joinCallButton = '';
        if (response.canJoin === 1) {
          joinCallButton = ` <a class="btn-border"  href="${video_call_url}/${ROOM_ID}" >Join Call Now</a>`;
        }
        var html10 = `
                <div class="user-chat-hdr">
                    <div class="profile-chat">
                      <img src="${user_profile}" alt="">
                      <h3>${name}</h3>
                    </div>
                    <div class="d-flex gap-4">
                    
                   ${joinCallButton}
                    </a>
                      <a class="btn-border"  onclick="pre_form(${RECEIVER_ID})" href="#" type="button">Preconsult form</a>
                      
                    </div>
                </div>`;
        $('.user_profile_show').html(html10);
        $('#messagebox').show();
      },
      error: function() {
        console.log('Error fetching chat availability');
      }
    });

  });
  socket.on('READ_MESSAGE_RESPONSE', (res) => {
    $('.readChat' + ROOM_ID).removeClass('active');
  });

  socket.on('CHAT_LIST_RESPONSE', (res) => {
    let result = JSON.parse(res);
    $('.type-msg').show();

    socket.emit("READ_MESSAGE", {
      senderId: SENDER_ID,
      roomId: ROOM_ID,
      messageId: 0,
      type: 'All'
    });

    if (result.status == true) {
      var html5 = '';
      if (parseInt(result.data.data.last_page) != parseInt(page)) {
        html5 = '<div class="loadmore text-center">loadmore</div><br/>';
      }

      const arr = result.data.data.data;
      var html4 = '';
      var setDate = ''; // To track and group messages by date
      arr.sort((a, b) => a.id - b.id); // Sorting messages by ID to display in correct order
      arr.map(function(data) {
        var html1 = '';
        var html2 = '';
        var html0 = '';

        var message = data.message;
        var file = '';
        if (data.file_type == 'IMAGE') {
          file = `<a href="${data.file}" target="_blank"><img src="${data.file}" style="width:100px"></a><br/>`;
        } else if (data.file_type == 'VIDEO') {
          file = `<video width="100px" target="_blank" controls><source src="${data.file}" type="video/mp4"></video><br/>`;
        } else if (data.file_type == 'PDF') {
          file = `<a class="pdf-down" target="_blank" href="${data.file}"><img src="{{url('/public/')}}/pdf.png" height="50px" alt="" /></a><br/>`;
        } else if (data.file_type == 'DOCS') {
          file = `<a class="pdf-down" target="_blank" href="${data.file}"><img src="{{url('/public/')}}/docs.png" height="50px" alt="" /></a><br/>`;
        }

        // Group by date logic
        if (setDate != data.date) {
          setDate = data.date;
          html0 = `<div class="col-12 text-center"><div class="btn btn-success btn-sm">${dateFormate(data.date)}</div></div>`;
        } else {
          html0 = '';
        }

        // Sent message
        if (parseInt(data.from_id) == parseInt(SENDER_ID)) {
          html1 = `<div class="right-msg">
                    <div class="send-msg">
                      <p>${file}${message}</p>
                    </div>
                    <span>${new Date(data.time).toLocaleString('en-US', { hour: '2-digit', minute: '2-digit' })}</span>
                  </div>`;

          if (parseInt(data.tip) > 0) {
            html1 += `<span>Tip Sent: ${data.tip}</span>`;
          }
        }

        // Received message
        if (parseInt(data.to_id) == parseInt(SENDER_ID)) {
          html2 = `<div class="left-msg">
                    <div class="send-msg">
                      <p>${file}${message}</p>
                    </div>
                    <span>${new Date(data.to_time).toLocaleString('en-US', { hour: '2-digit', minute: '2-digit' })}</span>
                  </div>`;
        }

        html4 += html0 + html1 + html2; // Append each message block
      });

      mesageList = html4 + mesageList; // Prepend the new messages

      $('#chat-details').html(html5 + mesageList);

      if (page == 1) {
        $(".chat-history-body").animate({
          scrollTop: $('.chat-history-body').prop("scrollHeight")
        }, 1000);
      }

    } else {
      let html = `<h6 class="text-muted mb-0 not_found">${result.message}</h6>`;
      $('#chat-details').html(html);
    }
  });




  $(document).on('change', '#attach-doc', function() {
    var form = new FormData();
    form.append("attachment", $(this)[0].files[0]);

    var settings = {
      "url": "{{url('api/fileUpload')}}",
      "method": "POST",
      "timeout": 0,
      "headers": {},
      "processData": false,
      "mimeType": "multipart/form-data",
      "contentType": false,
      "data": form
    };

    $.ajax(settings).done(function(response) {
      var data = JSON.parse(response);
      if (data.success == true) {
        $('.message_file').val(data.data.image);
        $('.file_type').val(data.data.type);
      }
    });

  });


  var RECEIVER_ID = parseInt("{{isset($user_data->id)?$user_data->id:''}}");
  var ROOM_ID = parseInt("{{isset($ROOM_ID)?$ROOM_ID:''}}");
  @if($ROOM_ID)
  socket.emit("CHAT_LIST", {
    senderId: SENDER_ID,
    roomId: ROOM_ID,
    page: page
  });
  @endif

  $(document).keypress(function(e) {
    var key = e.which;
    if (key == 13) // the enter key code
    {


      var message = $('#message-input').val();
      var message_file = $('.message_file').val();
      if (message == '' && message_file == '') {
        // alert('Please enter the message');
        return false
      } else {
        type = $('.file_type').val();
        message_file = $('.message_file').val();
        socket.emit("SEND_MESSAGE", {
          senderId: parseInt(SENDER_ID),
          receiveId: parseInt(RECEIVER_ID),
          roomId: parseInt(ROOM_ID),
          message: message,
          messageType: type,
          messageFile: message_file,
          tipId: 0
        });
        $('.message-input').val('');
        $('.file_type').val('TEXT');
        $('.message_file').val('');
        document.getElementById('preview-image_show').style.display = 'none';
        const fileInput = document.getElementById('attach-doc');
        fileInput.value = '';
      }
    }
  });

  $(document).on('click', '.sendMessage', function() {
    var message = $('#message-input').val();

    var message_file = $('.message_file').val();
    if (message == '' && message_file == '') {
      alert('Please enter the message');
      return false
    } else {
      type = $('.file_type').val();
      console.log({
        senderId: parseInt(SENDER_ID),
        receiveId: parseInt(RECEIVER_ID),
        roomId: parseInt(ROOM_ID),
        message: message,
        messageType: type,
        messageFile: message_file,
        tipId: 0
      });
      message_file = $('.message_file').val();
      socket.emit("SEND_MESSAGE", {
        senderId: parseInt(SENDER_ID),
        receiveId: parseInt(RECEIVER_ID),
        roomId: parseInt(ROOM_ID),
        message: message,
        messageType: type,
        messageFile: message_file,
        tipId: 0
      });
      $('.message-input').val('');
      $('.file_type').val('TEXT');
      $('.message_file').val('');
      document.getElementById('preview-image_show').style.display = 'none';
      const fileInput = document.getElementById('attach-doc');
      fileInput.value = '';
    }
  });



  let lastMessageDate = null; // Variable to store the last displayed message date

  socket.on('SEND_MESSAGE_RESPONSE', (res) => {
    let result = JSON.parse(res);
    console.log(result, 'Response Data');
    $('.not_found').hide();

    if (result.status === true) {
      let data = result.data.data;
      console.log("Message Data:", data);

      let file = '';
      if (data.file_type === 'IMAGE') {
        file = `<a href="${data.file}" target="_blank"><img src="${data.file}" style="width:100px"></a><br/>`;
      } else if (data.file_type === 'VIDEO') {
        file = `<video width="100px" target="_blank" controls><source src="${data.file}" type="video/mp4"></video><br/>`;
      } else if (data.file_type === 'PDF') {
        file = `<a class="pdf-down" target="_blank" href="${data.file}"><img src="{{url('/public/')}}/pdf.png" height="50px" alt="" /></a><br/>`;
      } else if (data.file_type === 'DOCS') {
        file = `<a class="pdf-down" target="_blank" href="${data.file}"><img src="{{url('/public/')}}/docs.png" height="50px" alt="" /></a><br/>`;
      }

      // Get the created_at timestamp and format it to 'YYYY-MM-DD'
      let messageDate = new Date(data.created_at).toISOString().split('T')[0];
      // alert(data.count);
      // Get today's date and format it to 'YYYY-MM-DD'
      // let todayDate = new Date().toISOString().split('T')[0];

      // Initialize variable to hold the grouped HTML
      let htmlGrouped = '';

      // If the message date is not today's date, add the date header
      if (data.count == 0) {
        htmlGrouped += `<div class="col-12 text-center date-header" data-date="${messageDate}">
                                <div class="btn btn-success btn-sm">${dateFormate(messageDate)}</div>
                            </div>`;
        lastMessageDate = messageDate; // Update last displayed date
      }

      // Generate message HTML based on sender/receiver
      let messageHtml = '';
      if (parseInt(data.from_id) === parseInt(SENDER_ID)) {
        // Message sent by the current user
        messageHtml = `<div class="right-msg">
                                <div class="send-msg">
                                    <p>${file}${data.message}</p>
                                </div>
                                <span>${new Date(data.time).toLocaleString('en-US', { hour: '2-digit', minute: '2-digit' })}</span>
                            </div>`;

        if (parseInt(data.tip) > 0) {
          messageHtml += `<span>Tip Sent: ${data.tip}</span>`;
        }
      } else if (parseInt(data.to_id) === parseInt(SENDER_ID)) {
        // Message received by the current user
        messageHtml = `<div class="left-msg">
                                <div class="send-msg">
                                    <p>${file}${data.message}</p>
                                </div>
                                <span>${new Date(data.to_current_time).toLocaleString('en-US', { hour: '2-digit', minute: '2-digit' })}</span>
                            </div>`;
      }

      // Append the message HTML to the grouped date
      htmlGrouped += messageHtml;

      // Append the grouped HTML (date and message) to the chat
      $('#chat-details').append(htmlGrouped);

      // Scroll to the bottom of the chat
      $(".chat-history-body").animate({
        scrollTop: $('.chat-history-body').prop("scrollHeight")
      }, 1000);
    }
  });
</script>
<script>
  function pre_form(id) {


    $.ajax({
      url: "{{ route('get_consult_form') }}",
      type: 'GET',
      data: {
        'id': id,
      },
      beforeSend: function() {
        $("#preloader").show();
      },
      success: function(res) {
        $("#preloader").hide();

        if (res.status == 1) {
          $(".pre_form").html(res.pre_form);

          $('#pre_form').modal('show');
        } else {
          swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Pre consult form not available!',
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
  }
</script>
@endsection