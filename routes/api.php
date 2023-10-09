<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\AddonPackageController;
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
Route::post('/email-check', [AuthController::class, 'email_check']);

// Route::post('/login', [AuthController::class, 'loginEmailOrphone']);
// Route::post('/register', [AuthController::class, 'register_email_phone']);


Route::middleware(['auth:sanctum'])->group(function () {
    // These routes are now protected by the 'auth:sanctum' middleware

    // profile routes
    Route::get('/profile', [AuthController::class, 'profile_get']);
    Route::post('/profile', [AuthController::class, 'profile_update']);

    Route::get('/countries', [CountryController::class, 'get_countries']);
    Route::get('/package-addon', [AddonPackageController::class, 'package_visa_addon']);
  

});
