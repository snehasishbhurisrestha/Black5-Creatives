<?php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Product;
    use App\Models\AddressBook;
    use App\Models\Service;

    use App\Models\Settings;

    if(!function_exists('general_settings')){
        function general_settings(){
            $setting = Settings::find(1);
            return $setting ?: null;
        }
    }

    if (!function_exists('generateOTP')) {
        function generateOTP($n = 4) {
            $otp = "";
            for ($i = 0; $i < $n; $i++) {
                $otp .= rand(0, 9);
            }
            return $otp;
        }
    }


    



    
    if (!function_exists('check_status')){
        function check_status($status){
            if($status == 1){
                $str = '<span class="badge bg-success text-light" style="font-size:15px;">Active</span>';
            }else{
                $str = '<span class="badge bg-danger text-light" style="font-size:15px;">Inactive</span>';
            }
            return $str;
        }
    }

    if (!function_exists('check_verified')){
        function check_verified($status){
            if($status == 1){
                $str = '<span class="text-success" title="Verified"><i class="fas fa-check-circle"></i></span></p>';
            }else{
                $str = '<span class="text-danger" title="Not Verified"><i class="fas fa-times-circle"></i></span></p>';
            }
            return $str;
        }
    }

    if (!function_exists('check_visibility')) {
        function check_visibility($val)
        {
            if($val==1){
                $str='<span class="tag tag-success">Active</span>';
            }else{
                $str='<span class="tag tag-danger">Inactive</span>';
            }
            return $str;
        }
    }

    if (!function_exists('check_uncheck')) {
        function check_uncheck($val1,$val2)
        {
            if($val1==$val2){
                $str='checked';
            }else{
                $str='';
            }
            return $str;
        }
    }

    if (!function_exists('generateToken')) {
        function generateToken($length = 32) {
            $bytes = random_bytes($length);
            $apiKey = base64_encode($bytes);
            $urlSafeApiKey = str_replace(['+', '/', '='], ['-', ''], $apiKey);
            return $urlSafeApiKey;
        }
    }

    if (!function_exists('get_user_name')) {
        function get_user_name($field, $id){
            $user = DB::table('users')->where($field, $id)->first();
            if ($user) {
                return $user->name;
            } else {
                return null;
            }
        }
    }

    if (!function_exists('get_category_name')) {
        function get_category_name($id){
            $category = DB::table('categories')->where('id', $id)->first();
            if ($category) {
                return $category->name;
            } else {
                return null;
            }
        }
    }



    if (!function_exists('get_join_green_date')) {
        function get_join_green_date($datetime)
        {
            if($datetime != ''){
                return format_datetime($datetime);
            }else{
                return '';
            }
        }
    }
    
    

    if(!function_exists('createSlug')) {
        function createSlug($name, $model)
        {
            $slug = Str::slug($name);
            $originalSlug = $slug;

            $count = 1;
            while ($model::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            return $slug;
        }
    }

    if (!function_exists('is_have_image')) {
        function is_have_image($image) {
            if($image){
                return 'block';
            }else{
                return 'none';
            }
        }
    }

    if (!function_exists('get_product_by_id')) {
        function get_product_by_id($product_id){
            $product = Products::find($product_id);
            return $product;
        }
    }

    if (!function_exists('get_product_price_by_id')) {
        function get_product_price_by_id($product_id){
            $product = Products::find($product_id);
            return $product->price;
        }
    }

    if (!function_exists('get_product_name')) {
        function get_product_name($product_id){
            $product = Products::find($product_id);
            if($product){
                return $product->title;
            }else{
                return '';
            }
        }
    }


    if (!function_exists('is_disabled')) {
        function is_disabled($value){
            if($value){
                return 'disabled';
            }else{
                return '';
            }
        }
    }

    if (!function_exists('get_country_name')){
        function get_country_name($id){
            $country = DB::table('location_countries')->where('id',$id)->value('name');
            return $country;
        }
    }

    if (!function_exists('get_state_name')){
        function get_state_name($id){
            $state = DB::table('location_states')->where('id',$id)->value('name');
            return $state;
        }
    }

    if (!function_exists('get_city_name')){
        function get_city_name($id){
            $city = DB::table('location_cities')->where('id',$id)->value('name');
            return $city;
        }
    }

    if (!function_exists('get_cgst')) {
        function get_cgst($gst_price) {
            if ($gst_price == 0) {
                return 0;
            }
    
            $cgst = $gst_price / 2;
            return round($cgst, 2);
        }
    }

    if (!function_exists('get_sgst')) {
        function get_sgst($gst_price) {
            if ($gst_price == 0) {
                return 0;
            }
    
            $sgst = $gst_price / 2;
            return round($sgst, 2);
        }
    }
    
    if (!function_exists('formatGSTRate')) {
        function formatGSTRate($rate,$is_csgst = 0)
        {
            if ($rate == 0) {
                return 0;
            }
            if($is_csgst){ return number_format($rate/2, 0) . '%'; }
            return number_format($rate, 0) . '%';
        }
    }

    if(!function_exists('getProductMainImage')){
        function getProductMainImage($productId){
            $product = Product::find($productId);

            if (!$product) {
                return null;
            }
            $mainImage = $product->getMedia('products-media')
                ->firstWhere('custom_properties.is_main', true);

            return $mainImage ? $mainImage->getUrl() : null;
        }
    }

    if (!function_exists('get_address_by_id')) {
        function get_address_by_id($id)
        {
            // Retrieve the address record by ID
            $address = AddressBook::find($id);
    
            if ($address) {
                $html = '';
    
                // Append address details
                $html .= $address->billing_address . ', ';
                $html .= $address->billing_zip_code . ', ';
    
                // Append location names
                $html .= optional($address->country)->name . ', ';
                $html .= optional($address->state)->name . ', ';
                $html .= optional($address->city)->name;
    
                return $html;
            } else {
                return false;
            }
        }
    }

    if (!function_exists('get_visible_services')) {
        function get_visible_services()
        {
            $services = Service::where('is_active',1)->get();
            return $services;
        }
    }

    if (!function_exists('generateUniqueId')) {
        function generateUniqueId(string $type)
        {
            // $prefix = $type === 'auditor' ? 'A' : 'U'; // 'A' for auditor, 'U' for user
            $prefix = match ($type) {
                'auditor' => 'A', // 'A' for auditor
                'system_user' => 'SU', // 'SU' for system user
                default => 'U', // 'U' for regular user
            };
            $uniquePart = substr(uniqid(), -8); // Last 8 characters of a unique ID

            return $prefix . $uniquePart;
        }
    }

    if (!function_exists('splitName')) {
        function splitName($fullName)
        {
            $nameParts = explode(' ', $fullName);
            $firstName = $nameParts[0] ?? '';
            $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';

            return ['first_name' => $firstName, 'last_name' => $lastName];
        }
    }

    if (!function_exists('getAddressById')) {
        function getAddressById($id)
        {
            $billing=AddressBook::find($id);
            if(!empty($billing)){
                $html=' <address class="col-md-4">';
                $html.='<p><strong>'.$billing->billing_first_name.' '.$billing->billing_last_name .'</strong></p>';
                $html.='<p>';
                $html.=$billing->billing_address_1.',';
                if(!empty($billing->billing_address_2)){
                    $html.='<br>'.$billing->billing_address_2.',';
                }
                if(!empty($billing->billing_landmark)){
                    $html.=$billing->billing_landmark.',';
                }
                $html.=$billing->country->name.', ';
                $html.=$billing->state->name.', ';
                $html.= is_numeric($billing->billing_city) ? $billing->city->name : $billing->billing_city;
                $html.=', '.$billing->billing_zip_code;
                $html.='</p>';
                // $html.=$billing->billing_email.',';
                $html.='<p>Mobile:'. $billing->billing_phone_number.'</p>';
                $html.=' <a href="'.route('user-dashboard.address.edit',$billing->id).'" class="check-btn sqr-btn "><i class="fa fa-edit"></i> Edit Address</a>';
                $html .= '<a href="'.route('user-dashboard.address.delete',$billing->id).'" onclick="return confirm(\'Are you sure?\')" class="check-btn sqr-btn text-danger" id="'.$billing->id.'"><i class="fas fa-trash-alt"></i> Delete</a>';
     
                $html.='</address>';
                return $html;
            }else{
                return false;
            }
        }
    }
    
