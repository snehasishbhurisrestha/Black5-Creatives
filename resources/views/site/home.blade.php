@extends('layouts.web-app')

@section('title') Home @endsection

@section('content')

<!-- Start Slider Area -->
@if($sliders->isNotEmpty())
<section class="hero">
    <div class="hero-slider owl-carousel">
        @foreach($sliders as $slider)
        <div class="hero-items set-bg" data-setbg="{{ $slider->getFirstMediaUrl('slider') }}">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-7 col-md-8">
                        <div class="hero-text">
                            <h6>{{ $slider->title }}</h6>
                            {!! $slider->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
<!-- End Slider Area -->		

<!--  Promo ITEM STRAT  -->
{{-- <section id="promo_area" class="section_padding">
    <div class="container">
        <div class="row">

            @if($categorys->isNotEmpty())
            <div class="col-md-12 text-center">
                <div class="section_title fws">						
                    <h2>Top <span>Categories</span></h2>						
                </div>
            </div>

            <div id="testimonial-slider2" class="owl-carousel">
                @foreach($categorys as $category)
                <div class="testimonial2">
                    <div class="pic">
                        <img src="{{ $category->getFirstMediaUrl('category') }}" alt="">
                        <h3 class="title">{{ $category->name }}</h3>
                    </div>
                </div>
                @endforeach
            </div>
            @endif



            <div class="col-lg-4 col-md-6 col-sm-12">	
                <a href="{{ route('product.all') }}">
                    <div class="single_promo">
                        <img src="{{ asset('assets/site-assets/img/promo/4.jpg') }}" alt="">
                        <div class="box-content">
                            <h3 class="title">Professional Sound</h3>
                            <span class="post">Shop Now</span>
                        </div>
                    </div>
                </a>						
            </div><!--  End Col -->						
            
            <div class="col-lg-8 col-md-6 col-sm-12">	
                <a href="{{ route('product.all') }}">
                    <div class="single_promo">
                        <img src="{{ asset('assets/site-assets/img/promo/5.jpg') }}" alt="">
                        <div class="box-content">
                            <h3 class="title">New Releases</h3>
                            <span class="post">Shop Now</span>
                        </div>
                    </div>	
                </a>	
                
            </div><!--  End Col -->					

        
        </div>			
    </div>		
</section> --}}
<section class="banner spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 offset-lg-4">
                <div class="banner-item">
                    <div class="banner-item-pic">
                        <img src="{{ asset('assets/site-assets/img/banner/banner-1.jpg') }}" alt="">
                    </div>
                    <div class="banner-item-text">
                        <h2>Clothing Collections 2030</h2>
                        <a href="#">Shop now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="banner-item banner-item--middle">
                    <div class="banner-item-pic">
                        <img src="{{ asset('assets/site-assets/img/banner/banner-2.jpg') }}" alt="">
                    </div>
                    <div class="banner-item-text">
                        <h2>Accessories</h2>
                        <a href="#">Shop now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="banner-item banner-item--last">
                    <div class="banner-item-pic">
                        <img src="{{ asset('assets/site-assets/img/banner/banner-3.jpg') }}" alt="">
                    </div>
                    <div class="banner-item-text">
                        <h2>Shoes Spring 2030</h2>
                        <a href="#">Shop now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--  Promo ITEM END -->	

<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="filter-controls">
                    <li class="active" data-filter="*">Best Sellers</li>
                    <li data-filter=".new-arrivals">New Arrivals</li>
                    <li data-filter=".hot-sales">Hot Sales</li>
                </ul>
            </div>
        </div>
        <div class="row product-filter">
            @foreach($all_products as $products)	
            <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
                <div class="product-item">
                    <a href="{{ route('product.details',$products->slug) }}">
                        <div class="product-item-pic set-bg" data-setbg="{{ getProductMainImage($products->id) }}" style="background-image: url('{{ getProductMainImage($products->id) }}');">
                            <span class="label">New</span>
                            {{-- <ul class="product-hover">
                                <li><a href="javascript:void(0);" class="add-to-wishlist" data-product-id="{{ $products->id }}"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                                <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a></li>
                                <li><a href="{{ route('product.details',$products->slug) }}"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                            </ul> --}}
                        </div>
                        <div class="product-item-text">
                            <h6>{{ $products->name }}</h6>
                            <a href="javascript:void(0);" class="add-cart add-to-cart-btn" data-product-id="{{ $products->id }}">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>₹{{ $products->total_price }}</h5>
                            <div class="product-color-select">
                                {{-- <label for="pc-1">
                                    <input type="radio" id="pc-1">
                                </label>
                                <label class="active black" for="pc-2">
                                    <input type="radio" id="pc-2">
                                </label>
                                <label class="grey" for="pc-3">
                                    <input type="radio" id="pc-3">
                                </label>
                            </div> --}}
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
            {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix hot-sales">
                <div class="product-item">
                    <div class="product-item-pic set-bg" data-setbg="{{ asset('assets/site-assets/img/product/product-2.jpg') }}">
                        <ul class="product-hover">
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                        </ul>
                    </div>
                    <div class="product-item-text">
                        <h6>Piqué Biker Jacket</h6>
                        <a href="#" class="add-cart">+ Add To Cart</a>
                        <div class="rating">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <h5>₹67.24</h5>
                        <div class="product-color-select">
                            <label for="pc-4">
                                <input type="radio" id="pc-4">
                            </label>
                            <label class="active black" for="pc-5">
                                <input type="radio" id="pc-5">
                            </label>
                            <label class="grey" for="pc-6">
                                <input type="radio" id="pc-6">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
                <div class="product-item sale">
                    <div class="product-item-pic set-bg" data-setbg="{{ asset('assets/site-assets/img/product/product-3.jpg') }}">
                        <span class="label">Sale</span>
                        <ul class="product-hover">
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                        </ul>
                    </div>
                    <div class="product-item-text">
                        <h6>Multi-pocket Chest Bag</h6>
                        <a href="#" class="add-cart">+ Add To Cart</a>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <h5>₹43.48</h5>
                        <div class="product-color-select">
                            <label for="pc-7">
                                <input type="radio" id="pc-7">
                            </label>
                            <label class="active black" for="pc-8">
                                <input type="radio" id="pc-8">
                            </label>
                            <label class="grey" for="pc-9">
                                <input type="radio" id="pc-9">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix hot-sales">
                <div class="product-item">
                    <div class="product-item-pic set-bg" data-setbg="{{ asset('assets/site-assets/img/product/product-4.jpg') }}">
                        <ul class="product-hover">
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                        </ul>
                    </div>
                    <div class="product-item-text">
                        <h6>Diagonal Textured Cap</h6>
                        <a href="#" class="add-cart">+ Add To Cart</a>
                        <div class="rating">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <h5>₹60.9</h5>
                        <div class="product-color-select">
                            <label for="pc-10">
                                <input type="radio" id="pc-10">
                            </label>
                            <label class="active black" for="pc-11">
                                <input type="radio" id="pc-11">
                            </label>
                            <label class="grey" for="pc-12">
                                <input type="radio" id="pc-12">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
                <div class="product-item">
                    <div class="product-item-pic set-bg" data-setbg="{{ asset('assets/site-assets/img/product/product-5.jpg') }}">
                        <ul class="product-hover">
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                        </ul>
                    </div>
                    <div class="product-item-text">
                        <h6>Lether Backpack</h6>
                        <a href="#" class="add-cart">+ Add To Cart</a>
                        <div class="rating">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <h5>₹31.37</h5>
                        <div class="product-color-select">
                            <label for="pc-13">
                                <input type="radio" id="pc-13">
                            </label>
                            <label class="active black" for="pc-14">
                                <input type="radio" id="pc-14">
                            </label>
                            <label class="grey" for="pc-15">
                                <input type="radio" id="pc-15">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix hot-sales">
                <div class="product-item sale">
                    <div class="product-item-pic set-bg" data-setbg="{{ asset('assets/site-assets/img/product/product-6.jpg') }}">
                        <span class="label">Sale</span>
                        <ul class="product-hover">
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                        </ul>
                    </div>
                    <div class="product-item-text">
                        <h6>Ankle Boots</h6>
                        <a href="#" class="add-cart">+ Add To Cart</a>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <h5>₹98.49</h5>
                        <div class="product-color-select">
                            <label for="pc-16">
                                <input type="radio" id="pc-16">
                            </label>
                            <label class="active black" for="pc-17">
                                <input type="radio" id="pc-17">
                            </label>
                            <label class="grey" for="pc-18">
                                <input type="radio" id="pc-18">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
                <div class="product-item">
                    <div class="product-item-pic set-bg" data-setbg="{{ asset('assets/site-assets/img/product/product-7.jpg') }}">
                        <ul class="product-hover">
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                        </ul>
                    </div>
                    <div class="product-item-text">
                        <h6>T-shirt Contrast Pocket</h6>
                        <a href="#" class="add-cart">+ Add To Cart</a>
                        <div class="rating">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <h5>₹49.66</h5>
                        <div class="product-color-select">
                            <label for="pc-19">
                                <input type="radio" id="pc-19">
                            </label>
                            <label class="active black" for="pc-20">
                                <input type="radio" id="pc-20">
                            </label>
                            <label class="grey" for="pc-21">
                                <input type="radio" id="pc-21">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix hot-sales">
                <div class="product-item">
                    <div class="product-item-pic set-bg" data-setbg="{{ asset('assets/site-assets/img/product/product-8.jpg') }}">
                        <ul class="product-hover">
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                        </ul>
                    </div>
                    <div class="product-item-text">
                        <h6>Basic Flowing Scarf</h6>
                        <a href="#" class="add-cart">+ Add To Cart</a>
                        <div class="rating">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <h5>₹26.28</h5>
                        <div class="product-color-select">
                            <label for="pc-22">
                                <input type="radio" id="pc-22">
                            </label>
                            <label class="active black" for="pc-23">
                                <input type="radio" id="pc-23">
                            </label>
                            <label class="grey" for="pc-24">
                                <input type="radio" id="pc-24">
                            </label>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</section>
<!-- End product Area -->

<!-- Special Offer Area -->
{{-- <div class="special_offer_area gray_section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-right">
                <div class="special_info">			
                    <h3>Home Studio Creation</h3>
                    <p>Made Affordable</p>							
                    <a href="{{ route('contact') }}" class="btn main_btn">Contact Now</a>					
                </div>
            </div>
        </div>

    </div>
</div>  --}}
<!-- End Special Offer Area -->

<!-- Start Featured product Area -->
{{-- @if($featured_products->isNotEmpty())
<section id="featured_product" class="featured_product_area section_padding">
    <div class="container">		
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section_title">						
                    <h2>Featured <span> Products</span></h2>
                </div>
            </div>
        </div>

        <div class="row text-center">		
            @foreach($featured_products as $featured_product)			
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="single_product">
                    <div class="product_image">
                        <img src="{{ getProductMainImage($featured_product->id) }}" alt=""/>
                        <div class="box-content">
                            <a href="javascript:void(0)" class="add-to-wishlist" data-product-id="{{ $featured_product->id }}"><i class="fa fa-heart-o"></i></a>
                            <a href="javascript:void(0)" class="add-to-cart-btn" data-product-id="{{ $featured_product->id }}"><i class="fa fa-cart-plus"></i></a>
                            <a href="{{ route('product.details',$products->slug) }}"><i class="fa fa-search"></i></a>
                        </div>										
                    </div>

                    <div class="product_btm_text">
                        <h4><a href="{{ route('product.details',$featured_product->slug) }}">{{ $featured_product->name }}</a></h4>
                        <span class="price">Rs {{ $featured_product->total_price }}</span>
                    </div>
                </div>								
            </div> 
            <!-- End Col -->	
            @endforeach
        </div>
    </div>
</section>
@endif --}}
<!-- End Featured Products Area -->


<!--  Brand -->
{{-- @if($brands->isNotEmpty()) --}}
{{-- <section id="brand_area" class="text-center">
    <div class="container">					
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section_title">						
                    <h2>Our <span>Brand</span></h2>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="brand_slide owl-carousel">
                    @foreach($brands as $brand)
                    <div class="item text-center"> <a href="{{ route('brands.products',$brand->slug) }}"><img src="{{ $brand->getFirstMediaUrl('brand') }}" alt="{{ $brand->name }}" class="img-responsive" /></a> </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>        
</section>    --}}
{{-- @endif      --}}
<!-- Brand end -->	


<!-- Testimonials Area -->
{{-- @if($testimonials->isNotEmpty()) --}}
{{-- <section id="testimonials" class="testimonials_area section_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-4"><img src="{{ asset('assets/site-assets/img/review.png') }}" alt="" class="img-responsive" /></a></div>
            <div class="col-md-8">

                <div class="rivwq">
                    <img src="{{ asset('assets/site-assets/img/testimonial/r1.jpg') }}" alt="">
                    <h2><span>4.9</span> 107 Google Reviews</h2>
                </div>

                <div id="testimonial-slider" class="owl-carousel">
                    @foreach($testimonials as $testimonial)
                    <div class="testimonial">
                        <div class="pic">
                            <img src="{{ $testimonial->getFirstMediaUrl('testimonial') }}" alt="">
                        </div>
                        <div class="testimonial-content">
                            <p class="description">
                                {!! $testimonial->message !!}
                            </p>
                            <div class="pd_ratng">
                                <div class="rtngs">
                                    @for($i=1; $i<= $testimonial->rating; $i++)
                                    <i class="fa fa-star"></i>
                                    @endfor
                                    @for($j=$testimonial->rating; $j< 5; $j++)
                                    <i class="fa fa-star" style="color: #817f79;"></i>
                                    @endfor
                                </div>
                            </div>
                            <h3 class="testimonial-title">{{ $testimonial->name }}</h3>
                            <small class="post"> - {{ $testimonial->address }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>  --}}
{{-- @endif --}}
<!-- End STestimonials Area -->		


@endsection