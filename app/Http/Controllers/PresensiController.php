<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\LokasiPresensi;
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

        $user = DB::selectOne("SELECT * FROM users WHERE id = ?", [$request->id]);

        // Check if the user was found
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Retrieve the pegawai_id from the result
        $id_pegawai = $user->id_pegawai;

        // Check if the pegawai_id exists
        if (!$id_pegawai) {
            return response()->json(['message' => 'Pegawai id not found'], 500);
        }

        $fileName = 'foto/masuk_' . now()->format('Y-m-d_H-i-s') . '.png';

        if (Storage::put($fileName, $decodedPhoto)) {
            $result = DB::table('presensi')->insert([
                // 'id_pegawai' => $request->id,
                'id_pegawai' => $id_pegawai,
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

    public function export(Request $request)
    {
        // $id = Auth::id();
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;
        // dd($tanggal_dari);

        $presensi = Presensi::where('id_pegawai', 2)
            ->whereBetween('tanggal_masuk', [$tanggal_dari, $tanggal_sampai])
            ->orderByDesc('tanggal_masuk')
            ->get();

        $lokasi_presensi = Session::get('lokasi_presensi');
        $lokasi = LokasiPresensi::where('nama_lokasi', 'Kantor Pusat')->first();
        // $lokasi = LokasiPresensi::where('nama_lokasi', $lokasi_presensi)->first();

        $jam_masuk_kantor = date('H:i:s', strtotime($lokasi->jam_masuk));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'REKAP PRESENSI');
        $sheet->setCellValue('A2', 'Tanggal Awal');
        $sheet->setCellValue('A3', 'Tanggal Akhir');
        $sheet->setCellValue('C2', $tanggal_dari);
        $sheet->setCellValue('C3', $tanggal_sampai);
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'TANGGAL MASUK');
        $sheet->setCellValue('C5', 'JAM MASUK');
        $sheet->setCellValue('D5', 'TANGGAL KELUAR');
        $sheet->setCellValue('E5', 'JAM KELUAR');
        $sheet->setCellValue('F5', 'TOTAL JAM KERJA');
        $sheet->setCellValue('G5', 'TOTAL JAM TERLAMBAR');

        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');

        $no = 1;
        $row = 6;

        foreach ($presensi as $data) {
            $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($data->tanggal_masuk . ' ' . $data->jam_masuk));
            $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($data->tanggal_keluar . ' ' . $data->jam_keluar));

            $timestamp_masuk = strtotime($jam_tanggal_masuk);
            $timestamp_keluar = strtotime($jam_tanggal_keluar);

            $selisih = $timestamp_keluar - $timestamp_masuk;
            $total_jam_kerja = floor($selisih / 3600);
            $selisih -= $total_jam_kerja * 3600;
            $selisih_menit_kerja = floor($selisih / 60);

            $jam_masuk = date('H:i:s', strtotime($data->jam_masuk));
            $timestamp_jam_masuk_real = strtotime($jam_masuk);
            $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);

            $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;
            $total_jam_terlambat = floor($terlambat / 3600);
            $terlambat -= $total_jam_terlambat * 3600;
            $selisih_menit_terlambat = floor($terlambat / 60);

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $data->tanggal_masuk);
            $sheet->setCellValue('C' . $row, $data->jam_masuk);
            $sheet->setCellValue('D' . $row, $data->tanggal_keluar);
            $sheet->setCellValue('E' . $row, $data->jam_keluar);
            $sheet->setCellValue('F' . $row, $total_jam_kerja . ' Jam ' . $selisih_menit_kerja . ' Menit');
            $sheet->setCellValue('G' . $row, $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit');

            $no++;
            $row++;
        }

        return response()->stream(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="Laporan_Presensi.xlsx"',
                'Cache-Control' => 'max-age=0',
            ]
        );
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
