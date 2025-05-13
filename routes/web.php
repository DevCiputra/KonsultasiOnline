<?php

use App\Http\Controllers\CategoryPolyclinicController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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



Route::group(['middleware' => ['auth', 'Admin']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('category', CategoryController::class);
    Route::resource('user', UserController::class);
    Route::resource('categoryPoly', CategoryPolyclinicController::class);
    Route::resource('role', RoleController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('dokter', DokterController::class);

    // Reservasi
    Route::put('/pasien/reservation/{id}', [App\Http\Controllers\PasienController::class, 'updateReservation'])
    ->name('pasien.updateReservation');

    Route::post('/dokter/{id}/jadwal', [App\Http\Controllers\DokterController::class, 'storeJadwal'])->name('dokter.storeJadwal');
    Route::post('/dokter/{id}/pendidikan', [App\Http\Controllers\DokterController::class, 'pendidikanDokter'])->name('dokter.pendidikanDokter');
    Route::post('/dokter/{id}/pengalaman', [App\Http\Controllers\DokterController::class, 'pengalamanDokter'])->name('dokter.pengalamanDokter');
    Route::post('/dokter/{id}/tindakan', [App\Http\Controllers\DokterController::class, 'tindakanMedis'])->name('dokter.tindakanMedis');

    // Delete Jadwal Dokter
    Route::delete('/dokter/jadwal/{id}', [App\Http\Controllers\DokterController::class, 'deleteJadwal'])->name('dokter.deleteJadwal');

    // Delete Pendidikan Dokter
    Route::delete('/dokter/pendidikan/{id}', [App\Http\Controllers\DokterController::class, 'deletePendidikan'])->name('dokter.deletePendidikan');


    // Delete Pengalaman Dokter
    Route::delete('/dokter/pengalaman/{id}', [App\Http\Controllers\DokterController::class, 'deletePengalaman'])->name('dokter.deletePengalaman');

    // Delete Tindakan Medis
    Route::delete('/dokter/medis/{id}', [App\Http\Controllers\DokterController::class, 'deleteMedis'])->name('dokter.deleteMedis');
});
