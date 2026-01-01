<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    Dashboard,
    RoleController,
    PermissionController,
    SystemUserController,
    CategoryController,
    ProductController,
    BrandController,
    SliderController,
    TestimonialController,
    OrderController,
    CouponController,
    ContactUsController,
    UsersController,
    ProductReviewController,
    FeaturePanelController,
    PageController,
    OfferController,
    FAQController,
    CategoryFreeOfferController,
    ReportController,
};

use App\Http\Controllers\Site\{
    HomeController,
    SiteContactUsController,
    SiteProductController,
    CartController,
    Checkout,
    UserDashboard,
    SearchController,
};

use App\Http\Controllers\LocationController;

// Route::post('get-state-list',[LocationController::class,'get_state_list'])->name('get-state-list');
// Route::post('get-city-list',[LocationController::class,'get_city_list'])->name('get-city-list');


// Route::get('/home',[HomeController::class,'index'])->name('home');

Route::get('/', function () {
    return redirect()->route('login');
});



// Route::get('/search', [SearchController::class, 'search'])->name('search');
// Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// Route::get('products',[SiteProductController::class,'index'])->name('product.all');
// Route::get('product/details/{slug?}',[SiteProductController::class,'product_details'])->name('product.details');

// Route::get('brands',[SiteProductController::class,'all_brands'])->name('brands.all');
// Route::get('brands/{slug?}',[SiteProductController::class,'products_by_brands'])->name('brands.products');

// Route::get('categories',[SiteProductController::class,'all_categories'])->name('categories.all');
// Route::get('categories/{slug?}',[SiteProductController::class,'products_by_category'])->name('categories.products');

// Route::get('/about', function () {
//     return view('site.about');
// })->name('about');

// // Route::get('registration',[RegistrationController::class,'registration'])->name('registration');
// // Route::post('registration',[RegistrationController::class,'register_user'])->name('register-user');

// Route::get('contact',[SiteContactUsController::class,'index'])->name('contact');
// Route::post('contact',[SiteContactUsController::class,'store'])->name('contact.store');

// Route::get('cart',[CartController::class, 'index'])->name('cart');
// Route::post('/cart/add-to-cart', [CartController::class, 'add_to_cart'])->name('add-to-cart');
// Route::get('/cart/count', [CartController::class, 'cartCount'])->name('cart.count');
// Route::get('/cart/products', [CartController::class, 'get_cart_products'])->name('cart.cart_products');
// Route::get('/cart/total', [CartController::class, 'sum_cart_total'])->name('cart.total');
// Route::patch('/cart/{id}', [CartController::class, 'updateCartQuantity'])->name('cart.update');
// Route::delete('/cart/{id}', [CartController::class, 'deleteCartItem'])->name('cart.delete');

// Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply.coupon');


// Route::get('/wishlist',[CartController::class, 'wishlist_index'])->name('wishlist.index');
// Route::post('/wishlist/add-to-wishlist', [CartController::class, 'add_to_wishlist'])->name('wishlist.add');
// Route::get('/wishlist/{id}/delete-from-wishlist', [CartController::class, 'delete_from_wishlist'])->name('wishlist.delete');

// Route::get('checkout',[Checkout::class, 'index'])->name('checkout')->middleware(['auth', 'verified']);
// Route::post('checkout/process',[Checkout::class, 'process_checkout'])->name('checkout.process');
// Route::post('/razorpay/callback', [Checkout::class, 'razorpay_callback'])->name('razorpay.callback');

Route::get('order/{order_number}/invoice',[Checkout::class, 'invoice'])->name('invoice')->middleware(['auth', 'verified']);

// Route::middleware('auth')->group(function () {
//     Route::controller(UserDashboard::class)->group(function () {
//         Route::prefix('uprofile')->group(function () {
//             Route::get('/','index')->name('user-profile');

//             Route::get('/profile','user_profile')->name('user-dashboard.profile');
//             Route::post('/profile','update_user_profile')->name('user-dashboard.profile.update');
            
//             Route::get('/orders','user_orders')->name('user-dashboard.orders');
//             Route::get('/orders/{id}/details','user_orders_details')->name('user-dashboard.orders.details');

//             Route::get('/address','user_address')->name('user-dashboard.address');
//             Route::post('/address/store','user_saveaddress')->name('user-dashboard.address.save');
//             Route::get('/address/{id}/edit','edit_user_address')->name('user-dashboard.address.edit');
//             Route::post('/address/update','user_update_address')->name('user-dashboard.address.update');
//             Route::get('/address/{id}/delete','delete_address')->name('user-dashboard.address.delete');
            
//         });
//     });

// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard',[Dashboard::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
    
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


        Route::controller(RoleController::class)->group(function () {
            Route::prefix('role')->group(function () {
                Route::get("/",'roles')->name('roles');
                Route::post("/create-role",'create_role')->name('role.create');
                Route::post("{roleId?}/update-role",'update_role')->name('role.update');
                Route::delete("/{roleId}/destroy-role",'destroy_role')->name('role.destroy');
                Route::get("/{roleId}/add-permission-to-role",'addPermissionToRole')->name('role.addPermissionToRole');
                Route::post("/{roleId}/give-permissions",'givePermissionToRole')->name('role.give-permissions');
            });
        });

        Route::controller(PermissionController::class)->group(function () {
            Route::prefix('permission')->group(function () {
                Route::get("/",'permission')->name('permission');
                Route::post("/create-permission",'create_permission')->name('permission.create');
                Route::post("{permissionId?}/update-permission",'update_permission')->name('permission.update');
                Route::delete("/{permissionId}/destroy-permission",'destroy_permission')->name('permission.destroy');
            });
        });

        Route::resource('category', CategoryController::class);
        Route::resource('brand', BrandController::class);
        Route::resource('offers', OfferController::class);

        Route::controller(ProductController::class)->group( function () {
            Route::prefix('product')->group( function () {
                Route::get('','index')->name('product.index');
                Route::post('get-products-by-category','get_products_by_category_id')->name('products.get-products-by-category');
                Route::post('update-product-stock','update_product_stock')->name('products.update-product-stock');
                Route::get('basic-info-create','basic_info_create')->name('products.basic-info-create');
                Route::post('basic-info-process','basic_info_process')->name('products.add-basic-info');

                Route::get('basic-info-edit/{id?}','basic_info_edit')->name('products.basic-info-edit');
                Route::post('basic-info-edit-process','basic_info_edit_process')->name('products.add-basic-edit-info');

                Route::get('price-edit/{id?}','price_edit')->name('products.price-edit');
                Route::post('price-edit-process','price_edit_process')->name('products.price-edit-process');

                
                Route::get('inventory-edit/{id?}','inventory_edit')->name('products.inventory-edit');
                Route::post('inventory-edit-process','inventory_edit_process')->name('products.inventory-edit-process');
                
                Route::get('variation-edit/{id?}','variation_edit')->name('products.variation-edit');
                Route::post('variation-edit-process','variation_edit_process')->name('products.variation-edit-process');
                Route::post('copy-variation', [ProductController::class, 'copyVariation'])->name('products.copyVariation');

                Route::get('product-images-edit/{id?}','product_images_edit')->name('products.product-images-edit');
                Route::post('product-gallery-save','productGalleryStore')->name('products.product-gallery-save');
                Route::post('get-product-temp-images','productTempImages')->name('products.get-product-temp-images');
                Route::post('delete-product-images','delete_product_media')->name('products.delete-product-images');
                Route::post('set-main-product-image','set_main_product_image')->name('products.set-main-product-image');
                Route::post('product-images-process','product_images_process')->name('products.product-images-process');

                Route::get('product-veriation-edit/{id?}','veriation_edit')->name('products.product-veriation-edit');
                Route::post('product-variations/store','storeVariation')->name('products.variation.store');
                Route::post('product-variation-options/store','storeVariationOption')->name('products.variation-option.store');

                Route::delete('destroy-variation/{id}','destroyVariation')->name('products.destroyVariation');
                Route::delete('destroy-variation-option/{id}','destroyVariationOption')->name('products.destroyVariationOption');

                Route::delete('delete/{id}','destroy')->name('products.delete');
            });
        });

        Route::resource('slider', SliderController::class);
        Route::resource('feature-panel', FeaturePanelController::class);
        Route::resource('page', PageController::class);
        Route::resource('testimonial', TestimonialController::class);


        Route::resource('system-user', SystemUserController::class);

        Route::controller(UsersController::class)->group( function() {
            Route::prefix('web-user')->group( function(){
                Route::get('','index')->name('web-user.index');
                Route::delete('{id}/destroy','destroy')->name('web-user.destroy');
            });
        });

        Route::resource('coupon', CouponController::class);

        Route::controller(OrderController::class)->group( function() {
            Route::prefix('orders')->group( function(){
                Route::get('','index')->name('order.index');
                Route::get('{id}/details','show')->name('order.details');
                Route::post('update-order-status','update_order_status')->name('order.update-order-status');
                Route::post('update-payment-status','update_payment_status')->name('order.update-payment-status');
                Route::delete('{id}/destroy','destroy')->name('order.destroy');
            });
        });

        Route::resource('contact-us', ContactUsController::class);
        Route::delete('/reviews/media-delete/{review}/media/{media}', [ProductReviewController::class, 'destroyMedia'])->name('reviews.media.destroy');
        Route::get('/reviews/approved/{id}', [ProductReviewController::class, 'update_approuval'])->name('reviews.approved');
        Route::resource('reviews', ProductReviewController::class);
        Route::resource('faqs', FAQController::class);
        Route::post('/faqs/reorder', [FAQController::class, 'reorder'])->name('faqs.reorder');


        Route::resource('category_free_offer',CategoryFreeOfferController::class);

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('/orders', [ReportController::class, 'orders'])->name('orders');
            Route::get('/products', [ReportController::class, 'products'])->name('products');
            Route::get('/payments', [ReportController::class, 'payments'])->name('payments');
            Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        });
    });

});

require __DIR__.'/auth.php';
