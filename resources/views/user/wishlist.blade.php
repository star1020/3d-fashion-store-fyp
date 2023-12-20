@extends('user/master')
@section('content')
    <!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.html" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Wishlist
			</span>
		</div>
	</div>
		

	<!-- Wishlist -->
	<form class="bg0 p-t-75 p-b-85">
        <div class="row">
            <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                <div class="m-l-25 m-r--38 m-lr-0-xl">
                    <div class="wrap-table-shopping-cart">
                        <table class="table-shopping-cart">
                            <tr class="table_head">
                                <th class="column-1">Product</th>
                                <th></th>
                                <th>Price</th>
                                <th>Stock Status</th>
                                <th>Actions</th>
                            </tr>

                            <tr class="table_row">
                                <td class="column-1">
                                    <div class="how-itemcart1">
                                        <img src="{{asset('user/images/item-cart-04.jpg')}}" alt="IMG">
                                    </div>
                                </td>
                                <td>Fresh Strawberries</td>
                                <td>$ 36.00</td>
                                <td>In Stock</td>
                                <td>
                                    <div class="wrap-btn-cart">
                                        <!-- Add Quick View button -->
                                        <a href="#" class="btn btn-secondary btn-sm quick-view-btn">Quick View</a>
                                
                                        <!-- Add to Cart button -->
                                        <a href="#" class="btn btn-primary btn-sm add-to-cart-btn">Add to Cart</a>
                                    </div>
                                </td>
                            </tr>

                            <tr class="table_row">
                                <td class="column-1">
                                    <div class="how-itemcart1">
                                        <img src="{{asset('user/images/item-cart-05.jpg')}}" alt="IMG">
                                    </div>
                                </td>
                                <td>Lightweight Jacket</td>
                                <td>$ 16.00</td>
                                <td>Out of Stock</td>
                                <td>
                                    <div class="wrap-btn-cart">
                                        <!-- Add Quick View button -->
                                        <a href="#" class="btn btn-secondary btn-sm quick-view-btn">Quick View</a>
                                
                                        <!-- Add to Cart button -->
                                        <a href="#" class="btn btn-primary btn-sm add-to-cart-btn">Add to Cart</a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
	</form>
@endsection