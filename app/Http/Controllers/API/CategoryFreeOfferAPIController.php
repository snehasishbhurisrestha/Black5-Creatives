<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CategoryFreeOffer;
use App\Models\Order;
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

            $orderCount = Order::where('user_id', $userId)
                ->where('order_status', 'delivered')
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
}
