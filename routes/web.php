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

Route::get('pegawai/store', [PegawaiController::class, 'store'])->name('clients.store');
Route::post('presensi/masuk', [PresensiController::class, 'masuk'])->name('presensi.masuk');
Route::post('presensi/keluar', [PresensiController::class, 'keluar'])->name('presensi.keluar');
Route::resource('pegawai', PegawaiController::class);

Auth::routes();
Route::get(
    '/home-pegawai',
    function () {
        return view('pegawai.index');
    }
);

Route::get(
    '/home-pegawai/masuk',
    function () {
        return view('pegawai.masuk');
    }
);

Route::get(
    '/home-pegawai/keluar',
    function () {
        return view('pegawai.keluar');
    }
);

Route::get(
    '/home-pegawai/presensi',
    function () {
        return view('pegawai.presensi.index');
    }
);

Route::get(
    '/home-pegawai/profile',
    function () {
        return view('pegawai.profile.view');
    }
);

Route::get(
    '/home-pegawai/profile/change-password',
    function () {
        return view('pegawai.profile.changepassword');
    }
);

Route::post('/login', [LoginController::class, 'verifyLogin']);
Route::post('/logout', [LoginController::class, 'logout']);
//Admin
Route::get('/dashboard', [AdminController::class, 'index']);
