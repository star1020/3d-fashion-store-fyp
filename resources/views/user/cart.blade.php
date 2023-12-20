@extends('user/master')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
	.cart-info--count {
  background-color: #6c757d;
  color: white;
  padding: 0.25em 0.75em;
  border-radius: 50px;
  float: right;
  font-weight: 700;
  font-size: 1.1em;
}
.product-checkbox, .all-product-checkbox{
    margin: 10px;
}
.disabled {
    pointer-events: none;
}

.unavailable {
    background-color: #cccccc;
    color: #666666;
    opacity: 0.4;
}
.unavailable .product-unavailable-message {
    color: red;
    font-weight: bold;
}
	</style>
    <!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="/" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Shoping Cart
			</span>
		</div>
	</div>
		

	<!-- Shoping Cart -->
	<form id="checkoutForm" class="bg0 p-t-75 p-b-85" action="/user/make-order" method="POST">
		@csrf
		<input type="hidden" name="shippingCostHidden" id="shippingCostHidden" value="undefined">
		<input type="hidden" name="discountRate" id="discountRateHidden" value="{{$discountRate}}">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
					<div class="m-l-25 m-r--38 m-lr-0-xl">
						<div class="wrap-table-shopping-cart">
							<table class="table-shopping-cart">
								@php
									$itemTotalPrice = 0;
									foreach ($cartItems as $item) {
										$itemTotalPrice += $item->quantity * $item->product->price;
									}
									// $discount = 10; // Example discount
								@endphp
								@if ($cartItems->isNotEmpty())
									<tr class="table_head">
										<th><input class="all-product-checkbox" type="checkbox" id="toggleAll" name="all"></th>
										<th class="column-1">Product</th>
										<th class="column-2"></th>
										<th class="column-3">Price</th>
										<th class="column-4">Quantity</th>
										<th class="column-5">Total</th>
									</tr>
								@endif
								@forelse ($cartItems as $item)
								@php
								$colors = explode('|', $item->product->color);
								$sizes = explode('|', $item->product->size);
								$stocks = explode('|', $item->product->stock);
								$isProductDeleted = $item->product->deleted == 1;
								$stockData = [];
								foreach ($colors as $colorIndex => $color) {
									$sizeList = explode(',', $sizes[$colorIndex]);
									$stockList = explode(',', $stocks[$colorIndex]);

									foreach ($sizeList as $sizeIndex => $size) {
										$stockData[$color][$size] = $stockList[$sizeIndex];
									}
								}

								$stockJson = json_encode($stockData);

								$subPrice = $item->quantity * $item->product->price;
								
								@endphp
								
								<tr class="table_row {{ $isProductDeleted ? 'unavailable' : '' }}" id="cart-item-{{ $item->id }}">
									<td>
										@if($isProductDeleted)
										<input type="checkbox" class="product-checkbox" name="selectedItems[]" value="{{ $item->id }}" id="checkbox-{{ $item->id }}" disabled>
										@else
											<input type="checkbox" class="product-checkbox" name="selectedItems[]" value="{{ $item->id }}" id="checkbox-{{ $item->id }}">
										@endif
									</td>
									<td class="column-1">
										<div class="how-itemcart1 cart-item-image" data-item-id="{{ $item->id }}">
											<img src="{{ asset('user/images/product/' . explode('|', $item->product->productImgObj)[0]) }}" alt="IMG">
										</div>
									</td>
									<td class="column-2">
										{{ $item->product->productName }} <br/> {{ $item->color }}, {{ $item->size}}
									</td>
									@if($isProductDeleted)
										<td class="column-3" colspan=5><div class="product-unavailable-message">Not Available</div></td>
									@else
									<td class="column-3">RM{{ number_format($item->product->price, 2) }}</td>
									<input type="hidden" class="selected-color" data-item-id="{{ $item->id }}" value="{{ $item->color }}">
    								<input type="hidden" class="selected-size" data-item-id="{{ $item->id }}" value="{{ $item->size }}">
									<td class="column-4">
										<div class="wrap-num-product flex-w m-l-auto m-r-0">
											<div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
												<i class="fs-16 zmdi zmdi-minus"></i>
											</div>
											
											<input class="mtext-104 cl3 txt-center num-product" id="quantityInput-{{ $item->id }}" type="number" name="num-product1" value="{{ $item->quantity }}" data-item-id="{{ $item->id }}" min="1">

											<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
												<i class="fs-16 zmdi zmdi-plus"></i>
											</div>
										</div>
									</td>
									<td class="column-5" id="subPrice-{{ $item->id }}">RM{{ number_format($subPrice, 2) }}</td>
									@endif
								</tr>
								<script>
									window.stockData = window.stockData || {};
									window.stockData['{{ $item->id }}'] = @json($stockData);
									$(document).ready(function() {
										$('.num-product').each(function() {
											var $input = $(this); // Define $input here
											var itemId = $input.data('item-id');
											var stockDataForItem = window.stockData[itemId];
											var selectedColor = $('.selected-color[data-item-id="' + itemId + '"]').val();
											var selectedSize = $('.selected-size[data-item-id="' + itemId + '"]').val();
											var $tableRow = $('#cart-item-' + itemId);

											if(stockDataForItem[selectedColor] && stockDataForItem[selectedColor][selectedSize]) {
												var maxStock = stockDataForItem[selectedColor][selectedSize];
												if (!$tableRow.hasClass('unavailable') && maxStock === '0') {
													$tableRow.addClass('unavailable');
													$tableRow.find('.column-2').append('<div class="product-unavailable-message">Out of Stock</div>');
													$tableRow.find('.product-checkbox').prop('disabled', true);
													$tableRow.find('.btn-num-product-down, .btn-num-product-up').prop('disabled', true).addClass('disabled');
                        							$input.prop('disabled', true);
												}
												$input.attr('max', maxStock);
												
											} else {
												if (!$tableRow.hasClass('unavailable')) {
													$tableRow.addClass('unavailable');
													$tableRow.find('.column-3').html('<div class="product-unavailable-message ">This Color is Removed</div>').attr('colspan', '5');
													$tableRow.find('.column-4, .column-5').remove();
													$tableRow.find('.product-checkbox').prop('disabled', true);
                        							$input.prop('disabled', true);
													console.error("Stock data not found for", selectedColor, selectedSize);
												}
											}
										});
										
									});

								</script>
								@empty
									<tr><td colspan="6" class="text-center">Your cart is empty.</td></tr>
								@endforelse
							</table>
						</div>
					</div>
				</div>

				<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
					<div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
						<h4 class="mtext-109 cl2 p-b-30">
							<span style="font-size: 30px;">Cart Totals</span>
							<span class="cart-info--count">
								0
							</span>
						</h4>
						
						<div class="flex-w flex-t bor12 p-b-13">
							<div class="size-208">
								<span class="stext-110 cl2">
									Subtotal:
								</span>
							</div>

							<div class="size-209">
								<span class="mtext-110 cl2" id="subItemPrice">
									RM{{ $itemTotalPrice }}
								</span>
							</div>
						</div>

						<div class="flex-w flex-t bor12 p-t-15 p-b-30">
							<div class="size-208 w-full-ssm">
								<span class="stext-110 cl2">
									Shipping:
								</span>
							</div>

							<div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
								<div class="size-209">
									<span class="mtext-110 cl2" id="shippingCost">
										undefined
									</span>
								</div>
								
								<div class="p-t-15">
									<span class="stext-112 cl8">
										Delivery Address
									</span>
									<div class="bor8 bg0 m-b-12">
										<input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="address" placeholder="Address">
									</div>
									
									<div class="bor8 bg0 m-b-12">
										<input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="postcode" placeholder="Postcode / Zip">
									</div>

									<div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12 m-t-9">
										<select class="js-select2" name="state" id="stateSelect">
											<option value="">Select a state...</option>
											<option value="Johor">Johor</option>
											<option value="Kedah">Kedah</option>
											<option value="Kelantan">Kelantan</option>
											<option value="Kuala Lumpur">Kuala Lumpur</option>
											<option value="Labuan">Labuan</option>
											<option value="Malacca">Malacca</option>
											<option value="Negeri Sembilan">Negeri Sembilan</option>
											<option value="Pahang">Pahang</option>
											<option value="Penang">Penang</option>
											<option value="Perak">Perak</option>
											<option value="Perlis">Perlis</option>
											<option value="Putrajaya">Putrajaya</option>
											<option value="Sabah">Sabah</option>
											<option value="Sarawak">Sarawak</option>
											<option value="Selangor">Selangor</option>
											<option value="Terengganu">Terengganu</option>
										</select>
										<div class="dropDownSelect2"></div>
									</div>

									<div class="rs1-select2 rs2-select2 bor8 bg0 m-b-22 m-t-9">
										<select class="js-select2" name="country">
											<option value="Malaysia">Malaysia</option>
										</select>
										<div class="dropDownSelect2"></div>
									</div>
									
										
								</div>
							</div>
						</div>
						<div class="flex-w flex-t bor12 p-t-15 p-b-30">
							<div class="size-208 w-full-ssm">
								<span class="stext-110 cl2" >
									Discount:
								</span><br>
								<span class="mtext-110 cl2" id="discountRate"> 
									<b>{{$discountRate}}%</b>
								</span>
							</div>

							<div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
								<div class="size-209">
									<span class="mtext-110 cl2" style="color: green;" id="discount"> 
										<b>RM0</b>
									</span>
									
								</div>
								
							</div>
						</div>

						<div class="flex-w flex-t p-t-27 p-b-33">
							<div class="size-208">
								<span class="mtext-101 cl2">
									Total:
								</span>
							</div>

							<div class="size-209 p-t-1">
								<span class="mtext-110 cl2" id="finalTotalPrice">
									RM{{ $totalPrice }}
								</span>
							</div>
						</div>

						<button type="submit" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
							Proceed to Checkout
						</button>
						  
					</div>
				</div>
			</div>
		</div>
	</form>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script>
    var isCartPage = true;
	$(document).ready(function() {
		$('form').on('keypress', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    if (window.isCartPage) {
        $('.js-show-cart').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Forcefully hide the cart pop-up
            $('.show-header-cart').hide(); // Replace with the actual selector for your cart pop-up
        });
    }
	function checkAllCheckboxes() {
		var enabledCheckboxes = $('.product-checkbox').not(':disabled');
		var checkedEnabledCheckboxes = enabledCheckboxes.filter(':checked');
		var allChecked = enabledCheckboxes.length === checkedEnabledCheckboxes.length;
		if (enabledCheckboxes.length !== 0){
			$('#toggleAll').prop('checked', allChecked);
		}
	}
	function updateTotal() {
		var subtotal = 0;
		var totalCount = 0;
		$('.product-checkbox:checked').each(function() {
			const itemId = $(this).val();
			console.log("Item ID: " + itemId); // Debugging
			const quantity = parseInt($(`#quantityInput-${itemId}`).val());
			const price = parseFloat($(`#subPrice-${itemId}`).text().replace('RM', '').replace(',',''));
			subtotal += price;
			totalCount += quantity;
		});

		$('#subItemPrice').text(`RM${subtotal.toFixed(2)}`);
		$('.cart-info--count').text(totalCount);

		const shippingCost = parseFloat($('#shippingCost').text().replace('RM', ''));
		const discountRate = parseFloat($('#discountRate').text().replace('%', '').replace('<b>', '').replace('</b>', ''));
		const discount = subtotal * discountRate/100;
		// Calculate and update the final total
		let finalTotal = subtotal + shippingCost - discount;
		if (subtotal === 0) {
			finalTotal = 0;
		}
		$('#finalTotalPrice').text(`RM${finalTotal.toFixed(2)}`);
		$('#discount').html(`<b>RM${discount.toFixed(2)}</b>`);
		checkAllCheckboxes();
	}

// Event listener for checkbox changes
$('.product-checkbox').on('change', function() {
        updateTotal();
    });

    // Event listener for the "Toggle All" checkbox
    $('#toggleAll').on('change', function() {
		$('.product-checkbox').each(function() {
			if (!$(this).is(':disabled')) {
				$(this).prop('checked', $('#toggleAll').is(':checked'));
			}
		});
		checkAllCheckboxes();
		updateTotal();
	});
    var shippingCost = 0;
	$('#stateSelect').on('change', function() {
		if (this.value === "") {
        	$('#shippingCost').text(`undefined`);
			$('#shippingCostHidden').val(`undefined`);
		} else {
			updateShippingCost(this.value);
		}
		updateTotal();
    });
	function updateShippingCost(selectedState) {
		// List of East Malaysia states
		var eastMalaysiaStates = ['Sabah', 'Sarawak', 'Labuan'];

		// Check if the selected state is in East Malaysia
		shippingCost = eastMalaysiaStates.includes(selectedState) ? 10 : 5;
		
		// Update the shipping cost display
		$('#shippingCost').text(`RM${shippingCost.toFixed(2)}`);
		$('#shippingCostHidden').val(shippingCost.toFixed(2));
	}

updateTotal();

window.onload = function() {
    document.querySelectorAll('.num-product').forEach(function(quantityInput) {
        var value = parseInt(quantityInput.value);
        var max = parseInt(quantityInput.getAttribute('max'));

        if (value > max) {
            quantityInput.value = max;
            $(quantityInput).trigger('change');
        }
		if(value == '0' && max !== 0){
            quantityInput.value = 1;
            $(quantityInput).trigger('change');
		}
    });
};

document.querySelectorAll('.num-product').forEach(function(quantityInput) {
    quantityInput.addEventListener('input', function() {
        var value = parseInt(this.value);
        var min = parseInt(this.getAttribute('min'));
        var max = parseInt(this.getAttribute('max'));

        if (!isFinite(value)) {
            this.value = min;
            return;
        }
        if (value < min) {
            this.value = min;
        } else if (value > max) {
            this.value = max;
        }
    });

	$(document).on('change', '.num-product', function() {
    var itemId = $(this).data('item-id');
    var quantity = $(this).val();
    $.ajax({
        url: '/user/update-cart-item',
        type: 'POST',
        data: {
            itemId: itemId,
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            document.querySelector(`#subPrice-${itemId}`).textContent = `RM${response.newSubPrice}`;
			document.querySelector('#discount').textContent = `RM${response.discount}`;
			updateTotal();
			updateCartTotalQuantity();
        },
        error: function(xhr) {
            console.error('Error updating cart:', xhr.responseText);
        }
    });
});
});
function updateCartTotalQuantity() {
    $.ajax({
        url: '../user/update-cart-header-quantity', // URL to the route that returns total quantity
        type: 'GET',
        success: function(response) {
            $('.icon-header-noti.js-show-cart').attr('data-notify', response.totalQuantity);
        },
        error: function(xhr) {
            // Handle error
        }
    });
}

                $(document).ready(function() {
                 $('.cart-item-image').on('click', function(event) {
            event.preventDefault(); 
            var itemId = $(this).data('item-id');
            swal({
                title: "Are you sure?",
                text: "Do you want to remove this item from your cart?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'POST',
                        url: '../user/remove-from-cart', // Update with your URL
                        data: {
                            _token: '{{ csrf_token() }}',
                            itemId: itemId
                        },
                        success: function(response) {        
							$('#cart-item-' + itemId).remove();

                        // Check if all items are removed
                        if ($('.table_row').length === 0) {
                            $('.table-shopping-cart').html('<tr><td colspan="6" class="text-center">Your cart is empty.</td></tr>');
                        }
						$('.icon-header-noti').attr('data-notify', response.totalQuantity);
                        swal("The item has been removed from your cart!", {
                            icon: "success",
                        });
						updateCartTotalQuantity()
                        },
                        error: function(xhr) {
                            console.error("Error removing item:", xhr.responseText);
                            var errorMessage = xhr.responseJSON.error;
                            swal("Error!", errorMessage, "error");
                        }
                    });
                }
            });
        });});
});

$(document).ready(function() {
    $('#checkoutForm').on('submit', function(e) {
        var errorMessages = [];
        var selectedItems = $("input[name='selectedItems[]']:checked").length;
        var address = $("input[name='address'][placeholder='Address']").val();
        var postcode = $("input[name='postcode'][placeholder='Postcode / Zip']").val();
        var state = $("#stateSelect").val();
        var country = $("select[name='country']").val();

        if (selectedItems === 0) {
            errorMessages.push('Please select at least one item to proceed.');
        }

        if (!address) {
    		errorMessages.push('Please enter your delivery address.');
		} else if (address.length < 3) {
			errorMessages.push('The address must be at least 3 characters long.');
		}

		if (!postcode) {
			errorMessages.push('Please enter your postcode or zip code.');
		} else if (!/^\d{5}$/.test(postcode)) {
			errorMessages.push('The postcode must be a 5-digit number.');
		}

        if (!state) {
            errorMessages.push('Please select a state.');
        }

        if (!country) {
            errorMessages.push('Please select your country.');
        }

        if (errorMessages.length > 0) {
			e.preventDefault();
            Swal.fire({
                title: 'Error!',
                html: errorMessages.join('<br>'),
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return false; // Stop the form from submitting
        }
    });
});



        </script>
@endsection