@extends('layouts.web-app')

@section('title') Cart @endsection

@section('content')

<!-- Page item Area -->
<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <h4>Shopping Cart</h4>
                    <div class="breadcrumb-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('product.all') }}">Shop</a>
                        <span>Shopping Cart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- <!-- Cart Page -->
<div class="cart_page_area">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="cart_table_area table-responsive">
                    <table class="table cart_prdct_table text-center">
                        <thead>
                            <tr>
                                <th class="cpt_no">No.</th>
                                <th class="cpt_img">image</th>
                                <th class="cpt_pn">product name</th>
                                <th class="cpt_q">quantity</th>
                                <th class="cpt_p">price</th>
                                <th class="cpt_t">total</th>
                                <th class="cpt_r">remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart_item)
                            <tr data-id="{{ $cart_item->id }}">
                                <td><span class="cp_no">{{ $loop->iteration }}</span></td>
                                <td><a href="{{ route('product.details', $cart_item->product?->slug) }}" class="cp_img"><img src="{{ getProductMainImage($cart_item->product?->id) }}" alt="" /></a></td>
                                <td>
                                    <a href="{{ route('product.details', $cart_item->product?->slug) }}" class="cp_title">
                                        {{ $cart_item->product?->name }} 
                                        @if($cart_item->productVariationOption)
                                            ({{ $cart_item->productVariationOption->variation_name }})
                                        @endif
                                    </a>
                                </td>
                                <td>										
                                    <div class="cp_quntty">																			 
                                        <input name="quantity" value="{{ $cart_item->quantity }}" class="qty" size="2" type="number">													
                                    </div>
                                </td>
                                <td>
                                    @if($cart_item->productVariationOption)
                                        <h5> {{ $cart_item->productVariationOption->price }}</p>
                                    @else
                                        <h5> {{ $cart_item->product?->total_price }}</p>
                                    @endif
                                </td>
                                <td><p class="cpp_total">
                                    @if($cart_item->productVariationOption)
                                        Rs {{ $cart_item->productVariationOption->price * $cart_item->quantity }}
                                    @else
                                        Rs {{ $cart_item->product?->total_price * $cart_item->quantity }}
                                    @endif
                                </p></td>
                                <td><a class="btn btn-default cp_remove"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8 col-xs-12 cart-actions cart-button-cuppon">
                <div class="row">
                    <div class="col-sm-7">
                        <div class="cart-action">
                            <a href="{{ route('product.all') }}" class="btn border-btn">continiue shopping</a>
                            <a href="javascript:void(0)" onclick="location.reload()" class="btn border-btn">update shopping bag</a>
                        </div>
                    </div>
                    
                    <div class="col-sm-5">
                        <div class="cuppon-wrap">
                            <h4>Discount Code</h4>
                            <p>Enter your coupon code if you have</p>
                            <form id="applyCouponForm">
                                @csrf
                                <input type="text" id="coupon_code" name="coupon_code" placeholder="Enter coupon code" />
                                <button type="submit" class="btn border-btn">Apply Coupon</button>
                            </form>
                            <p id="coupon-message"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-xs-12 cart-checkout-process text-right">
                <div class="wrap">
                    <p><span>Subtotal</span><span id="cart-subtotal">Rs {{ calculate_cart_total() }}</span></p>
                    <h4><span>Grand total</span><span id="cart-grandtotal">Rs {{ calculate_cart_total() }}</span></h4>
                    <a href="{{ route('checkout') }}" class="btn border-btn">process to checkout</a>
                </div>
            </div>
            
        </div>
    </div>
</div> --}}

<section class="shopping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="shopping-cart-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart_item)
                            <tr data-id="{{ $cart_item->id }}">
                                <td class="product-cart-item">
                                    <div class="product-cart-item-pic">
                                        <img src="{{ getProductMainImage($cart_item->product?->id) }}" alt=""  style="width: 90px; height: 90px; object-fit: cover;">
                                    </div>
                                    <div class="product-cart-item-text">
                                        <h6>
                                            {{ $cart_item->product?->name }} 
                                            @if($cart_item->productVariationOption)
                                                ({{ $cart_item->productVariationOption->variation_name }})
                                            @endif
                                        </h6>
                                        @if($cart_item->productVariationOption)
                                            <h5> ₹{{ $cart_item->productVariationOption->price }}</h5>
                                        @else
                                            <h5> ₹{{ $cart_item->product?->total_price }}</h5>
                                        @endif
                                    </div>
                                </td>
                                <td class="quantity-item">
                                    <div class="quantity">
                                        <div class="pro-qty-2">
                                            <input type="text" value="{{ $cart_item->quantity }}" class="qty">
                                        </div>
                                    </div>
                                </td>
                                <td class="cart-price">
                                    @if($cart_item->productVariationOption)
                                        ₹{{ $cart_item->productVariationOption->price * $cart_item->quantity }}
                                    @else
                                        ₹{{ $cart_item->product?->total_price * $cart_item->quantity }}
                                    @endif
                                </td>
                                <td class="cart-close cp_remove"><i class="fa fa-close"></i></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="continue-btn">
                            <a href="{{ route('product.all') }}">Continue Shopping</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="continue-btn update-btn">
                            <a href="#" onclick="location.reload(); return false;"><i class="fa fa-spinner"></i> Update cart</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="cart-discount">
                    <h6>Discount codes</h6>
                    <form action="#">
                    <form id="applyCouponForm">
                        @csrf
                        <input type="text" id="coupon_code" name="coupon_code" placeholder="Coupon code" />
                        <button type="submit">Apply</button>
                    </form>
                    <p id="coupon-message"></p>
                </div>
                <div class="cart-total">
                    <h6>Cart total</h6>
                    <ul>
                        <li>Subtotal <span>₹ {{ calculate_cart_total() }}</span></li>
                        <li>Total <span>₹{{ calculate_cart_total() }}</span></li>
                    </ul>
                    <a href="{{ route('checkout') }}" class="primary-btn">Proceed to checkout</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        $("#applyCouponForm").submit(function (e) {
            e.preventDefault();
            let couponCode = $("#coupon_code").val();

            $.ajax({
                url: "{{ route('cart.apply.coupon') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    coupon_code: couponCode
                },
                success: function (response) {
                    if (response.success) {
                        showToast('success', 'Success', response.message);
                        $("#cart-grandtotal").text("Rs " + response.new_total);
                    } else {
                        showToast('error', 'Error', response.message);
                    }
                }
            });
        });
    });

</script>
@endsection