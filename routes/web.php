<?php

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
Route::post('/login', [LoginController::class, 'verifyLogin']);
