<?php 
    use App\Models\Cart;
    use App\Models\Brand;
    use App\Models\Category;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cookie;

    if(!function_exists('get_cart_items')){
        function get_cart_items(){
            $userId = Auth::check() ? Auth::id() : Cookie::get('guest_user_id');
            $cart_items = Cart::where('user_id', $userId)->get();

            return $cart_items;
        }
    }

    if(!function_exists('get_visible_brands')){
        function get_visible_brands(){
            $brands = Brand::where('is_visible',1)->where('is_menu',1)->get();
            return $brands;
        }
    }

    if(!function_exists('get_menu_categories')){
        function get_menu_categories(){
            $categories = Category::where('is_visible',1)->where('is_menu',1)->orderBy('id','desc')->limit(4)->get();
            return $categories;
        }
    }

    if(!function_exists('get_special_categories')){
        function get_special_categories(){
            $categories = Category::where('is_visible',1)->where('is_menu',1)->where('is_special',1)->orderBy('id','desc')->limit(2)->get();
            return $categories;
        }
    }