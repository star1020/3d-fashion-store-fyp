<?php
use App\Enums\ProductColor;
?>

@extends('user/master')
@section('content')
<style>
	.category-link-active {
    color: #717fe0 !important;
    border-color: #717fe0 !important;
	font-weight: bold;
	
}
.zmdi-circle {
  display: inline-block;
  width: 20px; /* or the size you want */
  height: 20px; /* or the size you want */
  border-radius: 50%; /* This makes it round */
  text-align: center;
  line-height: 20px; 
  
}
.filter-link-active {
    font-weight: bold; /* This will make the text bold */
}

.filter-link{
	cursor: pointer;
}
.hidden{
	display:none;
}
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

	</style>
	<div class="bg0 m-t-23 p-b-140">
		<div class="container">
		<form action="{{ route('products.show') }}" method="GET" width="100%">

			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" type="button" data-filter="*">
						All Products
					</button>
					
					@foreach ($types as $type)
						<button type="button" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".{{ strtolower($type->value) }}" data-type="{{ strtolower($type->value) }}">
							{{ $type->label() }}
						</button>
					@endforeach
				</div>

				<div class="flex-w flex-c-m m-tb-10">
					<div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
						<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
						<i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						 Filter
					</div>

					<div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
						<i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
						<i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						Search
					</div>
				</div>
				
				<!-- Search product -->
				<div class="dis-none panel-search w-full p-t-10 p-b-15">
					<div class="bor8 dis-flex p-l-15">
						<button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
							<i class="zmdi zmdi-search"></i>
						</button>

						<input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product" id="search-product" placeholder="Search">
					</div>	
				</div>

				<!-- Filter -->
				<div class="dis-none panel-filter w-full p-t-10">
				
					<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
						<div class="filter-col1 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Sort By
							</div>

							<ul>
								<li class="p-b-6">
									<label for="sort_default">
										<span onclick="toggleSortingActive(this);clearSortingFilter()" class="filter-link stext-106 trans-04 filter-link-active sorting">
										Default
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="sort_popularity" name="sort" value="popularity" class="hidden" >
									<label for="sort_popularity">
										<span onclick="toggleSortingActive(this)" class="filter-link stext-106 trans-04 sorting">
										Popularity
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="sort_rating" name="sort" value="rating" class="hidden">
									<label for="sort_rating">
										<span onclick="toggleSortingActive(this)" class="filter-link stext-106 trans-04 sorting">
										Average Rating
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="sort_newness" name="sort" value="newness" class="hidden">
									<label for="sort_newness">
										<span onclick="toggleSortingActive(this)" class="filter-link stext-106 trans-04 sorting">
										Newness
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="sort_price_low_high" name="sort" value="price_asc" class="hidden">
									<label for="sort_price_low_high">
										<span onclick="toggleSortingActive(this)" class="filter-link stext-106 trans-04 sorting">
										Price: Low to High
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="sort_price_high_low" name="sort" value="price_desc" class="hidden">
									<label for="sort_price_high_low">
										<span onclick="toggleSortingActive(this)" class="filter-link stext-106 trans-04 sorting">
										Price: High to Low
										</span>
									</label>
								</li>
							</ul>
						</div>

						<div class="filter-col2 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Price
							</div>

							<ul>
								
								<li class="p-b-6">
									<label for="price_all">
										<span onclick="togglePriceActive(this);clearPriceFilter()" class="filter-link stext-106 trans-04 filter-link-active price">
										All
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="price_0_50" name="price" value="0-50" class="hidden">
									<label for="price_0_50">
										<span onclick="togglePriceActive(this)" class="filter-link stext-106 trans-04 price">
										RM0.00 - RM50.00
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="price_50_100" name="price" value="50-100" class="hidden">
									<label for="price_50_100">
										<span onclick="togglePriceActive(this)" class="filter-link stext-106 trans-04 price">
										RM50.00 - RM100.00
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="price_100_150" name="price" value="100-150" class="hidden">
									<label for="price_100_150">
										<span onclick="togglePriceActive(this)" class="filter-link stext-106 trans-04 price">
										RM100.00 - RM150.00
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="price_150_200" name="price" value="150-200" class="hidden">
									<label for="price_150_200">
										<span onclick="togglePriceActive(this)" class="filter-link stext-106 trans-04 price">
										RM150.00 - RM200.00
										</span>
									</label>
								</li>

								<li class="p-b-6">
									<input type="radio" id="price_200_plus" name="price" value="200+" class="hidden">
									<label for="price_200_plus">
										<span onclick="togglePriceActive(this)" class="filter-link stext-106 trans-04 price">
										RM200.00+
										</span>
									</label>
								</li>

							</ul>
						</div>

						<div class="filter-col3 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Color
							</div>

							<ul>
							@foreach (ProductColor::cases() as $color)
								<li class="p-b-6">
									<label>
										<input type="checkbox" class="color-checkbox" name="color[]" value="{{ $color->value }}" id="color_{{ $color->value }}" hidden>
										<span class="fs-15 lh-12 m-r-6">
										<i class="zmdi zmdi-circle" style="background: {{ $color->colorCode() }}; -webkit-background-clip: text; color: transparent;"></i>
										</span>
										<span onclick="toggleColorActive(this.previousElementSibling)" class="filter-link stext-106 trans-04" style="cursor:pointer">
											{{ ucwords($color->name) }}
										</span>
									</label>
								</li>
							@endforeach

						</div>
						<div class="filter-col4 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Category
							</div>
							<div class="flex-w p-t-4 m-r--5">
							@foreach ($categories as $category)
							<input type="checkbox" class="category-checkbox" name="category[]" value="{{ $category->value }}" id="category_{{ $category->value }}" hidden>
							<span onclick="toggleCategoryActive(document.getElementById('category_{{ $category->value }}'))" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5" style="cursor:pointer">
								{{ ucwords($category->value) }}
							</span>
							@endforeach


							</div>
							<div class="filter-apply-btn p-t-27 p-lr-40 w-full" style="margin-top: 20px; display: flex; justify-content: flex-end;">
								<button type="submit" class="flex-c-m stext-106 cl6 size-105 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
									Apply Filters
								</button>
							</div>
						</div>
					</div>
					</form>
				</div>
				
			</div>

			<div class="row isotope-grid">
			@foreach ($products as $product)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ $product->productType }}">
                        <div class="block2">
                            <div class="block2-pic hov-img0" style="display: flex; align-items: center; justify-content: center; height: 340px;background-color:#d3d3d3;">
								<img src="{{ asset('user/images/product/' . explode('|', $product->productImgObj)[0]) }}" alt="{{ $product->productImgObj }}">
                            </div>

                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="{{ route('product.detail', $product->id) }}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        {{ $product->productName }}
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
	
	<script>
function toggleCategoryActive(checkbox) {
    var span = checkbox.nextElementSibling;
    checkbox.checked = !checkbox.checked;
    span.classList.toggle('category-link-active', checkbox.checked);
}

function toggleSortingActive(element) {
    // Deactivate all other sorting options
    document.querySelectorAll('.sorting').forEach(function(link) {
        link.classList.remove('filter-link-active');
    });

    // Activate the clicked sorting option
    element.classList.add('filter-link-active');

}

function togglePriceActive(element) {
    // Deactivate all other price options
    document.querySelectorAll('.price').forEach(function(link) {
        link.classList.remove('filter-link-active');
    });

    // Activate the clicked price option
    element.classList.add('filter-link-active');

}

function toggleColorActive(checkbox) {
    var spanElement = checkbox.nextSibling;

    if (spanElement.nodeType === Node.TEXT_NODE) {
        spanElement = spanElement.nextSibling;
    }
    spanElement.classList.toggle('filter-link-active');
}



function setActiveFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);

    const sortParam = urlParams.get('sort');
	
    if (sortParam) {
        const activeSortOption = document.querySelector(`input[name="sort"][value="${sortParam}"]`);
        if (activeSortOption) {
            const activeSortSpan = activeSortOption.nextElementSibling.querySelector('.sorting');
            if (activeSortSpan) {
				document.querySelectorAll('.sorting').forEach(function(link) {
					link.classList.remove('filter-link-active');
				});
				const sortingInputs = document.querySelectorAll('input[type="radio"][name="sort"]');
				sortingInputs.forEach(input => {
					input.checked = false;
				});
                activeSortSpan.classList.add('filter-link-active');
				activeSortOption.checked = true;
            }
        }
    }

    let price = urlParams.get('price');
    if (price) {
        let priceElement = document.querySelector(`input[name="price"][value="${price}"]`);
        if (priceElement) {
            let span = priceElement.nextElementSibling.querySelector('.price');
            if (span) {
				document.querySelectorAll('.price').forEach(function(link) {
					link.classList.remove('filter-link-active');
				});
				const sortingInputs = document.querySelectorAll('input[type="radio"][name="price"]');
				sortingInputs.forEach(input => {
					input.checked = false;
				});
                span.classList.add('filter-link-active');
				priceElement.checked = true;
            }
        }
    }

    let colors = urlParams.getAll('color[]');
    document.querySelectorAll('input[type="checkbox"][name="color[]"]').forEach(checkbox => {
        checkbox.checked = colors.includes(checkbox.value);
        const labelSpan = checkbox.nextElementSibling.nextElementSibling;
        if (labelSpan) {
            if (checkbox.checked) {
                labelSpan.classList.add('filter-link-active');
            } else {
                labelSpan.classList.remove('filter-link-active');
            }
        }
    });

    let categories = urlParams.getAll('category[]');
	document.querySelectorAll('input[type="checkbox"][name="category[]"]').forEach(checkbox => {
        checkbox.checked = categories.includes(checkbox.value);
        let span = checkbox.nextElementSibling;
        if (span) {
            if (checkbox.checked) {
                span.classList.add('category-link-active');
            } else {
                span.classList.remove('category-link-active');
            }
        }
    });
}

// Call this function on page load to set the active filters based on the URL parameters.
window.addEventListener('DOMContentLoaded', setActiveFiltersFromURL);

// This function will be called when any sorting option is clicked.
function toggleSortingActive(element) {
    // Get all sorting spans.
    const sortingSpans = document.querySelectorAll('.sorting');

    // Remove the active class from all sorting options.
    sortingSpans.forEach(span => {
        span.classList.remove('filter-link-active');
    });

    // Add the active class to the clicked sorting option.
    element.classList.add('filter-link-active');
}

function clearSortingFilter() {
    // Get all sorting radio inputs
    var sortingRadios = document.querySelectorAll('input[name="sort"]');
    
    // Loop through all and set 'checked' property to false
    sortingRadios.forEach(function(radio) {
        radio.checked = false;
    });
}
function clearPriceFilter() {
    // Get all sorting radio inputs
    var sortingRadios = document.querySelectorAll('input[name="price"]');
    
    // Loop through all and set 'checked' property to false
    sortingRadios.forEach(function(radio) {
        radio.checked = false;
    });
}
    document.addEventListener('DOMContentLoaded', function() {
        var typeFromHash = window.location.hash.replace('#', '');
        if (typeFromHash) {
            var activeButton = document.querySelector('button[data-type="' + typeFromHash + '"]');
            if (activeButton) {
                activeButton.click();
            }
        }
    });

	$(document).ready(function() {
        var searchQuery = new URLSearchParams(window.location.search).get('search-product');
        if (searchQuery) {
            $('#search-product').val(searchQuery);
        }
    });
</script>

@endsection