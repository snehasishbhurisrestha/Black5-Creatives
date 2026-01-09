<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductVariationOption;

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
        $categorys = Category::all();
        $product_options = ProductVariationOption::select('variation_name')
                            ->distinct()
                            ->get();
        return view('admin.coupons.create',compact('categorys','product_options'));
    }

    public function store(Request $request)
    {
        $rules = [
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:percentage,flat,free_shipping,bogo,price_override',

            'minimum_purchase' => 'nullable|numeric|min:0',
            'min_qty' => 'nullable|integer|min:1',

            'category' => 'nullable|string',
            'product_type' => 'nullable|string',

            'usage_type' => 'required|in:one-time,multiple',
            'is_active' => 'nullable|boolean',

            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',

            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        // Required when percentage or flat
        if (in_array($request->type, ['percentage', 'flat'])) {
            $rules['value'] = 'required|numeric|min:0';
        }

        // Required when price override
        if ($request->type === 'price_override') {
            $rules['override_price'] = 'required|numeric|min:0';
        }

        // Required when BOGO
        if ($request->type === 'bogo') {
            $rules['buy_qty'] = 'required|integer|min:1';
            $rules['get_qty'] = 'required|integer|min:1';
            $rules['free_product_type'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        // Default active flag
        $validated['is_active'] = $request->is_active ?? 0;

        // Create Coupon First
        $coupon = Coupon::create($validated);

        // Add Media (Spatie)
        if ($request->hasFile('image')) {
            $coupon->addMedia($request->file('image'))
                ->toMediaCollection('coupon_image');
        }

        return redirect()
            ->route('coupon.index')
            ->with('success', 'Coupon created successfully!');
    }



    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $categorys = Category::all();
        $product_options = ProductVariationOption::select('variation_name')
                            ->distinct()
                            ->get();

        return view('admin.coupons.edit', compact('coupon','categorys','product_options'));
    }


    public function update(Request $request, string $id)
    {
        $coupon = Coupon::findOrFail($id);

        $rules = [
            'code' => 'required|unique:coupons,code,' . $id,
            'type' => 'required|in:percentage,flat,free_shipping,bogo,price_override',

            'minimum_purchase' => 'nullable|numeric|min:0',
            'min_qty' => 'nullable|integer|min:1',

            'category' => 'nullable|string',
            'product_type' => 'nullable|string',

            'usage_type' => 'required|in:one-time,multiple',
            'is_active' => 'nullable|boolean',

            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',

            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        // Required when percentage or flat
        if (in_array($request->type, ['percentage', 'flat'])) {
            $rules['value'] = 'required|numeric|min:0';
        }

        // Required when price override
        if ($request->type === 'price_override') {
            $rules['override_price'] = 'required|numeric|min:0';
        }

        // Required when BOGO
        if ($request->type === 'bogo') {
            $rules['buy_qty'] = 'required|integer|min:1';
            $rules['get_qty'] = 'required|integer|min:1';
            $rules['free_product_type'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        // Default active flag
        $validated['is_active'] = $request->is_active ?? 0;

        // Update coupon
        $coupon->update($validated);

        // Update Image (Spatie)
        if ($request->hasFile('image')) {
            $coupon->clearMediaCollection('coupon_image');
            $coupon->addMedia($request->file('image'))
                ->toMediaCollection('coupon_image');
        }

        return redirect()
            ->route('coupon.index')
            ->with('success', 'Coupon updated successfully!');
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
