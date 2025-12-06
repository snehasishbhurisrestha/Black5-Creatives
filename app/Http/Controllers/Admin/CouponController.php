<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Coupon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CouponController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Coupon Show', only: ['index']),
            new Middleware('permission:Coupon Create', only: ['create','store']),
            new Middleware('permission:Coupon Edit', only: ['edit','update']),
            new Middleware('permission:Coupon Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $coupons = Coupon::all();
        return view('admin.coupons.index',compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:percentage,flat',
            'value' => 'required|numeric|min:0',
            'minimum_purchase' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_type' => 'required|in:one-time,multiple',
            'is_active' => 'nullable|boolean',
        ]);

        Coupon::create($validated);

        return redirect()->back()->with('success', 'Coupon created successfully!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit',compact('coupon'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'code' => 'required|unique:coupons,code,' . $id,
            'type' => 'required|in:percentage,flat',
            'value' => 'required|numeric|min:0',
            'minimum_purchase' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_type' => 'required|in:one-time,multiple',
            'is_active' => 'nullable|boolean',
        ]);

        // Find the coupon by ID
        $coupon = Coupon::findOrFail($id);

        // Update the coupon with validated data
        $coupon->update($validated);

        return redirect()->back()->with('success', 'Coupon Updated successfully!');
    }

    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        if($coupon){
            $res = $coupon->delete();
            if($res){
                return back()->with('success','Deleted Successfully');
            }else{
                return back()->with('error','Not Deleted');
            }
        }else{
            return back()->with('error','Not Found');
        }
    }
}
