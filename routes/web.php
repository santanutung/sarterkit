<?php


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




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


Route::get('/test', function () {
    return setting('test1');
});



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['as' => 'login.', 'prefix' => 'login', 'namespace' => 'Auth'], function () {
    Route::get('/{provider}', [LoginController::class, 'redirectToProvider'])->name('provider');
    Route::get('/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('callback');
});


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('{slug}',[PageController::class, 'index'])->name('pages');

