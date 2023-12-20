<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signal</title>
    <link rel="icon" href="{{asset('user/images/icons/signal.png')}}">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}">
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('admin/assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/vendors/css/vendor.bundle.base.css')}}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- jquery --}}
    <!-- plugins:js -->
    <script src="{{asset('admin/assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.18/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.18/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>

    <style>
        .table {
        border-spacing: 0 0.85rem !important;
        }

        .table td,
        .table th {
        vertical-align: middle;
        margin-bottom: 10px;
        border: none;
        }

        .table thead tr,
        .table thead th {
        border: none;
        font-size: 12px;
        letter-spacing: 1px;
        text-transform: uppercase;
        background: transparent;
        }

        .table td {
        background: #fff;
        }

        .table td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        }

        .table td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        }

        table.dataTable.dtr-inline.collapsed
        > tbody
        > tr[role="row"]
        > td:first-child:before,
        table.dataTable.dtr-inline.collapsed
        > tbody
        > tr[role="row"]
        > th:first-child:before {
        top: 28px;
        left: 14px;
        border: none;
        box-shadow: none;
        }

        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child,
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child {
        padding-left: 48px;
        }

        table.dataTable > tbody > tr.child ul.dtr-details {
        width: 100%;
        }

        table.dataTable > tbody > tr.child span.dtr-title {
        min-width: 50%;
        }

        table.dataTable.dtr-inline.collapsed > tbody > tr > td.child,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.child,
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dataTables_empty {
        padding: 0.75rem 1rem 0.125rem;
        }

        div.dataTables_wrapper div.dataTables_length label,
        div.dataTables_wrapper div.dataTables_filter label {
        margin-bottom: 0;
        }

        .table a:hover,
        .table a:focus {
        text-decoration: none;
        }

        table.dataTable {
        margin-top: 12px !important;
        }

        .buttons-pdf, .buttons-excel{
            color: #333;
            border-color: #ccc;
        }

        .buttons-pdf:hover, .buttons-excel:hover {
        background: #e1e1e1;
        border: 1px solid #d0d0d0;
        }

        .table-hover tbody tr:hover td{
            background-color: #cfe2ff !important;
        }

        .alert p{
            margin-top: 0;
            margin-bottom: 0 !important;
        }

        .btn-edit,
        .btn-delete {
            font-size: 14px;
            padding: 10px 15px;
        }

        @keyframes shakeAnimation {
            0% { transform: translateX(0); }
            10% { transform: translateX(-10px); }
            20% { transform: translateX(10px); }
            30% { transform: translateX(-10px); }
            40% { transform: translateX(10px); }
            50% { transform: translateX(0); }
        }

        .shake {
            animation: shakeAnimation 1.0s;
            animation-iteration-count: infinite;
        }

        .notification-dot {
            height: 10px;
            width: 10px;
            background-color: red;
            border-radius: 50%;
            position: absolute;
            top: 25%;
            right: -5px;
            transform: translate(0, -50%);
            display: none;
            transition: display 0.2s, opacity 0.2s ease, transform 0.2s;
        }

        .nav-item {
            position: relative;
        }

    </style>
</head>
<body>
    <div class="container-scroller">
        {{View::make('admin/header')}}
        <div class="container-fluid page-body-wrapper">
            {{View::make('admin/sidebar')}}
            <div class="main-panel">
                <div class="content-wrapper">
                     @yield('content')
                </div>
                {{View::make('admin/footer')}}
            </div>
        </div>
    </div>
    
    <!-- inject:js -->
    <script src="{{asset('admin/assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('admin/assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('admin/assets/js/misc.js')}}"></script>
    <script src="{{asset('admin/assets/vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/jquery.cookie.js')}}" type="text/javascript"></script>
    <!-- Custom js for this page -->
    <script src="{{asset('admin/assets/js/file-upload.js')}}"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Function to fetch the initial state of chat requests
            function fetchInitialState() {
                $.ajax({
                    url: "{{ route('getChatRequestState') }}",
                    type: 'POST',
                    success: function(response) {
                        // Update UI based on response
                        if (response.hasPendingRequests) {
                            // Check if the current page is not the chat page
                            if (!window.location.href.includes('/admin/chat')) {
                                var chatLink = document.querySelector('.nav-link[href="{{route('livechat')}}"]');
                                var notificationDot = chatLink.querySelector('.notification-dot');

                                // Add the 'shake' class to the chat menu item and show the notification dot
                                chatLink.classList.add('shake');
                                notificationDot.style.display = 'inline-block'; // Show the dot
                                notificationDot.style.opacity = '1';
                                notificationDot.style.transform = 'scale(1)';
                            } else {
                                // Add new friend-drawers
                                response.pendingRequests.forEach(function(request) {
                                    if ($('.friend-drawer[data-user-id="' + request.user_id + '"]').length === 0) {
                                        var friendDrawerHtml = `
                                            <div class="friend-drawer friend-drawer--onhover" data-user-id="${request.user_id}" data-request-id="${request.id}">
                                                <img class="profile-image" src="${request.image}" alt="Profile Image">
                                                <div class="text">
                                                    <h6>${request.username}</h6>
                                                    <p class="text-muted">Live chat request</p>
                                                    <div class="action-buttons">
                                                        <button type="button" class="btn btn-accept" data-request-id="${request.id}">Accept</button>
                                                        <button type="button" class="btn btn-deny" data-request-id="${request.id}">Deny</button>
                                                    </div>
                                                </div>
                                                <span class="time text-muted small">${request.request_time}</span>
                                            </div>
                                            <hr>
                                        `;

                                        // Append the friendDrawerHtml to the container that holds these drawers
                                        $('.col-md-4.border-right').prepend(friendDrawerHtml);
                                    }
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error); // Logs error to browser's console
                        console.error("Status: " + status);
                        console.error("Response: ", xhr.responseText); // The responseText will contain the detailed error message
                        
                    }
                });
            }

            // Call the function on page load to get initial state
            fetchInitialState();

            // Setup Pusher for real-time updates
            var pusher = new Pusher('b0e7d97da0709c62519f', {
                cluster: 'ap1'
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
                fetchInitialState();
            });

            // When the chat menu item is clicked, remove the shake and hide the dot
            document.querySelector('.nav-link[href="{{route('livechat')}}"]').addEventListener('click', function() {
                this.classList.remove('shake');
                var notificationDot = this.querySelector('.notification-dot');
                if (notificationDot) {
                    notificationDot.style.opacity = '0';
                    notificationDot.style.transform = 'scale(0)';
                    setTimeout(function() {
                        notificationDot.style.display = 'none';
                    }, 200); // Wait for the opacity transition to finish before hiding
                }
            });
        });
    </script>

</body>
</html>