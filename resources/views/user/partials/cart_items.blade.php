        
                @foreach ($cartItems as $item)
                    <li class="header-cart-item flex-w flex-t m-b-12" id="cartItem-{{ $item->id }}">
                        <div class="header-cart-item-img cart-item-image" data-item-id="{{ $item->id }}">
                            <img src="{{ asset('user/images/product/' . explode('|', $item->product->productImgObj)[0]) }}" alt="Product Image" >
                        </div>

                        <div class="header-cart-item-txt p-t-2">
                            <a href="{{ route('product.detail', $item->product->id) }}" class="header-cart-item-name m-b-12 hov-cl1 trans-04">
                                {{ $item->product->productName }}
                            </a>
                            <span class="header-cart-item-info">
                                {{ $item->color }}, {{ $item->size}}
                                <br/>{{ $item->quantity }} x RM{{ number_format($item->product->price, 2) }}
                            </span>
                        </div>
                    </li>
                @endforeach
                

            <script>
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
                            $('#cartItem-' + itemId).remove();

                            if(response.newTotal !== undefined) {
                                $('#totalPrice').text('Total: RM' + response.newTotal);
                            }
                            if ($('.header-cart-item').length === 0) {
                                $('.header-cart-content').html('<p>Your cart is empty</p>');
                            }
                            $('.icon-header-noti').attr('data-notify', response.totalQuantity);
                            swal("The item has been removed from your cart!", {
                                icon: "success",
                            });
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
        </script>