<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Models\Ketidakhadiran;
use App\Models\LokasiPresensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


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

    public function detailLokasi($id)
    {
        $title = "Detail Lokasi Presensi";
        $lokasi = LokasiPresensi::where('id', $id)->first();
        return view('admin.lokasi_presensi.detail', [
            'title' => $title,
            'lokasi' => $lokasi
        ]);
    }

    public function editLokasi($id)
    {
        $title = "Edit Lokasi Presensi";
        $lokasi = LokasiPresensi::where('id', $id)->first();
        return view('admin.lokasi_presensi.edit', [
            'title' => $title,
            'lokasi' => $lokasi
        ]);
    }

    public function updateLokasi(Request $request, $id)
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
        $lokasi = LokasiPresensi::where('id', $id)->first();
        $lokasi->update([
            'nama_lokasi' => $validated['nama_lokasi'],
            'alamat_lokasi' => $validated['alamat_lokasi'],
            'tipe_lokasi' => $validated['tipe_lokasi'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'radius' => $validated['radius'],
            'zona_waktu' => $validated['zona_waktu'],
            'jam_masuk' => $validated['jam_masuk'],
            'jam_pulang' => $validated['jam_pulang'],
        ]);
        return redirect()->route('admin.lokasi-presensi')->with('success', 'Lokasi Presensi berhasil diubah');
    }

    public function destroyLokasi($id)
    {
        $lokasi = LokasiPresensi::where('id', $id)->first();
        $lokasi->delete();
        return redirect()->route('admin.lokasi-presensi')->with('success', 'Lokasi Presensi berhasil dihapus');
    }

    public function ketidakhadiran()
    {
        $title = "Data Ketidakhadiran";
        $ketidakhadiran = Ketidakhadiran::orderBy('id', 'desc')->get();
        return view('admin.ketidakhadiran.index', [
            'title' => $title,
            'ketidakhadiran' => $ketidakhadiran
        ]);
    }

    public function detailKetidakhadiran($id)
    {
        $title = "Detail Ketidakhadiran";

        // Ambil data ketidakhadiran berdasarkan ID
        $ketidakhadiran = Ketidakhadiran::where('id', $id)->first();

        // Cek apakah data ada dan apakah bukti bernilai null
        if (!$ketidakhadiran || is_null($ketidakhadiran->bukti)) {
            // Redirect ke halaman tertentu dengan pesan
            return redirect()->route('admin.ketidakhadiran.index')
                ->with('error', 'Bukti tidak ditemukan atau belum tersedia.');
        }

        // Jika bukti ada, tampilkan detail
        return view('admin.ketidakhadiran.detail', [
            'title' => $title,
            'ketidakhadiran' => $ketidakhadiran
        ]);
    }


    public function updateKetidakhadiran(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pengajuan' => 'required',
        ]);
        $ketidakhadiran = Ketidakhadiran::where('id', $id)->first();
        $ketidakhadiran->update([
            'status_pengajuan' => $validated['status_pengajuan'],
        ]);
        return redirect()->route('admin.ketidakhadiran')->with('success', 'Ketidakhadiran berhasil diubah');
    }

    public function downloadFile($id)
    {
        $ketidakhadiran = Ketidakhadiran::where('id', $id)->first();
        $filePath = public_path() . '/assets/ketidakhadiran/' . $ketidakhadiran->file;

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }
        return redirect()->route('admin.ketidakhadiran')->with('error', 'File tidak ditemukan.');
    }

    public function changePassword()
    {
        $title = "Ubah Password";
        return view('admin.profile.change_password', [
            'title' => $title
        ]);
    }

    public function profileAdmin()
    {
        $title = "";
        $user = Auth::user();
        return view('admin.profile.profile', [
            'title' => $title,
            'user' => $user
        ]);
    }

    public function pegawai()
    {
        $title = "Data Pegawai";
        $pegawai = User::all();
        return view('admin.pegawai.index', [
            'title' => $title,
            'pegawai' => $pegawai
        ]);
    }

    public function addPegawai()
    {
        $title = "Tambah Data Pegawai";
        return view('admin.pegawai.tambah', [
            'title' => $title
        ]);
    }

    public function editPegawai($id)
    {
        $title = "Edit Data Pegawai";
        $employee = User::where('id', $id)->first();
        return view('admin.pegawai.edit', [
            'employee' => $employee,
            'title' => $title
        ]);
    }

    public function destroyPegawai($id)
    {
        $pegawai = User::where('id', $id)->first();

        if (!$pegawai) {
            return redirect()->route('admin.pegawai')->with('error', 'Pegawai tidak ditemukan');
        }
        if ($pegawai->user) {
            $pegawai->user->delete();
        }

        // Hapus data pegawai
        $pegawai->delete();

        $pegawai->delete();
        return redirect()->route('admin.pegawai')->with('success', 'Pegawai berhasil dihapus');
    }

    public function detailPegawai($id)
    {
        $title = "Detail Pegawai";
        $user = User::where('id', $id)->first();

        return view('admin.pegawai.detail', [
            'title' => $title,
            'user' => $user
        ]);
    }
}
