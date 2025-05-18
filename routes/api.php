<?php

use App\Http\Controllers\API\CategoryPolyclinicController;
use App\Http\Controllers\API\CategoryProductController;
use App\Http\Controllers\API\DoctorProfileController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UlasanController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();


});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('v1/logout', [UserController::class, 'Logout']);
    Route::post('v1/updateProfile/{id}', [UserController::class, 'UpdateProfile']);


    // Profile Pasien Endpoint
    Route::post('v1/profilePasien', [ProfileController::class, 'ProfileAdd']);
    Route::post('v1/editProfilePasien/{id}', [ProfileController::class, 'editProfile']);
    Route::get('v1/FetchProfile', [ProfileController::class, 'FetchProfile']);


    // Profile Dokter Endpoint
    Route::post('v1/dokterProfile', [DoctorProfileController::class, 'PostDokterProfile']);
    Route::get('v1/FetchDokterProfile', [DoctorProfileController::class, 'FetchDokterProfile']);

    // Reservation
    Route::post('v1/PostReservation', [ReservationController::class, 'PostReservation']);
    Route::get('v1/FetchReservation', [ReservationController::class, 'FetchReservation']);
    Route::post('v1/updateReservation/{id}', [ReservationController::class, 'updateReservation']);

    // Transaction
    Route::post('v1/checkout', [TransactionController::class, 'checkout']);
    Route::post('v1/callbackTransaction/{id}', [TransactionController::class, 'CallBackTransaction']);
    Route::get('v1/transactionHistory', [TransactionController::class, 'transactionHistory']);

    // Category Product
    Route::get('v1/category', [CategoryProductController::class, 'category']);
    Route::get('v1/categoryPoly', [CategoryPolyclinicController::class, 'getCategoryPolyclinic']);

    // Ulasan
    Route::post('v1/ulasan', [UlasanController::class, 'PostUlasan']);
    Route::get('v1/ulasan', [UlasanController::class, 'FetchUlasan']);

    // Dokter Favorite
    Route::get('v1/dokterFavorite', [DoctorProfileController::class, 'FetchDokterFavorite']);

});


Route::post('v1/register', [UserController::class , 'Register']);
Route::post('v1/login', [UserController::class , 'Login']);
Route::post('v1/requestOTP', [UserController::class, 'requestPasswordResetOtp']);
Route::post('v1/resetPassword', [UserController::class, 'resetPasswordWithOtp']);
