<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductReviewApiController extends Controller
{
    /**
     * Return all product reviews with product & user details
     */
    /*public function index(Request $request, $produtc_id = null)
    {
        $reviews = ProductReview::with(['user', 'product'])
                    ->latest()
                    ->get()
                    ->map(function ($review) {
                        return [
                            'id'         => $review->id,
                            'user'       => $review->user,
                            'product'    => $review->product,
                            'rating'     => $review->rating,
                            'review_text'=> $review->review_text,
                            'created_at' => $review->created_at,
                            'images' => $review->getMedia('review_images')
                                            ->filter(fn ($m) => str_starts_with($m->mime_type, 'image/'))
                                            ->map(fn ($m) => $m->getUrl())
                                            ->values(),
                            // 'videos'     => $review->getMedia('review-media')
                            //                     ->filter(fn ($media) => str_starts_with($media->mime_type, 'video/'))
                            //                     ->map(fn ($media) => $media->getUrl())
                            //                     ->values(),
                            'video'     => $review->video_link,
                        ];
                    });


        return apiResponse(true, 'Reviews fetched successfully', $reviews, 200);
    }*/

    public function index(Request $request, $product_id = null)
    {
        $query = ProductReview::with(['user', 'product'])
            ->where('is_approved', 1)
            ->when($product_id, function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            })
            ->latest();

        $reviewsCollection = $query->get();

        // -------- Rating Statistics (only if product_id exists) --------
        $ratingStats = null;

        if ($product_id) {
            $totalReviews = $reviewsCollection->count();

            $ratingStats = [
                'total_reviews' => $totalReviews,
                'average_rating' => $totalReviews > 0
                    ? round($reviewsCollection->avg('rating'), 1)
                    : 0,

                'rating_breakdown' => [
                    5 => $reviewsCollection->where('rating', 5)->count(),
                    4 => $reviewsCollection->where('rating', 4)->count(),
                    3 => $reviewsCollection->where('rating', 3)->count(),
                    2 => $reviewsCollection->where('rating', 2)->count(),
                    1 => $reviewsCollection->where('rating', 1)->count(),
                ],
            ];
        }

        // -------- Review Data --------
        $reviews = $reviewsCollection->map(function ($review) {
            return [
                'id'          => $review->id,
                'user'        => $review->user,
                'product'     => $review->product,
                'rating'      => $review->rating,
                'review_text' => $review->review_text,
                'created_at'  => $review->created_at,

                'images' => $review->getMedia('review_images')
                    ->filter(fn ($m) => str_starts_with($m->mime_type, 'image/'))
                    ->map(fn ($m) => $m->getUrl())
                    ->values(),

                'video' => $review->video_link,
            ];
        });

        return apiResponse(true, 'Reviews fetched successfully', [
            'reviews' => $reviews,
            'stats'   => $ratingStats
        ], 200);
    }


    /**
     * Show single review with product & user details
     */
    public function show($id)
    {
        $review = ProductReview::with(['user', 'product'])
            ->findOrFail($id);

        return apiResponse(true, 'Review fetched successfully', $review, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'   => 'required|exists:products,id',
            'rating'       => 'required|integer|min:1|max:5',
            'review_text'  => 'nullable|string|max:1000',
            'video_link'   => 'nullable|url',
            'images'       => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, $validator->errors()->first(), null, 422);
        }

        $review = ProductReview::create([
            'user_id'     => $request->user()->id,
            'product_id'  => $request->product_id,
            'rating'      => $request->rating,
            'review_text' => $request->review_text,
            'video_link'  => $request->video_link,
            'is_approved' => 0
        ]);

        if ($request->has('images') && is_array($request->images)) {

            foreach ($request->images as $base64) {

                if (!$base64) {
                    continue;
                }

                // Remove spaces/newlines
                $base64 = preg_replace('/\s+/', '', $base64);

                // Validate Base64 image
                if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                    continue; // invalid image
                }

                $extension = $type[1] ?? 'png';

                // Decode safely
                $imageData = base64_decode(
                    substr($base64, strpos($base64, ',') + 1),
                    true
                );

                if ($imageData === false) {
                    continue;
                }

                // Save to Spatie media
                $review
                    ->addMediaFromString($imageData)
                    ->usingFileName(Str::uuid() . '.' . $extension)
                    ->toMediaCollection('review_images');
            }
        }

        $review->load(['user', 'product']);

        return apiResponse(true, 'Review added successfully', [
            'id'          => $review->id,
            'user'        => $review->user,
            'product'     => $review->product,
            'rating'      => $review->rating,
            'review_text' => $review->review_text,
            'created_at'  => $review->created_at,
            'images' => $review->getMedia('review_images')
                            ->filter(fn ($m) => str_starts_with($m->mime_type, 'image/'))
                            ->map(fn ($m) => $m->getUrl())
                            ->values(),
            'video'       => $review->video_link,
        ], 201);
    }
}
