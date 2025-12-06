<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\City;
use App\Models\Coupon;
use App\Models\AddressBook;
use App\Models\UsedCoupon;
use App\Models\Product;
use App\Models\ProductVariationOption;

use Razorpay\Api\Api;

class Checkout extends Controller
{
    public function index(){
        $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
        // $cart_items = Cart::where('user_id', $userId)->get();
        $cart_items = Cart::with('product', 'productVariationOption')
                ->where('user_id', $userId)
                ->get();
        if($cart_items->isNotEmpty()){
            $countrys = Country::where('is_visible',1)->get();
            $address = AddressBook::where('user_id',Auth::id())->get();
            // return view('site.checkout',compact('countrys','address'));
            return view('site.checkout.checkout',compact('countrys','address','cart_items'));
        }else{
            return redirect()->back()->with('error','Your Cart is empty');
        }
    }

    public function process_checkout(Request $request){
        $request->validate([
            'addrradio' => 'required',
        ]);
        
        $userID = Auth::id();
        if($request->addrradio == 'fornewaddr'){
            $this->saveaddress($request);
        }else{
            $address_id = $request->addrradio;
            $this->clear_default_address($userID);
            AddressBook::where('user_id',$userID)->where('id',$address_id)->update(['is_default' => 1]);
        }

        if ($request->payment_method == 'razorpay') {
            // return $this->razorpay_payment($request);
            $amount = calculate_cart_total() * 100; // Convert to paisa
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $order = $api->order->create([
                'receipt' => 'order_' . time(),
                'amount' => $amount,
                'currency' => 'INR',
            ]);

            return response()->json([
                'success' => true,
                'razorpay' => true,
                'order_id' => $order->id,
                'amount' => $amount,
                'key' => env('RAZORPAY_KEY'),
                'user' => Auth::user(),
            ]);
        } elseif ($request->payment_method == 'cod') {
            $this->place_order('COD');
            // return redirect(route('user-profile'))->with('success', 'Order Placed Successfully');
            return response()->json([
                'success' => true,
                'redirect' => route('user-dashboard.orders'),
                'message' => 'Order placed successfully!',
            ]);
        }

        // $this->place_order();

        // return redirect(route('user-profile'))->with('success','Order Placed Successfully');
    }

    public function razorpay_callback(Request $request)
    {
        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if ($payment->status == 'captured') {
                $this->place_order('Razorpay',$request->razorpay_payment_id);
                // return redirect(route('user-profile'))->with('success', 'Payment Successful and Order Placed');
                return response()->json([
                    'success' => true,
                    'redirect' => route('user-dashboard.orders'),
                    'message' => 'Payment successful, and order placed!',
                ]);
            } else {
                // return redirect()->back()->with('error', 'Payment Failed');
                return response()->json(['success' => false, 'message' => 'Payment failed.']);
            }
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Something went wrong. Please try again.');
            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again.']);
        }
    }

    protected function clear_default_address($user_id){
        AddressBook::where('user_id',$user_id)->update(['is_default' => 0]);
    }

    protected function saveaddress(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email',
            'phone' => 'required|digits:10|regex:/^[6789]/',
            'country' => 'required|exists:countries,id',
            'state' => 'required|exists:states,id',
            'city' => 'nullable|exists:cities,id',
            'pincode' => 'required|digits:6',
            'address' => 'nullable|string|max:255',
        ],[
            'pincode.digits' => 'The pincode must be exactly 6 digits.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $this->clear_default_address(Auth::id());

        $address = new AddressBook();
        $address->user_id = Auth::id();
        $address->shipping_first_name = $address->billing_first_name = $request->first_name;
        $address->shipping_last_name = $address->billing_last_name = $request->last_name;
        $address->shipping_email = $address->billing_email = $request->email;
        $address->shipping_phone_number = $address->billing_phone_number = $request->phone;
        $address->shipping_address = $address->billing_address = $request->address;
        $address->shipping_country = $address->billing_country = $request->country;
        $address->shipping_state = $address->billing_state = $request->state;
        $address->shipping_city = $address->billing_city = $request->city;
        $address->shipping_zip_code = $address->billing_zip_code = $request->pincode;
        $address->is_default = 1;
        $address->addl_info = $request->order_note;
        $address->save();
    }

    protected function place_order(string $payment_method=null, $razorpay_payment_id = null){
        $cart_items = Cart::where('user_id', Auth::id())->get();
        if($cart_items){

            $cart_sub_total = calculate_cart_sub_total_by_userId(Auth::id());
            $cart_total = calculate_cart_total();
            $coupone_discount = 0.00;
            $coupone_code = null;

            if (session()->has('applied_coupon')) {
                $coupone_discount = session('applied_coupon.discount');
                $coupone_code = session('applied_coupon.code');
            }

            $order = new Order();
            $order->order_number = generateOrderNumber();
            $order->user_id = Auth::id();
            $order->address_book_id = AddressBook::where('user_id', Auth::id())->where('is_default', 1)->value('id');
            $order->coupone_code = $coupone_code;
            $order->coupone_discount = $coupone_discount;
            $order->price_subtotal = $cart_sub_total;
            $order->price_gst = 0.00;
            $order->price_shipping = 0.00;
            $order->total_amount = calculate_cart_total();
            $order->discounted_price = $cart_sub_total-$order->total_amount;
            $order->payment_method = $payment_method ?? '';
            $order->payment_status = $payment_method == 'Razorpay' ? 'success' : 'pending';
            $order->transaction_id = $razorpay_payment_id;

            if($razorpay_payment_id != null){
                $order->payment_date = now();
            }
            $order->save();

            if($coupone_code != null){
                $coupone = Coupon::where('code',$coupone_code)->first();
                if($coupone){
                    UsedCoupon::create([
                        'user_id' => Auth::id(),
                        'coupon_id' => $coupone->id
                    ]);
                }
            }

            update_order_number($order->id, $order->order_number);

            foreach($cart_items as $cart_item){
                $order_item = new OrderItems();
                $order_item->order_id = $order->id;
                $order_item->product_id = $cart_item->product_id;
                $order_item->product_variation_options_id = $cart_item->product_variation_options_id;
                $order_item->product_name = $cart_item->product->name;
                if ($cart_item->productVariationOption) {
                    $order_item->product_name .= ' (' . $cart_item->productVariationOption->variation_name . ')';
                }
                $order_item->quantity = $cart_item->quantity;
                if(!empty($cart_item->product_variation_options_id)){
                    $order_item->price = $cart_item->productVariationOption->price;
                    $order_item->subtotal = $cart_item->productVariationOption->price * $cart_item->quantity;
                }else{
                    $order_item->price = $cart_item->product->price;
                    $order_item->subtotal = $cart_item->product->price * $cart_item->quantity;
                }
                // $order_item->price = $cart_item->product->total_price;
                $order_item->save();

                // **Stock Deduction Logic**
                if ($cart_item->product_variation_options_id) {
                    // Deduct stock from product variation
                    ProductVariationOption::where('id', $cart_item->product_variation_options_id)
                        ->decrement('stock', $cart_item->quantity);
                } else {
                    // Deduct stock from main product table for simple product
                    Product::where('id', $cart_item->product_id)
                        ->decrement('stock', $cart_item->quantity);
                }
            }

            //clear cart
            $cart_items = Cart::where('user_id', Auth::id())->delete();
            session()->forget('applied_coupon'); 
        }
    }

    public function invoice(string $order_number){
        $order = Order::where('order_number',$order_number)->first();
        if($order){
            $buyer_details = User::find($order->user_id);
            $address_book = AddressBook::find($order->address_book_id);
            $order_items = $order->items;
            return view('site.checkout.invoice',compact('order','buyer_details','order_items','address_book'));
        }
    }
}