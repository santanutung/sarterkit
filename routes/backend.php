<?php


use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashbordController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\MenuBuilderController;





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
Route::resource('pages', PageController::class);
Route::resource('menus', MenuController::class)->except(['show']);



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
