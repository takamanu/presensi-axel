<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KetidakhadiranController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PresensiController;
use App\Models\Ketidakhadiran;
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
Route::post('/reset/password', [LoginController::class, 'updatePassword'])->name('updatePassword');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', function ($request, $next) {
    if (Auth::user()->role != 'admin' && Auth::user()->role != 'supervisor') {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
    return $next($request);
}], 'as' => 'admin.'], function () {

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
    Route::get('/detail-lokasi/{id}', [AdminController::class, 'detailLokasi'])->name('detail-lokasi');
    Route::get('/edit-lokasi/{id}', [AdminController::class, 'editLokasi'])->name('edit-lokasi');
    Route::post('/update-lokasi/{id}', [AdminController::class, 'updateLokasi'])->name('update-lokasi');
    Route::delete('/destroy-lokasi/{id}', [AdminController::class, 'destroyLokasi'])->name('destroy-lokasi');

    //Ketidakhadiran
    Route::get('/ketidakhadiran', [AdminController::class, 'ketidakhadiran'])->name('ketidakhadiran');
    Route::get('/detail-ketidakhadiran/{id}', [AdminController::class, 'detailKetidakhadiran'])->name('detail-ketidakhadiran');
    Route::post('/update-ketidakhadiran/{id}', [AdminController::class, 'updateKetidakhadiran'])->name('update-ketidakhadiran');
    Route::get('/download-ketidakhadiran/{id}', [AdminController::class, 'downloadFile'])->name('download-ketidakhadiran');

    //Profile
    Route::get('/change-password', [AdminController::class, 'changePassword'])->name('change-password');
    Route::post('/update-password', [AdminController::class, 'updatePassword'])->name('update-password');
    Route::get('/profile-admin', [AdminController::class, 'profileAdmin'])->name('profile-admin');

    //Pegawai
    Route::get('/pegawai', [AdminController::class, 'pegawai'])->name('pegawai');
    Route::get('/add-pegawai', [AdminController::class, 'addPegawai'])->name('add-pegawai');
    Route::get('/edit-pegawai/{id}', [AdminController::class, 'editPegawai'])->name('edit-pegawai');
    Route::get('/detail-pegawai/{id}', [AdminController::class, 'detailPegawai'])->name('detail-pegawai');
    Route::delete('/destroy-pegawai/{id}', [AdminController::class, 'destroyPegawai'])->name('destroy-pegawai');
});



Route::get('pegawai/store', [PegawaiController::class, 'store'])->name('clients.store');
Route::get('data_pegawai', [PegawaiController::class, 'index'])->name('supervisor.data_pegawai.index');
Route::get('data_pegawai/tambah', [PegawaiController::class, 'create'])->name('supervisor.data_pegawai.create');

Route::get('data_pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('supervisor.data_pegawai.edit');
Route::get('data_pegawai/{id}/destroy', [PegawaiController::class, 'data_pegawai_destroy'])->name('supervisor.data_pegawai.destroy');

Route::post('pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::post('pegawai/update', [PegawaiController::class, 'update'])->name('pegawai.update.fixed');
Route::post('presensi/masuk', [PresensiController::class, 'masuk'])->name('presensi.masuk');
Route::post('presensi/keluar', [PresensiController::class, 'keluar'])->name('presensi.keluar');
Route::post('presensi/export', [PresensiController::class, 'export'])->name('presensi.export');
Route::resource('pegawai', PegawaiController::class);

Auth::routes();

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


Route::prefix('home-pegawai')->middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('pegawai.index');
    })->name('home-pegawai.index');

    Route::get('/masuk', function () {
        return view('pegawai.masuk')->with([
            'authUser' => Auth::user(),
            'authUserId' => Auth::id(),
        ]);
    });

    Route::get('/keluar', function () {
        return view('pegawai.keluar');
    });

    Route::get('/presensi', [PegawaiController::class, 'pegawai']);

    Route::get('/ketidakhadiran', [PegawaiController::class, 'ketidakhadiran'])->name('home-pegawai.ketidakhadiran');
    Route::get('/ketidakhadiran/create', [PegawaiController::class, 'createKetidakhadiran'])->name('home-pegawai.create-ketidakhadiran');
    Route::post('/ketidakhadiran/store', [KetidakhadiranController::class, 'store'])->name('home-pegawai.store');
    Route::get('/detail-ketidakhadiran/{id}', [PegawaiController::class, 'detailKetidakhadiran'])->name('pegawai.detail-ketidakhadiran');
    Route::post('/update-ketidakhadiran/{id}', [PegawaiController::class, 'updateKetidakhadiran'])->name('pegawai.update-ketidakhadiran');
    Route::get('/download-ketidakhadiran/{id}', [PegawaiController::class, 'downloadFile'])->name('pegawai.download-ketidakhadiran');
    Route::delete('/delete-ketidakhadiran/{id}', [KetidakhadiranController::class, 'destroy'])->name('pegawai.delete-ketidakhadiran');

    Route::get('/profile', [PegawaiController::class, 'profile']);

    Route::get('/profile/change-password', function () {
        return view('pegawai.profile.changepassword');
    });
});

Route::prefix('home-supervisor')->middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('supervisor.index');
    })->name('home-supervisor.index');

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

        Route::get('/edit/{id}', [PegawaiController::class, 'show'])->name('supervisor.data_pegawai.edit');

        Route::get('/detail/{id}', [PegawaiController::class, 'data_pegawai_show'])->name('supervisor.data_pegawai.show');
    });
});

Route::get('/ketidakhadiran', [KetidakhadiranController::class, 'index']);
Route::get('/ketidakhadiran/detail', [KetidakhadiranController::class, 'show']);
