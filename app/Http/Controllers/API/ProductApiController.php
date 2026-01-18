<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RecentlyViewedProduct;
use App\Models\ProductReview;

class ProductApiController extends Controller
{
    public function __construct() {
        $this->phone_case_id = env('PHONE_CASE_ID');
        $this->wall_art_id = env('WALL_ART_ID');
    }

    // 🔹 All products with filters + pagination
    public function index(Request $request)
    {
        // $query = Product::with(['categories', 'brands', 'variations.options']);
        $query = Product::with(['categories', 'variations.options']);

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%$search%")
                  ->orWhere('slug', 'LIKE', "%$search%");
        }

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
        $query->whereIn('product_type', ['simple','attribute']);

        $products = $query->paginate(3);

        return apiResponse(true, 'Products', ['products' => $products], 200);
    }

    // 🔹 Products by category
    public function byCategory($slug)
    {
        $products = Product::with(['categories', 'variations.options'])
            ->whereHas('categories', function ($q) use ($slug) {
                $q->where('categories.slug', $slug);
            })
            ->where('is_visible', 1)
            ->whereIn('product_type', ['simple','attribute'])
            ->paginate(6);

        return apiResponse(true, 'Category Products', ['products' => $products], 200);
    }

    // 🔹 Featured products
    public function featured()
    {
        $products = Product::with(['categories', 'brands'])
            ->where('is_featured', 1)
            ->where('is_visible', 1)
            ->whereIn('product_type', ['simple','attribute'])
            ->limit(10)
            ->get();

        return apiResponse(true, 'Featured Products', ['products' => $products], 200);
    }

    // 🔹 Special products
    public function special()
    {
        $products = Product::with(['categories', 'brand'])
            ->where('is_special', 1)
            ->where('is_visible', 1)
            ->whereIn('product_type', ['simple','attribute'])
            ->limit(10)
            ->get();

        return apiResponse(true, 'Special Products', ['products' => $products], 200);
    }

    // 🔹 Best selling products (based on orders count)
    public function bestSelling(Request $request)
    {
        // $products = Product::withCount('orderItems')
        //     ->orderBy('order_items_count', 'desc')
        //     ->where('is_visible', 1)
        //     ->limit(10)
        //     ->get();

        // $products = Product::where('is_visible', 1)
        //     ->where('is_best_selling', 1)
        //     ->limit(10)
        //     ->orderBy('created_at','desc')
        //     ->get();
        // $query = Product::with(['categories', 'brands', 'variations.options']);
        $query = Product::with(['categories', 'variations.options']);

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
        $query->where('is_visible', 1);
        $query->where('is_best_selling', 1);
        $query->whereIn('product_type', ['simple','attribute']);
        $query->limit(10);
        $query->orderBy('created_at','desc');
        $products = $query->get();

        return apiResponse(true, 'Best Selling Products', ['products' => $products], 200);
    }

    // 🔹 Product details
    public function show(Request $request, $slug)
    {
        // $product = Product::with(['categories', 'brands', 'variations.options', 'reviews.user', 'reviews.media'])
        $product = Product::with(['categories', 'variations.options', 'reviews.user', 'reviews.media'])
            // ->findOrFail($id);
            ->where('slug',$slug)->first();

        $categoryIds = $product->categories->pluck('id')->toArray();

        if (in_array($this->phone_case_id, $categoryIds)) {
            $product->case_type = 'phonecase';
        } elseif (in_array($this->wall_art_id, $categoryIds)) {
            $product->case_type = 'wallart';
        }
        // Save recently viewed product
        if ($request->user()) {
            RecentlyViewedProduct::updateOrCreate(
                [
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                ],
                [
                    'updated_at' => now(),
                ]
            );
        }

        // Format reviews (with images and videos)
        $reviews = $product->reviews->map(function ($review) {
            $images = $review->getMedia('review-media')
                ->filter(fn($media) => str_starts_with($media->mime_type, 'image/'))
                ->map(fn($media) => $media->getUrl())
                ->values();

            $videos = $review->getMedia('review-media')
                ->filter(fn($media) => str_starts_with($media->mime_type, 'video/'))
                ->map(fn($media) => $media->getUrl())
                ->values();

            return [
                'id'          => $review->id,
                'user'        => [
                    'id'     => $review->user->id,
                    'name'   => $review->user->name,
                    'avatar' => $review->user->profile_photo_url ?? null,
                ],
                'rating'      => $review->rating,
                'review_text' => $review->review_text,
                'images'      => $images,
                'videos'      => $videos,
                'created_at'  => $review->created_at->format('Y-m-d H:i'),
            ];
        });

        // Calculate review stats
        $averageRating = $product->reviews->avg('rating');
        $reviewCount   = $product->reviews->count();

        // Final response
        return apiResponse(true, 'Product Details', [
            'product'        => $product,
            'reviews'        => $reviews,
            'average_rating' => round($averageRating, 1),
            'review_count'   => $reviewCount,
        ], 200);
    }


    public function recently_viewed_products(Request $request)
    {
        // Get last 10 recently viewed products for authenticated user
        // $recent = RecentlyViewedProduct::with([
        //             'product.categories',
        //             'product.brands',
        //             'product.variations.options'
        //         ])
        //             ->where('user_id', $request->user()->id)
        //             ->latest('updated_at')
        //             ->take(10)
        //             ->get()
        //             ->pluck('product');
        
        // $query = RecentlyViewedProduct::with([
        //         'product.categories',
        //         'product.brands',
        //         'product.variations.options'
        //     ])
        //     ->where('user_id', $request->user()->id)
        //     ->latest('updated_at')
        //     ->take(10);

        // $product_ids = $request->product_ids;
        $products_slugs = $request->product_ids;

        // $query = Product::with(['categories', 'brands', 'variations.options'])
        $query = Product::with(['categories', 'variations.options'])
                        // ->whereIn('id',$product_ids)
                        ->whereIn('slug',$products_slugs)
                        ->take(10);

        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $type = $request->type;

            if ($type === 'phonecase') {
                $query->whereHas('categories', function ($q) {
                    $q->where('categories.id', $this->phone_case_id);
                });
            }

            if ($type === 'wallart') {
                $query->whereHas('categories', function ($q) {
                    $q->where('categories.id', $this->wall_art_id);
                });
            }
        }
        $query->whereIn('product_type', ['simple','attribute']);

        // $recent = $query->get()->pluck('product');
        $recent = $query->get();


        return apiResponse(true, 'Recently viewed products', ['product' => $recent], 200);
    }

    public function related_products($slug)
    {
        // Load the current product with its categories and brand
        // $product = Product::with(['categories', 'brands', 'variations.options'])
        $product = Product::with(['categories', 'variations.options'])
            // ->findOrFail($id);
            ->where('slug',$slug)->first();

        // Get category IDs and brand IDs
        $categoryIds = $product->categories->pluck('id')->toArray();
        $brandIds = $product->brands->pluck('id')->toArray();

        // Fetch related products
        $relatedProducts = Product::with([
                'categories',
                'brands',
                'variations.options'
            ])
            ->where('id', '!=', $product->id) // exclude current product
            ->where(function ($query) use ($categoryIds, $brandIds) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                })
                ->orWhereHas('brands', function ($q) use ($brandIds) {
                    $q->whereIn('brands.id', $brandIds);
                });
            })
            ->distinct()
            ->whereIn('product_type', ['simple','attribute'])
            ->take(10)
            ->get();

        return apiResponse(true, 'Related products', ['product' => $relatedProducts], 200);
    }

    public function search(Request $request)
    {
        $queryText = trim($request->input('query'));

        $query = Product::with(['categories', 'variations.options']);

        if (!empty($queryText)) {
            $keywords = explode(' ', $queryText);

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where(function ($sub) use ($word) {
                        $sub->where('name', 'LIKE', "%$word%")
                            ->orWhere('slug', 'LIKE', "%$word%")
                            ->orWhere('sort_description', 'LIKE', "%$word%")
                            ->orWhere('long_description', 'LIKE', "%$word%")
                            ->orWhere('price', 'LIKE', "%$word%")

                            // Category search
                            ->orWhereHas('categories', function ($cat) use ($word) {
                                $cat->where('name', 'LIKE', "%$word%");
                            })

                            // Variation & option search
                            ->orWhereHas('variations.options', function ($var) use ($word) {
                                $var->where('name', 'LIKE', "%$word%");
                            });
                    });
                }
            });
        }

        // Only active products
        $query->where('is_visible', 1)
            ->whereIn('product_type', ['simple', 'attribute']);

        $products = $query->paginate(10);

        return apiResponse(true, 'Search Results', ['products' => $products], 200);
    }

}
