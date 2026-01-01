<?php 
    use App\Models\Cart;
    use App\Models\Order;
    use App\Models\Brand;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cookie;

    if(!function_exists('calculate_cart_total')){
        function calculate_cart_total($user_id = null){
            $userId = $user_id ?? Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
            // $carts = Cart::where('user_id', $userId)->with('product')->get();

            // $totalPrice = $carts->sum(function ($cart) {
            //     return $cart->product->total_price * $cart->quantity;
            // });
            $carts = Cart::where('user_id', $userId)->with('product', 'productVariationOption')->get(); 

            $totalPrice = $carts->sum(function ($cart) {
                if ($cart->product_variation_options_id) {
                    $variation = $cart->productVariationOption;
                    $variationPrice = $variation ? $variation->price : 0;
                    // return $variationPrice;
                    return ($variationPrice) * $cart->quantity;
                }else{
                    return $cart->product->total_price * $cart->quantity;
                }

            });

            if (session()->has('applied_coupon')) {
                $totalPrice -= session('applied_coupon.discount');
            }

            return $totalPrice;
        }
    }

    if(!function_exists('cart_item_count')){
        function cart_item_count($user_id = null){
            $userId = $user_id ?? Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
            // $carts = Cart::where('user_id', $userId)->with('product')->get();

            // $totalPrice = $carts->sum(function ($cart) {
            //     return $cart->product->total_price * $cart->quantity;
            // });
            $cart_count = Cart::where('user_id', $userId)->count(); 

            return $cart_count;
        }
    }

    if(!function_exists('calculate_cart_sub_total_by_userId')){
        function calculate_cart_sub_total_by_userId(int $userId)
        {
            $total = 0;

            // $cartItems = Cart::where('user_id', $userId)->get();

            // foreach ($cartItems as $cartItem) {
            //     $total += $cartItem->quantity * $cartItem->product->price;
            // }
            $cartItems = Cart::where('user_id', $userId)->with('product', 'productVariationOption')->get(); // Include variations

            foreach ($cartItems as $cartItem) {
                // If variation is selected, add its price
                if ($cartItem->product_variation_options_id) {
                    $variation = $cartItem->productVariationOption; // Assuming relation to get variation
                    $variationPrice = $variation ? $variation->price : 0; // Get variation price
                    $total += ($variationPrice) * $cartItem->quantity; // Total with variation
                } else {
                    // No variation, just use the base product price
                    $total += $cartItem->product->price * $cartItem->quantity;
                }
            }

            return $total;
        }
    }

    if (!function_exists('generateOrderNumber')) {
        function generateOrderNumber() {
            $dateTime = date('YmdHis');
            $orderNumber = 'ORD' . $dateTime;
            return $orderNumber;
        }
    }

    if (!function_exists('update_order_number')) {
        function update_order_number($order_id, $order_number)
        {
            $data = array(
                'order_number' => $order_number.$order_id
            );
            Order::where('id', $order_id)->update($data);
        }
    }