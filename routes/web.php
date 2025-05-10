<?php

use App\Http\Controllers\CategoryPolyclinicController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Models\CategoryPolyclinic;
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

Route::get('/', function () {
    return view('auth.login');
});

Route::match(["GET", "POST"], "/register", function () {
    return redirect("/auth.login");
})->name("register");

Auth::routes();



Route::group(['middleware' => ['auth', 'admin']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('category', CategoryController::class);
    Route::resource('user', UserController::class);
    Route::resource('categoryPoly', CategoryPolyclinicController::class);
});
