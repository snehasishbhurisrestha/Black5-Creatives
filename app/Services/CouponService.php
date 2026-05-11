<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponService
{
    public function apply($code, $items)
    {
        // 1️⃣ Find coupon
        $coupon = Coupon::where('code', $code)
                        ->where('is_active', 1)
                        ->first();

        if (!$coupon) {
            return $this->error('Invalid coupon code');
        }

        // 2️⃣ Expiry check
        if ($coupon->expiry_date && Carbon::now()->gt($coupon->expiry_date)) {
            return $this->error('Coupon has expired');
        }

        // 3️⃣ Usage limit check
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return $this->error('Coupon usage limit reached');
        }

        // 4️⃣ Calculate subtotal
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item->price * $item->qty;
        }

        // 5️⃣ Minimum cart value
        if ($coupon->min_cart_value && $subtotal < $coupon->min_cart_value) {
            return $this->error(
                "Minimum order ₹{$coupon->min_cart_value} required"
            );
        }

        // 6️⃣ Filter eligible items
        $eligibleItems = $this->filterEligibleItems($coupon, $items);

        if (count($eligibleItems) == 0 && $coupon->type !== 'free_shipping') {
            return $this->error('Coupon not applicable to these products');
        }

        // 7️⃣ Calculate discount
        $discount = $this->calculateDiscount(
            $coupon,
            $eligibleItems,
            $subtotal
        );

        // 8️⃣ Max discount limit
        if ($coupon->max_discount && $discount > $coupon->max_discount) {
            $discount = $coupon->max_discount;
        }

        return [
            'success'  => true,
            'type'     => $coupon->type,
            'discount' => round($discount, 2),
            'message'  => 'Coupon applied successfully'
        ];
    }

    // ======================================
    // Filter Items Based on Coupon Rules
    // ======================================

    private function filterEligibleItems($coupon, $items)
    {
        $eligible = [];

        // Coupon categories
        $couponCategories = [];

        if ($coupon->categories) {
            $couponCategories = array_map(
                'trim',
                explode(',', $coupon->categories)
            );
        }

        // Coupon product types
        $couponTypes = [];

        if ($coupon->product_types) {
            $couponTypes = array_map(
                'trim',
                explode(',', $coupon->product_types)
            );
        }

        foreach ($items as $item) {

            $matchCategory = true;
            $matchType     = true;

            // Category check
            if (!empty($couponCategories)) {

                $matchCategory = !empty(
                    array_intersect(
                        $couponCategories,
                        $item->categories ?? []
                    )
                );
            }

            // Product type check
            if (!empty($couponTypes)) {

                $matchType = in_array(
                    $item->product_type,
                    $couponTypes
                );
            }

            if ($matchCategory && $matchType) {
                $eligible[] = $item;
            }
        }

        return $eligible;
    }

    // ======================================
    // Discount Calculation
    // ======================================

    private function calculateDiscount($coupon, $items, $subtotal)
    {
        $discount = 0;

        switch ($coupon->type) {

            // Percentage Discount
            case 'percentage':

                $eligibleTotal = 0;

                foreach ($items as $item) {
                    $eligibleTotal += $item->price * $item->qty;
                }

                $discount = ($eligibleTotal * $coupon->value) / 100;

                break;

            // Flat Discount
            case 'flat':

                $discount = $coupon->value;

                break;

            // Free Shipping
            case 'free_shipping':

                $discount = 0;

                break;
        }

        return $discount;
    }

    // ======================================
    // Error Response
    // ======================================

    private function error($message)
    {
        return [
            'success'  => false,
            'message'  => $message,
            'discount' => 0
        ];
    }
}
