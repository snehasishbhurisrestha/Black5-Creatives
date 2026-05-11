<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\AppliedCoupon;
use App\Models\UsedCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class SilverCardApiController extends Controller
{
    public function __construct() {
        $this->phone_case_id = env('PHONE_CASE_ID');
        $this->wall_art_id = env('WALL_ART_ID');
    }

    /*public function index(Request $request){
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
    }*/

    /*public function index(Request $request)
    {
        $now = Carbon::now();

        $user = $request->user(); // change guard if needed

        // Get type (optional)
        $type = $request->query('type');

        // Base query
        $query = Coupon::where('is_active', 1)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')
                ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $now);
            });

        // 🔹 If type is provided → filter
        if ($type) {

            // Validate
            if (!in_array($type, ['phone-case', 'wall-art'])) {
                return apiResponse(false, 'Invalid category type', [], 400);
            }

            // Get main category
            $mainCategory = Category::where('slug', $type)->first();

            if (!$mainCategory) {
                return apiResponse(false, 'Main category not found', [], 404);
            }

            // Get all sub category slugs
            $allCategorySlugs = $this->getAllChildSlugs($mainCategory);

            // Add main slug
            $allCategorySlugs[] = $mainCategory->slug;

            // Apply filter
            $query->whereIn('category', $allCategorySlugs);
        }

        // 🔹 If logged in → get applied & used coupon IDs
        $appliedCoupons = [];
        $usedCoupons    = [];

        if ($user) {

            $appliedCoupons = AppliedCoupon::where('user_id', $user->id)
                                ->pluck('coupon_id')
                                ->toArray();

            $usedCoupons = UsedCoupon::where('user_id', $user->id)
                                ->pluck('coupon_id')
                                ->toArray();
        }

        // 🔹 Execute query
        $coupons = $query->get()->map(function ($coupon) {
            return [
                'id'          => $coupon->id,
                'code'        => $coupon->code,
                'type'        => $coupon->type,
                'value'       => $coupon->value,
                'description' => $coupon->description,
                'start_date'  => $coupon->start_date,
                'end_date'    => $coupon->end_date,
                'image'       => $coupon->getFirstMediaUrl('coupon_image'),

                'is_applied'  => in_array($coupon->id, $appliedCoupons) ? 1 : 0,
                'is_used'     => in_array($coupon->id, $usedCoupons) ? 1 : 0,
            ];
        });

        return apiResponse(true, 'Silver Card fetched successfully', $coupons, 200);
    }*/

    public function index(Request $request)
    {
        $now = Carbon::now();

        $user = $request->user(); // change guard if needed

        // Get type (optional)
        $type = $request->query('type');

        // Base query
        $query = Coupon::where('is_active', 1)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')
                ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $now);
            });

        // 🔹 If type is provided → filter
        if ($type) {

            // Validate
            if (!in_array($type, ['phone-case', 'wall-art'])) {
                return apiResponse(false, 'Invalid category type', [], 400);
            }

            // Get main category
            $mainCategory = Category::where('slug', $type)->first();

            if (!$mainCategory) {
                return apiResponse(false, 'Main category not found', [], 404);
            }

            // Get all sub category slugs
            $allCategorySlugs = $this->getAllChildSlugs($mainCategory);

            // Add main slug
            $allCategorySlugs[] = $mainCategory->slug;

            // Apply filter
            $query->whereIn('category', $allCategorySlugs);
        }

        // 🔹 If logged in → get applied & used coupon IDs
        $appliedCoupons = [];
        $usedCoupons    = [];

        if ($user) {

            $appliedCoupons = AppliedCoupon::where('user_id', $user->id)
                                ->pluck('coupon_id')
                                ->toArray();

            $usedCoupons = UsedCoupon::where('user_id', $user->id)
                                ->pluck('coupon_id')
                                ->toArray();
        }

        // 🔹 Execute query
        $coupons = $query->get()->map(function ($coupon) use ($appliedCoupons, $usedCoupons) {
            return [
                'id'          => $coupon->id,
                'code'        => $coupon->code,
                'type'        => $coupon->type,
                'value'       => $coupon->value,
                'description' => $coupon->description,
                'start_date'  => $coupon->start_date,
                'end_date'    => $coupon->end_date,
                'image'       => $coupon->getFirstMediaUrl('coupon_image'),

                'is_applied'  => in_array($coupon->id, $appliedCoupons) ? 1 : 0,
                'is_used'     => in_array($coupon->id, $usedCoupons) ? 1 : 0,
            ];
        });

        return apiResponse(true, 'Silver Card fetched successfully', $coupons, 200);
    }

    private function getAllChildSlugs($category)
    {
        $slugs = [];

        $children = Category::where('parent_id', $category->id)->get();

        foreach ($children as $child) {

            $slugs[] = $child->slug;

            $slugs = array_merge(
                $slugs,
                $this->getAllChildSlugs($child)
            );
        }

        return $slugs;
    }
}