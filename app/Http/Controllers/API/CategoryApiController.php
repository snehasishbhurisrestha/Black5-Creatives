<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with(['children', 'parent']);

        // 🔎 Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%$search%")
                  ->orWhere('slug', 'LIKE', "%$search%");
        }

        // 🏠 is_home filter
        if ($request->has('is_home')) {
            $query->where('is_home', (bool) $request->is_home);
        }

        // ⭐ is_popular filter
        if ($request->has('is_popular')) {
            $query->where('is_popular', (bool) $request->is_popular);
        }

        // 📌 is_special filter
        if ($request->has('is_special')) {
            $query->where('is_special', (bool) $request->is_special);
        }

        // 📂 is_menu filter
        if ($request->has('is_menu')) {
            $query->where('is_menu', (bool) $request->is_menu);
        }

        // ✅ Only visible categories
        $query->where('is_visible', true);
        $query->where('parent_id', null);

        $categories = $query->paginate(10); // or ->get() if no pagination

        return apiResponse(true,'Categories',['categories'=>$categories],200);
    }

    public function show($slug)
    {
        $category = Category::with(['children', 'parent', 'products', 'products.variations.options'])
            // ->findOrFail($id);
            ->where('slug',$slug)->first();

        return apiResponse(true,'Categories',['category'=>$category],200);
    }

}
