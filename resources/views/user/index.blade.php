@extends('user/master')
@section('content')
<!-- Slider -->
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
    </style>
<section class="section-slide">
    <div class="wrap-slick1 rs1-slick1">
        <div class="slick1">
            <div class="item-slick1" style="background-image: url({{asset('user/images/slide-03.jpg')}});">
                <div class="container h-full">
                    <div class="flex-col-l-m h-full p-t-100 p-b-30">
                        <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                            <span class="ltext-202 cl2 respon2">
                                Men Collection 2018
                            </span>
                        </div>
                            
                        <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                            <h2 class="ltext-104 cl2 p-t-19 p-b-43 respon1">
                                New arrivals
                            </h2>
                        </div>
                            
                        <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                            <a href="/product" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                Shop Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="item-slick1" style="background-image: url({{asset('user/images/slide-02.jpg')}});">
                <div class="container h-full">
                    <div class="flex-col-l-m h-full p-t-100 p-b-30">
                        <div class="layer-slick1 animated visible-false" data-appear="rollIn" data-delay="0">
                            <span class="ltext-202 cl2 respon2">
                                Explore Our Virtual Showroom
                            </span>
                        </div>
                            
                        <div class="layer-slick1 animated visible-false" data-appear="lightSpeedIn" data-delay="800">
                            <h2 class="ltext-104 cl2 p-t-19 p-b-43 respon1">
                                Interactive 3D Experience
                            </h2>
                        </div>
                            
                        <div class="layer-slick1 animated visible-false" data-appear="slideInUp" data-delay="1600">
                            <a href="{{ url('/virtual-showroom') }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                Dive In Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="item-slick1" style="background-image: url({{asset('user/images/slide-04.jpg')}});">
                <div class="container h-full">
                    <div class="flex-col-l-m h-full p-t-100 p-b-30">
                        <div class="layer-slick1 animated visible-false" data-appear="rotateInDownLeft" data-delay="0">
                            <span class="ltext-202 cl2 respon2">
                                Women Collection 2018
                            </span>
                        </div>
                            
                        <div class="layer-slick1 animated visible-false" data-appear="rotateInUpRight" data-delay="800">
                            <h2 class="ltext-104 cl2 p-t-19 p-b-43 respon1">
                                NEW SEASON
                            </h2>
                        </div>
                            
                        <div class="layer-slick1 animated visible-false" data-appear="rotateIn" data-delay="1600">
                            <a href="/product" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                Shop Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Banner -->
<div class="sec-banner bg0">
    <div class="flex-w flex-c-m">
        <div class="size-202 m-lr-auto respon4">
            <!-- Block1 -->
            <div class="block1 wrap-pic-w">
                <img src="{{asset('user/images/banner-04.jpg')}}" alt="IMG-BANNER">

                <a href="/product" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                    <div class="block1-txt-child1 flex-col-l">
                        <span class="block1-name ltext-102 trans-04 p-b-8">
                            Women
                        </span>

                        <span class="block1-info stext-102 trans-04">
                            Spring 2018
                        </span>
                    </div>

                    <div class="block1-txt-child2 p-b-4 trans-05">
                        <div class="block1-link stext-101 cl0 trans-09">
                            Shop Now
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="size-202 m-lr-auto respon4">
            <!-- Block1 -->
            <div class="block1 wrap-pic-w">
                <img src="{{asset('user/images/banner-05.jpg')}}" alt="IMG-BANNER">

                <a href="/product" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                    <div class="block1-txt-child1 flex-col-l">
                        <span class="block1-name ltext-102 trans-04 p-b-8">
                            Men
                        </span>

                        <span class="block1-info stext-102 trans-04">
                            Spring 2018
                        </span>
                    </div>

                    <div class="block1-txt-child2 p-b-4 trans-05">
                        <div class="block1-link stext-101 cl0 trans-09">
                            Shop Now
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="size-202 m-lr-auto respon4">
            <!-- Block1 -->
            <div class="block1 wrap-pic-w">
                <img src="{{asset('user/images/banner-06.jpg')}}" alt="IMG-BANNER">

                <a href="/product" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                    <div class="block1-txt-child1 flex-col-l">
                        <span class="block1-name ltext-102 trans-04 p-b-8">
                            Bags
                        </span>

                        <span class="block1-info stext-102 trans-04">
                            New Trend
                        </span>
                    </div>

                    <div class="block1-txt-child2 p-b-4 trans-05">
                        <div class="block1-link stext-101 cl0 trans-09">
                            Shop Now
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>


<!-- Product -->
<section class="sec-product bg0 p-t-100 p-b-50">
    <div class="container">
        <div class="p-b-32">
            <h3 class="ltext-105 cl5 txt-center respon1">
                Store Overview
            </h3>
        </div>

        <!-- Tab01 -->
        <div class="tab01">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                @foreach ($types as $type)
                    <li class="nav-item p-b-10">
                        <a class="nav-link{{ $loop->first ? ' active' : '' }}" 
                        data-toggle="tab" 
                        href="#{{ $type->value }}" 
                        role="tab">
                        {{ $type->label() }}
                        </a>
                    </li>
                @endforeach
            </ul>


            <!-- Tab panes -->
            <div class="tab-content p-t-50">
                @foreach ($types as $type)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                    id="{{ $type->value }}" 
                    role="tabpanel">
                    <div class="wrap-slick2">
                        <div class="slick2">
                        @foreach ($products as $product)
                        @if ($product->productType->value == $type->value)
                            <div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
                                <!-- Block2 -->
                                <div class="block2">
                                    <div class="block2-pic hov-img0" style="display: flex; align-items: center; justify-content: center; height: 340px;">
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
                        @endif
                        @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


<!-- Blog -->
<section class="sec-blog bg0 p-t-60 p-b-90">
    <div class="container">
        <div class="p-b-66">
            <h3 class="ltext-105 cl5 txt-center respon1">
                Our Blogs
            </h3>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-4 p-b-40">
                <div class="blog-item">
                    <div class="hov-img0">
                        <a href="blog-detail.html">
                            <img src="{{asset('user/images/blog-01.jpg')}}" alt="IMG-BLOG">
                        </a>
                    </div>

                    <div class="p-t-15">
                        <div class="stext-107 flex-w p-b-14">
                            <span class="m-r-3">
                                <span class="cl4">
                                    By
                                </span>

                                <span class="cl5">
                                    Nancy Ward
                                </span>
                            </span>

                            <span>
                                <span class="cl4">
                                    on
                                </span>

                                <span class="cl5">
                                    July 22, 2017 
                                </span>
                            </span>
                        </div>

                        <h4 class="p-b-12">
                            <a href="blog-detail.html" class="mtext-101 cl2 hov-cl1 trans-04">
                                8 Inspiring Ways to Wear Dresses in the Winter
                            </a>
                        </h4>

                        <p class="stext-108 cl6">
                            Duis ut velit gravida nibh bibendum commodo. Suspendisse pellentesque mattis augue id euismod. Interdum et male-suada fames
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 p-b-40">
                <div class="blog-item">
                    <div class="hov-img0">
                        <a href="blog-detail.html">
                            <img src="{{asset('user/images/blog-02.jpg')}}" alt="IMG-BLOG">
                        </a>
                    </div>

                    <div class="p-t-15">
                        <div class="stext-107 flex-w p-b-14">
                            <span class="m-r-3">
                                <span class="cl4">
                                    By
                                </span>

                                <span class="cl5">
                                    Nancy Ward
                                </span>
                            </span>

                            <span>
                                <span class="cl4">
                                    on
                                </span>

                                <span class="cl5">
                                    July 18, 2017
                                </span>
                            </span>
                        </div>

                        <h4 class="p-b-12">
                            <a href="blog-detail.html" class="mtext-101 cl2 hov-cl1 trans-04">
                                The Great Big List of Men’s Gifts for the Holidays
                            </a>
                        </h4>

                        <p class="stext-108 cl6">
                            Nullam scelerisque, lacus sed consequat laoreet, dui enim iaculis leo, eu viverra ex nulla in tellus. Nullam nec ornare tellus, ac fringilla lacus. Ut sit ame
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 p-b-40">
                <div class="blog-item">
                    <div class="hov-img0">
                        <a href="blog-detail.html">
                            <img src="{{asset('user/images/blog-03.jpg')}}" alt="IMG-BLOG">
                        </a>
                    </div>

                    <div class="p-t-15">
                        <div class="stext-107 flex-w p-b-14">
                            <span class="m-r-3">
                                <span class="cl4">
                                    By
                                </span>

                                <span class="cl5">
                                    Nancy Ward
                                </span>
                            </span>

                            <span>
                                <span class="cl4">
                                    on
                                </span>

                                <span class="cl5">
                                    July 2, 2017 
                                </span>
                            </span>
                        </div>

                        <h4 class="p-b-12">
                            <a href="blog-detail.html" class="mtext-101 cl2 hov-cl1 trans-04">
                                5 Winter-to-Spring Fashion Trends to Try Now
                            </a>
                        </h4>

                        <p class="stext-108 cl6">
                            Proin nec vehicula lorem, a efficitur ex. Nam vehicula nulla vel erat tincidunt, sed hendrerit ligula porttitor. Fusce sit amet maximus nunc
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection