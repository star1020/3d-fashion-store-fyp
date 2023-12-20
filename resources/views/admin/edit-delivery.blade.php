@extends('admin/master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<style>
    .submit-btn {
        float: unset !important;
    }
        
.card-details {
    display: flex;
    flex-wrap: wrap;
    gap: 16px; 
    justify-content: center; 
}

.product-card {
    flex: 0 1 20%; 
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.product-card img {
    width: 100%; 
    height: auto; 
}

.card-body {
    padding: 15px !important;
    width: 100%; 
}

.card-body h5 {
    font-size: 1rem; 
    margin: 0;
    text-align: center;
}

.product-attributes {
    font-size: 0.9rem; 
    text-align: center; 
}

.buy-button {
    background-color: #4CAF50;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-align: center;
    width: auto; 
    margin-top: 8px; 
}

@media (max-width: 768px) {
    .product-card {
        flex: 0 1 46%; 
    }
}

@media (max-width: 480px) {
    .product-card {
        flex: 0 1 100%;
    }
}
</style>
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Delivery</h4>
            <form id="productForm" action="{{route('delivery.update', $ordersWithDelivery->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="orderId">Order ID</label>
                            <input type="text" class="form-control" id="orderId" name="orderId" value="{{ $ordersWithDelivery->id }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="deliveryId">Delivery ID</label>
                            <input type="text" class="form-control" id="deliveryId" name="deliveryId" value="{{ $ordersWithDelivery->delivery->id }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Cart Items</label>
                    <div class="form-card card-details">
                        @foreach ($allGroupedCartItems[$ordersWithDelivery->id] as $productId => $groupedItems)
                            @if ($firstItem = $groupedItems->first())
                                <div class="product-card">
                                    <img src="{{ $firstItem->product->productImgObj ? asset('/user/images/product/' . explode('|', $firstItem->product->productImgObj)[0]) : asset('/images/default.png') }}" alt="{{ $firstItem->product->productName }}">
                                    <div class="card-body">
                                        <h5>{{ $firstItem->product->productName }}</h5><br>
                                        @foreach ($groupedItems->groupBy('color') as $color => $items)
                                            <div class="product-attributes">
                                                <strong>{{ $color }}</strong> (
                                                @foreach ($items as $item)
                                                    {{ $item->size }}:{{ $item->quantity }}
                                                    @if (!$loop->last), @endif
                                                @endforeach
                                                )
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Delivery Address</label>
                    <textarea class="form-control" id="address" name="address" disabled>{{ $ordersWithDelivery->deliveryAddress }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="orderDate">Order Date</label>
                            <input type="text" class="form-control" id="orderDate" name="orderDate" value="{{ $ordersWithDelivery->orderDate }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="orderDate">Estimate Arrive Date</label>
                            <input type="text" class="form-control" id="orderDate" name="orderDate" value="{{ $ordersWithDelivery->delivery->estimatedDeliveryDate }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="orderDate">Order Status</label>
                    <select name="orderStatus" id="orderStatus" class="form-select" {{ $ordersWithDelivery->orderStatus === 'completed' ? 'disabled' : '' }}>
                        @if ($ordersWithDelivery->orderStatus === 'confirmed')
                            <option value="confirmed" selected>Confirmed</option>
                            <option value="courier_picked">Courier Picked</option>
                        @elseif ($ordersWithDelivery->orderStatus === 'courier_picked')
                            <option value="courier_picked" selected>Courier Picked</option>
                            <option value="on_the_way">On The Way</option>
                        @elseif ($ordersWithDelivery->orderStatus === 'on_the_way')
                            <option value="on_the_way" selected>On The Way</option>
                            <option value="ready_for_pickup">Ready For Pickup</option>
                        @elseif ($ordersWithDelivery->orderStatus === 'ready_for_pickup')
                            <option value="ready_for_pickup" selected>Ready For Pickup</option>
                            <option value="completed">Completed</option>
                        @elseif ($ordersWithDelivery->orderStatus === 'completed')
                            <option value="completed" selected>Completed</option>
                        @endif
                    </select>

                </div>

                <div id="deliveryManData" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="deliveryManName">Delivery Man Name</label>
                                <input type="text" class="form-control" id="deliveryManName" name="deliveryManName" value="{{ $ordersWithDelivery->delivery->deliveryManName }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="deliveryManPhone">Delivery Man Phone</label>
                                <input type="text" class="form-control" id="deliveryManPhone" name="deliveryManPhone" value="{{ $ordersWithDelivery->delivery->deliveryManPhone }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="deliveryCompany">Delivery Company</label>
                                <input type="text" class="form-control" id="deliveryCompany" name="deliveryCompany" value="{{ $ordersWithDelivery->delivery->deliveryCompany }}">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-gradient-primary me-2" id="submit-btn">Submit</button>
                <a href="/admin/all-delivery" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
        window.onload = function() {
            var currentStatus = document.getElementById('orderStatus').value;
            var deliveryData = document.getElementById('deliveryManData');
            var inputs = deliveryData.querySelectorAll('input');
            if (currentStatus !== 'confirmed') {
                document.getElementById('deliveryManData').style.display = 'block';
                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].disabled = true;
                }
                if(currentStatus === 'completed'){
                    document.getElementById('submit-btn').style.display = 'none';
                }
            }
        };
        document.getElementById('orderStatus').addEventListener('change', function() {
            var deliveryDataSection = document.getElementById('deliveryManData');
            var inputs = deliveryDataSection.getElementsByTagName('input');
            if (this.value !== 'confirmed') {
                deliveryDataSection.style.display = 'block';
            } else {
                deliveryDataSection.style.display = 'none';
            }
        });

        

        $('#productForm').on('submit', function(e) {
            var currentStatus = $('#orderStatus').val();
            var oldStatus = "{{ $ordersWithDelivery->orderStatus }}";
            var deliveryManName = $('#deliveryManName').val();
            var deliveryCompany = $('#deliveryCompany').val();
            var deliveryManPhone = $('#deliveryManPhone').val();
            var errors = [];

            if (currentStatus === oldStatus) {
                errors.push('No change in order status is allowed.');
            }

            if (currentStatus === 'courier_picked') {
                if (!deliveryManName || !deliveryCompany || !deliveryManPhone) {
                    if (!deliveryManName) {
                        errors.push('Delivery man name cannot be empty');
                    }
                    if (!deliveryCompany) {
                        errors.push('Delivery company cannot be empty');
                    }
                    if (!deliveryManPhone) {
                        errors.push('Delivery man phone number cannot be empty');
                    }
                } else {
                    var namePattern = /^[A-Za-z\s]+$/;
                    if (!namePattern.test(deliveryManName) || !namePattern.test(deliveryCompany)) {
                        errors.push('Invalid delivery man name or company.');
                    }

                    var phonePattern = /^(01\d{8,9})$/;
                    if (!phonePattern.test(deliveryManPhone)) {
                        errors.push('Invalid phone number (must be in the format 01212341234 or 0121234123).');
                    }
                }
            }

            if (errors.length > 0) {
                var errorHtml = "<ul style='text-align: left;'>";
                for (var i = 0; i < errors.length; i++) {
                    errorHtml += "<li>" + errors[i] + "</li>";
                }
                errorHtml += "</ul>";

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errorHtml,
                    confirmButtonText: 'Ok'
                });
                e.preventDefault();
            }
        });
</script>
@endsection

            
