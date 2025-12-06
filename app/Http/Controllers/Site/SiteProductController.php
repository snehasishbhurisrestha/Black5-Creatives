<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Slider;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\Product;

class SiteProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

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

        // Pagination Limit (Default: 4 products per page)
        $perPage = $request->input('show', 4);

        $query->where('is_visible',1);
        
        // Fetch products with applied filters and pagination
        $products = $query->paginate($perPage)->withQueryString();

        return view('site.products.products', compact('products'));
        // $products = Product::orderBy('id','desc')->get();
        // return view('site.products.products',compact('products'));
    }

    public function product_details(string $slug){
        $product = Product::where('slug',$slug)->first();

        $categoryIds = $product->categories->pluck('id');
        
        $relatedProducts = Product::whereHas('categories', function ($query) use ($categoryIds) {
                                        $query->whereIn('category_id', $categoryIds);
                                    })
                                    ->orWhere('brand_id', $product->brand_id)
                                    ->where('id', '!=', $product->id)
                                    ->inRandomOrder()
                                    ->take(4)
                                    ->get();

        $relatedProducts = $relatedProducts->reject(function ($relatedProduct) use ($product) {
            return $relatedProduct->id == $product->id;
        });
                                    

        return view('site.products.product_details',compact('product','relatedProducts'));
    }

    public function all_brands(Request $request){
        $query = Brand::query();

        // Sorting Logic
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_desc':
                    $query->orderBy('total_price', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('total_price', 'asc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        }

        // Pagination Limit (Default: 4 brands per page)
        $perPage = $request->input('show', 4);

        $query->where('is_visible',1);
        
        // Fetch brands with applied filters and pagination
        $brands = $query->paginate($perPage)->withQueryString();

        return view('site.brands', compact('brands'));
    }

    public function products_by_brands(Request $request,string $slug){
        $brand = Brand::where('slug',$slug)->first();
        if($brand){
            $query = Product::query();
    
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
    
            // Pagination Limit (Default: 4 products per page)
            $perPage = $request->input('show', 4);
    
            $query->where('is_visible',1);
            $query->where('brand_id',$brand->id);
            
            // Fetch products with applied filters and pagination
            $products = $query->paginate($perPage)->withQueryString();
    
            return view('site.products.products', compact('products'));
        }else{
            return back()->with('error','No Data Found');
        }
    }

    public function all_categories(Request $request){
        $query = Category::query();

        // Sorting Logic
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_desc':
                    $query->orderBy('total_price', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('total_price', 'asc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        }

        // Pagination Limit (Default: 4 brands per page)
        $perPage = $request->input('show', 4);

        $query->where('is_visible',1);
        $query->where('parent_id',null);
        
        // Fetch brands with applied filters and pagination
        $categorys = $query->paginate($perPage)->withQueryString();

        return view('site.categories', compact('categorys'));
    }

    public function products_by_category(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return back()->with('error', 'No Data Found');
        }

        $query = Product::query();

        // Filter products by category (Many-to-Many relationship)
        $query->whereHas('categories', function ($q) use ($category) {
            $q->where('categories.id', $category->id);
        });

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

        // Pagination Limit (Default: 4 products per page)
        $perPage = $request->input('show', 4);

        $query->where('is_visible', 1);

        // If you need to filter by brand, make sure $brand is defined or remove this line
        // $query->where('brand_id', $brand->id);

        // Fetch products with applied filters and pagination
        $products = $query->paginate($perPage)->withQueryString();

        return view('site.products.products', compact('products'));
    }

}