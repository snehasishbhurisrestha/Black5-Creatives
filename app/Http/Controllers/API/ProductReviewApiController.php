<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewApiController extends Controller
{
    /**
     * Return all product reviews with product & user details
     */
    public function index(Request $request)
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
                            'images'     => $review->getMedia('review-media')
                                                ->filter(fn ($media) => str_starts_with($media->mime_type, 'image/'))
                                                ->map(fn ($media) => $media->getUrl())
                                                ->values(),
                            // 'videos'     => $review->getMedia('review-media')
                            //                     ->filter(fn ($media) => str_starts_with($media->mime_type, 'video/'))
                            //                     ->map(fn ($media) => $media->getUrl())
                            //                     ->values(),
                            'video'     => $review->video_link,
                        ];
                    });


        return apiResponse(true, 'Reviews fetched successfully', $reviews, 200);
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
}
