<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\UsedCoupon;
use App\Models\ProductVariationOption;

class CartController extends Controller
{
    public function index(){
        $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
        // $carts = Cart::where('user_id', $userId)->get();
        $carts = Cart::with('product', 'productVariationOption')
                ->where('user_id', $userId)
                ->get();
        return view('site.cart.cart',compact('carts'));
    }

    public function wishlist_index(){
        $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
        $wishlists = Wishlist::where('user_id', $userId)->get();
        return view('site.cart.wishlist',compact('wishlists'));
    }

    public function cartCount()
    {
        $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
        $cartCount = Cart::where('user_id', $userId)->count();

        return response()->json(['count' => $cartCount]);
    }

    public function sum_cart_total(){
        $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
        // $carts = Cart::where('user_id', $userId)->with('product')->get();

        // $totalPrice = $carts->sum(function ($cart) {
        //     return $cart->product->total_price * $cart->quantity;
        // });

        $carts = Cart::where('user_id', $userId)->with('product', 'productVariationOption')->get(); // Include product variation option

        $totalPrice = $carts->sum(function ($cart) {
            // If the cart item has a selected variation, calculate using variation price
            if ($cart->product_variation_options_id) {
                $variation = $cart->productVariationOption;
                $variationPrice = $variation ? $variation->price : 0;
                return ($variationPrice) * $cart->quantity;
            }else{
                return $cart->product->total_price * $cart->quantity;
            }

        });

        if (session()->has('applied_coupon')) {
            $totalPrice -= session('applied_coupon.discount');
        }

        return response()->json(['total' => $totalPrice]);
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');

        // Check if product has variations and if a variation is selected
        $product = Product::find($request->product_id);
        $isAttributeProduct = $product->product_type === 'attribute';

        // If the product is an attribute product, ensure variation_id is provided
        if ($isAttributeProduct && !$request->has('variation_id')) {
            return response()->json(['error' => 'Please select a variation before adding to cart.'], 422);
        }

        // Get the variation name if it exists
        $variationName = null;
        if ($isAttributeProduct && $request->has('variation_id')) {
            $variation = ProductVariationOption::find($request->variation_id);
            $variationName = $variation ? $variation->variation_name : 'No variation selected';
        }

        // Initialize stock check
        $availableStock = $product->stock; // Default stock for simple products
        $variationName = null;

        // If variation is selected, get stock from variation table
        if ($isAttributeProduct && $request->has('variation_id')) {
            $variation = ProductVariationOption::find($request->variation_id);
            if (!$variation) {
                return response()->json([
                    'status' => 'false',
                    'massage' => 'Selected variation is invalid.'
                ]);
            }
            $availableStock = $variation->stock;
            $variationName = $variation->variation_name;
        }

        // Check if stock is available
        if ($availableStock < $request->quantity) {
            return response()->json([
                'status' => 'false',
                'massage' => 'Only ' . $availableStock . ' left in stock for ' . $product->name . ($variationName ? ' (' . $variationName . ')' : '') . '.'
            ]);
        }

        // Check for an existing cart item (either based on product_id or product_id + variation_id)
        $existingCartItem = Cart::where('user_id', $userId)
                                ->where('product_id', $request->product_id)
                                ->where('product_variation_options_id', $request->variation_id ?? null)
                                ->first();

        if ($existingCartItem) {

            // Check if total quantity exceeds stock before updating
            if ($existingCartItem->quantity + $request->quantity > $availableStock) {
                return response()->json([
                    'status' => 'false',
                    'massage' => 'Only ' . $availableStock . ' left in stock for ' . $product->name . ($variationName ? ' (' . $variationName . ')' : '') . '.'
                ]);
            }

            // Update quantity if the product is already in the cart
            $existingCartItem->quantity += $request->quantity;
            $existingCartItem->save();

            return response()->json([
                'status' => 'true',
                'massage' => $existingCartItem->product->name . ($variationName ? ' (' . $variationName . ')' : '') . ' updated in cart successfully'
            ]);
        }

        // Create a new cart item if no existing item is found
        $cartItem = Cart::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'product_variation_options_id' => $request->variation_id ?? null,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'status' => 'true',
            'massage' => $cartItem->product->name . ($variationName ? ' (' . $variationName . ')' : '') . ' added to cart successfully'
        ]);
    }



    public function add_to_wishlist(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');

        // Check if the product is already in the wishlist
        $wishlistItem = Wishlist::where('user_id', $userId)
                                ->where('product_id', $request->product_id)
                                ->first();

        if ($wishlistItem) {
            return response()->json([
                'status' => 'exists',
                'message' => 'Product is already in your wishlist!',
            ]);
        }

        // Add product to wishlist
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to wishlist!',
        ]);
    }
    
    public function delete_from_wishlist(string $id)
    {
        $wishlist = Wishlist::findOrFail($id);
        if ($wishlist) {
            $msg = $wishlist->product?->name.' Deleted from Cart Successfully';
            $wishlist->delete();
            return redirect()->back()->with('true',$msg);
        }
        return redirect()->back()->with('error','Item not found.');
    }

    public function updateCartQuantity(Request $request, $id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $newQuantity = $request->quantity;
            if ($newQuantity >= 1) {
                $cart->quantity = $newQuantity;
                $cart->save();
                // $total_price = $cart->product->total_price * $cart->quantity;
                $total_price = $cart->productVariationOption 
              ? $cart->productVariationOption->price * $cart->quantity 
              : $cart->product->total_price * $cart->quantity;

                return response()->json(['success' => 'Quantity updated successfully.','total_price'=>$total_price]);
            }
            return response()->json(['error' => 'Invalid quantity.'], 400);
        }
        return response()->json(['error' => 'Item not found.'], 404);
    }


    public function deleteCartItem($id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $msg = $cart->product->name.' Deleted from Cart Successfully';
            $cart->delete();
            return response()->json(['status'=>'true','massage' => $msg]);
        }
        return response()->json(['error' => 'Item not found.'], 404);
    }

    public function get_cart_products() {
        $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
        $carts = Cart::where('user_id', $userId)
                        ->with(['product', 'productVariationOption', 'product.media']) // Eager load variations and media
                        ->get()
                        ->map(function ($cartItem) {
                            // Add the main image URL to the associated product
                            $cartItem->product->image_url = $cartItem->product->getMedia('products-media')
                                ->firstWhere('custom_properties.is_main', true)?->getUrl();
    
                            // If variation exists, add the variation name and price
                            if ($cartItem->product_variation_options_id) {
                                $variation = $cartItem->productVariationOption; // Assuming this relation exists
                                $cartItem->variation_name = $variation ? $variation->variation_name : '';
                                $cartItem->variation_price = $variation ? $variation->price : 0;
                            } else {
                                // No variation selected
                                $cartItem->variation_name = '';
                                $cartItem->variation_price = 0;
                            }
                            return $cartItem;
                        });
    
        return response()->json($carts);
    }
    


    public function applyCoupon(Request $request)
    {
        $user = auth()->user(); // Ensure user is authenticated
    
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'You must be logged in to apply a coupon.']);
        }

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon']);
        }

        // Check if the user has already used this coupon (Only if usage_type is 'one-time')
        if ($coupon->usage_type === 'one-time') {
            $alreadyUsed = UsedCoupon::where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->exists();

            if ($alreadyUsed) {
                return response()->json(['success' => false, 'message' => 'You have already used this coupon.']);
            }
        }

        $cartTotal = calculate_cart_total();

        if ($cartTotal < $coupon->minimum_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum purchase of Rs ' . $coupon->minimum_purchase . ' required'
            ]);
        }

        // Calculate discount
        if ($coupon->type === 'percentage') {
            $discount = ($cartTotal * $coupon->value) / 100;
        } else {
            $discount = $coupon->value;
        }

        $newTotal = max(0, $cartTotal - $discount);

        // Store coupon details in session
        session(['applied_coupon' => [
            'code' => $coupon->code,
            'discount' => $discount,
            'new_total' => $newTotal
        ]]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'new_total' => $newTotal
        ]);
    }

}