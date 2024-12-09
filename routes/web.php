<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KetidakhadiranController;
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

Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/jabatan', [AdminController::class, 'jabatan'])->name('jabatan');
    Route::get('/add-jabatan', [AdminController::class, 'addJabatan'])->name('add-jabatan');
    Route::post('/store-jabatan', [AdminController::class, 'storeJabatan'])->name('store-jabatan');
    Route::get('{id}/edit-jabatan', [AdminController::class, 'editJabatan'])->name('edit-jabatan');
    Route::post('/update-jabatan/{id}', [AdminController::class, 'updateJabatan'])->name('update-jabatan');
    Route::delete('/destroy-jabatan/{id}', [AdminController::class, 'destroyJabatan'])->name('destroy-jabatan');

    Route::get('/lokasi-presensi', [AdminController::class, 'lokasiPresensi'])->name('lokasi-presensi');
    Route::get('/add-lokasi', [AdminController::class, 'addLokasi'])->name('add-lokasi');
    Route::post('/store-lokasi', [AdminController::class, 'storeLokasi'])->name('store-lokasi');
});

Route::get('pegawai/store', [PegawaiController::class, 'store'])->name('clients.store');
Route::get('data_pegawai', [PegawaiController::class, 'index'])->name('supervisor.data_pegawai.index');
Route::get('data_pegawai/tambah', [PegawaiController::class, 'create'])->name('supervisor.data_pegawai.create');

Route::get('data_pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('supervisor.data_pegawai.edit');
Route::get('data_pegawai/{id}/destroy', [PegawaiController::class, 'data_pegawai_destroy'])->name('supervisor.data_pegawai.destroy');

Route::get('pegawai/store', [PegawaiController::class, 'store'])->name('clients.store');
Route::post('presensi/masuk', [PresensiController::class, 'masuk'])->name('presensi.masuk');
Route::post('presensi/keluar', [PresensiController::class, 'keluar'])->name('presensi.keluar');
Route::post('presensi/export', [PresensiController::class, 'export'])->name('presensi.export');
Route::resource('pegawai', PegawaiController::class);

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

    Route::get('/', function () {
        return view('pegawai.index');
    });

    Route::get('/masuk', function () {
        return view('pegawai.masuk');
    });

    Route::get('/keluar', function () {
        return view('pegawai.keluar');
    });

    Route::get('/presensi', [PegawaiController::class, 'pegawai']);

    Route::get(
        '/profile',
        [PegawaiController::class, 'profile']
    );

    Route::get(
        '/profile/change-password',
        function () {
            return view('pegawai.profile.changepassword');
        }
    );
});

Route::prefix('home-supervisor')->group(function () {

    Route::get('/', function () {
        return view('supervisor.index');
    });

    Route::get('/presensi', [PegawaiController::class, 'pegawai']);

    Route::get(
        '/profile',
        ([PegawaiController::class, 'profile'])
    );

    Route::get('/profile/change-password', function () {
        return view('supervisor.profile.changepassword');
    });

    Route::prefix('data-pegawai')->group(function () {
        Route::get('/', [PegawaiController::class, 'data_pegawai']);

        Route::get('/tambah', function () {
            return view('supervisor.pegawai.create');
        });

        Route::get('/edit/{id}', function ($id) {
            return view('supervisor.pegawai.edit', compact('id'));
        });

        Route::get('/detail/{id}', [PegawaiController::class, 'data_pegawai_show'])->name('supervisor.data_pegawai.show');
    });
});


Route::post('/login', [LoginController::class, 'verifyLogin']);
Route::post('/reset/password', [LoginController::class, 'updatePassword'])->name('user.updatePassword');


Route::get('/ketidakhadiran', [KetidakhadiranController::class, 'index']);
Route::get('/ketidakhadiran/detail', [KetidakhadiranController::class, 'show']);
