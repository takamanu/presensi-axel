<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\LoginController;
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

Route::get('pegawai/store',[PegawaiController::class,'store'])->name('clients.store');
Route::resource('pegawai',PegawaiController::class);


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
