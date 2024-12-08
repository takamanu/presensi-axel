<?php

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
    return view('welcome');
});

Route::get('pegawai/store', [PegawaiController::class, 'store'])->name('clients.store');
Route::post('presensi/masuk', [PresensiController::class, 'masuk'])->name('presensi.masuk');
Route::post('presensi/keluar', [PresensiController::class, 'keluar'])->name('presensi.keluar');
Route::post('presensi/export', [PresensiController::class, 'export'])->name('presensi.export');
Route::resource('pegawai', PegawaiController::class);

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
    [PegawaiController::class, 'pegawai']
)->name('pegawai.index');

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

Route::post('/login', [LoginController::class, 'verifyLogin']);
Route::post('/reset/password', [LoginController::class, 'updatePassword'])->name('user.updatePassword');
