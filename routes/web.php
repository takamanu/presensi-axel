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
    //Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    //Master Data Jabatan
    Route::get('/jabatan', [AdminController::class, 'jabatan'])->name('jabatan');
    Route::get('/add-jabatan', [AdminController::class, 'addJabatan'])->name('add-jabatan');
    Route::post('/store-jabatan', [AdminController::class, 'storeJabatan'])->name('store-jabatan');
    Route::get('{id}/edit-jabatan', [AdminController::class, 'editJabatan'])->name('edit-jabatan');
    Route::post('/update-jabatan/{id}', [AdminController::class, 'updateJabatan'])->name('update-jabatan');
    Route::delete('/destroy-jabatan/{id}', [AdminController::class, 'destroyJabatan'])->name('destroy-jabatan');

    //Master Data Lokasi Presensi
    Route::get('/lokasi-presensi', [AdminController::class, 'lokasiPresensi'])->name('lokasi-presensi');
    Route::get('/add-lokasi', [AdminController::class, 'addLokasi'])->name('add-lokasi');
    Route::post('/store-lokasi', [AdminController::class, 'storeLokasi'])->name('store-lokasi');
    Route::get('/detail-lokasi/{id}', [AdminController::class, 'detailLokasi'])->name('detail-lokasi');
    Route::get('/edit-lokasi/{id}', [AdminController::class, 'editLokasi'])->name('edit-lokasi');
    Route::post('/update-lokasi/{id}', [AdminController::class, 'updateLokasi'])->name('update-lokasi');
    Route::delete('/destroy-lokasi/{id}', [AdminController::class, 'destroyLokasi'])->name('destroy-lokasi');

    //Ketidakhadiran
    Route::get('/ketidakhadiran', [AdminController::class, 'ketidakhadiran'])->name('ketidakhadiran');
    Route::get('/detail-ketidakhadiran/{id}', [AdminController::class, 'detailKetidakhadiran'])->name('detail-ketidakhadiran');
    Route::post('/update-ketidakhadiran/{id}', [AdminController::class, 'updateKetidakhadiran'])->name('update-ketidakhadiran');
    Route::get('/download-ketidakhadiran/{id}', [AdminController::class, 'downloadFile'])->name('download-ketidakhadiran');
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
