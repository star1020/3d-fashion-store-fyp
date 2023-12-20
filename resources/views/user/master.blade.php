<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signal</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,600;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('user/css/chatbot/chat.css')}}">
    <link rel="stylesheet" href="{{asset('user/css/chatbot/chatbot.css')}}">
    <link rel="stylesheet" href="{{asset('user/css/chatbot/typing.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{asset('user/images/icons/signal.png')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/fonts/iconic/css/material-design-iconic-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/fonts/linearicons-v1.0.0/icon-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('user/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('user/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/vendor/slick/slick.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/vendor/MagnificPopup/magnific-popup.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/vendor/perfect-scrollbar/perfect-scrollbar.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('user/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('user/css/main.css')}}">
<!--===============================================================================================-->

<!--===============================================================================================-->	
    <script src="{{asset('user/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('user/vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('user/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{asset('user/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->

    <style>
        /* Style for the notifications popup container */
        .notifications-popup {
            position: absolute;
            top: 100%;
            right: 10px;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            z-index: 9999;
            display: none; /* Hide the popup by default */
            scrollbar-color: #C1C1C1 #F5F5F5; /* thumb and track color */
            scrollbar-width: thin; /* 'auto' or 'thin' */
        }

        /* Total scrollbar styling */
        .notifications-popup::-webkit-scrollbar {
            width: 5px; /* Width of the scrollbar */
            background-color: #F5F5F5; /* Color of the scrollbar track */
        }

        /* Handle of the scrollbar */
        .notifications-popup::-webkit-scrollbar-thumb {
            background-color: #C1C1C1; /* Color of the scrollbar thumb */
            border-radius: 4px; /* Rounded corners of the scrollbar thumb */
        }

        /* Handle on hover */
        .notifications-popup::-webkit-scrollbar-thumb:hover {
            background-color: #A8A8A8; /* Darker color on hover */
        }

        /* Style for each notification row */
        .notification-item {
            position: relative; 
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .notification-item:last-child {
        border-bottom: none; /* Remove border for the last notification item */
        }

        /* Style for notification image */
        .notification-item img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        }

        /* Style for notification title */
        .notification-title {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
        }

        /* Style for notification description */
        .notification-description {
        margin: 5px 0 0;
        font-size: 14px;
        color: #888888;
        }

        /* Hover effect for notification items */
        .notification-item:hover, .notification-item.unread:hover {
        background-color: #f5f5f5;
        cursor: pointer;
        }

        .notification-item .close-notification {
            position: absolute;
            right: 10px;
            top: 10px;
            background: #cccccc; /* Light gray background, you can change as per your color scheme */
            border-radius: 50%;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            cursor: pointer;
            color: #fff; /* White text color */
        }

        /* Hover effect for close button */
        .notification-item .close-notification:hover {
            background: #555; /* Darken the button a bit when hovering */
        }

        .notification-item.unread {
            background-color: #edf2fa;
        }

        /* Additional styles for the timestamp */
        .notification-timestamp {
            display: block;
            color: #aaa;
            font-size: 12px;
            margin-top: 5px;
        }

        .emoji-picker-container {
            position: relative;
        }

        emoji-picker {
            position: absolute;
            bottom: 100%; /* This will make it pop out above the chatbox footer */
            right: 0; /* Adjust this as needed to align with the emoji icon */
            z-index: 1000; /* Ensure it's above other elements */
        }

        .chatbox__microphone-icon.listening {
            /* style change when listening */
            opacity: 0.5;
        }

        .chatbox__send--footer {
            cursor: pointer;
        }

        .chatbox__microphone-icon {
            cursor: pointer;
        }

        .chatbox__emoji-icon {
            cursor: pointer;
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>
<body class="animsition">
    {{View::make('user/header')}}
    @yield('content')
    {{View::make('user/footer')}}

    {{-- chatbot --}}
    <div class="container" id="chatbot__container">
        <div class="chatbox">
            <div class="chatbox__support">
                <div class="chatbox__header">
                    <div class="chatbox__image--header">
                        <img src="{{asset('user/images/image.png')}}" alt="image">
                    </div>
                    <div class="chatbox__content--header">
                        <h4 class="chatbox__heading--header">Chat support</h4>
                        <p class="chatbox__description--header">Signal Fashion Store</p>
                    </div>
                </div>
                <div class="chatbox__messages">
                    <div>
                        <div class="messages__item--operator">
                            <!-- Frequently Asked Question buttons -->
                            <div class="faq-buttons">
                                @foreach($faqs as $faq)
                                    <button type="button" class="faq-button" data-faq-id="{{ $faq->id }}" data-answer="{{ $faq->answer }}">{{ $faq->question }}</button>
                                @endforeach
                                @if(auth()->check())
                                    <button type="button" class="faq-button" id="human-handover">Live Agent</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- New Product Recommendations container -->
                    @if($newProductRecommendations && $newProductRecommendations->isNotEmpty())
                        <div class="personalized-suggestions">
                            <div class="suggestions-heading">New Product(s):</div>
                            <div class="suggestions-list">
                                @foreach($newProductRecommendations as $newProduct)
                                    <a href="{{ $newProduct->path }}" class="suggestion-item" target="_blank">
                                        <img src="{{asset('user/images/product/'.$newProduct->image)}}" alt="{{ $newProduct->productName }}" class="product-image">
                                        <div class="product-details">
                                            <div class="product-name">{{ $newProduct->productName }}</div>
                                            <div class="product-price">RM{{ number_format($newProduct->price, 2) }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- Personalized suggestions container -->
                    @if($personalizedProducts && $personalizedProducts->isNotEmpty())
                        <div class="personalized-suggestions">
                            <div class="suggestions-heading">Guess You Like...</div>
                            <div class="suggestions-list">
                                @foreach($personalizedProducts as $product)
                                    <a href="{{ $product->path }}" class="suggestion-item" target="_blank">
                                        <img src="{{asset('user/images/product/'.$product->image)}}" alt="{{ $product->productName }}" class="product-image">
                                        <div class="product-details">
                                            <div class="product-name">{{ $product->productName }}</div>
                                            <div class="product-price">RM{{ number_format($product->price, 2) }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="chatbox__footer">
                    <div class="emoji-picker-container" style="position: relative;">
                        <emoji-picker style="display: none; position: absolute; bottom: 100%;"></emoji-picker>
                    </div>
                    <img src="{{asset('user/images/icons/emojis.svg')}}" class="chatbox__emoji-icon" alt="">
                    <img src="{{asset('user/images/icons/microphone.svg')}}" class="chatbox__microphone-icon" alt="">
                    <input type="text" class="chatbox__userQuery" placeholder="Write a message..." name="chatbox__userQuery">
                    <input type="hidden" id="isLiveChat" value="false">
                    <input type="hidden" id="liveAgentId" value="">
                    <p class="chatbox__send--footer">Send</p>
                </div>
            </div>
            <div class="chatbox__button">
                <button>button</button>
            </div>
        </div>
    </div>
    {{-- end chatbot --}}

    <script src="{{asset('user/js/chatbot/Chat.js')}}"></script>
    <script type="text/javascript">
        var chatboxIconUrl = "{{ asset('user/images/icons/chatbox-icon.svg') }}";
    </script>
    <script src="{{asset('user/js/chatbot/chatbot.js')}}"></script>

<!--===============================================================================================-->
<script src="{{asset('user/vendor/select2/select2.min.js')}}"></script>
	<script>
		$(".js-select2").each(function(){
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
            $(this).on('select2:selecting', function (e) {
                $(this).select2('close');
            });
		})
	</script>
<!--===============================================================================================-->
	<script src="{{asset('user/vendor/daterangepicker/moment.min.js')}}"></script>
	<script src="{{asset('user/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('user/vendor/slick/slick.min.js')}}"></script>
	<script src="{{asset('user/js/slick-custom.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('user/vendor/parallax100/parallax100.js')}}"></script>
	<script>
        $('.parallax100').parallax100();
	</script>
<!--===============================================================================================-->
	<script src="{{asset('user/vendor/MagnificPopup/jquery.magnific-popup.min.js')}}"></script>
	<script>
		$('.gallery-lb').each(function() { // the containers for all your galleries
			$(this).magnificPopup({
		        delegate: 'a', // the selector for gallery item
		        type: 'image',
		        gallery: {
		        	enabled:true
		        },
		        mainClass: 'mfp-fade'
		    });
		});
	</script>
<!--===============================================================================================-->
	<script src="{{asset('user/vendor/isotope/isotope.pkgd.min.js')}}"></script>
<!--===============================================================================================-->
	{{-- <script src="{{asset('user/vendor/sweetalert/sweetalert.min.js')}}"></script> --}}
    <!-- Include SweetAlert2 CSS -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> --}}
    <!-- Include SweetAlert2 JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    @if ($message = Session::get('success'))
        <script>
                swal({
                    title: "Success!",
                    text: "{{ $message }}",
                    icon: "success",
                    button: "OK",
                });
        </script>
    @endif

	<script>
		$('.js-addwish-b2').on('click', function(e){
			e.preventDefault();
		});

		$('.js-addwish-b2').each(function(){
			var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
			$(this).on('click', function(){
				swal(nameProduct, "is added to wishlist !", "success");

				$(this).addClass('js-addedwish-b2');
				$(this).off('click');
			});
		});

		$('.js-addwish-detail').each(function(){
			var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

			$(this).on('click', function(){
				swal(nameProduct, "is added to wishlist !", "success");

				$(this).addClass('js-addedwish-detail');
				$(this).off('click');
			});
		});

		/*---------------------------------------------*/

        $('.js-addcart-detail').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission
                var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();

                // Collect form data
                var color = $('#colorSelect').val();
                var size = $('#sizeSelect').val();
                var quantity = $('#quantityInput').val();
                var errors = [];

                // Validation checks
                if (!color) {
                    errors.push("Please select a color.");
                }
                if (!size) {
                    errors.push("Please select a size.");
                }
                if (quantity <= 0 || isNaN(quantity)) {
                    errors.push("Please enter a valid quantity.");
                }

                // Display errors using SweetAlert, if any
                if (errors.length > 0) {
                    var errorMessage = errors.join("\n");
                    swal("Error", errorMessage, "error");
                    return; // Stop further execution
                }

                // If validation passes, proceed with AJAX request
                var formData = {
                    '_token': $('input[name="_token"]').val(),
                    'productId': $('input[name="productId"]').val(),
                    'color': color,
                    'size': size,
                    'num-product': quantity,
                    'maxProductQuantity': $('input[name="maxProductQuantity"]').val(),
                };

                $.ajax({
                    url: '../user/add-to-cart',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                    if ($('.header-cart-wrapitem').length === 0) {
                        // If the cart was initially empty, create the structure
                        var cartContentHtml = '<ul class="header-cart-wrapitem w-full">' + 
                                            response.cartItemsHtml + 
                                            '</ul><div class="w-full">' +
                                            '<div class="header-cart-total w-full p-tb-40" id="totalPrice">' +
                                            'Total: RM' + response.newTotalPrice + '</div>' +
                                            '<div class="header-cart-buttons flex-w w-full" style="display: flex; justify-content: center;">' +
                                            '<a href="/user/cart" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">View Cart</a>' +
                                            '</div></div>';
                        $('.header-cart-content').html(cartContentHtml);
                    } else {
                        // If the cart already has items, just update the list and total
                        $('.header-cart-wrapitem').html(response.cartItemsHtml);
                        $('#totalPrice').text('Total: RM' + response.newTotalPrice);
                    }
                    $('.icon-header-noti.js-show-cart').attr('data-notify', response.totalQuantity);
                    swal(nameProduct, "is added to cart!", "success");
                },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            swal("Error", xhr.responseJSON.error, "error");
                        } else {
                            swal("Error", "An error occurred while adding the product to the cart.", "error");
                        }
                    }
                });
            });


	</script>
<!--===============================================================================================-->
	<script src="{{asset('user/vendor/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
	<script>
		$('.js-pscroll').each(function(){
			$(this).css('position','relative');
			$(this).css('overflow','hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function(){
				ps.update();
			})
		});
	</script>

	<script>
		$(document).ready(function() {
			// Show/hide notifications popup on bell icon click
			$("#notificationBell").click(function() {
				$("#notificationsPopup").toggle();
			});

			// Hide notifications popup when clicking outside the popup
			$(document).click(function(event) {
				if (!$(event.target).closest("#notificationBell, #notificationsPopup").length) {
				$("#notificationsPopup").hide();
				}
			});
		});
	</script>
<!--===============================================================================================-->
	<script src="{{asset('user/js/main.js')}}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all FAQ buttons
            const faqButtons = document.querySelectorAll('.faq-button');

            // Add click event listener to each button
            faqButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Check if the clicked button is not the 'Live Agent' button
                    if (this.id !== 'human-handover') {
                        const answer = this.getAttribute('data-answer'); // Get the data-answer attribute value
                        // Display the FAQ answer
                        var $messages = $(".chatbox__messages");
                        $responseMessage = $('<div class="messages__item messages__item--operator">' + answer + '</div>');
                        $messages.append($responseMessage);
                        $messages.scrollTop($messages.prop("scrollHeight"));
                    }
                });
            });
        });
    </script>

    <script>
        // Function to handle click on 'Human Handover' button
        document.querySelector('#human-handover').addEventListener('click', function() {
            // Display waiting message to user
            displayWaitingMessage();

            // Make an AJAX request to server to initiate the handover process
            initiateHandoverToHuman();
        });

        function displayWaitingMessage() {
            var $messages = $(".chatbox__messages");
            $messages.append('<div class="messages__item--waiting"><div class="loader"></div>Please wait for an available agent.</div>');
            $messages.scrollTop($messages.prop("scrollHeight"));

        }

        function initiateHandoverToHuman() {
            // Implement AJAX request to backend to add user to queue and wait for an agent
            // On success, call displayAgentJoinedMessage when an agent is available
            $.ajax({
                type: 'POST',
                url: "{{ route('requestLiveChat') }}",
                success: function(response) {
                    document.getElementById('human-handover').classList.add('hidden');
                },
                error: function(xhr) {
                    
                }
            })
        }
    </script>

    @if (auth()->check())
        <script>
            var pusher = new Pusher('b0e7d97da0709c62519f', {
                cluster: 'ap1'
            });

            var channel = pusher.subscribe('user-channel-{{ auth()->user()->id }}');
            channel.bind('user-event-{{ auth()->user()->id }}', function(data) {
                var $messages = $(".chatbox__messages");
                if (data.message.message === 'accepted') {
                    // Set live chat status to true
                    $('#isLiveChat').val('true');
                    $('#liveAgentId').val(data.message.staffId);
                    // Remove the waiting message
                    var waitingMessage = document.querySelector('.messages__item--waiting');
                    if (waitingMessage) {
                        waitingMessage.remove();
                    }
                    var staffJoinedHtml = `
                        <div class="messages__item--waiting">
                            <img class="messages__staff-img" src="${data.message.staffImage}" alt="${data.message.staffName}">
                            <div>
                                <strong>${data.message.staffName}</strong> has joined the chat to help you.
                            </div>
                        </div>
                    `;
                    $messages.append(staffJoinedHtml);
                    $messages.scrollTop($messages.prop("scrollHeight"));
                } else if (data.message.message === 'end'){
                    $('#isLiveChat').val('false');
                    $('#liveAgentId').val('');
                    document.getElementById('human-handover').classList.remove('hidden');
                    var $messages = $(".chatbox__messages");
                    $messages.append('<div class="messages__item--waiting"><div><strong>' + data.message.staffName + '</strong> left the chat. Your session has ended.</div></div>');
                    $messages.scrollTop($messages.prop("scrollHeight"));
                } else {
                    var typingMessage = document.querySelector('.messages__item--typing');
                    if (typingMessage) {
                        typingMessage.remove();
                    }
                    var $newMessage = $('<div class="messages__item messages__item--operator">' + data.message + '</div>');
                    $messages.append($newMessage);
                    $messages.scrollTop($messages.prop("scrollHeight"));
                }
            });
        </script>
    @endif

    <script>
        // Check for browser support
        var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;

        if (SpeechRecognition) {
            let recognition = new SpeechRecognition();
            let isListening = false;
            const microphoneButton = document.querySelector('.chatbox__microphone-icon');
            const chatInput = document.querySelector('.chatbox__userQuery');

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

            recognition.onresult = (event) => {
                const transcript = Array.from(event.results)
                    .map(result => result[0])
                    .map(result => result.transcript)
                    .join('');

                chatInput.value += transcript; // Append the transcript to the input field
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
    </script>

    <script type="module">
        import 'https://cdn.jsdelivr.net/npm/emoji-picker-element@latest/index.js'
    </script>
      
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emojiPicker = document.querySelector('.emoji-picker-container emoji-picker');
            const emojiButton = document.querySelector('.chatbox__emoji-icon');
            const chatInput = document.querySelector('.chatbox__userQuery');

            emojiButton.addEventListener('click', () => {
                const isDisplayed = window.getComputedStyle(emojiPicker).display !== 'none';
                emojiPicker.style.display = isDisplayed ? 'none' : 'block';
                emojiButton.style.opacity = isDisplayed ? '1' : '0.5';
            });

            emojiPicker.addEventListener('emoji-click', event => {
                chatInput.value += event.detail.unicode;
                chatInput.focus(); // Brings focus back to the input after selecting an emoji
            });
        });
    </script>

    <script>
        function sendMessage() {
            $value = $('.chatbox__userQuery').val();
            var $messages = $(".chatbox__messages");
            var isLiveChat = $('#isLiveChat').val() === 'true';

            //avoid sending empty messages
            if ($value.trim() === '') {
                swal({
                    title: "Error!",
                    text: "Please don't send empty query messages",
                    icon: "error",
                    buttons: false,
                    timer: 3000
                });
                return;
            }

            // Create a new message element
            var $newMessage = $('<div class="messages__item messages__item--visitor">' + $value + '</div>');

            // Append the new message at the beginning of the container, which appears at the bottom due to flex-reverse
            $messages.append($newMessage);

            // Scroll to the new message
            $messages.scrollTop($messages.prop("scrollHeight"));

            // Clear the input field
            $('.chatbox__userQuery').val('');

            // Show a loading message
            var $loadingMessage = $('<div class="messages__item messages__item--typing"><span class="messages__dot"></span><span class="messages__dot"></span><span class="messages__dot"></span></div>');
            $messages.append($loadingMessage);
            $messages.scrollTop($messages.prop("scrollHeight"));

            if (isLiveChat) {
                // Send message to staff
                $.ajax({
                    type: 'POST',
                    url: "{{ route('sendLiveChat') }}",
                    data: {
                        'staff_id': $('#liveAgentId').val(),
                        'input': $value
                    },
                    success: function(response) {

                    },
                    error: function(xhr) {
                       
                    }
                })
            } else {
                // Send message to chatbot
                $.ajax({
                    type: 'POST',
                    url: "{{ route('sendChat') }}",
                    data: {
                        'input': $value
                    },
                    success: function(response) {
                        // Remove the loading message
                        $loadingMessage.remove();
                        $responseMessage = $('<div class="messages__item messages__item--operator">' + response.choices[0].message.content + '</div>');
                        $messages.append($responseMessage);
                        $messages.scrollTop($messages.prop("scrollHeight"));
                    },
                    error: function(xhr) {
                        // Remove the loading message
                        $loadingMessage.remove();

                        // Check if the response is in JSON format
                        var errorResponse = xhr.responseJSON;
                        
                        // Check if an error message is provided
                        var errorMessage = errorResponse && errorResponse.error ? errorResponse.error : "An unknown error occurred.";

                        // Display the error message
                        var $errorMessage = $('<div class="messages__item messages__item--operator">' + errorMessage + '</div>');
                        $messages.append($errorMessage);
                        $messages.scrollTop($messages.prop("scrollHeight"));
                    }
                })
            }
        }
            
        // Send button click event
        $(".chatbox__send--footer").on('click', sendMessage);

        // Keypress event for Enter key on the input field
        $('.chatbox__userQuery').on('keypress', function (e) {
            if (e.which == 13) { // 13 is the Enter key's keycode
                sendMessage();
                return false; // Prevents the default action of the Enter key
            }
        });
    </script>
</body>

</html>