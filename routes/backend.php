<?php


use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashbordController;



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




Route::get('/dashbord', DashbordController::class)->name('dashbord');
Route::resource('roles', RoleController::class);
 Route::resource('users',UserController::class);

//Route::group(['as' => 'app.', 'prefix' => 'app', 'namespace' => 'App\Http\Controllers\Backend','middleware'=>['auth']], function () {
//    Route::get('/dashbord', 'DashbordController')->name('dashbord');
//    Route::resource('roles', ' ');
//});
