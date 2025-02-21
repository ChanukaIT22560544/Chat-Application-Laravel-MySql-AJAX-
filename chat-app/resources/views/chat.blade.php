<!DOCTYPE html>
<html>
<head>
  <title>Group Chat</title>
  <!-- Bootstrap CDN -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body {
      background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
      background-color: #76f17c88;
    }
    
    .chat-container {
      max-width: 800px;
      margin: 20px auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      background: #fff;
      border-radius: 15px;
      overflow: hidden;
    }
    
    .chat-header {
      background-color: #075e54;
      color: #fff;
      padding: 15px;
      font-size: 18px;
      text-align: center;
      position: relative;
    }
    .chat-header .logout-btn {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgb(41, 161, 51);
      font-weight: bolder;
      border-radius: 15px;
    }
    .logout {
      font-weight: bold;
      color: #d41616;
    }
    
    .chat-window {
      background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
      background-color: #27a72dad;
      height: 400px;
      overflow-y: auto;
      padding: 20px;
    }

    .date-separator {
      margin: 15px 0;
      text-align: center;
    }

    .message {
      display: flex;
      margin-bottom: 10px;
    }
    .message.me {
      justify-content: flex-end;
      font-weight: bold;
    }
    .message.other {
      justify-content: flex-start;
    }
    .message .msg-content {
      max-width: 70%;
      padding: 8px 12px;
      border-radius: 15px;
      font-size: 14px;
      line-height: 1.4;
      position: relative;
    }
    .message.me .msg-content {
      background-color: #dcf8c6;
      border-bottom-right-radius: 0;
    }
    .message.other .msg-content {
      background-color: #fff;
      border-top-left-radius: 0;
      box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    }
    .message small {
      display: block;
      margin-top: 5px;
      color: #999;
      font-size: 11px;
    }
    .chat-input {
      border-top: 1px solid #ddd;
      padding: 10px 15px;
      background-color: #14d4549a;
    }
    .chat-input .form-control {
      border: none;
    }
    .chat-input .btn {
      border: none;
      background-color: #075e54;
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="chat-container">
    <div class="chat-header">
      Group Chat 
      <a href="{{ route('logout') }}" class="btn btn-sm logout-btn">
        {{ Auth::user()->name }} / <span class="logout">Logout</span>
      </a>
    </div>

    <div class="chat-window" id="chat-window">
      @php
         $lastDate = null;
         $today = \Carbon\Carbon::now('Asia/Colombo')->format('d M Y');
         $maxId = $messages->max('id') ?? 0;
      @endphp
      @foreach($messages as $msg)
         @php
            $msgTime = \Carbon\Carbon::parse($msg->created_at)->setTimezone('Asia/Colombo');
            $msgDate = $msgTime->format('d M Y');
            $displayDate = ($msgDate === $today) ? 'Today' : $msgDate;
         @endphp

         @if($lastDate !== $displayDate)
            <div class="date-separator"><span class="badge badge-secondary">{{ $displayDate }}</span></div>
            @php $lastDate = $displayDate; @endphp
         @endif

         <div class="message {{ $msg->user_id == auth()->id() ? 'me' : 'other' }}">
           <div class="msg-content">
             @if($msg->user_id != auth()->id())
               <strong>{{ $msg->username }}</strong><br>
             @endif
             {{ $msg->message }}<br>
             <small class="text-muted">{{ $msgTime->format('h:i A') }}</small>
           </div>
         </div>
      @endforeach
    </div>

    <div class="chat-input">
      <form id="message-form">
        @csrf
        <div class="input-group">
          <input type="text" name="message" id="message" class="form-control" placeholder="Type a message" required>
          <div class="input-group-append">
            <button type="submit" class="btn">Send</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- jQuery & Moment.js -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>

  <script>
      var lastMessageId = {{ $maxId }};
      
      $(document).ready(function() {
          $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);
      });

      function fetchMessages(){
          $.ajax({
              url: "{{ route('fetch.messages') }}",
              method: "GET",
              dataType: "json",
              success: function(data){
                  data.forEach(function(msg) {
                      if(msg.id > lastMessageId){
                          lastMessageId = msg.id;
                          var msgTime = moment.utc(msg.created_at).tz("Asia/Colombo").format('h:mm A');
                          var messageHtml = `
                              <div class="message ${msg.user_id == {{ auth()->id() }} ? 'me' : 'other'}">
                                <div class="msg-content">
                                  ${msg.user_id != {{ auth()->id() }} ? `<strong>${msg.username}</strong><br>` : ""}
                                  ${msg.message}<br>
                                  <small class="text-muted">${msgTime}</small>
                                </div>
                              </div>`;
                          $('#chat-window').append(messageHtml);
                          $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);
                      }
                  });
              },
              error: function() {
                  console.log("Error fetching messages");
              }
          });
      }

      setInterval(fetchMessages, 3000);

      $('#message-form').on('submit', function(e) {
          e.preventDefault();
          var message = $('#message').val().trim();
          if(message === '') return;
          $.post("{{ route('send.message') }}", { message: message, _token: $('meta[name="csrf-token"]').attr('content') }, function() {
              $('#message').val('');
              fetchMessages();
          });
      });
  </script>
</body>
</html>
