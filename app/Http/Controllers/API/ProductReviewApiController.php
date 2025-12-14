<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'   => 'required|exists:products,id',
            'rating'       => 'required|integer|min:1|max:5',
            'review_text'  => 'nullable|string|max:1000',
            'video_link'   => 'nullable|url',
            'images'       => 'nullable|array',
            'images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:9216', // 9MB
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

        // Attach images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $review
                    ->addMedia($image)
                    ->toMediaCollection('review-media');
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
            'images'      => $review->getMedia('review-media')
                                    ->filter(fn ($m) => str_starts_with($m->mime_type, 'image/'))
                                    ->map(fn ($m) => $m->getUrl())
                                    ->values(),
            'video'       => $review->video_link,
        ], 201);
    }
}
