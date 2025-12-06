@extends('layouts.web-app')

@section('title') Checkout @endsection

@section('content')

<!-- Page item Area -->
<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <h4>Check Out</h4>
                    <div class="breadcrumb-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('product.all') }}">Shop</a>
                        <span>Check Out</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Checkout Page -->
<section class="checkout spad">
    <div class="container">
        <form class="checkout_form" id="checkoutForm" action="{{ route('checkout.process') }}" method="post">
            @csrf
        <div class="row">
            <div class="col-lg-8 col-md-6">
                {{-- <h6 class="coupon-code"><span class="icon_tag_alt"></span> Have a coupon? <a href="#">Click
                here</a> to enter your code</h6> --}}
                <h6 class="checkout-title">Billing Details</h6>
                    @if($address->isNotEmpty())
                        @foreach($address as $addr)
                            <div class="radio form-check eachradio">
                                <label>
                                    <input type="radio" name="addrradio" required class="form-check-input" required value="{{ $addr->id }}" {{ $addr->is_default==1 ? 'checked' : '' }}>
                                    <p class="name">{{ $addr->billing_first_name }} {{ !empty($addr->billing_first_name) ?', '.$addr->billing_phone_number : '' }}</p>
                                    <p>{{ get_address_by_id($addr->id) }}</p>
                                    <div class="invalid-tooltip" style="width:23%;">Address is Required</div>
                                </label>
                            </div>
                        @endforeach
                    @endif
                    <div class="radio form-check eachradio">
                        <label>
                            <input type="radio" name="addrradio" class="form-check-input" required value="fornewaddr" @if($address->isEmpty()) checked @endif>
                            <p class="name">Add New</p><div class="invalid-tooltip" style="width:23%;">Address is Required</div>
                        </label>
                    </div>
                    <div id="address-data-fild">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input name="first_name" placeholder="First name" value="{{ old('first_name') }}" class="form-control" type="text" required>
                            </div>
                            
                            <div class="form-group col-md-6">								
                                <input name="last_name" placeholder="Last name" class="form-control" value="{{ old('last_name') }}" type="text" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input name="email" placeholder="Email address" class="form-control" value="{{ old('email') }}" type="email" required>
                            </div>
                    
                            <div class="form-group col-md-6">
                                <input name="phone" placeholder="Phone number" class="form-control" value="{{ old('phone') }}" type="tel" required>
                            </div>
                        </div>
                            
                        <div class="form-row">
                            <div class="form-group col-md-4">  
                                <label for="country">Country:</label>
                                <div class="custom-select-wrapper">
                                    <select id="country_id" name="country" required class="custom-select">
                                        <option value="" selected disabled>Choose...</option>
                                        @foreach($countrys as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">  
                                <label for="states_id">State:</label>
                                <div class="custom-select-wrapper">
                                    <select id="states_id" name="state" required class="custom-select" required>
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">  
                                <label for="citys_id">City:</label>
                                <div class="custom-select-wrapper">
                                    <select id="citys_id" name="city" required class="custom-select" required>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="address">Address:</label>    
                            <textarea rows="3" name="address" id="address" placeholder="Street address. Apartment, suite, unit etc. (optional)" class="form-control">{{ old('address') }}</textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input name="pincode" placeholder="Post code / Zip" value="{{ old('pincode') }}" class="form-control" type="text">
                            </div>
                            
                            {{-- <div class="form-group col-md-6">
                                <input name="city" placeholder="Town / City" class="form-control" type="text">
                            </div>								 --}}
                            
                        </div>

                        <div class="form-group">
                            <label for="address">Order note:</label>    
                            <textarea rows="3" placeholder="Order note" class="form-control" name="order_note">{{ old('order_note') }}</textarea>
                        </div>
                    </div>
                
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="checkout-order">
                    <h4 class="order-title">Your order</h4>
                    <div class="checkout-order-products">Product <span>Total</span></div>
                    <ul class="checkout-total-products">
                        @foreach($cart_items as $item)
                        <li>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}. {{ $item->product?->name }}
                                    @if($item->productVariationOption)
                                        ({{ $item->productVariationOption->variation_name }})
                                    @endif <span>₹ @if($item->productVariationOption)
                                        {{ $item->productVariationOption->price }}
                                    @else
                                    {{ $item->product?->total_price }}
                                    @endif</span></li>
                        @endforeach
                    </ul>
                    <ul class="checkout-total-all">
                        <li>Subtotal <span>₹{{ calculate_cart_total() }}</span></li>
                        <li>Total <span>₹{{ calculate_cart_total() }}</span></li>
                    </ul>

                   <ul>
                        <li>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="cod" name="payment_method" value="cod" class="custom-control-input" checked>
                                <label class="custom-control-label" for="cod">Cash on Delivery (COD)</label>
                                <p>Pay when you receive the product at your doorstep.</p>
                            </div>
                        </li>
                        
                        <li>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="razorpay" name="payment_method" value="razorpay" class="custom-control-input">
                                <label class="custom-control-label" for="razorpay">Razorpay</label>
                                <p>Secure online payment via Razorpay gateway.</p>
                            </div>
                        </li>
                    </ul>

                    <button type="submit" id="placeOrderBtn" class="site-btn"  tabindex="0">PLACE ORDER</button>
                </div>
            </div>
        </div>
    </form>
    </div>
</section>

@endsection

@section('script')

<script>
    $(document).ready(function () {
        const formRow = $('#address-data-fild');
    
        function toggleFormRow() {
            const selectedValue = $('input[name="addrradio"]:checked').val();
    
            if (selectedValue === 'fornewaddr') {
                // Show the row and make fields required
                formRow.show();
                formRow.find('input, textarea, select').prop('required', true);
            } else {
                // Hide the row and remove the required attribute
                formRow.hide();
                formRow.find('input, textarea, select').prop('required', false);
            }
        }
    
        // Attach change event to the radio buttons
        $('input[name="addrradio"]').on('change', toggleFormRow);
    
        // Initialize row visibility based on default selection
        toggleFormRow();
    });
</script>

<!-- Include Razorpay Script -->
{{-- <script src="https://checkout.razorpay.com/v1/checkout.js"></script> --}}

<script>
    $(document).ready(function () {
        $('#checkoutForm').on('submit', function (e) {
            e.preventDefault();

            let paymentMethod = $('input[name="payment_method"]:checked').val();
            let placeOrderBtn = $('#placeOrderBtn');
            placeOrderBtn.prop('disabled', true); // Disable button to prevent multiple clicks

            let formData = $(this).serialize();
            // Send AJAX request to process checkout
            $.ajax({
                url: '{{ route("checkout.process") }}',
                method: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        if (response.razorpay) {
                            // Open Razorpay payment gateway
                            let options = {
                                key: response.key,
                                amount: response.amount,
                                currency: 'INR',
                                name: 'M7Prime',
                                description: 'Complete your order payment',
                                order_id: response.order_id,
                                prefill: {
                                    name: response.user.name,
                                    email: response.user.email,
                                    contact: response.user.phone,
                                },
                                handler: function (paymentResponse) {
                                    // Payment successful callback
                                    $.ajax({
                                        url: '{{ route("razorpay.callback") }}',
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        data: {
                                            razorpay_payment_id: paymentResponse.razorpay_payment_id
                                        },
                                        success: function (callbackResponse) {
                                            if (callbackResponse.success) {
                                                showToast('success', 'Success', callbackResponse.message);
                                                window.location.href = callbackResponse.redirect;
                                            } else {
                                                alert(callbackResponse.message);
                                                placeOrderBtn.prop('disabled', false);
                                            }
                                        },
                                        error: function () {
                                            alert('An error occurred while verifying the payment.');
                                            placeOrderBtn.prop('disabled', false);
                                        }
                                    });
                                },
                                theme: {
                                    color: '#012652'
                                },
                                modal: {
                                    ondismiss: function () {
                                        // Handle Razorpay modal cancellation
                                        placeOrderBtn.prop('disabled', false); // Re-enable button on cancel
                                    }
                                }
                            };
                            let rzp = new Razorpay(options);
                            rzp.open();
                        } else {
                            // For COD, redirect directly
                            showToast('success', 'Success', response.message);
                            window.location.href = response.redirect;
                        }
                    } else {
                        alert(response.message);
                        placeOrderBtn.prop('disabled', false);
                    }
                },
                error: function () {
                    alert('An error occurred while processing the checkout.');
                    placeOrderBtn.prop('disabled', false);
                }
            });
        });
    });
</script>

@endsection