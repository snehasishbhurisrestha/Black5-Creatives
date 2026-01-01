<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponService
{
    public function apply($code, $cartItems)
    {
        $coupon = Coupon::where('code', $code)->first();

        if(!$coupon){
            return $this->error("Invalid coupon");
        }

        if(!$coupon->is_active){
            return $this->error("Coupon is inactive");
        }

        if($coupon->start_date && now()->lt($coupon->start_date)){
            return $this->error("Coupon not started yet");
        }

        if($coupon->end_date && now()->gt($coupon->end_date)){
            return $this->error("Coupon expired");
        }

        // Filter eligible items based on category/product type
        $eligibleItems = $this->filterEligible($cartItems, $coupon);

        if($eligibleItems->count() == 0){
            return $this->error("Coupon not applicable on these products");
        }

        // Required min qty check
        if($coupon->min_qty && $eligibleItems->sum('qty') < $coupon->min_qty){
            return $this->error("Minimum {$coupon->min_qty} items required");
        }

        // Required min purchase check
        $subtotal = $eligibleItems->sum(fn($i)=> $i->price * $i->qty);

        if($coupon->minimum_purchase && $subtotal < $coupon->minimum_purchase){
            return $this->error("Minimum purchase ₹{$coupon->minimum_purchase} required");
        }

        // Apply discount based on type
        return $this->applyDiscount($coupon, $eligibleItems, $cartItems);
    }


    private function filterEligible($cartItems, $coupon)
    {
        return $cartItems->filter(function($item) use ($coupon){

            $type = $item->product_type;     // hybrid, soft, hard, frame_premium etc
            $category = $item->category;     // case, wall_art

            if($coupon->category && $coupon->category != $category){
                return false;
            }

            if($coupon->product_type && $coupon->product_type != $type){
                return false;
            }

            return true;
        });
    }


    private function applyDiscount($coupon, $eligibleItems, $cartItems)
    {
        $discount = 0;

        switch ($coupon->type) {

            case 'percentage':
                $discount = $eligibleItems->sum(fn($i)=> ($i->price * $i->qty));
                $discount = ($discount * $coupon->value) / 100;
                break;

            case 'flat':
                $discount = $coupon->value;
                break;

            case 'free_shipping':
                return [
                    'success' => true,
                    'type' => 'free_shipping',
                    'discount' => 0
                ];

            case 'price_override':
                foreach($eligibleItems as $item){
                    $discount += ($item->price - $coupon->override_price) * $item->qty;
                }
                break;

            case 'bogo':
                $discount = $this->applyBogo($coupon, $eligibleItems);
                break;
        }

        return [
            'success' => true,
            'type' => $coupon->type,
            'discount' => max($discount,0)
        ];
    }


    private function applyBogo($coupon, $items)
    {
        $discount = 0;

        foreach($items as $item){

            $freeUnits = intdiv($item->qty, $coupon->buy_qty) * $coupon->get_qty;

            $discount += $freeUnits * $item->price;
        }

        return $discount;
    }


    private function error($msg)
    {
        return [
            'success' => false,
            'message' => $msg
        ];
    }
}
