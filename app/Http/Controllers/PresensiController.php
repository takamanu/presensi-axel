<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    public function show(Request $request)
    {

        //
    }

    /**
     * Display the specified resource.
     */
    public function masuk(Request $request)
    {
        $photoData = str_replace('data:image/jpeg;base64,', '', $request->input('photo'));
        $photoData = str_replace(' ', '+', $photoData);
        $decodedPhoto = base64_decode($photoData);

        $fileName = 'foto/masuk_' . now()->format('Y-m-d_H-i-s') . '.png';

        if (Storage::put($fileName, $decodedPhoto)) {
            $result = DB::table('presensi')->insert([
                // 'id_pegawai' => $request->id,
                'id_pegawai' => 11,
                'tanggal_masuk' => $request->tanggal_masuk,
                'jam_masuk' => $request->jam_masuk,
                'foto_masuk' => $fileName,
                'tanggal_keluar' => $request->tanggal_masuk,
                'jam_keluar' => $request->jam_masuk,
                'foto_keluar' => $fileName
            ]);

            if ($result) {
                Session::flash('berhasil', 'Presensi Masuk berhasil!');
                return response()->json(['message' => 'Presensi masuk berhasil'], 200);
            } else {
                Session::flash('gagal', 'Presensi masuk gagal');
                return response()->json(['message' => 'Presensi masuk gagal'], 500);
            }
        } else {
            return response()->json(['message' => 'Gagal menyimpan foto.'], 500);
        }
    }

    public function keluar(Request $request)
    {
        $photoData = str_replace('data:image/jpeg;base64,', '', $request->input('photo'));
        $photoData = str_replace(' ', '+', $photoData);
        $decodedPhoto = base64_decode($photoData);

        $fileName = 'foto/keluar_' . now()->format('Y-m-d_H-i-s') . '.png';

        if (Storage::put($fileName, $decodedPhoto)) {
            $idPresensi = 22;
            // $idPresensi = $request->id;
            $result = DB::table('presensi')
                ->where('id', $idPresensi)
                ->update([
                    'tanggal_keluar' => $request->tanggal_keluar,
                    'jam_keluar' => $request->jam_keluar,
                    'foto_keluar' => $fileName
                ]);

            if ($result) {
                Session::flash('berhasil', 'Presensi Masuk berhasil!');
                return response()->json(['message' => 'Presensi masuk berhasil'], 200);
            } else {
                Session::flash('gagal', 'Presensi masuk gagal');
                return response()->json(['message' => 'Presensi masuk gagal'], 500);
            }
        } else {
            return response()->json(['message' => 'Gagal menyimpan foto.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presensi $presensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presensi $presensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presensi $presensi)
    {
        //
    }
}
