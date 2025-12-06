@extends('layouts.web-app')

@section('title') Wishlist @endsection

@section('content')
<!-- Page item Area -->
<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <h4>Wishlist</h4>
                    <div class="breadcrumb-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('product.all') }}">Shop</a>
                        <span>Wishlist</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Wishlist Page -->
{{-- <div class="wishlist-page">
    <div class="container">
        <div class="table-responsive">
            <table class="table cart-table cart_prdct_table text-center">
                <thead>
                    <tr>
                        <th class="cpt_no">#</th>
                        <th class="cpt_img">image</th>
                        <th class="cpt_pn">product name</th>
                        <th class="cpt_p">price</th>
                        <th class="add-cart">add to cart</th>
                        <th class="cpt_r">remove</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wishlists as $wishlist)
                    <tr>
                        <td><span class="cart-number">{{ $loop->iteration }}</span></td>
                        <td><a href="{{ route('product.details',$wishlist->product?->slug) }}" class="cp_img"><img src="{{ getProductMainImage($wishlist->product?->id) }}" alt="" /></a></td>
                        <td><a href="{{ route('product.details',$wishlist->product?->slug) }}" class="cart-pro-title">{{ $wishlist->product?->name }}</a></td>
                        <td><p class="cart-pro-price">Rs {{ $wishlist->product?->total_price }}</p></td>
                        <td><a href="javascript:void(0);" class="btn border-btn add-to-cart-btn" data-product-id="{{ $wishlist->product?->id }}">add to cart</a></td>
                        <td><a href="{{ route('wishlist.delete',$wishlist->id) }}" style="background: transparent;border: none;font-size: 20px;transition: .5s;"><i class="fa fa-trash"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> --}}

<section class="shopping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shopping-cart-table">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Add to Cart</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wishlists as $wishlist)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('product.details',$wishlist->product?->slug) }}" class="cp_img"><img src="{{ getProductMainImage($wishlist->product?->id) }}" alt="" style="width: 90px; height: 90px; object-fit: cover;" /></a>
                                </td>
                                <td>
                                    <a href="{{ route('product.details',$wishlist->product?->slug) }}" class="cart-pro-title">{{ $wishlist->product?->name }}</a>
                                </td>
                                <td>
                                    ₹{{ $wishlist->product?->total_price }}
                                </td>
                                <td><a href="javascript:void(0);" class="btn btn-danger add-to-cart-btn" data-product-id="{{ $wishlist->product?->id }}">Add to Cart</a></td>
                                <td class="cart-close cp_remove"><a href="{{ route('wishlist.delete',$wishlist->id) }}"><i class="fa fa-close"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection