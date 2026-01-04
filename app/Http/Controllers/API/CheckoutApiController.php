<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AddressBook;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\ProductVariationOption;
use App\Models\Product;
use Razorpay\Api\Api;

class CheckoutApiController extends Controller
{
    public function get_saved_address(Request $request)
    {
        $userId = $request->user()->id;

        $addresses = AddressBook::with(['country', 'state', 'city'])
            ->where('user_id', $userId)
            ->get();

        return apiResponse(true, 'User Saved Address.', $addresses, 200);
    }

    public function add_new_addresss_book(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name'  => 'nullable|string|max:255',
            'billing_email'      => 'nullable|email',
            'billing_phone_number' => 'nullable|string|max:20',
            'billing_address'    => 'nullable|string',
            'billing_country'    => 'nullable',
            'billing_state'      => 'nullable',
            'billing_city'       => 'nullable',
            'billing_zip_code'   => 'nullable|string|max:20',
            'shipping_first_name' => 'nullable|string|max:255',
            'shipping_last_name'  => 'nullable|string|max:255',
            'shipping_email'      => 'nullable|email',
            'shipping_phone_number' => 'nullable|string|max:20',
            'shipping_address'   => 'nullable|string',
            'shipping_country'   => 'nullable',
            'shipping_state'     => 'nullable',
            'shipping_city'      => 'nullable',
            'shipping_zip_code'  => 'nullable|string|max:20',
            'is_default'         => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $address = AddressBook::create([
            'user_id' => $request->user()->id, // take logged-in user
            ...$request->all()
        ]);

        return apiResponse(true, 'Address saved successfully', $address, 200);
    }

    public function createRazorpayOrder(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'amount' => 'required|numeric|min:1', // amount in INR
        //     'currency' => 'nullable|string|in:INR'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 422);
        // }

        $cart_total = calculate_cart_total($request->user()->id);

        $cart_count = cart_item_count($request->user()->id);
        if($cart_count > 2){
            $shipping_charge = 0;
        }else{
            $shipping_charge = 50;
        }

        $cart_total += $shipping_charge;

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $orderData = [
            'receipt'         => 'rcptid_' . uniqid(),
            'amount'          => $cart_total * 100, // Razorpay works in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        try {
            $razorpayOrder = $api->order->create($orderData);

            $data = [
                'order_id' => $razorpayOrder['id'],
                'amount' => $razorpayOrder['amount'],
                'currency' => $razorpayOrder['currency'],
                'receipt' => $razorpayOrder['receipt'],
                'key' => env('RAZORPAY_KEY') // frontend will need this
            ];

            return apiResponse(true, 'Razorpay Order Created Successfully.', $data, 200);
        } catch (\Exception $e) {
            return apiResponse(false, 'Validation error.', ['error' => $e->getMessage()], 500);
        }
    }

    public function placeOrderWithRazorpay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            // ✅ Step 1: Verify signature
            $generatedSignature = hash_hmac(
                'sha256',
                $request->razorpay_order_id . "|" . $request->razorpay_payment_id,
                env('RAZORPAY_SECRET')
            );

            if ($generatedSignature !== $request->razorpay_signature) {
                return apiResponse(false, 'Invalid payment signature', null, 400);
            }

            // ✅ Step 2: Fetch payment details
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if ($payment['status'] !== 'captured') {
                return apiResponse(false, 'Payment not captured yet', ['status' => $payment['status']], 400);
            }

            // ✅ Step 3: Call place_order()
            $order = $this->place_order('Razorpay', $request->razorpay_payment_id, $request->user()->id);

            return apiResponse(true, 'Order placed successfully', ['order' => $order], 200);
        } catch (\Exception $e) {
            return apiResponse(false, 'Payment verification failed', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Place Order Function (adapted for API)
     */
    protected function place_order(string $payment_method = null, $razorpay_payment_id = null, $user_id)
    {
        $cart_items = Cart::where('user_id', $user_id)->get();
        if (!$cart_items->count()) {
            return null;
        }

        $cart_sub_total = calculate_cart_sub_total_by_userId($user_id);
        $cart_total = calculate_cart_total($user_id);
        $cart_count = cart_item_count($user_id);
        if($cart_count > 2){
            $shipping_charge = 0;
        }else{
            $shipping_charge = 50;
        }
        $cart_total += $shipping_charge;

        $coupone_discount = 0.00;
        $coupone_code = null;

        // if (session()->has('applied_coupon')) {
        //     $coupone_discount = session('applied_coupon.discount');
        //     $coupone_code = session('applied_coupon.code');
        // }

        $order = new Order();
        $order->order_number = generateOrderNumber();
        $order->user_id = $user_id;
        $order->address_book_id = AddressBook::where('user_id', $user_id)->where('is_default', 1)->value('id');
        $order->coupone_code = $coupone_code;
        $order->coupone_discount = $coupone_discount;
        $order->price_subtotal = $cart_sub_total;
        $order->price_gst = 0.00;
        $order->price_shipping = $shipping_charge;
        $order->total_amount = $cart_total;
        $order->discounted_price = ($cart_sub_total - $cart_total) + $shipping_charge;
        $order->payment_method = $payment_method ?? '';
        $order->payment_status = $payment_method === 'Razorpay' ? 'success' : 'pending';
        $order->transaction_id = $razorpay_payment_id;

        if ($razorpay_payment_id) {
            $order->payment_date = now();
        }
        $order->save();

        // ✅ Track Used Coupon
        // if ($coupone_code) {
        //     $coupone = Coupon::where('code', $coupone_code)->first();
        //     if ($coupone) {
        //         UsedCoupon::create([
        //             'user_id' => Auth::id(),
        //             'coupon_id' => $coupone->id
        //         ]);
        //     }
        // }

        update_order_number($order->id, $order->order_number);

        // ✅ Save Order Items & Deduct Stock
        foreach ($cart_items as $cart_item) {
            $order_item = new OrderItems();
            $order_item->order_id = $order->id;
            $order_item->product_id = $cart_item->product_id;
            $order_item->product_variation_options_id = $cart_item->product_variation_options_id;
            $order_item->product_name = $cart_item->product->name;
            $order_item->brand_name = $cart_item->brand_name;
            $order_item->model_name = $cart_item->model_name;

            if ($cart_item->productVariationOption) {
                $order_item->product_name .= ' (' . $cart_item->productVariationOption->variation_name . ')';
            }

            $order_item->quantity = $cart_item->quantity;
            if ($cart_item->product_variation_options_id) {
                $order_item->price = $cart_item->productVariationOption->price;
                $order_item->subtotal = $cart_item->productVariationOption->price * $cart_item->quantity;
                // ProductVariationOption::where('id', $cart_item->product_variation_options_id)
                //     ->decrement('stock', $cart_item->quantity);
            } else {
                $order_item->price = $cart_item->product->price;
                $order_item->subtotal = $cart_item->product->price * $cart_item->quantity;
                // Product::where('id', $cart_item->product_id)
                //     ->decrement('stock', $cart_item->quantity);
            }

            $order_item->save();
        }

        // ✅ Clear cart after placing order
        Cart::where('user_id', $user_id)->delete();
        // session()->forget('applied_coupon');

        return $order;
    }
}
