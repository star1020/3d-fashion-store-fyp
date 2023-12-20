@extends('user/master')
@section('content')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.0/gsap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>

    <style>
    /* redeem page css */
    .list-item {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .list-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .image img {
        width: 150px;
        height: 100px;
        object-fit: cover;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .title h1 {
        font-size: 1.25rem;
        margin: 0;
    }

    .des {
        padding: 10px;
        flex-grow: 1;
    }

    .des p {
        margin: 5px 0;
        color: #555;
    }

    .list-item button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.2s;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .list-item button:hover {
        background-color: #0056b3;
    }

        .card {
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 15px 1px rgba(52,40,104,.08);
        }
        .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #e5e9f2;
            border-radius: .2rem;
        }
        .card-header:first-child {
            border-radius: calc(.2rem - 1px) calc(.2rem - 1px) 0 0;
        }
        .card-header {
            border-bottom-width: 1px;
        }
        .card-header {
            padding: .75rem 1.25rem;
            margin-bottom: 0;
            color: inherit;
            background-color: #fff;
            border-bottom: 1px solid #e5e9f2;
        }
    </style>

    @if(Session::has('redeem_reward_success'))
    <script>
        swal({
            title: "Success!",
            text: "{{ Session::get('redeem_reward_success') }}",
            icon: "success",
            button: "OK",
        });
    </script>
    @endif

    @if(Session::has('redeem_reward_error'))
        <script>
            swal({
                title: "Error!",
                text: "{{ Session::get('redeem_reward_error') }}",
                icon: "error",
                button: "OK",
            });
        </script>
    @endif

    <div class="container p-0">
        <h1 class="h3 mb-3">Reward Page</h1>
        <div class="row">
            {{-- sidebar --}}
            <div class="col-md-5 col-xl-4">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0" id="userPoints">Points: {{auth()->user()->reward_point}}</h5>
                    </div>

                    <div class="list-group list-group-flush" role="tablist">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#claim-reward" role="tab">
                        Claim Reward
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#lucky-wheel-roulette" role="tab">
                        Lucky Wheel Roulette
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#reward-history" role="tab">
                        Reward History
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-xl-8">
                <div class="tab-content">
                    {{-- claim reward --}}
                    <div class="tab-pane fade show active" id="claim-reward" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Item</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($rewards as $reward)
                                    @if ($reward->quantity_available > 0)
                                        <div class="list-item">
                                            <div class="image">
                                                <img src="{{asset('user/images/reward/'.$reward->image)}}">
                                            </div>
                                            <div class="des">
                                                <div class="title">
                                                    <h1>{{ $reward->name }}</h1>
                                                </div>
                                                <br>
                                                <p>{{ $reward->description }}</p>
                                                <p>Points required: {{ $reward->points_required }}</p>
                                                <p>Quantity available: {{ $reward->quantity_available }}</p>
                                            </div>
                                            @if (auth()->user()->reward_point >= $reward->points_required)
                                                <button class="btn btn-primary redeem-btn" data-toggle="modal" data-target="#redeemModal" data-reward-id="{{ $reward->id }}">Redeem</button>
                                            @else
                                                <button class="btn btn-secondary" disabled>Not enough points</button>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="lucky-wheel-roulette" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <!-- If you try to spin the wheel again it behaves oddly (because I forked without understing the code completely:P) so I am hiding the button after the first click for now until I wrap my head around it -->

                                <svg id="luckyWheel" viewBox="0 0 730 730" xmlns="http://www.w3.org/2000/svg">

                                    <g id="wheel">
                                    <circle id="frame" cx="365" cy="365" r="347.6" id="circle402" fill="#fff" />
                                
                                    <g id="wheelSticks" fill="#fff"></g>
                                    <g id="wheelSectors"></g>
                                
                                    <g id="wheelMiddleCircleGroup">
                                        <g id="wheelMiddleCircleShadowContainer" opacity="0.2">
                                        <circle id="wheelMiddleCircleShadowCircle" cx="368.5" cy="368.5" r="54.5" />
                                        </g>
                                        <g class="wheelMiddleCircleInnerGroup" id="g449" fill="#fff">
                                        <circle id="wheelMiddleCircle" cx="365" cy="365" r="54.5" fill="#fff" />
                                        </g>
                                        <circle id="wheelMiddleCenterCircle" cx="365" cy="365" r="11.6" fill="#ccc" />
                                    </g>
                                    </g>
                                    <g id="wheelShadowGroup" opacity="0.15">
                                    <path id="wheelShadow" d="M46.9,372.5c0-181.7,147.4-329,329.1-329A327.3,327.3,0,0,1,556.3,97.2,327.3,327.3,0,0,0,365,35.9C183.3,35.9,35.9,183.3,35.9,365c0,115.2,59.2,216.5,148.8,275.3C101.3,580.6,46.9,482.9,46.9,372.5Z" />
                                    </g>
                                
                                    <g id="wheelIndicatorGroup">
                                    <path id="wheelIndicatorShadow" d="M 363.57585,6.9887496 C 342.35478,7.1789321 325.27613,25.281132 325.38193,47.392641 a 41.7,41.7 0 0 0 4.75773,19.003525 l 32.16722,59.694554 a 2.3,2.3 0 0 0 2.03579,1.27104 2.4,2.4 0 0 0 2.15115,-1.36841 L 397.91576,66.035684 A 43.8,43.8 0 0 0 402.42236,46.9218 C 402.23173,24.757331 384.82878,6.9363518 363.57585,6.9887496 Z" fill-opacity="0.22" />
                                    <path id="wheelIndicator" d="M 363.54132,1.1905768 A 38.4,38.4 0 0 0 330.11457,58.128194 l 32.09183,57.171796 a 2.1,2.1 0 0 0 2.0358,1.27104 2.6,2.6 0 0 0 2.18301,-1.23063 l 31.4336,-57.710474 a 39.6,39.6 0 0 0 4.47517,-18.30827 38.5,38.5 0 0 0 -38.79266,-38.1310792 z" fill="#ffffff" />
                                    <path id="wheelIndicatorInnerCircle" d="m 355.60575,31.838852 a 9.3,9.3 0 0 0 0.57623,8.140513 l 1.35764,1.790758 a 14.9,14.9 0 0 0 1.6754,1.28181 8.8,8.8 0 0 0 4.1452,1.291247 9.2,9.2 0 0 0 4.08058,-0.871118 7.6,7.6 0 0 0 2.63856,-1.771437 l 0.75221,-0.827153 a 8.6,8.6 0 0 0 1.71538,-3.880399 8.4,8.4 0 0 0 -0.31998,-4.208042 7.4,7.4 45 0 0 -2.16414,-3.708975 8.5,8.5 0 0 0 -3.6156,-2.139495 8.4,8.4 0 0 0 -4.3355,-0.23116 7.4,7.4 45 0 0 -3.80457,1.750787 l -0.78408,0.689367 a 12.2,12.2 0 0 0 -1.91733,2.693297 z" fill="#cccccc" />
                                    </g>

                                    <defs>
                                        <radialGradient id="grad1" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
                                          <stop offset="0%" style="stop-color:#007bff;stop-opacity:1" />
                                          <stop offset="100%" style="stop-color:#0062cc;stop-opacity:1" />
                                        </radialGradient>
                                    </defs>

                                    <circle cx="365" cy="365" r="40" fill="url(#grad1)" id="btnPlay" style="cursor:pointer;"/>
                                    <text x="365" y="370" fill="white" font-size="12px" text-anchor="middle" alignment-baseline="central" font-size="14" style="pointer-events:none;" id="btnPlayText">Start</text>
                                    <text x="365" y="400" text-anchor="middle" font-size="8px" fill="#CCCCCC" style="pointer-events:none;" id="btnPlayPoints">10 Points</text>

                                </svg>
                                
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="reward-history" role="tabpanel">
                        <div id="mapid" style="height: 400px;"></div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">History</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($rewardHistory as $history)
                                    <div class="list-item">
                                        <div class="image">
                                            <img src="{{asset('user/images/reward/'.$history->reward->image)}}">
                                        </div>
                                        <div class="des">
                                            <div class="title">
                                                <h1>{{ $history->reward->name }}</h1>
                                            </div>
                                            <p>{{ $history->reward->description }}</p>
                                            <p>Points used: {{ $history->reward->points_required }}</p>
                                            <p>Delivery address: {{ $history->delivery_address }}</p>
                                            <p>Redeem at: {{ $history->created_at }}</p>
                                        </div>
                                        <button class="track-button" data-claim-id="{{$history->id}}">Track</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="redeemModal" tabindex="-1" role="dialog" aria-labelledby="redeemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="margin-top: 130px">
                <form method="POST" action="" id="redeemForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="redeemModalLabel">Redeem Reward</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Deliver to:</p>
                        <input type="text" class="form-control" name="address" value="{{ auth()->user()->address }}" placeholder="Enter your delivery address" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirm Redeem</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // Define custom icons
        var startIcon = L.icon({
            iconUrl: "{{asset('user/images/reward/truck.png')}}", // URL to start icon image
            iconSize: [25, 37], // size of the icon
            iconAnchor: [12, 41], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -41] // point from which the popup should open relative to the iconAnchor
        });
        var endIcon = L.icon({
            iconUrl: "{{asset('user/images/reward/house.png')}}", // URL to end icon image
            iconSize: [25, 37],
            iconAnchor: [12, 41],
            popupAnchor: [0, -41]
        });
        var currentMarkers = [];

        function clearMapMarkers() {
            currentMarkers.forEach(function(marker) {
                map.removeLayer(marker);
            });
            currentMarkers = []; // Reset the array
        }

        // Initialize the map
        var map = L.map('mapid').setView([3.1390, 101.6869], 13); // Set initial view to some default coordinates
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        $('a[data-toggle="list"]').on('shown.bs.tab', function(e) {
            map.invalidateSize();
        });

        document.querySelectorAll('.track-button').forEach(button => {
            button.addEventListener('click', function() {
                clearMapMarkers();
                var claimId = this.getAttribute('data-claim-id'); // Assuming you have a data attribute for claim ID

                // AJAX request to your server to get start and end addresses
                $.ajax({
                    url: "{{ route('deliveryTracking') }}", // URL to your endpoint that returns addresses
                    type: 'POST',
                    data: { claimId: claimId },
                    success: function(response) {
                        // Get the addresses from the response
                        var startAddress = response.startAddress;
                        var endAddress = response.endAddress;

                        // Function to update the map with the given coordinates
                        function updateMap(startCoords, endCoords) {
                            clearMapMarkers();

                            map.setView(startCoords, 13);
                            var startMarker = L.marker(startCoords, {icon: startIcon}).addTo(map).bindPopup(startAddress).openPopup();
                            var endMarker = L.marker(endCoords, {icon: endIcon}).addTo(map).bindPopup(endAddress);

                            currentMarkers.push(startMarker);
                            currentMarkers.push(endMarker);
                        }

                        // Geocode the start address
                        $.get(`https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(startAddress)}&key=cedd451258b344b18b10cfb561f2bb15`, function(data) {
                            if (data.results.length > 0) {
                                var startCoords = [data.results[0].geometry.lat, data.results[0].geometry.lng];

                                // Geocode the end address
                                $.get(`https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(endAddress)}&key=cedd451258b344b18b10cfb561f2bb15`, function(data) {
                                    if (data.results.length > 0) {
                                        var endCoords = [data.results[0].geometry.lat, data.results[0].geometry.lng];
                                        updateMap(startCoords, endCoords);
                                    }
                                });
                            }
                        });
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    },
                    error: function(error) {
                        // Handle errors
                        console.log(error);
                    }
                });
            });
        });

    </script>

    <script>
        document.querySelectorAll('.redeem-btn').forEach(button => {
            button.addEventListener('click', function() {
                const rewardId = this.getAttribute('data-reward-id');
                // Update the form action
                document.getElementById('redeemForm').action = `{{ url('/user/redeem') }}/${rewardId}`;
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // CHANGEABLE
            // MAX = 10 (can be increased carefully)
            var sectionsCount = 10;
            var colors = [
                "#ba4d4e",
                "#4d90fe",
                "#5ab946",
                "#fc7800",
                "#f9c851",
                "#b613e7",
                "#ba4d4e",
                "#4d90fe",
                "#5ab946",
                "#fc7800"
            ];

            // CHAR LIMIT = 30 (can be increased carefully);
            var sectionText = [
                "-5",
                "0",
                "10",
                "20",
                "0",
                "-10",
                "-20",
                "30",
                "0",
                "-30"
            ];

            var textStyle = `
                fill: white;
                font-family: 'Roboto', sans-serif;
                font-size: 1.3rem;
                font-weight: bold;
            `;

            // END OF CHANGEABLE

            //  VARIABLES
            var wheel = $("#wheel"),
                active = $("#wheelIndicatorGroup"),
                $btnPlay = $("#btnPlay");
                $btnPlayText = $("#btnPlayText");
                $btnPlayPoints = $("#btnPlayPoints");

            var cx = 365,
                cy = -365,
                r = 347.6,
                strokeWidth = 18.5,
                stickWidth = 26,
                stickHeight = 10,
                lastRotation = 0,
                chosenSection,
                currentRotation,
                tolerance,
                deg;

            var rWithoutStroke = r - strokeWidth,
                sectionAngle = 360 / sectionsCount,
                calculatedTransformOrigin =
                sectionsCount % 2 == 0 ? "50% 50%" : "49.99999794% 50.9211%";

            var angles = [],
                stickElements = [],
                sectionElements = [],
                sectionTextElements = [];

            //FUNCTIONS
            function getRandomDeg(min = 5000, max = 9000) {
                var random = Math.floor(Math.random() * (max - min + 1)) + min;
                if ((random / sectionAngle) % 1 < 0.09) {
                random = random + 3;
                }

                return random;
            }

            function getStickPositionX(angle) {
                return cx + r * Math.cos((90 - angle) * (Math.PI / 180)) - stickWidth / 2;
            }

            function getStickPositionY(angle) {
                return (
                -1 * (cy + r * Math.sin((90 - angle) * (Math.PI / 180))) - stickHeight / 2
                );
            }

            function getStickRotateAngle(angle) {
                return -1 * (90 - angle);
            }

            function getStickRotatePositionX(angle) {
                return cx + r * Math.cos((90 - angle) * (Math.PI / 180));
            }

            function getStickRotatePositionY(angle) {
                return -1 * (cy + r * Math.sin((90 - angle) * (Math.PI / 180)));
            }

            function getSectionFirstPointX(angle) {
                return cx + rWithoutStroke * Math.cos((90 - angle) * (Math.PI / 180));
            }

            function getSectionFirstPointY(angle) {
                return (
                -1 * (cy + rWithoutStroke * Math.sin((90 - angle) * (Math.PI / 180)))
                );
            }

            function getSectionSecondPointX(angle) {
                return (
                cx +
                rWithoutStroke * Math.cos((90 - (angle - sectionAngle)) * (Math.PI / 180))
                );
            }

            function getSectionSecondPointY(angle) {
                return (
                -1 *
                (cy +
                    rWithoutStroke *
                    Math.sin((90 - (angle - sectionAngle)) * (Math.PI / 180)))
                );
            }

            function getStickElement(angle) {
                var stick = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                stick.setAttribute("x", getStickPositionX(angle));
                stick.setAttribute("y", getStickPositionY(angle));
                stick.setAttribute("width", "26");
                stick.setAttribute("height", "10");
                stick.setAttribute("rx", "4");
                stick.setAttribute("ry", "4");
                stick.setAttribute(
                "transform",
                "rotate(" +
                    getStickRotateAngle(angle) +
                    " " +
                    getStickRotatePositionX(angle) +
                    " " +
                    getStickRotatePositionY(angle) +
                    ")"
                );

                return stick;
            }

            function getSectionElement(id, angle, color) {
                var section = document.createElementNS(
                "http://www.w3.org/2000/svg",
                "path"
                );
                section.setAttribute("id", `${id}`);
                section.setAttribute(
                "d",
                `M365,365 ${getSectionFirstPointX(angle)},${getSectionFirstPointY(
                    angle
                )} A 328.5,328.5 0,0,0 ${getSectionSecondPointX(
                    angle
                )},${getSectionSecondPointY(angle)} Z`
                );
                section.setAttribute("transform", `translate(0)`);
                section.setAttribute("fill", `${color}`);
                section.setAttribute("stroke", "black");
                section.setAttribute("stroke-width", "2");

                return section;
            }

            function getSectionTextElement(id, text) {
                var textElement = document.createElementNS(
                "http://www.w3.org/2000/svg",
                "text"
                );
                textElement.setAttribute(
                "transform",
                `rotate(${-1 * (sectionAngle / 2)} 365 365)`
                );
                textElement.setAttribute("x", "0");
                textElement.setAttribute("y", "0");
                textElement.setAttribute("dominant-baseline", "middle");
                textElement.setAttribute("style", textStyle);

                var textPathElement = document.createElementNS(
                "http://www.w3.org/2000/svg",
                "textPath"
                );
                textPathElement.setAttribute("href", `#${id}`);
                textPathElement.setAttribute("text-anchor", "middle");
                textPathElement.setAttribute("startOffset", "190");
                textPathElement.innerHTML = text;

                textElement.append(textPathElement);
                return textElement;
            }

            function spinTheWheel() {
                $btnPlay.hide();
                $btnPlayText.hide();
                $btnPlayPoints.hide();

                var indicator = new TimelineMax();
                var spinWheel = new TimelineMax();

                indicator
                .to(active, 0.13, {
                    rotation: -10,
                    transformOrigin: "50% 28%",
                    ease: Power1.easeOut
                })
                .to(active, 0.13, { rotation: 3, ease: Power4.easeOut })
                .add("end");

                spinWheel
                .to(wheel, 10, {
                    rotation: deg,
                    transformOrigin: calculatedTransformOrigin,
                    ease: Power4.easeOut,
                    onUpdate: function () {
                    currentRotation = Math.round(
                        parseInt(this._targets[0]._gsap.rotation)
                    );
                    tolerance = currentRotation - lastRotation;
                    if (
                        Math.round(currentRotation) % (360 / sectionsCount) <=
                        tolerance
                    ) {
                        if (indicator.progress() > 0.2 || indicator.progress() === 0) {
                        indicator.play(0);
                        }
                    }
                    lastRotation = currentRotation;
                    },
                    onComplete: function () {
                    chosenSection = Math.ceil(
                        (360 - (deg / 360 - Math.trunc(deg / 360)) * 360) / sectionAngle
                    );
                    if (chosenSection == sectionsCount) chosenSection = 0;
                    // console.log(chosenSection);
                    // console.log("Spin Wheel Value:", sectionText[chosenSection]);
                    //TODO: ANNOUNCE THE PRIZE.
                    deg = getRandomDeg(); // Get a new random degree for the next spin
                    $btnPlay.show(); // Show the start button again
                    $btnPlayText.show(); // Show the start button again
                    $btnPlayPoints.show(); // Show the start button again
                    updateUserPoints(sectionText[chosenSection]);
                    }
                })
                .add("end");
            }

            function checkPointsAndSpin() {
                var userPointsText = $('#userPoints').text(); // Fetch the current points text
                var userPoints = parseInt(userPointsText.replace(/[^\d]/g, '')); // Extract the numerical value
                var costPerSpin = 10; // Define the cost per spin

                if (userPoints < costPerSpin) {
                    swal({
                        title: "Error!",
                        text: "Not enough points to spin the wheel.",
                        icon: "error",
                        button: "OK",
                    });
                } else {
                    deductPointsForSpin(costPerSpin);
                    spinTheWheel();
                }
            }

            deg = getRandomDeg();

            for (var i = 0; i < sectionsCount; i++) {
                angles.push(i * (360 / sectionsCount));
            }

            for (var i = 0; i < angles.length; i++) {
                stickElements.push(getStickElement(angles[i]));
            }

            for (var i = 0; i < angles.length; i++) {
                sectionElements.push(getSectionElement(`p${i}`, angles[i], colors[i]));
            }

            for (var i = 0; i < angles.length; i++) {
                sectionTextElements.push(getSectionTextElement(`p${i}`, sectionText[i]));
            }

            for (let i = 0; i < angles.length; i++) {
                $("#wheelSticks").append(stickElements[i]);
                $("#wheelSectors").append(sectionElements[i]);
                $("#wheelSectors").append(sectionTextElements[i]);
            }

            //   Buttons
            $btnPlay.click(function() {
                checkPointsAndSpin();
            });
        });
    </script>

    <script>
        function deductPointsForSpin(points) {
            $.ajax({
                url: "{{ route('deductPoints') }}",
                type: 'POST',
                data: {
                    points: points,
                },
                success: function(response) {
                    if (response.success) {
                        // Assuming you have an element with id 'userPoints' to display the points
                        $('#userPoints').text("Points: " + response.newPoints);
                    }
                },
                error: function() {
                    // Handle errors (e.g., user doesn't exist, not enough points, etc.)
                }
            });
        }

        function updateUserPoints(points) {
            $.ajax({
                url: "{{ route('updatePoints') }}",
                type: 'POST',
                data: {
                    points: points,
                },
                success: function(response) {
                    if (response.success) {
                        // Assuming you have an element with id 'userPoints' to display the points
                        $('#userPoints').text("Points: " + response.newPoints);

                        // Optionally, show a success message
                        swal({
                            title: "Points Updated",
                            text: "Your new points total is: " + response.newPoints,
                            icon: "success",
                            button: "OK",
                        });
                    } 
                },
                error: function() {
                    // Handle errors (e.g., user doesn't exist, not enough points, etc.)
                }
            });
        }
    </script>
@endsection