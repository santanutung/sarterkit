<?php


use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\AddonPackageController;
use App\Http\Controllers\Backend\VisaController;
use App\Http\Controllers\Backend\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashbordController;
use App\Http\Controllers\Backend\ProfileController;

use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\MenuBuilderController;
use App\Http\Controllers\Backend\SettingController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/dashboard', DashbordController::class)->name('dashbord');
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::resource('countries', CountryController::class);
Route::resource('addon-packages', AddonPackageController::class);
Route::resource('visas', VisaController::class);
Route::resource('pages', PageController::class);
Route::resource('menus', MenuController::class)->except(['show']);

Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::post('update-status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');


//  profile
Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');



//  Update Password
Route::get('profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.index');
Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');


//  profile
Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');



//  Update Password
Route::get('profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.index');
Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

//Route::group(['as' => 'app.', 'prefix' => 'app', 'namespace' => 'App\Http\Controllers\Backend','middleware'=>['auth']], function () {
//    Route::get('/dashbord', 'DashbordController')->name('dashbord');
//    Route::resource('roles', ' ');
//});




Route::group(['as' => 'menus.', 'prefix' => 'menus/{id}/'], function () {
   Route::get('builder', [MenuBuilderController::class, 'index'])->name('builder');
   Route::post('order', [MenuBuilderController::class, 'order'])->name('order');
   // Menu Item
   Route::group(['as' => 'item.', 'prefix' => 'item'], function () {
      Route::get('/create', [MenuBuilderController::class, 'itemCreate'])->name('create');
      Route::post('/store', [MenuBuilderController::class, 'itemStore'])->name('store');
      Route::get('/{itemId}/edit', [MenuBuilderController::class, 'itemEdit'])->name('edit');
      Route::put('/{itemId}/update', [MenuBuilderController::class, 'itemUpdate'])->name('update');
      Route::delete('/{itemId}/destroy', [MenuBuilderController::class, 'itemDestroy'])->name('destroy');
   });
});

 Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
   Route::get('/general', [SettingController::class, 'index'])->name('general');
   Route::patch('/general', [SettingController::class,'update'])->name('update');

   Route::get('/appearance', [SettingController::class, 'appearance'])->name('appearance.index');
   Route::patch('/appearance', [SettingController::class, 'updateAppearance'])->name('appearance.update');

   Route::get('/mail', [SettingController::class, 'mail'])->name('mail.index');
   Route::patch('/mail', [SettingController::class, 'updateMailSettings'])->name('mail.update');

   Route::get('socialite', [SettingController::class, 'socialite'])->name('socialite.index');
   Route::patch('socialite', [SettingController::class, 'updateSocialiteSettings'])->name('socialite.update');

 });
