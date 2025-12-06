<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Slider;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('is_visible',1)->get();
        $categorys = Category::where('is_visible',1)->where('is_popular',1)->where('is_home',1)->get();
        $all_products = $categorys->pluck('products')->flatten();
        $brands = Brand::where('is_visible',1)->where('is_popular',1)->where('is_home',1)->get();
        $testimonials = Testimonial::where('is_visible',1)->get();
        $featured_products = Product::where('is_featured',1)
                                    ->where('is_visible',1)
                                    ->where('is_home',1)
                                    ->limit(8)
                                    ->orderBy('id','desc')
                                    ->get();
        return view('site.home',compact('sliders','categorys','brands','testimonials','featured_products','all_products'));
    }
}
 
