<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\{
    ContactUsApiController,
    CategoryApiController,
    BrandApiController,
    ProductApiController,
    GoogleAuthApiController,
    AuthenticationApiController,
    CartApiController,
    CheckoutApiController,
    LocationApiController,
    UserApiController,
    ProductReviewApiController,
    FeaturePanelApiController,
    PagesApiController,
    FAQApiController,
};


Route::post('contact-us',[ContactUsApiController::class,'store']);

Route::get('get-faqs',[FAQApiController::class,'index']);
Route::post('submit-question',[FAQApiController::class,'store']);

Route::get('/categories', [CategoryApiController::class, 'index']);
Route::get('/categories/{slug}', [CategoryApiController::class, 'show']);

Route::get('/brands', [BrandApiController::class, 'index']);
Route::get('/brands/{id}', [BrandApiController::class, 'show']);

Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/category/{slug}', [ProductApiController::class, 'byCategory']);
Route::get('/products/featured', [ProductApiController::class, 'featured']);
Route::get('/products/special', [ProductApiController::class, 'special']);
Route::get('/products/best-selling', [ProductApiController::class, 'bestSelling']);
// Route::get('/products/{id}', [ProductApiController::class, 'show']);

Route::get('/products/{slug}', [ProductApiController::class, 'show']);
Route::get('/products/{slug}/related', [ProductApiController::class, 'related_products']);
Route::post('/recently-viewed-products', [ProductApiController::class, 'recently_viewed_products']);

Route::get('/auth/google/generate-url', [GoogleAuthApiController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthApiController::class, 'handleGoogleCallback']);

Route::post('/register', [AuthenticationApiController::class, 'register']);
Route::post('/verify-otp', [AuthenticationApiController::class, 'verifyOtp']);

Route::post('/forgot-password', [AuthenticationApiController::class, 'forgotPassword']);
Route::post('/verify-forgot-otp', [AuthenticationApiController::class, 'verifyForgotOtp']);
Route::post('/reset-password', [AuthenticationApiController::class, 'resetPassword']);
Route::post('/resend-forgot-otp', [AuthenticationApiController::class, 'resendForgotOtp']);


Route::post('/login', [AuthenticationApiController::class, 'login']);

Route::prefix('feature-panels')->group(function () {
    // Fetch phone case feature panels
    Route::get('phone-case', [FeaturePanelApiController::class, 'phoneCaseFeaturePanel']);

    // Fetch wall art feature panels
    Route::get('wall-art', [FeaturePanelApiController::class, 'wallArtFeaturePanel']);
});

// Public routes for frontend
Route::get('/pages', [PagesApiController::class, 'index']); // list all pages
Route::get('/pages/{slug}', [PagesApiController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    // Route::get('/products/{slug}', [ProductApiController::class, 'show']);
    // Route::get('/products/{slug}/related', [ProductApiController::class, 'related_products']);
    // Route::get('/recently-viewed-products', [ProductApiController::class, 'recently_viewed_products']);

    Route::post('/logout', [AuthenticationApiController::class, 'logout']);

    Route::post('/add-to-cart', [CartApiController::class, 'add_to_cart']);
    Route::post('/update-cart-quantity', [CartApiController::class, 'update_cart_quantity']);
    Route::get('/get-cart-items', [CartApiController::class, 'cart_items']);
    Route::post('/remove-from-cart', [CartApiController::class, 'remove_from_cart']);

    Route::get('/get-saved-address', [CheckoutApiController::class, 'get_saved_address']);
    Route::post('/add-new-addresss-book', [CheckoutApiController::class, 'add_new_addresss_book']);

    Route::get('create-razorpay-order',[CheckoutApiController::class, 'createRazorpayOrder']);
    Route::post('place-order',[CheckoutApiController::class, 'placeOrderWithRazorpay']);

    // Profile
    Route::get('/user/get-profile', [UserApiController::class, 'getProfile']);
    Route::post('/user/update-profile', [UserApiController::class, 'updateProfile']);

    // Orders
    Route::get('/user/orders', [UserApiController::class, 'getOrders']);
    Route::get('/user/orders/{id}', [UserApiController::class, 'getOrderDetails']);

    // Address
    Route::get('/user/get-addresses', [UserApiController::class, 'getAddresses']);
    Route::post('/user/add-addresses', [UserApiController::class, 'addAddress']);
    Route::put('/user/update-addresses/{id}', [UserApiController::class, 'updateAddress']);
    Route::delete('/user/delete-addresses/{id}', [UserApiController::class, 'deleteAddress']); 

    Route::post('reviews/store', [ProductReviewApiController::class, 'store']);
});

Route::get('/countries', [LocationApiController::class, 'getCountries']);
Route::get('/states/{country_id}', [LocationApiController::class, 'getStates']);
Route::get('/cities/{state_id}', [LocationApiController::class, 'getCities']);

// Reviews
Route::get('reviews', [ProductReviewApiController::class, 'index']);
Route::get('reviews/{id}', [ProductReviewApiController::class, 'show']);