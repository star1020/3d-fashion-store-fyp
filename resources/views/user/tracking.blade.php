@extends('user/master')
@section('content')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@php
use App\Enums\OrderStatus;
@endphp
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');body{background-color: #eeeeee;font-family: 'Open Sans',serif}.container{margin-top:50px;margin-bottom: 50px}.card{position: relative;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-orient: vertical;-webkit-box-direction: normal;-ms-flex-direction: column;flex-direction: column;min-width: 0;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0, 0, 0, 0.1);border-radius: 0.10rem}.card-header:first-child{border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0}.card-header{padding: 0.75rem 1.25rem;margin-bottom: 0;background-color: #fff;border-bottom: 1px solid rgba(0, 0, 0, 0.1)}.track{position: relative;background-color: #ddd;height: 7px;display: -webkit-box;display: -ms-flexbox;display: flex;margin-bottom: 60px;margin-top: 50px}.track .step{-webkit-box-flex: 1;-ms-flex-positive: 1;flex-grow: 1;width: 25%;margin-top: -18px;text-align: center;position: relative}.track .step.active:before{background: #FF5722}.track .step::before{height: 7px;position: absolute;content: "";width: 100%;left: 0;top: 18px}.track .step.active .icon{background: #ee5435;color: #fff}.track .icon{display: inline-block;width: 40px;height: 40px;line-height: 40px;position: relative;border-radius: 100%;background: #ddd}.track .step.active .text{font-weight: 400;color: #000}.track .text{display: block;margin-top: 7px}.itemside{position: relative;display: -webkit-box;display: -ms-flexbox;display: flex;width: 100%}.itemside .aside{position: relative;-ms-flex-negative: 0;flex-shrink: 0}.img-sm{width: 80px;height: 80px;padding: 7px}ul.row, ul.row-sm{list-style: none;padding: 0}.itemside .info{padding-left: 15px;padding-right: 7px}.itemside .title{display: block;margin-bottom: 5px;color: #212529}p{margin-top: 0;margin-bottom: 1rem}.btn-warning{color: #ffffff;background-color: #ee5435;border-color: #ee5435;border-radius: 1px}.btn-warning:hover{color: #ffffff;background-color: #ff2b00;border-color: #ff2b00;border-radius: 1px}
        html {
            background-color: #eeeeee;
        }
    </style>

    @if(Session::has('membership_upgrade_message'))
        <script>
            swal({
                title: "Success!",
                text: "{{ Session::get('membership_upgrade_message') }}",
                icon: "success",
                button: "OK",
            });
        </script>
    @endif

    <!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.html" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Order Tracking
			</span>
		</div>
	</div>
		
    <div class="container">
        <article class="card">
            <header class="card-header"> My Orders / Tracking </header>
            <div class="card-body">
                <h6>Order ID: {{ $order->id }}</h6>
                <article class="card">
                    <div class="card-body row">
                        <div class="col"> <strong>Estimated Delivery time:</strong> <br>{{ $delivery->estimatedDeliveryDate ?? 'Not available' }} </div>
                        <div class="col"> <strong>Shipping BY: </strong> <br>{{ $delivery->deliveryManName ?? 'Not available' }} | <i class="fa fa-phone"></i> {{ $delivery->deliveryManPhone ?? 'Not available' }} | <i class="fa fa-building"></i> {{ $delivery->deliveryCompany ?? '' }} </div>
                        <div class="col"> <strong>Status:</strong> <br> {{ ucwords(str_replace('_', ' ', $order->orderStatus)) }} </div>
                    </div>
                </article>
                <div class="track">
                    <div class="step {{ $order->orderStatus >= OrderStatus::Confirmed->value || $order->orderStatus == OrderStatus::Completed->value ? 'active' : '' }}">
                        <span class="icon"> <i class="fa fa-check"></i> </span>
                        <span class="text">Order confirmed</span>
                    </div>
                    <div class="step {{ $order->orderStatus >= OrderStatus::CourierPicked->value || $order->orderStatus == OrderStatus::Completed->value ? 'active' : '' }}">
                        <span class="icon"> <i class="fa fa-user"></i> </span>
                        <span class="text">Picked by courier</span>
                    </div>
                    <div class="step {{ $order->orderStatus >= OrderStatus::OnTheWay->value || $order->orderStatus == OrderStatus::Completed->value ? 'active' : '' }}">
                        <span class="icon"> <i class="fa fa-truck"></i> </span>
                        <span class="text">On the way</span>
                    </div>
                    <div class="step {{ $order->orderStatus == OrderStatus::ReadyForPickup->value || $order->orderStatus == OrderStatus::Completed->value ? 'active' : '' }}">
                        <span class="icon"> <i class="fa fa-cube"></i> </span>
                        <span class="text">Ready for pickup</span>
                    </div>
                    <div class="step {{ $order->orderStatus == OrderStatus::Completed->value ? 'active' : '' }}">
                        <span class="icon"> <i class="fa fa-flag-checkered"></i> </span>
                        <span class="text">Order completed</span>
                    </div>
                </div>
                

                <hr>
                <ul class="row">
                    @foreach ($cartItems as $cartItem)
                        @php
                            // Explode the productImgObj field to get an array of image filenames
                            $imageFiles = explode('|', $cartItem->product->productImgObj);
                            // Get the first image filename
                            $firstImageFile = $imageFiles[0];
                        @endphp

                        <li class="col-md-4">
                            <figure class="itemside mb-3">
                                <div class="aside">
                                    <!-- Display the first image from the productImgObj -->
                                    <img src="{{ asset('user/images/product/' . $firstImageFile) }}" class="img-sm border">
                                </div>
                                <figcaption class="info align-self-center">
                                    <p class="title">{{ $cartItem->product->productName }}</p>
                                    <span class="text-muted">{{ $cartItem->color }},{{ $cartItem->size }} {{ $cartItem->quantity }}</span><br>
                                    <span class="text-muted">RM {{ $cartItem->product->price }}</span>
                                </figcaption>
                            </figure>
                        </li>
                    @endforeach
                </ul>
                <hr>
                
                <div class="d-flex justify-content-between">
                    <a href="/user/payment-history" class="btn btn-warning" data-abc="true">
                        <i class="fa fa-chevron-left"></i> Back to orders
                    </a>
                    @if ($order->orderStatus === OrderStatus::ReadyForPickup->value)
                        <form id="orderReceivedForm-{{ $order->id }}" action="{{ url('/user/mark-order-received', ['orderId' => $order->id]) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmOrderReceived({{ $order->id }})" class="btn btn-success">
                                Order Received <i class="fa fa-check"></i>
                            </button>
                        </form>
                    @endif


                </div>
            </div>
        </article>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
    function confirmOrderReceived(orderId) {
        Swal.fire({
            title: 'Confirm Order Received',
            text: "Are you sure you want to mark this order as received?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, mark it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('orderReceivedForm-' + orderId).submit();
            }
        });
    }
</script>
@endsection