<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Models\LokasiPresensi;

class AdminController extends Controller
{
    public function index()
    {
        $title = "Home";
        $userAktif = User::where('status', 'Aktif')->count();
        return view('admin.index', [
            'users' => $userAktif,
            'title' => $title,
        ]);
    }

    public function jabatan()
    {
        $title = "Data Jabatan";
        $jabatan = Jabatan::all();
        return view('admin.jabatan.index', [
            'jabatans' => $jabatan,
            'title' => $title,
        ]);
    }

    public function addJabatan()
    {
        $title = "Tambah Data Jabatan";
        return view('admin.jabatan.tambah', [
            'title' => $title
        ]);
    }

    public function storeJabatan(Request $request)
    {
        $validated = $request->validate([
            'jabatan' => 'required',
        ]);

        Jabatan::create([
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('admin.jabatan')->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function editJabatan($id)
    {
        $title = "Edit Data Jabatan";
        $jabatan = Jabatan::where('id', $id)->first();
        return view('admin.jabatan.edit', [
            'jabatans' => $jabatan,
            'title' => $title
        ]);
    }

    public function updateJabatan(Request $request, $id)
    {
        $validated = $request->validate([
            'jabatan' => 'required',
        ]);
        $jabatan = Jabatan::where('id', $id)->first();
        $jabatan->update([
            'jabatan' => $validated['jabatan'],
        ]);
        return redirect()->route('admin.jabatan')->with('success', 'Jabatan berhasil diubah');
    }

    public function destroyJabatan($id)
    {
        $jabatan = Jabatan::where('id', $id)->first();
        $jabatan->delete();
        return redirect()->route('admin.jabatan')->with('success', 'Jabatan berhasil dihapus');
    }

    public function lokasiPresensi()
    {
        $title = "Data Lokasi Presensi";
        $lokasi = LokasiPresensi::all();
        return view('admin.lokasi_presensi.index', [
            'title' => $title,
            'lokasi' => $lokasi
        ]);
    }

    public function addLokasi()
    {
        $title = "Tambah Lokasi Presensi";
        return view('admin.lokasi_presensi.tambah', [
            'title' => $title
        ]);
    }

    public function storeLokasi(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required',
            'alamat_lokasi' => 'required',
            'tipe_lokasi' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required',
            'zona_waktu' => 'required',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
        ]);

        LokasiPresensi::create([
            'nama_lokasi' => $request->nama_lokasi,
            'alamat_lokasi' => $request->alamat_lokasi,
            'tipe_lokasi' => $request->tipe_lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'zona_waktu' => $request->zona_waktu,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang
        ]);

        return redirect()->route('admin.lokasi-presensi')->with('success', 'Lokasi Presensi berhasil ditambahkan');
    }
}
