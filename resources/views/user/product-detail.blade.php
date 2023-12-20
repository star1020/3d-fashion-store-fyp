@extends('user/master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<style>
    
	.block2-txt-child2 {
		display: flex;
		flex-direction: column;
		align-items: flex-end; /* Align children to the right */
	}
	
	.rating-below-icon {
		margin-top: 5px; /* Adjust as needed for spacing */
	}
	
	.rating-low {
		color: red;
		font-weight: bold;
	}
	
	.rating-high {
		color: green;
		font-weight: bold;
	}
	.bg4{
		background-color:#9A9A9A;
	}
	.wrap-modal1 {
		display: flex;
		justify-content: center;
		align-items: center;
		position: fixed; 
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.3); 
		z-index: 1000;
	}
	.wrap-modal1 img {
		object-fit: contain;
		width: 320px;
		height: 320px;
	}
	.select2-container--default .select2-results__option[aria-disabled=true] {
	color: #999;
	padding-top: 10px;
	padding-bottom: 10px;
	padding-left: 20px; }

	.review-images img {
		min-width: 100px; 
		min-height: 100px; 
		max-width: 100px; 
		max-height: 100px;
	}
	.like-buttons button:hover {
		color: #333;
	}
	.like-buttons button.clicked {
		color: #717fe0;
	}
	.like-buttons button {
		margin: 10px 5px;
	}
	.text-muted {
		margin-top: 5px; /* Adds space above the like count */
	}

	.comment-time {
		font-size: 0.8em;
		color: #777;
	}

	.admin-reply {
		flex-basis: 100%;
		margin-left: 60px;
		background-color: #e9f0f5;
		padding: 10px;
		margin-top: 20px;
		border-radius: 8px;
		border-left: 3px solid #717fe0;
	}

	.admin-reply p {
		margin-bottom: 0;
		color: #666666;
		font-size: 14px;
	}

	.admin-reply:before {
		content: 'Admin Reply:';
		font-weight: bold;
		display: block;
		margin-bottom: 5px;
		color: #717fe0;
	}

	.badge {
		font-size: 0.8em;
		color: #777;
		font-weight: lighter;
	}

	</style>
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.1.1/model-viewer.min.js"></script>
   <!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="/" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<a href="/product#{{ $mainProduct->productType }}" class="stext-109 cl8 hov-cl1 trans-04">
			{{ $mainProduct->productType->label() }}
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				{{ $mainProduct->productName }}
			</span>
		</div>
	</div>
		

	<!-- Product Detail -->
	<section class="sec-product-detail bg0 p-t-65 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-7 p-b-30">
					<div class="p-l-25 p-r-30 p-lr-0-lg">
						<div class="wrap-slick3 flex-sb flex-w">
							<div class="wrap-slick3-dots"></div>
							<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>
							@php
							$imageFiles = explode('|', $mainProduct->productImgObj);
							@endphp
							<div class="slick3 gallery-lb">
							@php
							$gltfWithImages = [];
							$imagesToShow = [];

							foreach ($imageFiles as $file) {
								$extension = pathinfo($file, PATHINFO_EXTENSION);
								$baseName = pathinfo($file, PATHINFO_FILENAME);
								
								if ($extension === 'gltf' || $extension === 'glb') {
									$gltfWithImages[$file] = null; // Initialize with null
									foreach ($imageFiles as $image) {
										if (pathinfo($image, PATHINFO_FILENAME) === $baseName && pathinfo($image, PATHINFO_EXTENSION) !== 'gltf') {
											$gltfWithImages[$file] = $image;
											break;
										}
									}
								} 
							}

							foreach ($imageFiles as $image) {
								$extension = pathinfo($image, PATHINFO_EXTENSION);
								if (!in_array($extension, ['gltf', 'glb'])) {
									$baseName = pathinfo($image, PATHINFO_FILENAME);
									if (!in_array($baseName . '.gltf', array_keys($gltfWithImages)) && !in_array($baseName . '.glb', array_keys($gltfWithImages))) {
										$imagesToShow[] = $image;
									}
								}
							}
						@endphp

						@foreach($gltfWithImages as $gltfFile => $imageFile)
						<div class="item-slick3" data-thumb="{{ asset('user/images/product/' . ($imageFile ?? 'default-thumbnail.jpg')) }}">
							<div class="wrap-pic-w pos-relative">
								<model-viewer src="{{ asset('user/images/product/' . $gltfFile) }}"
											ar
											ar-modes="webxr scene-viewer quick-look"
											camera-controls
											poster="{{ asset('user/images/product/' . ($imageFile ?? 'default-poster.webp')) }}"
											shadow-intensity="1"
											style="height:700px;width:100%">
								</model-viewer>
							</div>
						</div>
					@endforeach

					{{-- Display standalone images --}}
					@foreach($imagesToShow as $imageFile)
						<div class="item-slick3" data-thumb="{{ asset('user/images/product/' . $imageFile) }}">
							<div class="wrap-pic-w pos-relative">
								<img src="{{ asset('user/images/product/' . $imageFile) }}" alt="IMG-PRODUCT">
								<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="{{ asset('user/images/product/' . $imageFile) }}">
									<i class="fa fa-expand"></i>
								</a>
							</div>
						</div>
					@endforeach

							</div>
						</div>
					</div>
				</div>
					
				<div class="col-md-6 col-lg-5 p-b-30">
					<div class="p-r-50 p-t-5 p-lr-0-lg">
						<h4 class="mtext-105 cl2 js-name-detail p-b-14">
						{{ $mainProduct->productName}}
						</h4>

						<span class="mtext-106 cl2">
						RM {{ number_format($mainProduct->price, 2)}}
						</span>

						<p class="stext-102 cl3 p-t-23">
						{{ $mainProduct->productDesc}}
						</p>
						
						<!--  -->
						@php
							$colors = explode('|', $mainProduct->color); // 'Blue|Red'
							$sizePairs = explode('|', $mainProduct->size); // 'S,M,L|L'
							$quantityPairs = explode('|', $mainProduct->stock); // '30,50,20|20'

							$colorSizeMap = [];
							$maxQuantityMap = [];

							foreach ($colors as $index => $color) {
								$colorTrimmed = trim($color);
								$sizes = isset($sizePairs[$index]) ? explode(',', $sizePairs[$index]) : [];
								$quantities = isset($quantityPairs[$index]) ? explode(',', $quantityPairs[$index]) : [];

								$colorSizeMap[$colorTrimmed] = $sizes;

								foreach ($sizes as $sizeIndex => $size) {
									$sizeTrimmed = trim($size);
									$maxQuantity = isset($quantities[$sizeIndex]) ? (int)$quantities[$sizeIndex] : 0;

									// Update maxQuantityMap to include color as a key
									if (!isset($maxQuantityMap[$colorTrimmed])) {
										$maxQuantityMap[$colorTrimmed] = [];
									}
									$maxQuantityMap[$colorTrimmed][$sizeTrimmed] = $maxQuantity;
								}
							}
						@endphp
						<form action="/user/add-to-cart" method="POST">
    					@csrf
						<input type="hidden" name="productId" value="{{ $mainProduct->id }}">
						<input type="hidden" name="maxProductQuantity" type="num" id="maxProductQuantity" value="0"/>

						<div class="p-t-33">
							<div class="flex-w flex-r-m p-b-10">
								<div class="size-203 flex-c-m respon6">
									Color
								</div>

								<div class="size-204 respon6-next">
									<div class="rs1-select2 bor8 bg0">
										<select class="js-select2" name="color" id="colorSelect" onchange="updateSizes()">
											<option value="">Choose an option</option>
											@foreach ($colorSizeMap as $color => $sizes)
												<option value="{{ $color }}">{{ $color }}</option>
											@endforeach
										</select>
										<div class="dropDownSelect2" id="colorDropDownSelect"></div>
									</div>
								</div>
							</div>
							<div class="flex-w flex-r-m p-b-10">
								<div class="size-203 flex-c-m respon6">
									Size
								</div>

								<div class="size-204 respon6-next">
									<div class="rs1-select2 bor8 bg0">
										<select class="js-select2" name="size" id="sizeSelect" disabled onchange="updateQuantity()">
											<option value="">--Please Select a Color--</option>
										</select>
										<div class="dropDownSelect2"></div>
									</div>
								</div>
							</div>
						</div>

							<div class="flex-w flex-r-m p-b-10">
								<div class="size-204 flex-w flex-m respon6-next">
									<div class="wrap-num-product flex-w m-r-20 m-tb-10">
										<div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
											<i class="fs-16 zmdi zmdi-minus"></i>
										</div>

										<input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" id="quantityInput" value="0" min="0" max="0" required>

										<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
											<i class="fs-16 zmdi zmdi-plus"></i>
										</div>
									</div>

									<button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail" type="submit">
										Add to cart
									</button>

									@if (!empty($mainProduct->productTryOnQR))
										<button class="flex-c-m stext-101 cl0 size-101 bg4 bor1 hov-btn1 p-lr-15 trans-04 js-show-modal1" id="tryOnBtn" type="button" style="margin-top:50px">
											Try It Now
										</button>
									@endif
								</div>
							</div>	
						</div>
						</form>
						<!--  -->
						<div class="flex-w flex-m p-l-100 p-t-40 respon7">
							<div class="flex-m bor9 p-r-10 m-r-11">
								<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="Add to Wishlist">
									<i class="zmdi zmdi-favorite"></i>
								</a>
							</div>

							<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Facebook">
								<i class="fa fa-facebook"></i>
							</a>

							<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Twitter">
								<i class="fa fa-twitter"></i>
							</a>

							<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Google Plus">
								<i class="fa fa-google-plus"></i>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="bor10 m-t-50 p-t-43 p-b-40">
				<!-- Tab01 -->
				<div class="tab01">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item p-b-10">
							<a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a>
						</li>

						<li class="nav-item p-b-10">
							<a class="nav-link" data-toggle="tab" href="#information" role="tab">Additional information</a>
						</li>

						<li class="nav-item p-b-10">
							<a class="nav-link" data-toggle="tab" href="#reviews" role="tab">
								@if ($totalReviews > 0)
									{{-- Display star icons based on the average rating --}}
									@for ($i = 0; $i < 5; $i++)
										@if ($i < floor($averageRating))
											<i class="zmdi zmdi-star" style="color: #FFD700;"></i> {{-- Full star --}}
										@elseif ($i < ceil($averageRating))
											<i class="zmdi zmdi-star-half" style="color: #FFD700;"></i> {{-- Half star --}}
										@else
											<i class="zmdi zmdi-star-outline" style="color: #FFD700;"></i> {{-- Empty star --}}
										@endif
									@endfor

									{{-- Display the average rating and total number of reviews --}}
									<span>{{$averageRating}}/5 ({{ $totalReviews }} Reviews)</span>
								@else
									@for ($i = 0; $i < 5; $i++)
										<i class="zmdi zmdi-star" style="color: #FFD700;"></i> {{-- Full star --}}
									@endfor
									{{-- Display "No reviews" message --}}
									<span>(0 Reviews)</span>
								@endif
							</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content p-t-43">
						<!-- - -->
						<div class="tab-pane fade show active" id="description" role="tabpanel">
							<div class="row">
								<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
									<ul class="p-lr-28 p-lr-15-sm">
										<li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												{{ $mainProduct->productDesc}}
											</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- - -->
						
						<div class="tab-pane fade" id="information" role="tabpanel">
							<div class="row">
								<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
									<ul class="p-lr-28 p-lr-15-sm">
										<li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Color
											</span>

											<span class="stext-102 cl6 size-206">
											{{ implode(', ', explode('|', $mainProduct->color)) }}
											</span>
										</li>

										<li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Size
											</span>

											<span class="stext-102 cl6 size-206">
											@php
												$sizeArray = explode(',', str_replace('|', ',', $mainProduct->size));
												$sizeArray = array_map('trim', $sizeArray);
												$uniqueSizes = array_unique($sizeArray);
											@endphp
											{{ implode(', ', $uniqueSizes) }}
											</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
						
						<!-- Reviews -->
						<div class="tab-pane fade" id="reviews" role="tabpanel">
							<div class="row">
								<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
									<div class="p-b-30 m-lr-15-sm">
										<div class="flex-w flex-sb-m p-b-52" id="filterBox">
											<div class="flex-w flex-c-m m-tb-10">
												<div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
													<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
													<i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
													 Filter
												</div>
											</div>
											
											<!-- Filter -->
											<div class="dis-none panel-filter w-full p-t-10">
												<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
													<div class="filter-col1 p-r-15 p-b-27">
														<div class="mtext-102 cl2 p-b-15">
															Rating Star
														</div>
							
														<ul>
															@for ($i = 5; $i >= 1; $i--)
																<li class="p-b-6">
																	<a href="#" class="filter-link stext-106 trans-04" data-filter-type="rating" data-filter-value="{{ $i }}">
																		@for ($j = 0; $j < $i; $j++)
																			<i class="zmdi zmdi-star"></i>
																		@endfor
																		@if (isset($totalCommentsPerStarLevel[$i]))
																			({{ $totalCommentsPerStarLevel[$i]['totalComments'] }})
																		@else
																			(0)
																		@endif
																	</a>
																</li>
															@endfor
														</ul>
													</div>
							
													<div class="filter-col2 p-r-15 p-b-27">
														<div class="mtext-102 cl2 p-b-15">
															Size
														</div>
							
														<ul>
															@foreach ($uniqueSizes as $size)
																<li class="p-b-6">
																	<a href="#" class="filter-link stext-106 trans-04" data-filter-type="size" data-filter-value="{{$size}}">
																		{{ $size }}
																	</a>
																</li>
															@endforeach
														</ul>
													</div>
							
													<div class="filter-col3 p-r-15 p-b-27">
														<div class="mtext-102 cl2 p-b-15">
															Color
														</div>
							
														<ul>
															@foreach ($colors as $color)
																<li class="p-b-6">
																	<span class="fs-15 lh-12 m-r-6" style="color: {{$color}};">
																		<i class="zmdi zmdi-circle"></i>
																	</span>
								
																	<a href="#" class="filter-link stext-106 trans-04" data-filter-type="color" data-filter-value="{{$color}}">
																		{{$color}}
																	</a>
																</li>
															@endforeach
														</ul>
													</div>
												</div>
											</div>
										</div>
										
										<!-- Container for the reviews that will be dynamically shown/hidden -->
										<div id="commentContainer">
											<!-- Reviews will be inserted here by JavaScript -->
										</div>

										<!-- Button to load more reviews -->
										<button class="stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04" id="loadMoreBtn">Load More</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="bg6 flex-c-m flex-w size-302 m-t-73 p-tb-15">
			<span class="stext-107 cl6 p-lr-25">
				Type: 
				<a href="/product#{{ $mainProduct->productType }}"> {{ucwords($mainProduct->productType->value)}}</a>
			</span>

			<span class="stext-107 cl6 p-lr-25">
				Categories: 
				<a href="/product?category[]={{ $mainProduct->category }}"> {{ucwords($mainProduct->category->value)}}</a>
				
			</span>
		</div>
	</section>


	<!-- Related Products -->
	<section class="sec-relate-product bg0 p-t-45 p-b-105">
		<div class="container">
			<div class="p-b-45">
				<h3 class="ltext-106 cl5 txt-center">
					Related Products
				</h3>
			</div>

			<!-- Slide2 -->
			<div class="wrap-slick2">
				<div class="slick2">
				@foreach ($relatedProducts as $product)
					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						
						<div class="block2">
							<div class="block2-pic hov-img0" style="display: flex; align-items: center; justify-content: center; height: 340px;background-color:#d3d3d3;">
								<img src="{{ asset('user/images/product/' . explode('|', $product->productImgObj)[0]) }}" alt="{{ $product->productImgObj }}">
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="{{ route('product.detail', $product->id) }}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										{{$product->productName}}
									</a>

									<span class="stext-105 cl3">
										RM {{number_format($product->price, 2)}}
									</span>
								</div>

								<div class="block2-txt-child2 flex-r p-t-3">
									<div class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
										<img class="icon-heart1 dis-block trans-04" src="{{ asset('user/images/icons/icon-heart-01.png') }}" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="{{ asset('user/images/icons/icon-heart-02.png') }}" alt="ICON">
									</div>
									<!-- Product Rating -->
									<div class="rating-below-icon">
										@if (isset($product->average_rating))
											<span class="stext-105 cl3 {{ $product->average_rating < 2 ? 'rating-low' : ($product->average_rating > 4.5 ? 'rating-high' : '') }}">
												{{ round($product->average_rating, 1) }}/5
											</span>
										@endif
									</div>
								</div>
							</div>
						</div>
						
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</section>
	<div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
		<div class="overlay-modal1 js-hide-modal1"></div>

		<img src="{{ asset('user/images/product/' . $mainProduct->productTryOnQR) }}"/>
	</div>

	<script>
		function initMagnificPopup() {
			$('.image-link').magnificPopup({
				type: 'image',
				gallery: {
					enabled: true
				}
			});
		}

		$(document).ready(function() {
			initMagnificPopup();
		});
	</script>

	<!-- JavaScript to handle loading more reviews -->
	<script>
		var showFilterBox = JSON.parse('@json($comments)');
		if (showFilterBox.length === 0) {
			document.getElementById('filterBox').style.display = 'none';
		}
		var comments = @json($comments); // Your reviews data from the server, as a JSON array
		var currentIndex = 0; // Keeps track of which review to load next
		var commentsPerClick = 3; // Number of reviews to show each time the button is clicked

		function createRatingStars(rating) {
			let starsHtml = '';
			for (let i = 1; i <= 5; i++) {
				if (i <= rating) {
					// Full star
					starsHtml += `<i class="zmdi zmdi-star"></i>`;
				} else {
					// Empty star
					starsHtml += `<i class="zmdi zmdi-star-outline"></i>`;
				}
			}
			return starsHtml;
		}

		function createCommentHTML(comment) {
			var isAuthenticated = @json(auth()->check());
			var currentUserId = @json(auth()->id());

			// comment image
			var imageUrls = [];

			if (comment.image && comment.image.trim() !== '') {
				imageUrls = comment.image.split('|');
			}

			var imagesHTML = imageUrls.map(function(url) {
				return `<a href="{{asset('user/images/review/${url}')}}" class="image-link"><img src="{{asset('user/images/review/${url}')}}" alt="Review Image" class="mr-3 mb-3 img-fluid img-equal"></a>`;
			}).join('');

			// Total likes and check if user has liked
			var likes = comment.likes && comment.likes.users_id ? comment.likes.users_id.length : 0;
    		var userHasLiked = comment.likes && comment.likes.users_id && comment.likes.users_id.includes(currentUserId);
			// Like button HTML, only if the user is authenticated
			var likeButtonClass = userHasLiked ? "like-button clicked" : "like-button";
			var likeIconClass = userHasLiked ? "fa fa-thumbs-up" : "fa fa-thumbs-o-up";
			var likeButtonHTML = isAuthenticated ? `
				<div class="like-buttons">
					<button class="${likeButtonClass}" id="${comment.id}">
						<i class="${likeIconClass}"></i> Like
					</button>
				</div>
			` : '';

			// comment posted date and time
			var createdAt = new Date(comment.created_at).toLocaleString('en-US', {
				year: 'numeric', month: '2-digit', day: '2-digit',
				hour: '2-digit', minute: '2-digit', second: '2-digit',
				hour12: false
			});

			// Create rating stars
			var ratingStars = createRatingStars(comment.rating);

			// Admin reply section
			// asset('user/images/profile_image/263-2635979_admin-abuse.png') 
			var adminReplyHTML = '';
			if (comment.admin_reply) {
				adminReplyHTML = `
				<div class="admin-reply">
					<p>${comment.admin_reply}</p>
				</div>
				`;
			}

			// Create the sizes and colors badges
			var sizesAndColorsHtml = '';
			if (comment.sizesAndColors) {
				var sizesAndColors = Array.isArray(comment.sizesAndColors) ? comment.sizesAndColors : [comment.sizesAndColors];
				sizesAndColorsHtml = '<div class="sizes-and-colors">' +
					sizesAndColors.map(function(sc) {
						return `<span class="badge">${sc}</span>`;
					}).join(' ') + // This joins the array elements with a space
				'</div>';
			}

			return `
				<div class="flex-w flex-t p-b-68">
					<div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
						<img src="{{asset('user/images/profile_image/${comment.user.image}')}}" alt="AVATAR">
					</div>

					<div class="size-207">
						<div class="flex-w flex-sb-m p-b-17">
							<span class="mtext-107 cl2 p-r-20">
								${comment.user.name}
								${sizesAndColorsHtml}
							</span>
							<span class="fs-18 cl11">
								${ratingStars}
							</span>
						</div>

						<p class="stext-102">
							${comment.review}
						</p>

						<div class="review-images">
							<div>
								${imagesHTML}
							</div>
						</div>

						${likeButtonHTML}
						<div>
							<small class="text-muted">${likes} likes</small>
						</div>

						<div class="comment-time">
							Posted on ${createdAt}
						</div>
					</div>
					${adminReplyHTML}
				</div>
			`;
		}

		function loadReviews() {
			var commentContainer = document.getElementById('commentContainer');
			for (var i = 0; i < commentsPerClick && currentIndex < comments.length; i++, currentIndex++) {
				var comment = comments[currentIndex];
				var commentHTML = createCommentHTML(comment);
				commentContainer.insertAdjacentHTML('beforeend', commentHTML);
			}
			// Hide the button if there are no more reviews to load
			if (currentIndex >= comments.length) {
				document.getElementById('loadMoreBtn').style.display = 'none';
			} else {
				document.getElementById('loadMoreBtn').style.display = 'block';
			}
			initMagnificPopup();
		}

		// Initially load the first few reviews
		loadReviews();

		// Add event listener to the Load More button
		document.getElementById('loadMoreBtn').addEventListener('click', loadReviews);
	</script>

	<script>
		// Object to store the state of each filter
		var filters = {
			rating: null,
			size: null,
			color: null
		};

		var allComments = @json($comments);
		var comments = allComments.slice();

		// Function to apply all active filters
		function applyFilters() {
			comments = allComments.filter(function(comment) {
				var ratingMatch = !filters.rating || comment.rating === filters.rating;
				// Parse the sizesAndColors string into an array of size-color pairs
				var sizesAndColorsArray = comment.sizesAndColors ? comment.sizesAndColors.match(/\[(.*?)\]/g).map(function(entry) {
					// Remove the brackets and split by comma and space to get the individual size and color
					return entry.replace(/[\[\]]/g, '').split(', ');
				}) : [];

				// Check if the array contains a pair with both the selected size and color
				var sizeColorMatch = sizesAndColorsArray.some(function(pair) {
					// Check if the pair includes both the size and color, exactly
					return (!filters.size || pair.includes(filters.size.trim())) &&
						(!filters.color || pair.includes(filters.color.trim()));
				});
				return ratingMatch && sizeColorMatch;
			});

			// Clear the current comments and reset the index
			var commentContainer = document.getElementById('commentContainer');
			commentContainer.innerHTML = '';
			currentIndex = 0;

			// Update comments with the filtered list and load the reviews
			loadReviews();
		}

		// Function to update filter state and UI
		function updateFilter(type, value) {
			// Toggle the filter value if it's already set, or set a new value
			if (filters[type] === value) {
				filters[type] = null; // Toggle off
			} else {
				filters[type] = value; // Set new value
			}
			
			// Update the filter link UI
			document.querySelectorAll(`.filter-link[data-filter-type="${type}"]`).forEach(function(link) {
				if (link.getAttribute('data-filter-value') === value.toString()) {
					link.classList.toggle('filter-link-active', filters[type] === value);
				} else {
					link.classList.remove('filter-link-active');
				}
			});

			// Apply all active filters
			applyFilters();
		}

		// Add click event listeners to filter links
		document.querySelectorAll('.filter-link').forEach(function(link) {
			link.addEventListener('click', function(event) {
				event.preventDefault();
				var filterType = this.getAttribute('data-filter-type');
				var filterValue = this.getAttribute('data-filter-value');
				// Convert string value to number for rating
				var value = filterType === 'rating' ? parseInt(filterValue) : filterValue;
				updateFilter(filterType, value);
			});
		});
	</script>


	<script>
		$(document).on('click', '.like-button', function(e) {
			e.preventDefault(); // Prevent the default anchor tag behaviour
			var likeButton = $(this);
			var likeButtonId = $(this).attr('id'); // Assuming the button's ID is the comment ID

			$.ajax({
				url: '/user/comment/' + likeButtonId + '/like', // Your server endpoint
				type: 'POST', // Assuming liking is a POST action
				dataType: 'json',
				success: function(data) {
					// Update the likes count text
					var likesCount = likeButton.closest('.flex-w').find('.text-muted');
            		likesCount.text(data.num_likes + ' likes');

					// Toggle the like button class
					likeButton.toggleClass('clicked'); // If 'clicked' class is present, it is removed; if it's not, it is added

					// Change the icon
					var iconEl = likeButton.find('i');
					if (likeButton.hasClass('clicked')) {
						iconEl.removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up');
					} else {
						iconEl.removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
					}
				}.bind(this), // .bind(this) ensures 'this' inside the success function refers to the clicked like button
				error: function(xhr, status, error) {
					console.error("An error occurred: " + xhr.status + " " + error);
				}
			});
		});
	</script>

	<script>

		$('.js-show-modal1').on('click',function(e){
        e.preventDefault();
        $('.js-modal1').addClass('show-modal1');
    });

    $('.js-hide-modal1').on('click',function(){
        $('.js-modal1').removeClass('show-modal1');
    });
    var colorSizeMap = @json($colorSizeMap); 
	var maxQuantityMap = @json($maxQuantityMap);
function updateSizes() {
	// Get the selected color value
	var colorSelect = document.getElementById('colorSelect');
	var selectedColor = colorSelect.value; // This gets the selected color value from the dropdown
	var sizeSelect = document.getElementById('sizeSelect');
	var selectedSize = sizeSelect.value;
	var quantityInput = document.getElementById('quantityInput');
	var maxProductQuantity = document.getElementById('maxProductQuantity');
	if (selectedColor!== "") {
        sizeSelect.disabled = false;
        sizeSelect.innerHTML = '<option value="">Choose a size</option>';
        $(sizeSelect).trigger('change'); // If using Select2
    } else {
        sizeSelect.innerHTML = '<option value="">--Please Select a Color--</option>';
        sizeSelect.disabled = true;
        $(sizeSelect).trigger('change');
		quantityInput.max = 0;
        quantityInput.value = 0;
		maxProductQuantity.value = 0;
    }

	// Check if the selected color is in the colorSizeMap
	if (selectedColor in colorSizeMap) {
		// Iterate over the sizes for the selected color
		colorSizeMap[selectedColor].forEach(function(size) {
			var option = document.createElement('option');
			option.value = size.trim();
			option.text = 'Size ' + size.trim();
			if (selectedColor in maxQuantityMap && maxQuantityMap[selectedColor].hasOwnProperty(size.trim()) && maxQuantityMap[selectedColor][size.trim()] === 0) {
				option.disabled = true;
			}
			sizeSelect.appendChild(option);
		});

		// If using Select2, trigger the update for the size select dropdown
		$('#sizeSelect').trigger('change'); // Notify Select2 to update the sizeSelect dropdown
	}
}

function updateQuantity() {
    var colorSelect = document.getElementById('colorSelect');
    var selectedColor = colorSelect.value; // Get the selected color value
    var sizeSelect = document.getElementById('sizeSelect');
    var selectedSize = sizeSelect.value; // Get the selected size value from the dropdown
    var quantityInput = document.getElementById('quantityInput'); // Get the quantity input element
    var maxProductQuantity = document.getElementById('maxProductQuantity'); // Ensure this element exists in your HTML

    // Correctly access the max quantity using both color and size
    if (selectedColor in maxQuantityMap && selectedSize in maxQuantityMap[selectedColor]) {
        var maxQuantity = maxQuantityMap[selectedColor][selectedSize];

        quantityInput.max = maxQuantity; // Set the max attribute
        maxProductQuantity.value = maxQuantity;
        quantityInput.value = maxQuantity > 0 ? 1 : 0; // Reset the quantity input to 1 or 0 depending on availability
    } else {
        quantityInput.max = 0; // No size selected, or no quantity available
        quantityInput.value = 0;
        maxProductQuantity.value = 0;
    }
}

	var quantityInput = document.getElementById('quantityInput');

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
		</script>
@endsection