@extends('admin/master')
@section('content')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css">
<!-- Include Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">

<!-- Include Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.1.1/compressor.min.js"></script>

<style>
.container {
  margin: 60px auto;
  background: #fff;
  padding: 0;
  border-radius: 7px;
}

.profile-image {
  width: 50px;
  height: 50px;
  border-radius: 40px;
}

.settings-tray {
  background: #eee;
  padding: 10px 15px;
  border-radius: 7px;
}

.no-gutters {
  padding: 0;
}

.settings-tray--right {
  float: right;
}

.search-box {
  background: #fafafa;
  padding: 10px 13px;
}

.search-box .input-wrapper {
  background: #fff;
  border-radius: 40px;
}

.search-box .input-wrapper i {
  color: grey;
  margin-left: 7px;
  vertical-align: middle;
}

input {
  border: none;
  border-radius: 30px;
  width: 80%;
}

input::placeholder {
  color: #e3e3e3;
  font-weight: 300;
  margin-left: 20px;
}

input:focus {
  outline: none;
}

.friend-drawer {
  padding: 10px 15px;
  display: flex;
  vertical-align: baseline;
  background: #fff;
  transition: .3s ease;
}

.friend-drawer--grey {
  background: #eee;
}

.friend-drawer .text {
  margin-left: 12px;
  width: 70%;
}

.friend-drawer .text h6 {
  margin-top: 6px;
  margin-bottom: 0;
}

.friend-drawer .text p {
  margin: 0;
}

.friend-drawer .time {
  color: grey;
}

.friend-drawer--onhover:hover {
  background: #74b9ff;
  cursor: pointer;
}

.friend-drawer--onhover:hover p,
.friend-drawer--onhover:hover h6,
.friend-drawer--onhover:hover .time {
  color: #fff !important;
}

hr {
  margin: 5px auto;
  width: 60%;
}

.chat-bubble {
  padding: 10px 14px;
  background: #eee;
  margin: 10px 30px;
  border-radius: 9px;
  position: relative;
  animation: fadeIn 1s ease-in;
  max-width: 80%;
  overflow-wrap: break-word;
}

.chat-bubble img {
  max-width: 100%;
  height: auto;
  display: block; /* This will ensure that the image doesn't have extra space around it */
}

.chat-bubble:after {
  content: '';
  position: absolute;
  top: 50%;
  width: 0;
  height: 0;
  border: 20px solid transparent;
  border-bottom: 0;
  margin-top: -10px;
}

.chat-bubble--left:after {
  left: 0;
  border-right-color: #eee;
  border-left: 0;
  margin-left: -20px;
}

.chat-bubble--right:after {
  right: 0;
  border-left-color: #74b9ff;
  border-right: 0;
  margin-right: -20px;
}

.border-right {
    border-right: 1px solid #dee2e6!important;
}

.no-gutters {
  margin: 0;
  padding: 0;
}

.no-gutters > .col,
.no-gutters > [class*="col-"] {
  padding-right: 0;
  padding-left: 0;
}

.col-md-4, .col-md-8 {
  padding: 0;
}

.col-md-4 {
  height: calc(100vh - 50px);
  overflow-y: auto;
}


@keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

.offset-md-9 .chat-bubble {
  background: #74b9ff;
  color: #fff;
}

.chat-panel {
  display: flex;
  flex-direction: column;
  height: calc(100vh - 120px);
}

.chat-box-tray {
  background: #eee;
  display: flex;
  padding: 10px 15px;
  align-items: center;
  margin-top: auto;
}

.chat-box-tray input {
  margin: 0 10px;
  padding: 6px 2px;
}

.chat-box-tray i {
  color: grey;
  font-size: 30px;
  vertical-align: middle;
}

.chat-box-tray i:last-of-type {
  margin-left: 25px;
}

.action-buttons {
  display: flex;
  justify-content: flex-start;
  margin-top: 10px;
}

.btn {
  padding: 5px 10px;
  margin-right: 5px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-accept {
  background-color: #28a745;
  color: white;
}

.btn-deny {
  background-color: #dc3545;
  color: white;
}

.chat-bubbles-container { /* You might need to add this class to your HTML */
  flex-grow: 1;
  overflow-y: auto; /* Allow scrolling for overflow content */
}

.chatbox__microphone-icon {
    cursor: pointer;
}

.chatbox__microphone-icon.listening {
    opacity: 0.5;
}

.emoji-picker-container {
    position: relative;
}

.chatbox__emoji-icon {
    cursor: pointer;
}

.chatbox__send {
    cursor: pointer;
}

emoji-picker {
    position: absolute;
    bottom: 100%;
    right: 0;
    z-index: 1000;
}

.end-chat-btn {
    background-color: #f44336; /* Red color for end chat */
    border: none;
    border-radius: 50%;
    color: white;
    padding: 8px;
    margin-left: 100px;
    cursor: pointer;
    font-size: 24px;
}

.end-chat-btn:hover {
    background-color: #d32f2f; /* Darker red on hover */
}

/* Adjust the size and position of the toolbar icons */
.note-editor .note-toolbar .btn i,
.note-editor .note-toolbar .note-btn i {
    font-size: 16px;
}

/* Center the content of each button */
.note-editor .note-toolbar .btn,
.note-editor .note-toolbar .note-btn {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

/* Center the icons inside the buttons */
.note-editor .note-toolbar .btn i,
.note-editor .note-toolbar .note-btn i {
  margin: 2px;
}

</style>
    <div class="container">
        <div class="row no-gutters">
            <div class="col-md-4 border-right">
                {{-- <div class="friend-drawer friend-drawer--onhover">
                    <img class="profile-image" src="https://randomuser.me/api/portraits/men/20.jpg" alt="">
                    <div class="text">
                        <h6>Robo Cop</h6>
                        <p class="text-muted">Hey, you're arrested!</p>
                        <div class="action-buttons">
                            <button type="button" class="btn btn-accept">Accept</button>
                            <button type="button" class="btn btn-deny">Deny</button>
                        </div>
                    </div>
                    <span class="time text-muted small">13:21</span>
                </div>
                <hr>
                <div class="friend-drawer friend-drawer--onhover">
                    <img class="profile-image" src="https://randomuser.me/api/portraits/women/64.jpg" alt="">
                    <div class="text">
                        <h6>Optimus</h6>
                        <p class="text-muted">Wanna grab a beer?</p>
                    </div>
                    <span class="time text-muted small">00:32</span>
                </div>
                <hr> --}}
            </div>
            <div class="col-md-8">
                {{-- <div class="settings-tray">
                    <div class="friend-drawer no-gutters friend-drawer--grey">
                        <img class="profile-image" src="https://randomuser.me/api/portraits/men/30.jpg" alt="">
                        <div class="text">
                            <h6>Robo Cop</h6>
                            <p class="text-muted">email</p>
                        </div>
                        <span class="settings-tray--right">
                            <button class="end-chat-btn material-icons">call_end</button>
                        </span>
                    </div>
                </div>
                <div class="chat-panel">
                    <div class="chat-bubbles-container">
                        <div class="row no-gutters">
                            <div class="col-md-3">
                                <div class="chat-bubble chat-bubble--left">
                                    Hello dude!
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-md-3 offset-md-9">
                                <div class="chat-bubble chat-bubble--right">
                                    Hello dude!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-box-tray">
                        <div class="emoji-picker-container" style="position: relative;">
                            <emoji-picker style="display: none; position: absolute; bottom: 100%;"></emoji-picker>
                        </div>
                        <i class="material-icons chatbox__emoji-icon">sentiment_very_satisfied</i>
                        <!-- Div for Summernote -->
                        <div id="chatbox__userQuery" class="chatbox__userQuery"></div>
                        <i class="material-icons chatbox__microphone-icon">mic</i>
                        <i class="material-icons">send</i>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <script>
        $( '.friend-drawer--onhover' ).on( 'click',  function() {
            $( '.chat-bubble' ).hide('slow').show('slow');
        });
    </script>

    <script>
        $(document).ready(function() {
            // Accept button click handler
            $(document).on('click', '.btn-accept', function() {
                var requestId = $(this).data('request-id');
                var userId = $('.friend-drawer--onhover').data('user-id');
                updateChatRequestStatus(requestId, userId, 'active');
            });

            // Deny button click handler
            $(document).on('click', '.btn-deny', function() {
                var requestId = $(this).data('request-id');
                var userId = $('.friend-drawer--onhover').data('user-id');
                updateChatRequestStatus(requestId, userId, 'end');
            });

            function updateChatRequestStatus(requestId, userId, status) {
                $.ajax({
                    url: "{{ route('updateChatRequestState') }}",
                    type: 'POST',
                    data: {
                        id: requestId,
                        status: status,
                        user_id: userId
                    },
                    success: function(response) {
                        var friendDrawer = $('.friend-drawer[data-request-id="' + requestId + '"]');
                        if (status === 'active') {
                            // If the chat request is accepted, fade out the accept/deny buttons
                            friendDrawer.find('.action-buttons').fadeOut('slow', function() {
                                // This callback function is executed after the fading completes
                                $(this).remove(); // Remove the buttons from the DOM
                                // You may want to add additional UI changes here, such as showing a "Chat started" message
                                var chatInterfaceHtml = `
                                    <div class="settings-tray">
                                        <div class="friend-drawer no-gutters friend-drawer--grey">
                                            <img class="profile-image" src="${response.activeRequests[0].image}" alt="">
                                            <div class="text">
                                                <h6>${response.activeRequests[0].username}</h6>
                                                <p class="text-muted">${response.activeRequests[0].email}</p>
                                            </div>
                                            <span class="settings-tray--right">
                                                <button class="end-chat-btn material-icons">call_end</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="chat-panel">
                                        <div class="chat-bubbles-container">
                                            <!-- Chat bubbles will be dynamically added here -->
                                        </div>
                                        <div class="chat-box-tray">
                                            <div class="emoji-picker-container" style="position: relative;">
                                                <emoji-picker style="display: none; position: absolute; bottom: 100%;"></emoji-picker>
                                            </div>
                                            <i class="material-icons chatbox__emoji-icon">sentiment_very_satisfied</i>
                                            <div id="chatbox__userQuery" class="chatbox__userQuery"></div>
                                            <i class="material-icons chatbox__microphone-icon">mic</i>
                                            <i class="material-icons chatbox__send">send</i>
                                        </div>
                                    </div>
                                `;
                                $('.col-md-8').html(chatInterfaceHtml);
                                setupEmojiPicker();
                                setupSpeechRecognition();
                                setupSendMsg();
                                setupEndChat();
                                setupSendMessageBox();
                            });
                        } else {
                            // If the chat request is denied, remove the entire chat box
                            friendDrawer.fadeOut('slow', function() {
                                // This callback function is executed after the fading completes
                                $(this).next('hr').remove();
                                $(this).remove(); // Remove the chat box from the DOM
                                // Any additional cleanup can be done here
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
                            swal({
                                title: "Error!",
                                text: xhr.responseJSON.message,
                                icon: "error",
                                buttons: false,
                                timer: 2000
                            });
                            var friendDrawer = $('.friend-drawer[data-request-id="' + requestId + '"]');
                            friendDrawer.fadeOut('slow', function() {
                                // This callback function is executed after the fading completes
                                $(this).next('hr').remove();
                                $(this).remove(); // Remove the chat box from the DOM
                                // Any additional cleanup can be done here
                            });
                        }
                    }
                });
            }
        });
    </script>

    <script>
        function setupSendMessageBox(){
            $('#chatbox__userQuery').summernote({
                placeholder: 'Type your message here...',
                toolbar: [
                    ['style', ['bold', 'underline', 'clear']],
                    ['insert', ['link', 'picture', 'video']]
                ],
                height: 70,
                width: '100%',
                callbacks: {
                    onKeydown: function(e) {
                        // When Enter is pressed without the Shift key
                        if (e.keyCode === 13 && !e.shiftKey) {
                            e.preventDefault(); // Prevent the default paragraph insertion
                            // Trigger your send message function here
                            sendMessage();
                        }
                    },
                    onImageUpload: function(files) {
                        var formData = new FormData();
                        $.each(files, function(i, file) {
                            // Initialize the Compressor with options
                            new Compressor(file, {
                                maxWidth: 200, // Define the maximum width
                                maxHeight: 200, // Define the maximum height
                                success(result) {
                                    // Append the compressed image file to FormData
                                    formData.append('images[]', result, result.name);
                                    
                                    // Continue with the AJAX request once all files are processed
                                    if (i === files.length - 1) {
                                        $.ajax({
                                            url: "{{ route('liveChatUploadImage') }}",
                                            method: 'POST',
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(response) {
                                                if(response.urls){
                                                    response.urls.forEach(function(url){
                                                        $('#chatbox__userQuery').summernote('insertImage', url);
                                                    });
                                                }
                                            },
                                            error: function() {
                                                console.error('Image upload failed');
                                            }
                                        });
                                    }
                                },
                                error(err) {
                                    console.log(err.message);
                                },
                            });
                        });
                    }
                }
            });
        }
    </script>

    <script>
        function setupSendMsg(){
            $('.chatbox__send').click(function() {
                sendMessage();
            });
        }
    </script>

    <script>
        function sendMessage() {
            // Get the message from input field
            var message = $('#chatbox__userQuery').summernote('code');
            var messageText = $('<div>').html(message).text().trim();
            // Retrieve user ID
            var userId = $('.friend-drawer--onhover').data('user-id')

            // Check if the message is not empty
            if (messageText || $(message).find('img').length > 0) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('liveAgentResponse') }}",
                    data: {
                        'input': message,
                        'user_id': userId
                    },
                    success: function(response) {
                        // Create a new chat bubble element
                        var chatBubbleHtml = `<div class="row no-gutters">
                                                <div class="col-md-3 offset-md-9">
                                                    <div class="chat-bubble chat-bubble--right">${message}</div>
                                                </div>
                                            </div>`;

                        // Append the chat bubble to the chat container
                        $('.chat-bubbles-container').append(chatBubbleHtml);

                        // Scroll to the bottom of the chat container
                        var chatContainer = $('.chat-bubbles-container');
                        chatContainer.scrollTop(chatContainer.prop("scrollHeight"));

                        // Clear the input field
                        $('#chatbox__userQuery').summernote('reset');
                    },
                    error: function(xhr) {
                        
                    }
                })
            } else {
                swal({
                    title: "Error!",
                    text: "Please don't send empty query messages",
                    icon: "error",
                    buttons: false,
                    timer: 3000
                });
            }
        }
    </script>

    <script>
        function setupEndChat() {
            $('.end-chat-btn').click(function() {
                var userId = $('.friend-drawer--onhover').data('user-id');
                var requestId = $('.friend-drawer--onhover').data('request-id');

                $.ajax({
                    url: "{{ route('endChatSession') }}",
                    type: 'POST',
                    data: {
                        id: requestId,
                        user_id: userId
                    },
                    success: function(response) {
                        // Remove the chat UI elements
                        $('.settings-tray').remove();
                        $('.chat-panel').remove();
                        var friendDrawer = $('.friend-drawer--onhover[data-user-id="' + userId + '"]');
                        friendDrawer.next('hr').remove(); // Remove the hr following the friend-drawer
                        friendDrawer.remove(); // Remove the friend-drawer itself
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error("Error ending chat: " + error);
                    }
                });
            });
        }
    </script>

    <script>
        function setupSpeechRecognition() {
            // Check for browser support
            var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;

            if (SpeechRecognition) {
                let recognition = new SpeechRecognition();
                let isListening = false;
                const microphoneButton = document.querySelector('.chatbox__microphone-icon');
                const chatInput = document.querySelector('.chatbox__userQuery');

                if (microphoneButton) {
                    microphoneButton.addEventListener('click', () => {
                        if (isListening) {
                            recognition.stop();
                            return;
                        }

                        recognition.lang = 'en-US'; // Set the language of the recognition
                        recognition.start(); // Start the recognition
                        isListening = true;
                        microphoneButton.classList.add('listening'); // Optional: change the icon style when listening
                    });
                }

                recognition.onresult = (event) => {
                    const transcript = Array.from(event.results)
                        .map(result => result[0])
                        .map(result => result.transcript)
                        .join('');

                        $('#chatbox__userQuery').summernote('insertText', transcript); // Append the transcript to the input field
                };

                recognition.onend = () => {
                    isListening = false;
                    microphoneButton.classList.remove('listening'); // Optional: revert the icon style when not listening
                };

                recognition.onerror = (event) => {
                    console.error('Speech recognition error', event.error);
                    isListening = false;
                    microphoneButton.classList.remove('listening'); // Optional: revert the icon style when not listening
                };
            } else {
                console.log('Speech Recognition Not Available');
                // Hide or disable the microphone button as speech recognition is not supported in the browser
            }
        }
    </script>

    <script type="module">
        import 'https://cdn.jsdelivr.net/npm/emoji-picker-element@latest/index.js'
    </script>
    
    <script>
        function setupEmojiPicker() {
            const emojiPicker = document.querySelector('.emoji-picker-container emoji-picker');
            const emojiButton = document.querySelector('.chatbox__emoji-icon');
            const chatInput = document.querySelector('.chatbox__userQuery');

            emojiButton.addEventListener('click', () => {
                const isDisplayed = window.getComputedStyle(emojiPicker).display !== 'none';
                emojiPicker.style.display = isDisplayed ? 'none' : 'block';
                emojiButton.style.opacity = isDisplayed ? '1' : '0.5';
            });

            emojiPicker.addEventListener('emoji-click', event => {
                $('#chatbox__userQuery').summernote('insertText', event.detail.unicode);
                chatInput.focus(); // Brings focus back to the input after selecting an emoji
            });
        }
    </script>

    @if (auth()->check())
        <script>
            var pusher = new Pusher('b0e7d97da0709c62519f', {
                cluster: 'ap1'
            });

            var channel = pusher.subscribe('admin-channel-{{ auth()->user()->id }}');
            channel.bind('admin-event-{{ auth()->user()->id }}', function(data) {
                // Create a new chat bubble element
                var chatBubbleHtml = `<div class="row no-gutters">
                                        <div class="col-md-3">
                                            <div class="chat-bubble chat-bubble--left">${data.message}</div>
                                        </div>
                                    </div>`;

                // Append the chat bubble to the chat container
                $('.chat-bubbles-container').append(chatBubbleHtml);

                // Scroll to the bottom of the chat container
                var chatContainer = $('.chat-bubbles-container');
                chatContainer.scrollTop(chatContainer.prop("scrollHeight"));
            });
        </script>
    @endif

@endsection