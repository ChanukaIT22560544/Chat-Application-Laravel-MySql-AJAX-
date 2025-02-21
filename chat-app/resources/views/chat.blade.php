<!DOCTYPE html>
<html>
<head>
  <title>Group Chat</title>
  <!-- Bootstrap CDN -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
      background-color: #76f17c88;
    }
    
    .chat-container {
      max-width: 800px;
      max-height: 1000px;
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
      overflow-y: scroll;
      padding: 20px;
    }
    /* Date separator styling */
    .date-separator {
      margin: 15px 0;
    }
    .date-separator .badge {
      font-size: 12px;
      padding: 5px 10px;
    }
    /* Message container styles */
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
      padding: 1px 5px;
      border-radius: 15px;
      font-size: 14px;
      line-height: 1.4;
      position: relative;
    }
    /* Own messages (green bubble) */
    .message.me .msg-content {
      background-color: #dcf8c6;
      border-bottom-right-radius: 0;
    }
    /* Messages from others (white bubble) */
    .message.other .msg-content {
      background-color: #fff;
      border-top-left-radius: 0;
      box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    }
    /* Timestamp styling */
    .message small {
      display: block;
      margin-top: 5px;
      color: #999;
      font-size: 11px;
    }
    /* Chat input area */
    .chat-input {
      border-top: 1px solid #ddd;
      padding: 10px 15px;
      background-color: #14d4549a;
    }
    .chat-input .input-group {
      border-radius: 50px;
      overflow: hidden;
    }
    .chat-input .form-control {
      border: none;
      border-radius: 0;
      box-shadow: none;
    }
    .chat-input .btn {
      border: none;
      background-color: #075e54;
      color: #fff;
      border-radius: 0;
    }
    .msg {
      justify-content: center;
      padding: 10px;
    }
  </style>
</head>
<body>
  <div class="chat-container">
    <!-- Chat Header -->
    <div class="chat-header">
      Group Chat 
      <a href="{{ route('logout') }}" class="btn btn-sm logout-btn">
        {{ $user->name }} / <span class="logout">Logout</span>
      </a>
    </div>
    <!-- Chat Window -->
    <div class="chat-window" id="chat-window">
      @php
         $lastDate = null;
         // Today's date in Asia/Colombo timezone formatted as 'd M Y'
         $today = \Carbon\Carbon::now('Asia/Colombo')->format('d M Y');
         // Set lastMessageId to the maximum message id from the messages collection (if available)
         $maxId = $messages->max('id') ?? 0;
      @endphp
      @foreach($messages as $msg)
         @php
            // Convert message time to Asia/Colombo timezone on the server side using Carbon
            $msgTime = \Carbon\Carbon::parse($msg->created_at)->setTimezone('Asia/Colombo');
            $msgDate = $msgTime->format('d M Y');
            // If message date is today, show 'Today'
            $displayDate = ($msgDate === $today) ? 'Today' : $msgDate;
         @endphp

         @if($lastDate !== $displayDate)
            <div class="date-separator text-center">
              <span class="badge badge-secondary">{{ $displayDate }}</span>
            </div>
            @php $lastDate = $displayDate; @endphp
         @endif

         <div class="message {{ $msg->user_id == auth()->id() ? 'me' : 'other' }}">
           <div class="msg-content">
             @if($msg->user_id != auth()->id())
               <span>{{ $msg->username }}</span><br>
             @endif
             <strong class="msg">{{ $msg->message }}</strong><br>
             <small class="text-muted">
               {{ $msgTime->format('h:i A') }}
             </small>
           </div>
         </div>
      @endforeach
    </div>
    <!-- Chat Input -->
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

  <!-- jQuery, Moment.js and Moment Timezone via CDN -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- Moment.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <!-- Moment Timezone with Data -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
  <script>
      // Set lastMessageId to the maximum id from initial load to prevent duplicates
      var lastMessageId = {{ $maxId }};
      
      // On document ready, scroll to the bottom of the chat window
      $(document).ready(function() {
          $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);
      });

      // Function to poll the server for new messages
      function fetchMessages(){
          $.ajax({
              url: "{{ route('fetch.messages') }}",
              method: "GET",
              dataType: "json",
              success: function(data){
                  data.forEach(function(msg) {
                      // Only process messages that haven't been displayed yet
                      if(msg.id > lastMessageId){
                          lastMessageId = msg.id; // Update last message id

                          // Parse the UTC timestamp and convert to Asia/Colombo timezone
                          var msgTime = moment.utc(msg.created_at, "YYYY-MM-DD HH:mm:ss").tz("Asia/Colombo");
                          var msgDate = msgTime.format('DD MMM YYYY');
                          var today = moment.tz("Asia/Colombo").format('DD MMM YYYY');
                          var displayDate = (msgDate === today) ? 'Today' : msgDate;

                          // Check if we need to insert a date separator
                          var lastDateSeparator = $('#chat-window .date-separator').last();
                          var lastDateText = lastDateSeparator.length ? lastDateSeparator.text().trim() : '';
                          if(lastDateText !== displayDate) {
                              var dateSeparatorHtml = '<div class="date-separator text-center my-2">' +
                                                        '<span class="badge badge-secondary">' + displayDate + '</span>' +
                                                        '</div>';
                              $('#chat-window').append(dateSeparatorHtml);
                          }
                          
                          // Build message HTML based on sender
                          var messageHtml = '';
                          if(msg.user_id == {{ auth()->id() }}){
                              messageHtml = '<div class="message me">' +
                                                '<div class="msg-content">' +
                                                  '<strong class="msg">' + msg.message + '</strong><br>' +
                                                  '<small class="text-muted">' + msgTime.format('h:mm A') + '</small>' +
                                                '</div>' +
                                              '</div>';
                          } else {
                              messageHtml = '<div class="message other">' +
                                                '<div class="msg-content">' +
                                                  '<span>' + msg.username + '</span><br>' +
                                                  '<strong class="msg">' + msg.message + '</strong><br>' +
                                                  '<small class="text-muted">' + msgTime.format('h:mm A') + '</small>' +
                                                '</div>' +
                                              '</div>';
                          }
                          // Append the new message to the chat window
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

      // Poll the server every 3 seconds
      setInterval(fetchMessages, 50);

      // Handle message submission via AJAX (without optimistic update)
      $('#message-form').on('submit', function(e) {
          e.preventDefault();
          var message = $('#message').val().trim();
          if(message === '') return;
          
          // Clear the input immediately
          $('#message').val('');
          
          // Send the message via AJAX
          $.ajax({
    url: "https://chat-application-laravel-mysql-a-production-469c.up.railway.app/send-message", // Use full URL here
    method: "POST",
    data: {
        _token: "{{ csrf_token() }}",
        message: message
    },
    success: function(response) {
        console.log('Message sent successfully:', response);
    },
    error: function(xhr, status, error) {
        console.log('Error sending message:', error);
        alert("Error sending message. Please try again.");
    }
});
      });
  </script>
</body>
</html>
