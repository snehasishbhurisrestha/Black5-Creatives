<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryFreeOffer;
use App\Models\CategoryFreeOfferItem;
use App\Models\ProductVariationOption;
use Illuminate\Http\Request;

class CategoryFreeOfferController extends Controller
{
    /**
     * Show all offers
     */
    public function index()
    {
        $offers = CategoryFreeOffer::with(['category', 'items.variationOption'])
                    ->orderBy('id', 'desc')
                    ->get();

        return view('admin.category_free_offers.index', compact('offers'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::where('is_visible', 1)->whereNull('parent_id')->get();
        $variationOptions = ProductVariationOption::with('variation.product')->get();

        return view('admin.category_free_offers.create', compact('categories', 'variationOptions'));
    }

    /**
     * Store a new offer
     */
    /*public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'required_qty' => 'required|integer|min:1',
            'free_product_qty' => 'required|integer|min:1',
            // 'free_variations' => 'required|array|min:1',
            // 'free_variations.*.variation_option_id' => 'required|exists:product_variation_options,id',
            // 'free_variations.*.free_qty' => 'required|integer|min:1',
        ]);

        // Create main offer
        $offer = CategoryFreeOffer::create([
            'category_id' => $request->category_id,
            'required_qty' => $request->required_qty,
            'free_product_qty' => $request->free_product_qty,
            'is_active' => $request->is_active ? 1 : 0,
        ]);

        // Save free items
        // foreach ($request->free_variations as $item) {
        //     CategoryFreeOfferItem::create([
        //         'offer_id' => $offer->id,
        //         'variation_option_id' => $item['variation_option_id'],
        //         'free_qty' => $item['free_qty'],
        //     ]);
        // }

        return redirect()->route('category_free_offer.index')
                         ->with('success', 'Offer created successfully');
    }*/

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'required_qty' => 'required|integer|min:1',
            'free_product_qty' => 'required|integer|min:1',

            'offer_image' => 'required|image|max:9216',
            'success_image' => 'required|image|max:9216',

            'step_images' => 'required|array',
            'step_images.*' => 'required|image|max:9216',
        ]);

        // Create Offer
        $offer = CategoryFreeOffer::create([
            'category_id' => $request->category_id,
            'required_qty' => $request->required_qty,
            'free_product_qty' => $request->free_product_qty,
            'is_active' => $request->is_active ?? 0,
        ]);

        // Offer Image
        $offer->addMedia($request->file('offer_image'))
            ->toMediaCollection('offer_image');

        // Success Image
        $offer->addMedia($request->file('success_image'))
            ->toMediaCollection('success_image');

        // Step Images (1,2,3…N)
        foreach ($request->file('step_images') as $step => $image) {
            $offer->addMedia($image)
                ->usingName('step_' . $step)
                ->withCustomProperties(['step' => $step])
                ->toMediaCollection('step_images');
        }

        return redirect()
            ->route('category_free_offer.index')
            ->with('success', 'Offer created successfully');
    }


    /**
     * Edit form
     */
    public function edit($id)
    {
        $offer = CategoryFreeOffer::with('items')->findOrFail($id);
        $categories = Category::where('is_visible', 1)->whereNull('parent_id')->get();
        $variationOptions = ProductVariationOption::with('variation.product')->get();

        return view('admin.category_free_offers.edit', compact('offer', 'categories', 'variationOptions'));
    }

    /**
     * Update the offer
     */
    /*public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'required_qty' => 'required|integer|min:1',
            'free_product_qty' => 'required|integer|min:1',
            // 'free_variations' => 'required|array|min:1',
            // 'free_variations.*.variation_option_id' => 'required|exists:product_variation_options,id',
            // 'free_variations.*.free_qty' => 'required|integer|min:1',
        ]);

        $offer = CategoryFreeOffer::findOrFail($id);

        $offer->update([
            'category_id' => $request->category_id,
            'required_qty' => $request->required_qty,
            'free_product_qty' => $request->free_product_qty,
            'is_active' => $request->is_active ? 1 : 0,
        ]);

        // Remove old free items
        CategoryFreeOfferItem::where('offer_id', $offer->id)->delete();

        // Add updated free items
        foreach ($request->free_variations as $item) {
            CategoryFreeOfferItem::create([
                'offer_id' => $offer->id,
                'variation_option_id' => $item['variation_option_id'],
                'free_qty' => $item['free_qty'],
            ]);
        }

        return redirect()->route('category_free_offer.index')
                         ->with('success', 'Offer updated successfully');
    }*/

    public function update(Request $request, CategoryFreeOffer $category_free_offer)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'required_qty' => 'required|integer|min:1',
            'free_product_qty' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
            
            'offer_image' => 'sometimes|image|max:9216',
            'success_image' => 'sometimes|image|max:9216',
            'step_images' => 'sometimes|array',
            'step_images.*' => 'sometimes|image|max:9216',
        ]);

        // Update basic offer info
        $category_free_offer->update([
            'category_id' => $request->category_id,
            'required_qty' => $request->required_qty,
            'free_product_qty' => $request->free_product_qty,
            'is_active' => $request->is_active ?? 0,
        ]);

        // Handle offer image
        if ($request->has('remove_offer_image')) {
            // Remove current offer image
            $category_free_offer->clearMediaCollection('offer_image');
        }
        
        if ($request->hasFile('offer_image')) {
            // Remove old if exists
            $category_free_offer->clearMediaCollection('offer_image');
            // Add new
            $category_free_offer->addMedia($request->file('offer_image'))
                ->toMediaCollection('offer_image');
        }

        // Handle success image
        if ($request->has('remove_success_image')) {
            $category_free_offer->clearMediaCollection('success_image');
        }
        
        if ($request->hasFile('success_image')) {
            $category_free_offer->clearMediaCollection('success_image');
            $category_free_offer->addMedia($request->file('success_image'))
                ->toMediaCollection('success_image');
        }

        // Handle step images removal
        if ($request->has('remove_step_images')) {
            foreach ($request->input('remove_step_images') as $mediaId) {
                $category_free_offer->deleteMedia($mediaId);
            }
        }

        // Handle new/replacement step images
        if ($request->hasFile('step_images')) {
            foreach ($request->file('step_images') as $step => $image) {
                // Check if a step image already exists for this step
                $existingMedia = $category_free_offer->getMedia('step_images')
                    ->firstWhere('custom_properties.step', $step);
                
                if ($existingMedia) {
                    // Replace existing
                    $existingMedia->delete();
                }
                
                // Add new step image
                $category_free_offer->addMedia($image)
                    ->usingName('step_' . $step)
                    ->withCustomProperties(['step' => $step])
                    ->toMediaCollection('step_images');
            }
        }

        // Handle case when required_qty is reduced
        $currentStepImages = $category_free_offer->getMedia('step_images');
        if ($currentStepImages->count() > $request->required_qty) {
            // Remove excess step images (keep only required_qty)
            $imagesToKeep = $currentStepImages->sortBy('custom_properties.step')
                ->take($request->required_qty);
            
            $imagesToRemove = $currentStepImages->diff($imagesToKeep);
            
            foreach ($imagesToRemove as $image) {
                $image->delete();
            }
        }

        return redirect()
            ->route('category_free_offer.index')
            ->with('success', 'Offer updated successfully');
    }

    /**
     * Delete
     */
    public function destroy($id)
    {
        $offer = CategoryFreeOffer::findOrFail($id);
        $offer->delete();

        return back()->with('success', 'Offer deleted successfully');
    }
}
