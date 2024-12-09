<?php

namespace App\Http\Controllers;

use App\Models\Ketidakhadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KetidakhadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataKetidakhadiran = Ketidakhadiran::where('id_pegawai', 16)
            ->orderBy('id', 'desc')
            ->get();

        dd($dataKetidakhadiran);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status_pengajuan' => 'required|string',
        ]);

        $file = $request->file('file');
        $filePath = null;

        if ($file) {
            $filePath = $file->store('file_ketidakhadiran', 'public');
        }

        Ketidakhadiran::create([
            // 'id_pegawai' => Auth::id(),
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'deskripsi' => $request->deskripsi,
            'file' => $filePath,
            'status_pengajuan' => $request->status_pengajuan,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ketidakhadiran $ketidakhadiran)
    {
        $pegawai = DB::selectOne("
            SELECT 
                users.id_pegawai, users.username, users.password, users.status, users.role, pegawai.*
            FROM users 
            JOIN pegawai ON users.id_pegawai = pegawai.id 
            WHERE pegawai.id = ?", [16]);

        dd($pegawai);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ketidakhadiran $ketidakhadiran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ketidakhadiran $ketidakhadiran)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status_pengajuan' => 'required|string',
        ]);

        $ketidakhadiran = Ketidakhadiran::findOrFail($request->id);
        $file = $request->file('file');

        if ($file) {
            if ($ketidakhadiran->file) {
                Storage::disk('public')->delete($ketidakhadiran->file);
            }
            $filePath = $file->store('file_ketidakhadiran', 'public');
            $ketidakhadiran->file = $filePath;
        }

        $ketidakhadiran->update($request->only(['tanggal', 'keterangan', 'deskripsi', 'status_pengajuan']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ketidakhadiran = Ketidakhadiran::findOrFail($id);
        if ($ketidakhadiran->file) {
            Storage::disk('public')->delete($ketidakhadiran->file);
        }
        $ketidakhadiran->delete();
    }
}
