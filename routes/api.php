<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\VisaController;
use App\Http\Controllers\Api\AddonPackageController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\OrderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/email-otp-sand', [AuthController::class, 'sendResetOtpEmail']);
Route::post('/otp-check', [AuthController::class, 'otp_check']);
Route::post('/password-reset', [AuthController::class, 'ResetPassword']);
Route::post('/email-check', [AuthController::class, 'email_check']);
Route::get('/countries', [CountryController::class, 'get_countries']);
Route::get('/country-search', [CountryController::class, 'country_search']);
// Route::post('/login', [AuthController::class, 'loginEmailOrphone']);
// Route::post('/register', [AuthController::class, 'register_email_phone']);


Route::middleware(['auth:sanctum'])->group(function () {
    // These routes are now protected by the 'auth:sanctum' middleware

    // profile +++
    Route::get('/profile', [AuthController::class, 'profile_get']);
    Route::get('/terms-condition', [AuthController::class, 'terms_condition']);
    Route::get('/privacy-policy', [AuthController::class, 'privacy_policy']);
    Route::get('/help-support', [AuthController::class, 'help_support']);

    Route::post('/profile', [AuthController::class, 'profile_update']);
    //  profile ---

    // package-addon +++
    Route::get('/package-addon', [AddonPackageController::class, 'package_visa_addon']);
    // package-addon ---


    //  Country +++
    Route::get('/most-visited-countries', [CountryController::class, 'most_visited_countries']);
    Route::get('/get-schengen-visa', [CountryController::class, 'get_schengen_visa']);
    //  Country ---

    //    Visa  +++
    Route::get('/select-visa-by-country/{country_id}', [VisaController::class, 'get_visa_by_country']);
    // Route::get('/select-visa-with-pakege/{country_id}', [VisaController::class, 'get_visa_by_country']);
    Route::get('/get-visa-details/{visa_id}', [VisaController::class, 'get_visa_by_id']);
    //    Visa   ---


    //    cart and wishlist  +++
    Route::prefix('cart')->group(function () {
        Route::post('add-to-cart', [CartController::class, 'addToCart']);
        // Route::put('update', [CartController::class, 'updateCart']);
        Route::get('remove-visa/{productId}', [CartController::class, 'removeFromCart']);
        Route::get('/', [CartController::class, 'carts']);
    });
    Route::prefix('wishlist')->group(function () {
        Route::post('add-to-wishlist', [WishlistController::class, 'addToWishlist']);
        // Route::put('update', [CartController::class, 'updateCart']);
        Route::get('remove-visa/{productId}', [WishlistController::class, 'removeFromWishlist']);
        Route::get('/', [WishlistController::class, 'wishlists']);
    });
    //    wishlist   ---

    Route::get('/apply-form', [AddonPackageController::class, 'apply_form']);
    Route::post('/create-order', [OrderController::class, 'create_order']);
    Route::get('/order-history', [OrderController::class, 'order_history']);
});
