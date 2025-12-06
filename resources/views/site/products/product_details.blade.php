@extends('layouts.web-app')

@section('title') Product Details @endsection

@section('content')

<!-- Product Details Area  -->
{{-- <div class="prdct_dtls_page_area">
    <div class="container">
        <div class="row">
            <!-- Product Details Image -->
            <div class="col-md-6 col-xs-12">
                <div class="pd_img fix">
                    @include('site.products._preview')
                </div>
            </div>
            <!-- Product Details Content -->
            <div class="col-md-6 col-xs-12">
                <div class="prdct_dtls_content">
                    <a class="pd_title" href="#">{{ $product->name }}</a>
                    <div class="pd_price_dtls fix">
                        <!-- Product Price -->
                        <div class="pd_price">
                            <span class="new" id="dynamic-price">Rs {{ $product->total_price }}</span>
                            <span class="old">({{ $product->price }})</span>
                        </div>
                        <!-- Product Ratting -->
                        <div class="pd_ratng">
                            <div class="rtngs">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </div>
                        </div>
                    </div>
                    <div class="pd_text">
                        <h4>overview:</h4>
                        {!! $product->sort_description !!}
                    </div>
                    <div class="pd_clr_qntty_dtls fix">
                        @if($product->product_type == 'attribute')
                            @foreach($product->variations as $variation)
                                <div class="pd_clr">
                                    <h4>{{ $variation->name }}:</h4>
                                    @foreach($variation->options as $option)
                                        @if($option->variation_type == 'color')
                                            <a href="#" class="color-option variation-option"
                                                data-option-id="{{ $option->id }}"
                                                data-price="{{ $option->price }}"
                                                style="background: {{ $option->value }};">
                                                {{ $option->value }}
                                            </a>
                                        @elseif($option->variation_type == 'image')
                                            <a href="#" class="image-option variation-option"
                                                data-option-id="{{ $option->id }}"
                                                data-price="{{ $option->price }}">
                                                <img src="{{ asset('uploads/variations/' . $option->value) }}" alt="{{ $option->value }}" width="30" height="30">
                                            </a>
                                        @else
                                            <a href="#" class="text-option variation-option"
                                                data-option-id="{{ $option->id }}"
                                                data-price="{{ $option->price }}">
                                                {{ $option->value }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                        <input type="hidden" id="selected-variation-id" value="">

                        <div class="pd_qntty_area">
                            <h4>quantity:</h4>
                            <div class="pd_qty fix">
                                <input value="1" name="qttybutton" class="cart-plus-minus-box" id="quantity_6041ce9eca5d6" type="number">
                            </div>
                        </div>
                    </div>
                    <!-- Product Action -->
                    <div class="pd_btn fix">
                        <a class="btn btn-default acc_btn add-to-cart-btn" data-product-id="{{ $product->id }}" data-product-type="{{ $product->product_type }}">add to bag</a>
                        <a class="btn btn-default acc_btn btn_icn add-to-wishlist" data-product-id="{{ $product->id }}" data-product-type="{{ $product->product_type }}"><i class="fa fa-heart"></i></a>
                    </div>
                    <div class="pd_share_area fix">
                        <h4>share this on:</h4>
                        <div class="pd_social_icon">
                            <a class="facebook" href="#"><i class="fa fa-facebook"></i></a>
                            <a class="twitter" href="#"><i class="fa fa-twitter"></i></a>
                            <a class="vimeo" href="#"><i class="fa fa-vimeo"></i></a>
                            <a class="google_plus" href="#"><i class="fa fa-google-plus"></i></a>
                            <a class="tumblr" href="#"><i class="fa fa-tumblr"></i></a>
                            <a class="pinterest" href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12">					
                <div class="pd_tab_area fix">									
                    <ul class="pd_tab_btn nav nav-tabs" role="tablist">
                      <li>
                        <a class="active" href="#description" role="tab" data-toggle="tab">Description</a>
                      </li>
                      <li>
                        <a href="#reviews" role="tab" data-toggle="tab">Reviews</a>
                      </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade show active" id="description">
                            {!! $product->long_description !!}					  
                        </div>

                            <div role="tabpanel" class="tab-pane fade" id="reviews">
                                <div class="pda_rtng_area fix">
                                    <h4>4.5 <span>(Overall)</span></h4>
                                    <span>Based on 9 Comments</span>
                                </div>
                                <div class="rtng_cmnt_area fix">
                                    <div class="single_rtng_cmnt">
                                        <div class="rtngs">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-o"></i>
                                        <span>(4)</span>
                                        </div>
                                        <div class="rtng_author">
                                            <h3>John Doe</h3>
                                            <span>11:20</span>
                                            <span>6 January 2017</span>
                                        </div>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Utenim ad minim veniam, quis nost rud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Utenim ad minim veniam, quis nost.</p>
                                    </div>

                                </div>
                                <div class="col-md-6 rcf_pdnglft">
                                    <div class="rtng_cmnt_form_area fix">
                                        <h3>Add your Comments</h3>
                                        <div class="rtng_form">
                                            <form action="#">
                                                <div class="input-area"><input type="text" placeholder="Type your name" /></div>
                                                <div class="input-area"><input type="text" placeholder="Type your email address" /></div>
                                                <div class="input-area"><textarea name="message" placeholder="Write a review"></textarea></div>
                                                <input class="btn border-btn" type="submit" value="Add Review" />
                                            </form>
                                        </div>
                                    </div>
                                </div>				  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}


<section class="shop-details">
    <div class="product-details-pic">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-details-breadcrumb">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('product.all') }}">Shop</a>
                        <span>Product Details</span>
                    </div>
                </div>
            </div>
            <div class="row">
                @include('site.products._preview')
            </div>
        </div>
    </div>
    <div class="product-details-content">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="product-details-text">
                        <h4>{{ $product->name }}</h4>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-o"></i>
                            <span> - 5 Reviews</span>
                        </div>
                        <h3>₹{{ $product->total_price }} <span>{{ $product->price }}</span></h3>
                        <p>{!! $product->sort_description !!}</p>
                        {{-- <div class="product-details-option">
                            <div class="product-details-option-size">
                                <span>Size:</span>
                                <label for="xxl">xxl
                                    <input type="radio" id="xxl">
                                </label>
                                <label class="active" for="xl">xl
                                    <input type="radio" id="xl">
                                </label>
                                <label for="l">l
                                    <input type="radio" id="l">
                                </label>
                                <label for="sm">s
                                    <input type="radio" id="sm">
                                </label>
                            </div>
                            <div class="product-details-option-color">
                                <span>Color:</span>
                                <label class="c-1" for="sp-1">
                                    <input type="radio" id="sp-1">
                                </label>
                                <label class="c-2" for="sp-2">
                                    <input type="radio" id="sp-2">
                                </label>
                                <label class="c-3" for="sp-3">
                                    <input type="radio" id="sp-3">
                                </label>
                                <label class="c-4" for="sp-4">
                                    <input type="radio" id="sp-4">
                                </label>
                                <label class="c-9" for="sp-9">
                                    <input type="radio" id="sp-9">
                                </label>
                            </div>
                        </div> --}}
                        <div class="product-details-cart-option">
                            <div class="quantity">
                                <div class="pro-qty">
                                    <input type="text" value="1">
                                </div>
                            </div>
                            <a href="#" class="primary-btn add-to-cart-btn" data-product-id="{{ $product->id }}">add to cart</a>
                        </div>
                        <div class="product-details-btns-option">
                            <a href="#"><i class="fa fa-heart"></i> add to wishlist</a>
                            <a href="#"><i class="fa fa-exchange"></i> Add To Compare</a>
                        </div>
                        <div class="product-details-last-option">
                            <h5><span>Guaranteed Safe Checkout</span></h5>
                            <img src="{{ asset('assets/site-assets/img/shop-details/details-payment.png') }}" alt="">
                            <ul>
                                <li><span>SKU:</span> {{ $product->sky }}</li>
                                <li><span>Categories:</span> {{ $product->category }}</li>
                                {{-- <li><span>Tag:</span> Clothes, Skin, Body</li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-details-tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-5"
                                role="tab">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Customer
                                Previews(5)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-7" role="tab">Additional
                                information</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                <div class="product-details-tab-content">
                                    <p class="note">{!! $product->long_description !!}	</p>
                                    {{-- <div class="product-details-tab-content-item">
                                        <h5>Products Infomation</h5>
                                        <p>A Pocket PC is a handheld computer, which features many of the same
                                            capabilities as a modern PC. These handy little devices allow
                                            individuals to retrieve and store e-mail messages, create a contact
                                            file, coordinate appointments, surf the internet, exchange text messages
                                            and more. Every product that is labeled as a Pocket PC must be
                                            accompanied with specific software to operate the unit and must feature
                                        a touchscreen and touchpad.</p>
                                        <p>As is the case with any new technology product, the cost of a Pocket PC
                                            was substantial during it’s early release. For approximately ₹700.00,
                                            consumers could purchase one of top-of-the-line Pocket PCs in 2003.
                                            These days, customers are finding that prices have become much more
                                            reasonable now that the newness is wearing off. For approximately
                                        ₹350.00, a new Pocket PC can now be purchased.</p>
                                    </div>
                                    <div class="product-details-tab-content-item">
                                        <h5>Material used</h5>
                                        <p>Polyester is deemed lower quality due to its none natural quality’s. Made
                                            from synthetic materials, not natural like wool. Polyester suits become
                                            creased easily and are known for not being breathable. Polyester suits
                                            tend to have a shine to them compared to wool and cotton suits, this can
                                            make the suit look cheap. The texture of velvet is luxurious and
                                            breathable. Velvet is a great choice for dinner party jacket and can be
                                        worn all year round.</p>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-6" role="tabpanel">
                                <div class="product-details-tab-content">
                                    <div class="product-details-tab-content-item">
                                        <h5>Products Infomation</h5>
                                        <p>A Pocket PC is a handheld computer, which features many of the same
                                            capabilities as a modern PC. These handy little devices allow
                                            individuals to retrieve and store e-mail messages, create a contact
                                            file, coordinate appointments, surf the internet, exchange text messages
                                            and more. Every product that is labeled as a Pocket PC must be
                                            accompanied with specific software to operate the unit and must feature
                                        a touchscreen and touchpad.</p>
                                        <p>As is the case with any new technology product, the cost of a Pocket PC
                                            was substantial during it’s early release. For approximately ₹700.00,
                                            consumers could purchase one of top-of-the-line Pocket PCs in 2003.
                                            These days, customers are finding that prices have become much more
                                            reasonable now that the newness is wearing off. For approximately
                                        ₹350.00, a new Pocket PC can now be purchased.</p>
                                    </div>
                                    <div class="product-details-tab-content-item">
                                        <h5>Material used</h5>
                                        <p>Polyester is deemed lower quality due to its none natural quality’s. Made
                                            from synthetic materials, not natural like wool. Polyester suits become
                                            creased easily and are known for not being breathable. Polyester suits
                                            tend to have a shine to them compared to wool and cotton suits, this can
                                            make the suit look cheap. The texture of velvet is luxurious and
                                            breathable. Velvet is a great choice for dinner party jacket and can be
                                        worn all year round.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-7" role="tabpanel">
                                <div class="product-details-tab-content">
                                    <p class="note">Nam tempus turpis at metus scelerisque placerat nulla deumantos
                                        solicitud felis. Pellentesque diam dolor, elementum etos lobortis des mollis
                                        ut risus. Sedcus faucibus an sullamcorper mattis drostique des commodo
                                    pharetras loremos.</p>
                                    <div class="product-details-tab-content-item">
                                        <h5>Products Infomation</h5>
                                        <p>A Pocket PC is a handheld computer, which features many of the same
                                            capabilities as a modern PC. These handy little devices allow
                                            individuals to retrieve and store e-mail messages, create a contact
                                            file, coordinate appointments, surf the internet, exchange text messages
                                            and more. Every product that is labeled as a Pocket PC must be
                                            accompanied with specific software to operate the unit and must feature
                                        a touchscreen and touchpad.</p>
                                        <p>As is the case with any new technology product, the cost of a Pocket PC
                                            was substantial during it’s early release. For approximately ₹700.00,
                                            consumers could purchase one of top-of-the-line Pocket PCs in 2003.
                                            These days, customers are finding that prices have become much more
                                            reasonable now that the newness is wearing off. For approximately
                                        ₹350.00, a new Pocket PC can now be purchased.</p>
                                    </div>
                                    <div class="product-details-tab-content-item">
                                        <h5>Material used</h5>
                                        <p>Polyester is deemed lower quality due to its none natural quality’s. Made
                                            from synthetic materials, not natural like wool. Polyester suits become
                                            creased easily and are known for not being breathable. Polyester suits
                                            tend to have a shine to them compared to wool and cotton suits, this can
                                            make the suit look cheap. The texture of velvet is luxurious and
                                            breathable. Velvet is a great choice for dinner party jacket and can be
                                        worn all year round.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($relatedProducts->isNotEmpty())
<section class="related spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="related-title">Related Product</h3>
            </div>
        </div>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                <div class="product-item">
                    <a href="{{ route('product.details',$relatedProduct->slug) }}">
                        <div class="product-item-pic set-bg" data-setbg="{{ getProductMainImage($relatedProduct->id) }}" style="background-image: url('{{ getProductMainImage($relatedProduct->id) }}');">
                            <span class="label">New</span>
                            <ul class="product-hover">
                                <li><a href="javascript:void(0);" class="add-to-wishlist" data-product-id="{{ $relatedProduct->id }}"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                                <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a></li>
                                <li><a href="{{ route('product.details',$relatedProduct->slug) }}"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product-item-text">
                            <h6>{{ $relatedProduct->name }}</h6>
                            <a href="javascript:void(0);" class="add-cart add-to-cart-btn" data-product-id="{{ $relatedProduct->id }}">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>₹{{ $relatedProduct->total_price }}</h5>
                            {{-- <div class="product-color-select">
                                <label for="pc-1">
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
            {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                <div class="product-item">
                    <div class="product-item-pic set-bg" data-setbg="img/product/product-2.jpg">
                        <ul class="product-hover">
                            <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                            <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
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
            <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                <div class="product-item sale">
                    <div class="product-item-pic set-bg" data-setbg="img/product/product-3.jpg">
                        <span class="label">Sale</span>
                        <ul class="product-hover">
                            <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                            <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
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
            <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                <div class="product-item">
                    <div class="product-item-pic set-bg" data-setbg="img/product/product-4.jpg">
                        <ul class="product-hover">
                            <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                            <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                            <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
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
            </div> --}}
        </div>
    </div>
</section>
@endif

@endsection