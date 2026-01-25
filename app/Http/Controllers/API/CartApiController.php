<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductVariationOption;
use App\Models\Cart;
use Illuminate\Support\Str;
use App\Services\CouponService;

class CartApiController extends Controller
{
    public function add_to_cart(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        // $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
        $userId = $request->user()->id;

        // Check if product has variations and if a variation is selected
        $product = Product::find($request->product_id);
        $isAttributeProduct = $product->product_type === 'attribute';

        // If the product is an attribute product, ensure variation_id is provided
        if ($isAttributeProduct && !$request->has('variation_id')) {
            return apiResponse(false,'Please select a variation before adding to cart.',null,422);
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
        // if ($isAttributeProduct && $request->has('variation_id')) {
        //     $variation = ProductVariationOption::find($request->variation_id);
        //     if (!$variation) {
        //         return apiResponse(false,'Selected variation is invalid.',null,422);
        //     }
        //     $availableStock = $variation->stock;
        //     $variationName = $variation->variation_name;
        // }

        // Check if stock is available
        // if ($availableStock < $request->quantity) {
        //     return apiResponse(false,'Only ' . $availableStock . ' left in stock for ' . $product->name . ($variationName ? ' (' . $variationName . ')' : '') . '.',null,200);
        // }

        // Check for an existing cart item (either based on product_id or product_id + variation_id)
        $existingCartItem = Cart::where('user_id', $userId)
                                ->where('product_id', $request->product_id)
                                ->where('product_variation_options_id', $request->variation_id ?? null)
                                ->first();
        if($request->type=='customise'){
            $existingCartItem = null;
        }
        if ($existingCartItem) {

            // Check if total quantity exceeds stock before updating
            // if ($existingCartItem->quantity + $request->quantity > $availableStock) {
            //     return apiResponse(false,'Only ' . $availableStock . ' left in stock for ' . $product->name . ($variationName ? ' (' . $variationName . ')' : '') . '.',null,200);
            // }

            // Update quantity if the product is already in the cart
            $existingCartItem->quantity += $request->quantity;
            $existingCartItem->save();

            return apiResponse(true,$existingCartItem->product->name . ($variationName ? ' (' . $variationName . ')' : '') . ' updated in cart successfully',null,200);
        }

        // Create a new cart item if no existing item is found
        $cartItem = Cart::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'product_variation_options_id' => $request->variation_id ?? null,
            'product_variation_options_id2' => $request->variation_id2 ?? null,
            'quantity' => $request->quantity,
            'brand_name' => $request->brand_name,
            'model_name' => $request->model_name,
            'media_id' => $request->media_id
        ]);

        // if ($request->filled('choice_image')) {

        //     $base64 = $request->choice_image;

        //     preg_match('/^data:image\/(\w+);base64,/', $base64, $type);
        //     $extension = $type[1];

        //     $imageData = base64_decode(substr($base64, strpos($base64, ',') + 1));

        //     $tempPath = storage_path('app/temp_' . Str::uuid() . '.' . $extension);
        //     file_put_contents($tempPath, $imageData);

        //     $cartItem
        //         ->addMedia($tempPath)
        //         ->toMediaCollection('choice_image');
                
        //     if (file_exists($tempPath)) {
        //         unlink($tempPath);
        //     }
        // }

        if ($request->filled('choise_image')) {

            $base64 = preg_replace('/\s+/', '', $request->choise_image);

            if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                return response()->json(['error' => 'Invalid image format'], 422);
            }

            $extension = $type[1];
            $imageData = base64_decode(
                substr($base64, strpos($base64, ',') + 1),
                true
            );

            if ($imageData === false) {
                return response()->json(['error' => 'Decode failed'], 422);
            }

            $cartItem
                ->addMediaFromString($imageData)
                ->usingFileName(Str::uuid() . '.' . $extension)
                ->toMediaCollection('choice_image');
        }


        if ($request->has('images') && is_array($request->images)) {

            foreach ($request->images as $base64) {

                if (!$base64) {
                    continue;
                }

                // Remove spaces/newlines
                $base64 = preg_replace('/\s+/', '', $base64);

                // Validate Base64 image
                if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                    continue; // invalid image
                }

                $extension = $type[1] ?? 'png';

                // Decode safely
                $imageData = base64_decode(
                    substr($base64, strpos($base64, ',') + 1),
                    true
                );

                if ($imageData === false) {
                    continue;
                }

                // Save to Spatie media
                $cartItem
                    ->addMediaFromString($imageData)
                    ->usingFileName(Str::uuid() . '.' . $extension)
                    ->toMediaCollection('cart_images');
            }
        }


        return apiResponse(true,$cartItem->product->name . ($variationName ? ' (' . $variationName . ')' : '') . ' added to cart successfully',null,200);
    }

    public function update_cart_quantity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|integer|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $userId = $request->user()->id;

        // Get the cart item
        $cartItem = Cart::where('id', $request->cart_id)
                        ->where('user_id', $userId)
                        ->with(['product', 'variation'])
                        ->first();

        if (!$cartItem) {
            return apiResponse(false, 'Cart item not found.', null, 404);
        }

        $product = $cartItem->product;
        $variation = $cartItem->variation;

        // Determine available stock
        $availableStock = $variation ? $variation->stock : $product->stock;

        if ($request->quantity > $availableStock) {
            return apiResponse(false, 'Only ' . $availableStock . ' left in stock.', null, 200);
        }

        // Update quantity
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // ✅ Use variation price if available, else product total_price
        $price = $variation 
            ? $variation->price 
            : $product->total_price;

        // Cart item subtotal
        $cartItemAmount = $price * $cartItem->quantity;

        // ✅ Calculate total cart amount dynamically
        $cartItems = Cart::where('user_id', $userId)
                        ->with(['product', 'variation'])
                        ->get();

        $totalCartAmount = $cartItems->sum(function ($item) {
            $price = $item->variation 
                ? $item->variation->price 
                : $item->product->total_price;
            return $price * $item->quantity;
        });

        return apiResponse(true, 'Cart updated successfully', [
            'cart_item' => [
                'id' => $cartItem->id,
                'product_name' => $product->name,
                'variation_name' => $variation->variation_name ?? null,
                'quantity' => $cartItem->quantity,
                'price' => $price,
                'cart_item_amount' => $cartItemAmount,
            ],
            'total_cart_amount' => $totalCartAmount,
        ], 200);
    }



    public function cart_items(Request $request)
    {
        $userId = $request->user()->id;

        $cartItems = Cart::with([
                'product:id,name,price,total_price,stock,product_type',
                'variation:id,variation_id,variation_name,price,stock',
                'variation2:id,variation_id,variation_name,price,stock'
            ])
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return apiResponse(false, 'Your cart is empty.', [], 200);
        }

        $data = $cartItems->map(function ($item) {
            // Price preference: variation price → product discount price → product base price
            // 1️⃣ Calculate price (variation + variation2)
            $price = 0;

            if ($item->variation && $item->variation->price) {
                $price += $item->variation->price;
            }

            if ($item->variation2 && $item->variation2->price) {
                $price += $item->variation2->price;
            }

            // 2️⃣ Fallback to product price if no variations
            if ($price == 0) {
                $price = $item->product->total_price;
            }

            return [
                'cart_id'        => $item->id,
                'product_id'     => $item->product_id,
                'product_name'   => $item->product->name,
                'product_type'   => $item->product->product_type,
                'variation_id'   => $item->product_variation_options_id,
                'variation_name' => $item->variation->variation_name ?? null,
                'brand_name'     => $item->brand_name,
                'model_name'     => $item->model_name,
                'quantity'       => $item->quantity,
                'price'          => $price,
                'subtotal'       => $item->quantity * $price,
                'stock'          => $item->variation ? $item->variation->stock : $item->product->stock,
                'product_image'  => $item->product->image_link, // main product image
                'variation_image'=> $item->variation ? $item->variation->image_url : null, // variation image if exists
                // 🖼️ CART IMAGES (Spatie)
                'choice_image'     => $item->getFirstMediaUrl('choice_image'),

                'cart_images'      => $item->getMedia('cart_images')->map(function ($media) {
                                        return $media->getUrl();
                                    }),
            ];
        });

        $total_cart_item = $cartItems->count();
        if($total_cart_item > 2){
            $shipping_charge = 0;
        }else{
            $shipping_charge = 50;
        }

        $totalAmount = $data->sum('subtotal');

        $grand_total = $totalAmount + $shipping_charge;

        return apiResponse(true, 'Cart items fetched successfully.', [
            'items' => $data,
            'total_amount' => $totalAmount,
            'shipping_charge' => $shipping_charge,
            'discount' => 0,
            'grand_total' => $grand_total
        ], 200);
    }

    public function remove_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|integer|exists:carts,id',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $userId = $request->user()->id;

        // Find cart item by ID and user_id to prevent deleting others' cart
        $cartItem = Cart::where('id', $request->cart_id)
                        ->where('user_id', $userId)
                        ->first();

        if (!$cartItem) {
            return apiResponse(false, 'Cart item not found.', null, 404);
        }

        $productName = $cartItem->product ? $cartItem->product->name : 'Product';

        $cartItem->delete();

        return apiResponse(true, $productName . ' removed from cart successfully.', null, 200);
    }

    public function apply_coupon(Request $request, CouponService $couponService)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $userId = $request->user()->id;

        // 1️⃣ Load cart items
        $cartItems = Cart::with([
            'product:id,total_price,product_type',
            'variation:id,price,variation_name'
        ])
        ->where('user_id', $userId)
        ->get();

        if ($cartItems->isEmpty()) {
            return apiResponse(false, 'Cart is empty', null, 200);
        }

        // 2️⃣ Convert cart → coupon items
        $couponItems = $cartItems->map(function ($item) {

            $price = 0;

            if ($item->variation && $item->variation->price) {
                $price = $item->variation->price;
            } else {
                $price = $item->product->total_price;
            }

            return (object)[
                'price' => $price,
                'qty' => $item->quantity,
                'category' => 'phone-case',
                'product_type' => $item->variation
                                ? $item->variation->variation_name
                                : null,
            ];
        });

        // return $couponItems;

        // 3️⃣ Apply coupon
        $result = $couponService->apply($request->coupon_code, $couponItems);

        if (!$result['success']) {
            return apiResponse(false, $result['message'], null, 200);
        }

        // 4️⃣ Calculate totals
        $subtotal = $couponItems->sum(fn($i) => $i->price * $i->qty);

        $discount = $result['discount'] ?? 0;

        // Shipping logic
        $shipping = $result['type'] === 'free_shipping' ? 0 : 50;

        $grandTotal = max(($subtotal + $shipping) - $discount, 0);

        return apiResponse(true, 'Coupon applied successfully', [
            'coupon_code' => $request->coupon_code,
            'coupon_type' => $result['type'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'grand_total' => $grandTotal
        ], 200);
    }
}
