<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SilverCardApiController extends Controller
{
    public function index(){
        $now = Carbon::now();

        $all_active_coupon = Coupon::where('is_active', 1)
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')
                ->orWhere('start_date', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $now);
            })
            ->get()
            ->map(function ($coupon) {
                return [
                    'code'         => $coupon->code,
                    'type'         => $coupon->type,
                    'description'  => $coupon->description,
                    'start_date'   => $coupon->start_date,
                    'end_date'     => $coupon->end_date,
                    'image'        => $coupon->getFirstMediaUrl('coupon_image'),
                ];
            });

        return apiResponse(true, 'Silver Card fetched successfully', $all_active_coupon, 200);
    }
}