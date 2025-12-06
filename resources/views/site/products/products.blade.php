@extends('layouts.web-app')

@section('title') Shop @endsection

@section('content')

<!-- Page item Area -->
<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <h4>Shop</h4>
                    <div class="breadcrumb-links">
                        <a href="{{ route('home') }}">Home</a>
                        <span>Shop</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Shop Product Area -->
{{-- <div class="shop_page_area">
    <div class="container">
        <div class="shop_bar_tp fix">
            <form method="GET" id="filterForm">
                <div class="row">
                    <div class="col-sm-6 col-xs-12 short_by_area">
                        <div class="short_by_inner">
                            <label>Sort by:</label>
                            <select class="sort-select" name="sort_by" onchange="document.getElementById('filterForm').submit()">
                                <option value="" selected disabled>Choose...</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name Descending</option>
                                <option value="date_desc" {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>Date Descending</option>
                                <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price Assending</option>
                                <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price Descending</option>
                            </select>
                        </div>
                    </div>
        
                    <div class="col-sm-6 col-xs-12 show_area">
                        <div class="show_inner">
                            <label>Show:</label>
                            <select class="show-select" name="show" onchange="document.getElementById('filterForm').submit()">
                                <option value="4" {{ request('show') == '4' ? 'selected' : '' }}>4</option>
                                <option value="8" {{ request('show') == '8' ? 'selected' : '' }}>8</option>
                                <option value="12" {{ request('show') == '12' ? 'selected' : '' }}>12</option>
                                <option value="30" {{ request('show') == '30' ? 'selected' : '' }}>30</option>
                                <option value="{{ $products->total() }}" {{ request('show') == $products->total() ? 'selected' : '' }}>ALL</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
            
        <div class="shop_details text-center">
            <div class="row">    
                @foreach($products as $product)
                <div class="col-md-3 col-sm-6">
                    <div class="single_product">
                        <div class="product_image">
                            <img src="{{ getProductMainImage($product->id) }}" alt=""/>
                            <div class="box-content">
                                <a href="javascript:void(0)" class="add-to-wishlist" data-product-id="{{ $product->id }}"><i class="fa fa-heart-o"></i></a>
                                
                                <a href="{{ route('product.details',$product->slug) }}"><i class="fa fa-search"></i></a>
                            </div>                                        
                        </div>
        
                        <div class="product_btm_text">
                            <h4><a href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a></h4>
                            <span class="price">Rs {{ $product->total_price }}</span>
                        </div>
                    </div>                                
                </div> 
                @endforeach
            </div>
        </div>
        
        <div class="col-xs-12">
            <div class="blog_pagination pgntn_mrgntp fix">
                <div class="pagination text-center">
                    @if ($products->hasPages())
                        <ul>
                            @if ($products->onFirstPage())
                                <li class="disabled"><a href="#"><i class="fa fa-angle-left"></i></a></li>
                            @else
                                <li><a href="{{ $products->previousPageUrl() }}"><i class="fa fa-angle-left"></i></a></li>
                            @endif
        
                            @foreach ($products->links()->elements as $element)
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        <li>
                                            <a href="{{ $url }}" class="{{ ($page == $products->currentPage()) ? 'active' : '' }}">
                                                {{ $page }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
        
                            @if ($products->hasMorePages())
                                <li><a href="{{ $products->nextPageUrl() }}"><i class="fa fa-angle-right"></i></a></li>
                            @else
                                <li class="disabled"><a href="#"><i class="fa fa-angle-right"></i></a></li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> --}}

<section class="shop spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="shop-sidebar">
                    <div class="shop-sidebar-search">
                        <form action="#">
                            <input type="text" placeholder="Search...">
                            <button type="submit"><span class="icon_search"></span></button>
                        </form>
                    </div>
                    <div class="shop-sidebar-accordion">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-heading">
                                    <a data-toggle="collapse" data-target="#collapseOne">Categories</a>
                                </div>
                                <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="shop-sidebar-categories">
                                            <ul class="nice-scroll">
                                                <li><a href="#">Men (20)</a></li>
                                                <li><a href="#">Women (20)</a></li>
                                                <li><a href="#">Bags (20)</a></li>
                                                <li><a href="#">Clothing (20)</a></li>
                                                <li><a href="#">Shoes (20)</a></li>
                                                <li><a href="#">Accessories (20)</a></li>
                                                <li><a href="#">Kids (20)</a></li>
                                                <li><a href="#">Kids (20)</a></li>
                                                <li><a href="#">Kids (20)</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-heading">
                                    <a data-toggle="collapse" data-target="#collapseTwo">Branding</a>
                                </div>
                                <div id="collapseTwo" class="collapse show" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="shop-sidebar-brand">
                                            <ul>
                                                <li><a href="#">Louis Vuitton</a></li>
                                                <li><a href="#">Chanel</a></li>
                                                <li><a href="#">Hermes</a></li>
                                                <li><a href="#">Gucci</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-heading">
                                    <a data-toggle="collapse" data-target="#collapseThree">Filter Price</a>
                                </div>
                                <div id="collapseThree" class="collapse show" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="shop-sidebar-price">
                                            <ul>
                                                <li><a href="#">₹0.00 - ₹50.00</a></li>
                                                <li><a href="#">₹50.00 - ₹100.00</a></li>
                                                <li><a href="#">₹100.00 - ₹150.00</a></li>
                                                <li><a href="#">₹150.00 - ₹200.00</a></li>
                                                <li><a href="#">₹200.00 - ₹250.00</a></li>
                                                <li><a href="#">250.00+</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-heading">
                                    <a data-toggle="collapse" data-target="#collapseFour">Size</a>
                                </div>
                                <div id="collapseFour" class="collapse show" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="shop-sidebar-size">
                                            <label for="xs">xs
                                                <input type="radio" id="xs">
                                            </label>
                                            <label for="sm">s
                                                <input type="radio" id="sm">
                                            </label>
                                            <label for="md">m
                                                <input type="radio" id="md">
                                            </label>
                                            <label for="xl">xl
                                                <input type="radio" id="xl">
                                            </label>
                                            <label for="2xl">2xl
                                                <input type="radio" id="2xl">
                                            </label>
                                            <label for="xxl">xxl
                                                <input type="radio" id="xxl">
                                            </label>
                                            <label for="3xl">3xl
                                                <input type="radio" id="3xl">
                                            </label>
                                            <label for="4xl">4xl
                                                <input type="radio" id="4xl">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-heading">
                                    <a data-toggle="collapse" data-target="#collapseFive">Colors</a>
                                </div>
                                <div id="collapseFive" class="collapse show" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="shop-sidebar-color">
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
                                            <label class="c-5" for="sp-5">
                                                <input type="radio" id="sp-5">
                                            </label>
                                            <label class="c-6" for="sp-6">
                                                <input type="radio" id="sp-6">
                                            </label>
                                            <label class="c-7" for="sp-7">
                                                <input type="radio" id="sp-7">
                                            </label>
                                            <label class="c-8" for="sp-8">
                                                <input type="radio" id="sp-8">
                                            </label>
                                            <label class="c-9" for="sp-9">
                                                <input type="radio" id="sp-9">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-heading">
                                    <a data-toggle="collapse" data-target="#collapseSix">Tags</a>
                                </div>
                                <div id="collapseSix" class="collapse show" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="shop-sidebar-tags">
                                            <a href="#">Product</a>
                                            <a href="#">Bags</a>
                                            <a href="#">Shoes</a>
                                            <a href="#">Fashio</a>
                                            <a href="#">Clothing</a>
                                            <a href="#">Hats</a>
                                            <a href="#">Accessories</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="shop-product-option">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="shop-product-option-left">
                                <p>Showing 1–12 of 126 results</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="shop-product-option-right">
                                <p>Sort by Price:</p>
                                <select name="sort_by" onchange="document.getElementById('filterForm').submit()">
                                    <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Low To High</option>
                                    <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>High To Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="product-item">
                            <a href="{{ route('product.details',$product->slug) }}">
                                <div class="product-item-pic set-bg" data-setbg="{{ getProductMainImage($product->id) }}" style="background-image: url('{{ getProductMainImage($product->id) }}');">
                                    {{-- <ul class="product-hover">
                                        <li><a href="javascript:void(0);" class="add-to-wishlist" data-product-id="{{ $product->id }}"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a></li>
                                        <li><a href="#"><img src="{{ asset('assets/site-assets/img/icon/compare.png') }}" alt=""> <span>Compare</span></a>
                                        </li>
                                        <li><a href="{{ route('product.details',$product->slug) }}"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a></li>
                                    </ul> --}}
                                </div>
                                <div class="product-item-text">
                                    <h6>{{ $product->name }}</h6>
                                    <a href="javascript:void(0);" class="add-cart add-to-cart-btn" data-product-id="{{ $product->id }}">+ Add To Cart</a>
                                    <div class="rating">
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </div>
                                    <h5>₹{{ $product->total_price }}</h5>
                                    {{-- <div class="product-color-select">
                                        <label for="pc-4">
                                            <input type="radio" id="pc-4">
                                        </label>
                                        <label class="active black" for="pc-5">
                                            <input type="radio" id="pc-5">
                                        </label>
                                        <label class="grey" for="pc-6">
                                            <input type="radio" id="pc-6">
                                        </label>
                                    </div> --}}
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        @if ($products->hasPages())
                            <div class="product-pagination">
                                {{-- Previous Page Link --}}
                                @if ($products->onFirstPage())
                                    <a class="disabled" href="#"><i class="fa fa-angle-left"></i></a>
                                @else
                                    <a href="{{ $products->previousPageUrl() }}"><i class="fa fa-angle-left"></i></a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($products->links()->elements as $element)
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            <a href="{{ $url }}" class="{{ $page == $products->currentPage() ? 'active' : '' }}">
                                                {{ $page }}
                                            </a>
                                        @endforeach
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($products->hasMorePages())
                                    <a href="{{ $products->nextPageUrl() }}"><i class="fa fa-angle-right"></i></a>
                                @else
                                    <a class="disabled" href="#"><i class="fa fa-angle-right"></i></a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection