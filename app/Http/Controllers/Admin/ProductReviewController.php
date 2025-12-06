<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    // Show all reviews
    public function index()
    {
        $reviews = ProductReview::with(['user', 'product'])->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    // Create form
    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.reviews.create', compact('users', 'products'));
    }

    // Store review
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|exists:users,id',
            'product_id'  => 'required|exists:products,id',
            'rating'      => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string',
            'video_link'  => 'nullable|string',
            'media.*'     => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // 20MB
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $review = ProductReview::create([
            'user_id'     => $request->user_id,
            'product_id'  => $request->product_id,
            'rating'      => $request->rating,
            'review_text' => $request->review_text,
            'video_link'  => $request->video_link,
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $review->addMedia($file)->toMediaCollection('review-media');
            }
        }

        return redirect()->route('reviews.index')->with('success', 'Review created successfully');
    }

    // Edit form
    public function edit($id)
    {
        $review   = ProductReview::findOrFail($id);
        $users    = User::all();
        $products = Product::all();
        return view('admin.reviews.edit', compact('review', 'users', 'products'));
    }

    // Update review
    public function update(Request $request, $id)
    {
        $review = ProductReview::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|exists:users,id',
            'product_id'  => 'required|exists:products,id',
            'rating'      => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string',
            'video_link'  => 'nullable|string',
            'media.*'     => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // 20MB
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $review->update([
            'user_id'     => $request->user_id,
            'product_id'  => $request->product_id,
            'rating'      => $request->rating,
            'review_text' => $request->review_text,
            'video_link'  => $request->video_link,
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $review->addMedia($file)->toMediaCollection('review-media');
            }
        }

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully');
    }

    // Delete review
    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully');
    }

    public function destroyMedia($reviewId, $mediaId)
    {
        $review = ProductReview::findOrFail($reviewId);

        $media = $review->media()->where('id', $mediaId)->firstOrFail();

        $media->delete();

        return back()->with('success', 'Media deleted successfully.');
    }

}
