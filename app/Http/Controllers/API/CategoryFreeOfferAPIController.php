<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CategoryFreeOffer;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;

class CategoryFreeOfferAPIController extends Controller
{
    public function getOffer(Request $request, $category_id)
    {
        $offer = CategoryFreeOffer::where('category_id', $category_id)
                    ->where('is_active', 1)
                    ->first();

        if (!$offer) {
            return apiResponse(false, 'No active offer found', [], 404);
        }

        // 🔹 Default (Guest user)
        $data = [
            'type' => 'offer',
            'image' => $offer->getFirstMediaUrl('offer_image'),
            'required_qty' => $offer->required_qty
        ];

        // 🔹 Logged-in user (Bearer token)
        if ($request->user()) {

            $userId = $request->user()->id;

            // $orderCount = Order::where('user_id', $userId)
            //     ->where('order_status', 'delivered')
            //     ->count();
            $orderCount = OrderItems::whereHas('order', function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                    ->where('order_status', 'delivered');
                })
                ->whereHas('product.categories', function ($q) use ($category_id) {
                    $q->where('category_id', $category_id);
                })
                ->count();

            // ✅ Completed all required orders
            if ($orderCount >= $offer->required_qty) {
                return apiResponse(true, 'Success image', [
                    'type' => 'success',
                    'image' => $offer->getFirstMediaUrl('success_image'),
                    'completed_orders' => $orderCount
                ], 200);
            }

            // ✅ Partial progress → step image
            if ($orderCount > 0) {

                $stepMedia = $offer->getMedia('step_images')
                    ->firstWhere('custom_properties.step', $orderCount);

                return apiResponse(true, 'Step image', [
                    'type' => 'step',
                    'step' => $orderCount,
                    'image' => $stepMedia ? $stepMedia->getUrl() : null,
                    'completed_orders' => $orderCount
                ], 200);
            }

            // ✅ Logged in but no orders
            return apiResponse(true, 'Offer image', [
                'type' => 'offer',
                'image' => $offer->getFirstMediaUrl('offer_image'),
                'completed_orders' => 0
            ], 200);
        }

        // 🔹 Guest response
        return apiResponse(true, 'Offer image', $data, 200);
    }


    public function platinum_card_details(Request $request, $category_id)
    {
        $offer = CategoryFreeOffer::where('category_id', $category_id)
            ->where('is_active', 1)
            ->first();

        if (!$offer) {
            return apiResponse(false, 'No active offer found', [], 404);
        }

        $userOrderCount = 0;

        // 🔹 Logged-in user order count (category-wise order items)
        if ($request->user()) {
            $userId = $request->user()->id;

            $userOrderCount = OrderItems::whereHas('order', function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                    ->where('order_status', 'delivered');
                })
                ->whereHas('product.categories', function ($q) use ($category_id) {
                    $q->where('category_id', $category_id);
                })
                ->count();
        }

        $steps = [];

        /**
         * 🔹 STEP 0 → OFFER IMAGE
         */
        $steps[] = [
            'step'   => 0,
            'type'   => 'offer',
            'image'  => $offer->getFirstMediaUrl('offer_image'),
            'status' => $userOrderCount >= 1 ? 'unlocked' : 'unlocked'
        ];

        /**
         * 🔹 STEP IMAGES (1...N)
         */
        $stepImages = $offer->getMedia('step_images')
            ->sortBy(fn ($media) => (int) ($media->custom_properties['step'] ?? 0));

        foreach ($stepImages as $media) {

            $stepNo = (int) ($media->custom_properties['step'] ?? 0);

            if ($stepNo <= 0) continue;

            if ($userOrderCount > $stepNo) {
                $status = 'unlocked';
            } elseif ($userOrderCount == $stepNo) {
                $status = 'unlocked';
            } else {
                $status = 'locked';
            }

            $steps[] = [
                'step'   => $stepNo,
                'type'   => 'step',
                'image'  => $media->getUrl(),
                'status' => $status
            ];
        }

        /**
         * 🔹 FINAL SUCCESS IMAGE
         */
        $steps[] = [
            'step'   => $offer->required_qty,
            'type'   => 'success',
            'image'  => $offer->getFirstMediaUrl('success_image'),
            'status' => $userOrderCount >= $offer->required_qty ? 'unlocked' : 'locked'
        ];

        return apiResponse(true, 'Platinum card details', [
            'completed_orders' => $userOrderCount,
            'required_qty'     => $offer->required_qty,
            'steps'            => $steps
        ], 200);
    }

}
