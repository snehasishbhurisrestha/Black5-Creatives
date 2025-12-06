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
use App\Models\City;
use App\Models\AddressBook;

class UserDashboard extends Controller
{
    public function index(){
        return view('site.user_dashboard.dashboard');
    }

    public function user_profile(Request $request){
        $user = Auth::user();
        if($user->hasRole('Super Admin')){
            return redirect()->route('dashboard');
        }
        return view('site.user_dashboard.profile');
    }

    public function update_user_profile(Request $request){
        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->first_name.' '.$request->last_name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        if($user->update()){
            return back()->with('success','Updated Sucessfully');
        }else{
            return back()->with('error','Not Updated, Try Again!');
        }
    }

    public function user_orders(Request $request){
        $orders = Order::orderBy('id','desc')->where('user_id',Auth::id())->get();
        return view('site.user_dashboard.orders',compact('orders'));
    }

    public function user_orders_details(string $id){
        $order = Order::where('id',$id)->where('user_id',Auth::id())->first();
        if($order){
            $address_book = AddressBook::find($order->address_book_id);
            $order_items = $order->items;
            return view('site.user_dashboard.order_details',compact('order','address_book','order_items'));
        }else{
            return back()->with('error','Not found any order');
        }
    }

    public function user_address(Request $request){
        $addresses = AddressBook::where('user_id',Auth::id())->get();
        $countrys = Country::where('is_visible',1)->get();
        return view('site.user_dashboard.address',compact('addresses','countrys'));
    }

    public function user_saveaddress(Request $request){
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
        $address->is_default = 0;
        if($address->save()){
            return back()->with('success','Address Saved Successfully');
        }else{
            return back()->with('error','Address not saved');
        }
    }

    public function edit_user_address(string $id){
        $address = AddressBook::where('user_id',Auth::id())->where('id',$id)->first();
        if($address){
            $countrys = Country::where('is_visible',1)->get();
            $states = State::where('country_id',$address->billing_country)->get();
            $citys = City::where('state_id',$address->billing_state)->get();
            return view('site.user_dashboard.edit_address',compact('address','countrys','states','citys'));
        }else{
            return back()->with('error','Not Found any Address');
        }
    }

    public function user_update_address(Request $request){
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

        $address = AddressBook::findOrFail($request->address_id);
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
        if($address->update()){
            return back()->with('success','Address Updated Successfully');
        }else{
            return back()->with('error','Address not updated');
        }
    }

    public function delete_address(string $id){
        $AddressBook = AddressBook::findOrFail($id);
        if($AddressBook){
            $AddressBook->delete();
            return back()->with('success','Address Deleted Successfully');
        }else{
            return back()->with('error','Not Found any Address');
        }
    }
}