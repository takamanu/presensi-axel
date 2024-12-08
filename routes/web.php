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
<<<<<<< Updated upstream
Route::post('presensi/masuk', [PresensiController::class, 'masuk'])->name('presensi.masuk');
Route::post('presensi/keluar', [PresensiController::class, 'keluar'])->name('presensi.keluar');
Route::post('presensi/export', [PresensiController::class, 'export'])->name('presensi.export');
Route::resource('pegawai', PegawaiController::class);
=======
Route::resource('pegawai', PegawaiController::class);

>>>>>>> Stashed changes

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('home-pegawai')->group(function () {

    // Home Route for Pegawai
    Route::get('/', function () {
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

<<<<<<< Updated upstream
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
=======
    // Presensi Routes for Pegawai
    Route::get('/presensi', function () {
        return view('pegawai.presensi.index');
    });

    // Profile Route for Pegawai
    Route::get('/profile', function () {
        return view('pegawai.profile.view');
    });
});

Route::prefix('home-supervisor')->group(function () {

    // Supervisor Home Route
    Route::get('/', function () {
        return view('supervisor.index');
    });

    // Presensi Routes
    Route::get('/presensi', function () {
        return view('supervisor.presensi.index');
    });

    // Profile Routes
    Route::get('/profile', function () {
        return view('supervisor.profile.view');
    });

    Route::get('/profile/change-password', function () {
        return view('supervisor.profile.changepassword');
    });

    // Data Pegawai Routes
    Route::prefix('data-pegawai')->group(function () {
        Route::get('/', function () {
            return view('supervisor.pegawai.index');
        });

        Route::get('/tambah', function () {
            return view('supervisor.pegawai.create');
        });

        Route::get('/edit/{id}', function ($id) {
            return view('supervisor.pegawai.edit', compact('id'));
        });

        Route::get('/detail/{id}', function ($id) {
            return view('supervisor.pegawai.view', compact('id'));
        });
    });
});

>>>>>>> Stashed changes

Route::post('/login', [LoginController::class, 'verifyLogin']);
Route::post('/reset/password', [LoginController::class, 'updatePassword'])->name('user.updatePassword');
