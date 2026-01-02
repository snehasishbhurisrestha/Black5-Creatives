<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RecentlyViewedProduct;
use App\Models\ProductReview;

class CustomiseApiController extends Controller
{
    public function __construct() {
        $this->phone_case_id = env('PHONE_CASE_ID');
        $this->wall_art_id = env('WALL_ART_ID');
    }

    // 🔹 All products with filters + pagination
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'brands', 'variations.options']);

        if ($request->has('type') && $request->type) {
            $type = $request->type;
            if($type == 'phonecase'){
                $query->whereHas('categories', function($q) {
                    $q->where('categories.id', $this->phone_case_id);
                });
            }
            if($type == 'wallart'){
                $query->whereHas('categories', function($q) {
                    $q->where('categories.id', $this->wall_art_id);
                });
            }
        }

        // Filter active only
        $query->where('is_visible', 1);
        $query->where('product_type', 'customise');

        $products = $query->get();

        return apiResponse(true, 'Customise Products', ['products' => $products], 200);
    }
}
