<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::with('products')->latest()->get();
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.offers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'type' => 'required|string',
            'status' => 'boolean',
            'benefits' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'products' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('offers', 'public');
        }

        $offer = Offer::create($data);
        if ($request->products) {
            $offer->products()->sync($request->products);
        }

        return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
    }

    public function edit(Offer $offer)
    {
        $products = Product::all();
        return view('admin.offers.edit', compact('offer', 'products'));
    }

    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'type' => 'required|string',
            'status' => 'boolean',
            'benefits' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'products' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $data['image'] = $request->file('image')->store('offers', 'public');
        }

        $offer->update($data);
        $offer->products()->sync($request->products);

        return redirect()->route('offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        if ($offer->image) {
            Storage::disk('public')->delete($offer->image);
        }
        $offer->delete();
        return redirect()->route('offers.index')->with('success', 'Offer deleted successfully.');
    }
}