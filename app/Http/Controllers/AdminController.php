<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Models\Ketidakhadiran;
use App\Models\LokasiPresensi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
        $title = "Detail Cuti";
        $ketidakhadiran = Ketidakhadiran::where('id', $id)->first();

        // Cek apakah data ada dan apakah bukti bernilai null
        if (!$ketidakhadiran || is_null($ketidakhadiran->file)) {
            // Redirect ke halaman tertentu dengan pesan
            return redirect()->route('admin.ketidakhadiran')
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
        return redirect()->route('admin.ketidakhadiran')->with('success', 'Status berhasil diubah');
    }

    public function downloadFile($id)
    {
        // Find the record
        $ketidakhadiran = Ketidakhadiran::find($id);

        if (!$ketidakhadiran) {

            return redirect()->action([self::class, 'ketidakhadiran'])
                ->with('error', 'Data tidak ditemukan.');
        }

        // Normalize file path using DIRECTORY_SEPARATOR
        $filePath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .  $ketidakhadiran->file);

        // return $filePath;
        // Check if the file exists
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->action([self::class, 'ketidakhadiran'])
            ->with('error', 'Data tidak ditemukan.');
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
        $lokasi = LokasiPresensi::all();
        $employee = User::where('id', $id)->first();
        return view('admin.pegawai.edit', [
            'employee' => $employee,
            'title' => $title,
            'lokasi' => $lokasi
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

    public function rekapBulanan(Request $request)
    {
        $title = "Rekap Presensi Bulanan";

        // Get current month and year
        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;

        // Retrieve query parameters for month and year
        $bulan = $request->input('bulan', $currentMonth);
        $tahun = $request->input('tahun', $currentYear);

        // Calculate the start and end dates for the selected month and year
        $tanggal_dari = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
        $tanggal_sampai = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');

        $id_pegawai = Auth::user()->id_pegawai;

        // Query logic based on role
        if (Auth::user()->role == "admin" || Auth::user()->role == "supervisor") {
            $presensi = DB::select(
                "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
                FROM presensi
                JOIN pegawai ON presensi.id_pegawai = pegawai.id
                WHERE tanggal_masuk BETWEEN ? AND ?
                ORDER BY tanggal_masuk DESC",
                [$tanggal_dari, $tanggal_sampai]
            );
        } else {
            $presensi = DB::select(
                "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
                FROM presensi
                JOIN pegawai ON presensi.id_pegawai = pegawai.id
                WHERE pegawai.id = ?
                AND tanggal_masuk BETWEEN ? AND ?
                ORDER BY tanggal_masuk DESC",
                [$id_pegawai, $tanggal_dari, $tanggal_sampai]
            );
        }

        // Format the date range for display
        $formatted_date_range = \Carbon\Carbon::parse($tanggal_dari)->translatedFormat('d F Y') .
            ' - ' .
            \Carbon\Carbon::parse($tanggal_sampai)->translatedFormat('d F Y');

        // Redirect with query parameters on first load
        if (!$request->has('bulan') || !$request->has('tahun')) {
            return redirect()->route('admin.rekap-bulanan', [
                'bulan' => $currentMonth,
                'tahun' => $currentYear
            ]);
        }

        // Pass data to the view
        return view('admin.rekap_presensi.rekap-bulanan', compact('presensi', 'title', 'formatted_date_range', 'bulan', 'tahun'));
    }

    public function exportRekapBulanan(Request $request)
    {
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Retrieve query parameters for month and year
        $bulan = $request->input('bulan', $currentMonth);
        $tahun = $request->input('tahun', $currentYear);

        // Calculate the start and end dates for the selected month and year
        $tanggal_dari = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
        $tanggal_sampai = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');

        $id_pegawai = Auth::user()->id_pegawai;

        // Query logic based on role
        if (Auth::user()->role == "admin" || Auth::user()->role == "supervisor") {
            $presensi = DB::select(
                "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
            FROM presensi
            JOIN pegawai ON presensi.id_pegawai = pegawai.id
            WHERE tanggal_masuk BETWEEN ? AND ?
            ORDER BY tanggal_masuk DESC",
                [$tanggal_dari, $tanggal_sampai]
            );
        } else {
            $presensi = DB::select(
                "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
            FROM presensi
            JOIN pegawai ON presensi.id_pegawai = pegawai.id
            WHERE pegawai.id = ?
            AND tanggal_masuk BETWEEN ? AND ?
            ORDER BY tanggal_masuk DESC",
                [$id_pegawai, $tanggal_dari, $tanggal_sampai]
            );
        }

        // Create the spreadsheet and set up the first sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header
        $sheet->setCellValue('A1', 'REKAP PRESENSI BULANAN');
        $sheet->setCellValue('A2', 'Bulan');
        $sheet->setCellValue('A3', 'Tahun');
        $sheet->setCellValue('C2', Carbon::parse($tanggal_dari)->translatedFormat('F Y'));
        $sheet->setCellValue('C3', $tahun);
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'TANGGAL MASUK');
        $sheet->setCellValue('C5', 'JAM MASUK');
        $sheet->setCellValue('D5', 'TANGGAL KELUAR');
        $sheet->setCellValue('E5', 'JAM KELUAR');
        $sheet->setCellValue('F5', 'TOTAL JAM KERJA');
        $sheet->setCellValue('G5', 'TOTAL JAM TERLAMBAR');

        // Merge header cells
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');

        // Initialize row and counter for presensi data
        $no = 1;
        $row = 6;

        $lokasi = LokasiPresensi::where('nama_lokasi', 'Kantor Pusat')->first();  // Adjust as needed
        $jam_masuk_kantor = date('H:i:s', strtotime($lokasi->jam_masuk));

        // Loop through each presensi record and add data to the sheet
        foreach ($presensi as $data) {
            $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($data->tanggal_masuk . ' ' . $data->jam_masuk));
            $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($data->tanggal_keluar . ' ' . $data->jam_keluar));

            // Calculate total work hours and late hours
            $timestamp_masuk = strtotime($jam_tanggal_masuk);
            $timestamp_keluar = strtotime($jam_tanggal_keluar);
            $selisih = $timestamp_keluar - $timestamp_masuk;
            $total_jam_kerja = floor($selisih / 3600);
            $selisih -= $total_jam_kerja * 3600;
            $selisih_menit_kerja = floor($selisih / 60);

            // Calculate late arrival
            $jam_masuk = date('H:i:s', strtotime($data->jam_masuk));
            $timestamp_jam_masuk_real = strtotime($jam_masuk);
            $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);
            $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;
            $total_jam_terlambat = floor($terlambat / 3600);
            $terlambat -= $total_jam_terlambat * 3600;
            $selisih_menit_terlambat = floor($terlambat / 60);

            // Set row data
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

        // Generate a random string and current time for the file name
        $randomString = strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5));
        $currentTime = Carbon::now()->format('Y-m-d');

        // Return the Excel file as a response for download
        return response()->stream(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="LRPB_' . $randomString . '_' . $currentTime . '.xlsx"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }

    public function rekapHarian(Request $request)
    {
        $title = 'Rekap Presensi Harian';

        // Get current date
        $currentDate = \Carbon\Carbon::now();
        $currentDateMinusSeven = \Carbon\Carbon::now()->subDays(7);

        // Set default 'tanggal_dari' to 7 days ago and 'tanggal_sampai' to today
        $tanggal_dari = $request->input('tanggal_dari', $currentDateMinusSeven->format('Y-m-d'));
        $tanggal_sampai = $request->input('tanggal_sampai', $currentDate->format('Y-m-d'));


        // If both 'bulan' and 'tahun' are missing from the query, redirect with the current month and year
        if (!$request->has('tanggal_dari') || !$request->has('tanggal_sampai')) {
            // return $tanggal_dari . " " . $tanggal_sampai;
            return redirect()->route('supervisor.rekap-harian', [
                'tanggal_dari' => $tanggal_dari,
                'tanggal_sampai' => $tanggal_sampai
            ]);
        }

        $id_pegawai = Auth::user()->id_pegawai;

        // Query logic based on role
        if (Auth::user()->role == "admin" || Auth::user()->role == "supervisor") {
            $presensi = DB::select(
                "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
            FROM presensi
            JOIN pegawai ON presensi.id_pegawai = pegawai.id
            WHERE tanggal_masuk BETWEEN ? AND ?
            ORDER BY tanggal_masuk DESC",
                [$tanggal_dari, $tanggal_sampai]
            );
        }

        // Format the date range for display
        $formatted_date_range = \Carbon\Carbon::parse($tanggal_dari)->translatedFormat('d F Y') .
            ' - ' .
            \Carbon\Carbon::parse($tanggal_sampai)->translatedFormat('d F Y');

        // Pass data to the view
        return view('admin.rekap_presensi.rekap-harian', compact('presensi', 'title', 'formatted_date_range', 'tanggal_dari', 'tanggal_sampai'));
    }

    public function exportRekapHarian(Request $request)
    {
        // Get the date range from the request
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

        // Validate the input dates
        // $validated = $request->validate([
        //     'tanggal_dari' => 'required|date',
        //     'tanggal_sampai' => 'required|date',
        // ]);

        // Retrieve the presensi data for a specific employee within the date range
        $presensi = Presensi::whereBetween('tanggal_masuk', [$tanggal_dari, $tanggal_sampai])
            ->orderByDesc('tanggal_masuk')
            ->get();

        // Get the location settings from the session or database
        $lokasi_presensi = Session::get('lokasi_presensi');
        $lokasi = LokasiPresensi::where('nama_lokasi', 'Kantor Pusat')->first();  // Adjust as needed
        $jam_masuk_kantor = date('H:i:s', strtotime($lokasi->jam_masuk));

        // Create the spreadsheet and set up the first sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header
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

        // Merge header cells
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');

        // Initialize row and counter for presensi data
        $no = 1;
        $row = 6;

        // Loop through each presensi record and add data to the sheet
        foreach ($presensi as $data) {
            $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($data->tanggal_masuk . ' ' . $data->jam_masuk));
            $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($data->tanggal_keluar . ' ' . $data->jam_keluar));

            // Calculate total work hours and late hours
            $timestamp_masuk = strtotime($jam_tanggal_masuk);
            $timestamp_keluar = strtotime($jam_tanggal_keluar);
            $selisih = $timestamp_keluar - $timestamp_masuk;
            $total_jam_kerja = floor($selisih / 3600);
            $selisih -= $total_jam_kerja * 3600;
            $selisih_menit_kerja = floor($selisih / 60);

            // Calculate late arrival
            $jam_masuk = date('H:i:s', strtotime($data->jam_masuk));
            $timestamp_jam_masuk_real = strtotime($jam_masuk);
            $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);
            $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;
            $total_jam_terlambat = floor($terlambat / 3600);
            $terlambat -= $total_jam_terlambat * 3600;
            $selisih_menit_terlambat = floor($terlambat / 60);

            // Set row data
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

        // Return the Excel file as a response for download

        $randomString = strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5));
        // Get the current time
        $currentTime = date('Y-m-d'); // Format: YYYY-MM-DD HH:MM:SS


        return response()->stream(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="LRPH_' . $randomString . $currentTime . '.xlsx"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }
}
