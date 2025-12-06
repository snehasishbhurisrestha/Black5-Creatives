<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Slider;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\Product;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Product::query();

        // Search keyword
        if ($request->filled('query')) {
            $search = trim($request->input('query'));

            // Extract price filters (under, above, between)
            preg_match('/(\d+)/', $search, $priceMatches);
            preg_match('/(under|above|between)/i', $search, $priceFilter);

            // Extract text keywords (removing numbers and filter words)
            $keywords = preg_replace('/\d+|under|above|between/i', '', $search);
            $keywords = trim($keywords);

            if (!empty($keywords)) {
                $query->where('name', 'LIKE', "%{$keywords}%");
            }

            // Apply price filter
            if (!empty($priceMatches)) {
                $price = (int) $priceMatches[0];

                if (!empty($priceFilter)) {
                    switch (strtolower($priceFilter[0])) {
                        case 'under':
                            $query->where('total_price', '<=', $price);
                            break;
                        case 'above':
                            $query->where('total_price', '>=', $price);
                            break;
                        case 'between':
                            preg_match_all('/\d+/', $search, $betweenMatches);
                            if (count($betweenMatches[0]) == 2) {
                                $minPrice = (int)$betweenMatches[0][0];
                                $maxPrice = (int)$betweenMatches[0][1];
                                $query->whereBetween('total_price', [$minPrice, $maxPrice]);
                            }
                            break;
                    }
                }
            }
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by Brand
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Sorting Logic
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        }

        // Pagination Limit (Default: 12 products per page)
        $perPage = $request->input('show', 12);
        $query->where('is_visible', 1);

        // Fetch filtered products
        $products = $query->paginate($perPage)->withQueryString();

        return view('site.products.products', compact('products'));
    }


    public function suggestions(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('name', 'LIKE', "%{$query}%")->limit(5)->get();
        $categories = Category::where('name', 'LIKE', "%{$query}%")->limit(5)->get();
        $brands = Brand::where('name', 'LIKE', "%{$query}%")->limit(5)->get();

        $suggestions = [];

        foreach ($products as $product) {
            $suggestions[] = [
                'name' => $product->name,
                'url' => route('product.details', $product->slug),
                'type' => 'Product'
            ];
        }

        foreach ($categories as $category) {
            $suggestions[] = [
                'name' => $category->name,
                'url' => route('categories.products', $category->slug),
                'type' => 'Category'
            ];
        }

        foreach ($brands as $brand) {
            $suggestions[] = [
                'name' => $brand->name,
                'url' => route('brands.products', $brand->slug),
                'type' => 'Brand'
            ];
        }

        // Static All Links
        $suggestions[] = ['name' => 'All Products', 'url' => route('product.all'), 'type' => ''];
        $suggestions[] = ['name' => 'All Categories', 'url' => route('categories.all'), 'type' => ''];
        $suggestions[] = ['name' => 'All Brands', 'url' => route('brands.all'), 'type' => ''];

        return response()->json($suggestions);
    }

}