<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\User;
use App\Models\AddressBook;

class UserApiController extends Controller
{
    // ✅ Get Profile
    public function getProfile(Request $request)
    {
        $user = $request->user();
        return apiResponse(true, 'User Profile', $user, 200);
    }

    // ✅ Update Profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $user = User::find($user->id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'nullable|digits:10|regex:/^[6789]/',
            'address'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation Error', $validator->errors(), 422);
        }

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->name       = $request->first_name . ' ' . $request->last_name;
        $user->phone      = $request->phone;
        $user->address    = $request->address;
        $user->save();

        return apiResponse(true, 'Profile Updated Successfully', $user, 200);
    }

    // ✅ Get All Orders
    public function getOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
                       ->orderBy('id', 'desc')
                       ->get();

        return apiResponse(true, 'User Orders', $orders, 200);
    }

    // ✅ Get Order Details
    public function getOrderDetails(Request $request, $id)
    {
        $order = Order::where('id', $id)
                      ->where('user_id', $request->user()->id)
                      ->first();

        if (!$order) {
            return apiResponse(false, 'Order not found', null, 404);
        }

        $address = AddressBook::find($order->address_book_id);
        $items   = $order->items;

        return apiResponse(true, 'Order Details', [
            'order'   => $order,
            'address' => $address,
            'items'   => $items,
        ], 200);
    }

    // ✅ Get All Addresses
    public function getAddresses(Request $request)
    {
        $addresses = AddressBook::where('user_id', $request->user()->id)->get();
        return apiResponse(true, 'User Addresses', $addresses, 200);
    }

    // ✅ Add Address
    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'required|digits:10|regex:/^[6789]/',
            'country'    => 'required|exists:countries,id',
            'state'      => 'required|exists:states,id',
            'city'       => 'nullable|exists:cities,id',
            'pincode'    => 'required|digits:6',
            'address'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation Error', $validator->errors(), 422);
        }

        $address = new AddressBook();
        $address->user_id = $request->user()->id;
        $address->billing_first_name = $address->shipping_first_name = $request->first_name;
        $address->billing_last_name  = $address->shipping_last_name  = $request->last_name;
        $address->billing_email      = $address->shipping_email      = $request->email;
        $address->billing_phone_number = $address->shipping_phone_number = $request->phone;
        $address->billing_address    = $address->shipping_address    = $request->address;
        $address->billing_country    = $address->shipping_country    = $request->country;
        $address->billing_state      = $address->shipping_state      = $request->state;
        $address->billing_city       = $address->shipping_city       = $request->city;
        $address->billing_zip_code   = $address->shipping_zip_code   = $request->pincode;
        $address->is_default         = 0;
        $address->save();

        return apiResponse(true, 'Address Added Successfully', $address, 200);
    }

    // ✅ Update Address
    public function updateAddress(Request $request, $id)
    {
        $address = AddressBook::where('user_id', $request->user()->id)->find($id);

        if (!$address) {
            return apiResponse(false, 'Address not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'required|digits:10|regex:/^[6789]/',
            'country'    => 'required|exists:countries,id',
            'state'      => 'required|exists:states,id',
            'city'       => 'nullable|exists:cities,id',
            'pincode'    => 'required|digits:6',
            'address'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation Error', $validator->errors(), 422);
        }

        $address->billing_first_name = $address->shipping_first_name = $request->first_name;
        $address->billing_last_name  = $address->shipping_last_name  = $request->last_name;
        $address->billing_email      = $address->shipping_email      = $request->email;
        $address->billing_phone_number = $address->shipping_phone_number = $request->phone;
        $address->billing_address    = $address->shipping_address    = $request->address;
        $address->billing_country    = $address->shipping_country    = $request->country;
        $address->billing_state      = $address->shipping_state      = $request->state;
        $address->billing_city       = $address->shipping_city       = $request->city;
        $address->billing_zip_code   = $address->shipping_zip_code   = $request->pincode;
        $address->save();

        return apiResponse(true, 'Address Updated Successfully', $address, 200);
    }

    // ✅ Delete Address
    public function deleteAddress(Request $request, $id)
    {
        $address = AddressBook::where('user_id', $request->user()->id)->find($id);

        if (!$address) {
            return apiResponse(false, 'Address not found', null, 404);
        }

        $address->delete();

        return apiResponse(true, 'Address Deleted Successfully', null, 200);
    }
}
