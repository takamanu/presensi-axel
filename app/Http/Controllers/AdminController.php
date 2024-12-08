<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $userAktif = User::where('status', 'Aktif')->count();
        return view('admin.index', [
            'users' => $userAktif,
        ]);
    }

    public function jabatan()
    {
        $jabatan = Jabatan::all();
        return view('admin.jabatan.index', [
            'jabatans' => $jabatan,
        ]);
    }

    public function addJabatan()
    {
        return view('admin.jabatan.tambah');
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
        $jabatan = Jabatan::where('id', $id)->first();
        return view('admin.jabatan.edit', [
            'jabatans' => $jabatan
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
}
