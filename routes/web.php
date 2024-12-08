<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/verifyLogin', [LoginController::class, 'verifyLogin'])->name('verifyLogin');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/reset/password', [LoginController::class, 'updatePassword'])->name('user.updatePassword');
//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
});

Auth::routes();
//Pegawai
// Route::group(['prefix' => 'pegawai', 'middleware' => ['auth'], 'as' => 'pegawai.'], function () {
//     Route::get('pegawai/store', [PegawaiController::class, 'store'])->name('clients.store');
//     Route::post('presensi/masuk', [PresensiController::class, 'masuk'])->name('presensi.masuk');
//     Route::post('presensi/keluar', [PresensiController::class, 'keluar'])->name('presensi.keluar');
//     Route::post('presensi/export', [PresensiController::class, 'export'])->name('presensi.export');
//     Route::resource('pegawai', PegawaiController::class);
// });

Route::get(
    '/home-pegawai/profile',
    [PegawaiController::class, 'profile']
);

Route::get(
    '/home-pegawai/profile/change-password',
    function () {
        return view('pegawai.profile.changepassword');
    }
);


Route::prefix('home-pegawai')->group(function () {

    // Home Route for Pegawai
    Route::get('/pegawai-index', function () {
        return view('pegawai.index');
    });

    // Masuk Route for Pegawai
    Route::get('/masuk', function () {
        return view('pegawai.masuk');
    });

    // Keluar Route for Pegawai
    Route::get('/keluar', function () {
        return view('pegawai.keluar');
    });
});
Route::get(
    '/home-pegawai/presensi',
    [PegawaiController::class, 'pegawai']
)->name('pegawai.index');
