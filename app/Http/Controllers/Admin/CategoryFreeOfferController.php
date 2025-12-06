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
    public function store(Request $request)
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
    public function update(Request $request, $id)
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
