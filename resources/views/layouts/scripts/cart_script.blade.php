<script>
    $(document).ready(function() {
        let selectedVariationId = null;

        // Handle variation selection
        $(".variation-option").on("click", function (e) {
            e.preventDefault();

            // Get selected price and ID
            selectedVariationId = $(this).data("option-id");
            let newPrice = $(this).data("price");

            // Update UI
            $("#dynamic-price").text('Rs '+newPrice);
            $("#selected-variation-id").val(selectedVariationId);

            // Remove active class from all and add to selected
            $(".variation-option").removeClass("active");
            $(this).addClass("active");
        });


        $('.add-to-cart-btn').on('click', function() {
            var productId = $(this).data('product-id');
            let isAttributeProduct = $(this).data('product-type') == 'attribute' ? true : false;
            var quantity = $('#quantity_6041ce9eca5d6').val();

            if (isAttributeProduct && !selectedVariationId) {
                alert("Please select a variation before adding to cart.");
                return;
            }
            
            if (!quantity || quantity === '') {
                quantity = 1;
            }

            // Send AJAX request
            $.ajax({
                url: "{{ route('add-to-cart') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    variation_id: isAttributeProduct ? selectedVariationId : null,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.status === "true") {
                        updateCartTotal();
                        updateCartCount();
                        update_mini_cart_wrapper();
                        updateCartUI();
                        showToast('success', 'Success', response.massage);
                    } else {
                        showToast('error', 'Error', response.massage);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        // Loop through errors and append to the message
                        $.each(errors, function (key, value) {
                            errorMessages += value[0] + '<br>';
                        });

                        // Show all error messages
                        showToast('error', 'Error', errorMessages);
                    } else {
                        // Show generic error message
                        showToast('error', 'Error', "Something went wrong. Please try again.");
                    }
                }
            });
        });

        $('.qty').on('change', function () {
            const button = $(this);
            const input = $(this);
            let quantity = parseInt(input.val());
            const cartId = button.closest('tr').data('id');

            $.ajax({
                url: "{{ route('cart.update',':id') }}".replace(':id', cartId),
                type: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: { quantity: quantity },
                success: function (response) {
                    // Update cart total price in the table
                    const totalPrice = response.total_price;
                    button.closest('tr').find('.cpp_total').text('₹' + totalPrice);

                    updateCartTotal();
                },
                error: function (error) {
                    showToast('error', 'Error', error.responseJSON.error);
                    input.val(1);
                }
            });
        });


        $('.cp_remove').on('click', function () {
            const cartId = $(this).closest('tr').data('id');
            $.ajax({
                url: "{{ route('cart.delete', ':id') }}".replace(':id', cartId),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status === "true") {
                        updateCartTotal();
                        updateCartCount();
                        update_mini_cart_wrapper();
                        showToast('success', 'Success', response.massage);
                        $('tr[data-id="' + cartId + '"]').remove();
                    } else {
                        showToast('error', 'Error', "Failed to delete item from cart.");
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors; // Get validation errors
                        let errorMessages = '';

                        // Loop through errors and append to the message
                        $.each(errors, function (key, value) {
                            errorMessages += value[0] + '<br>'; // Add each error message
                        });

                        // Show all error messages
                        showToast('error', 'Error', errorMessages);
                    } else {
                        // Show generic error message
                        showToast('error', 'Error', "Something went wrong. Please try again.");
                    }
                }
            });
        });


        function updateCartCount() {
            $.ajax({
                url: "{{ route('cart.count') }}",
                method: 'GET',
                success: function (response) {
                    if (response.count > 0) {
                        $('#cart-count').text(response.count).show();
                    } else {
                        $('#cart-count').hide();
                    }
                },
                error: function () {
                    console.error('Failed to fetch cart count.');
                }
            });
        }

        function updateCartUI() {
            $.ajax({
                url: "{{ route('cart.count') }}",
                method: 'GET',
                success: function (response) {
                    if (response.count > 0) {
                        $('#cart-indicator').removeClass('d-none');
                    } else {
                        $('#cart-count').hide();
                          $('#cart-indicator').addClass('d-none');
                    }
                },
                error: function () {
                    console.error('Failed to fetch cart count.');
                }
            });
        }

        
        function update_mini_cart_wrapper() {
            $.ajax({
                url: "{{ route('cart.cart_products') }}",
                method: 'GET',
                success: function (response) {
                    // console.log(response);
                    let miniCartWrapper = $('.mini-cart-wrapper');
                    let miniCartContent = '';

                    // Iterate through the response to build the cart items
                    response.forEach(function (cartItem) {
                        let product = cartItem.product;
                        let variationName = cartItem.variation_name;
                        let variationPrice = cartItem.variation_price;

                        // If a variation exists, include it in the display
                        let productName = variationName ? `${product.name} (${variationName})` : product.name;
                        let price = variationPrice ? variationPrice : (product.total_price || 0);

                        // Add each cart item to the mini-cart
                        miniCartContent += `
                            <div class="mc-sin-pro fix">
                                <a href="/product/details/${product.slug}" class="mc-pro-image float-left">
                                    <img src="${product.image_url || '/default-image.jpg'}" width="49" height="64" alt="${product.name}" />
                                </a>
                                <div class="mc-pro-details fix">
                                    <a href="/product/details/${product.slug}">${productName}</a>
                                    <span>${cartItem.quantity} x Rs ${price}</span>
                                </div>
                            </div>`;
                    });

                    // Calculate the subtotal
                    let subtotal = response.reduce((total, cartItem) => {
                        let product = cartItem.product;
                        let price = cartItem.variation_price || product.total_price; // Use variation price if available
                        return total + (cartItem.quantity * price);
                    }, 0).toFixed(2);

                    // Add subtotal and checkout button
                    miniCartContent += `
                        <div class="mc-subtotal fix">
                            <h4>Subtotal <span id="cart-total">Rs ${subtotal}</span></h4>
                        </div>
                        <div class="mc-button">
                            <a href="/checkout" class="checkout_btn">checkout</a>
                        </div>`;

                    // Update the mini-cart wrapper
                    miniCartWrapper.html(miniCartContent);

                },
                error: function () {
                    console.error('Failed to fetch cart products.');
                }
            });
        }


        function updateCartTotal() {
            $.ajax({
                url: "{{ route('cart.total') }}",
                method: 'GET',
                success: function (response) {
                    $('#cart-subtotal').text('₹' +parseFloat(response.total).toFixed(2))
                    $('#cart-grandtotal').text(response.total)
                },
                error: function () {
                    console.error('Failed to fetch cart count.');
                }
            });
        }       
        
        

        // Call the function to update cart count on page load
        updateCartCount();
        updateCartTotal();
        update_mini_cart_wrapper();
        updateCartUI();



        $('.add-to-wishlist').on('click', function() {

            let productId = $(this).data('product-id');
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('wishlist.add') }}",
                type: "POST",
                data: {
                    product_id: productId,
                    _token: csrfToken,
                },
                success: function (response) {
                    if (response.status === 'success') {
                        showToast('success', 'Success', response.message);
                    } else if (response.status === 'exists') {
                        showToast('warning', 'Warning', response.message);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 401) {
                        showToast('error', 'Error', "You need to log in to add items to your wishlist!");
                    } else {
                        showToast('error', 'Error', "Something went wrong. Please try again!");
                    }
                }
            });
        });


    });
</script>