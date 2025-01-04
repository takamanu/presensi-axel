<?php

namespace App\Http\Controllers;

use App\Models\LokasiPresensi;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Ketidakhadiran;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pegawai::all();
        $results = DB::select("
            SELECT pegawai.id, pegawai.nip, pegawai.nama, username, pegawai.jabatan, role
            FROM users
            JOIN pegawai ON users.id_pegawai = pegawai.id
        ");

        // dd($results);
        return view('pegawai.index');
    }

    public function indexSupervisor()
    {
        // Pegawai::all();


        $tanggalHariIni = date('Y-m-d'); // Get today's date in 'YYYY-MM-DD' format

        $countPegawaiMasuk = DB::select('
    SELECT COUNT(*) as total
    FROM presensi
    WHERE tanggal_masuk = ?', [$tanggalHariIni]);

        $totalPegawaiMasuk = $countPegawaiMasuk[0]->total;

        $countPegawaiICS = DB::select('
    SELECT COUNT(*) as total
    FROM ketidakhadiran
    WHERE tanggal = ?', [$tanggalHariIni]);

        $totalPegawaiICS = $countPegawaiICS[0]->total;

        $totalPegawai = DB::table('pegawai')->count();

        $totalPegawaiTidakMasuk = $totalPegawai - $totalPegawaiMasuk;
        // $userAktif = User::where('status', 'Aktif')->count();


        // dd($results);
        // return view('pegawai.index');
        return view('supervisor.index', compact('totalPegawai', 'totalPegawaiMasuk', 'totalPegawaiTidakMasuk', 'totalPegawaiICS'));
    }

    public function homePegawai()
    {

        $idPegawai = Auth::user()->id_pegawai;

        $status_wh = "";

        $lokasiPresensi = DB::table('pegawai')
            ->join('lokasi_presensi', 'pegawai.lokasi_presensi', '=', 'lokasi_presensi.nama_lokasi')
            ->where('pegawai.id', $idPegawai)
            ->select('lokasi_presensi.jam_masuk', 'lokasi_presensi.jam_pulang', 'lokasi_presensi.latitude', 'lokasi_presensi.longitude')
            ->first();

        if (!$lokasiPresensi) {
            return response()->json(['message' => 'Location not found for this employee.']);
        }

        $currentTime = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s');

        $jamMasuk = $lokasiPresensi->jam_masuk;
        $jamPulang = $lokasiPresensi->jam_pulang;

        // return $currentTime . $jamPulang;

        if ($currentTime >= $jamMasuk && $currentTime < $jamPulang) {
            $status_wh = 'in_wh';
        } elseif ($currentTime >= $jamPulang) {
            $status_wh = 'after_wh';
        } else {
            $status_wh = 'before_wh';
        }

        $presensiMasuk = false;
        $presensiKeluar = false;
        $presensiMasukDate = "";
        $presensiKeluarDate = "";


        $results = DB::select("
            SELECT *
            FROM presensi
            WHERE id_pegawai = ?
            AND DATE(tanggal_masuk) = CURDATE()
            AND DATE(tanggal_keluar) = CURDATE()
            LIMIT 1
        ", [$idPegawai]);

        if (empty($results)) {
            return view('pegawai.index', compact('status_wh', 'presensiMasuk', 'presensiKeluar', 'presensiMasukDate', 'presensiKeluarDate', 'jamMasuk', 'jamPulang', 'lokasiPresensi'));
            // return response()->json(['message' => 'No data available for today.']);
        }

        $presensi = new Presensi();
        foreach ((array)$results[0] as $key => $value) {
            $presensi->$key = $value;
        }

        $presensiMasuk = true;
        $presensiMasukDate = $presensi->jam_masuk;

        if ($presensi->foto_keluar == "null") {

            return view('pegawai.index', compact('status_wh', 'presensiMasuk', 'presensiKeluar', 'presensiMasukDate', 'presensiKeluarDate', 'jamMasuk', 'jamPulang', 'lokasiPresensi'));
        }

        $presensiKeluar = true;
        $presensiKeluarDate = $presensi->jam_keluar;

        return view('pegawai.index', compact('status_wh', 'presensiMasuk', 'presensiKeluar', 'presensiMasukDate', 'presensiKeluarDate', 'jamMasuk', 'jamPulang', 'lokasiPresensi'));
    }


    public function profile(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        $userID = Auth::user()->id;
        // $id = 2;
        $result = DB::select("
            SELECT
                users.id_pegawai,
                users.username,
                users.status,
                users.role,
                pegawai.*
            FROM users
            JOIN pegawai ON users.id_pegawai = pegawai.id
            WHERE users.id = ?
        ", [$userID]);

        // return dd($result);


        return view('pegawai.profile.view', ['pegawai' => $result[0]]);
    }

    // public function pegawai(Request $request)
    // {
    //     $tanggal_dari = $request->input('tanggal_dari', date('Y-m-d'));
    //     $tanggal_sampai = $request->input('tanggal_sampai', date('Y-m-d'));

    //     $id_pegawai = Auth::user()->id_pegawai;

    //     if (empty($request->tanggal_dari)) {
    //         if (Auth::user()->role == "admin" || Auth::user()->role == "supervisor") {
    //             $presensi = DB::select(
    //                 "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
    //         FROM presensi
    //         JOIN pegawai ON presensi.id_pegawai = pegawai.id
    //         ORDER BY tanggal_masuk DESC"
    //             );
    //         } else {
    //             $presensi = DB::select(
    //                 "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
    //             FROM presensi
    //             JOIN pegawai ON presensi.id_pegawai = pegawai.id
    //             WHERE pegawai.id = ?
    //             ORDER BY tanggal_masuk DESC",
    //                 [$id_pegawai]
    //             );
    //         }
    //     } else {
    //         if (Auth::user()->role == "admin" || Auth::user()->role == "supervisor") {
    //             $presensi = DB::select(
    //                 "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
    //             FROM presensi
    //             JOIN pegawai ON presensi.id_pegawai = pegawai.id
    //             AND tanggal_masuk BETWEEN ? AND ?
    //             ORDER BY tanggal_masuk DESC",
    //                 [$tanggal_dari, $tanggal_sampai]
    //             );
    //         } else {
    //             $presensi = DB::select(
    //                 "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
    //             FROM presensi
    //             JOIN pegawai ON presensi.id_pegawai = pegawai.id
    //             WHERE pegawai.id = ?
    //             AND tanggal_masuk BETWEEN ? AND ?
    //             ORDER BY tanggal_masuk DESC",
    //                 [$id_pegawai, $tanggal_dari, $tanggal_sampai]
    //             );
    //         }
    //     }
    //     if ($tanggal_dari && $tanggal_sampai) {
    //         $formatted_date_range = \Carbon\Carbon::parse($tanggal_dari)->translatedFormat('d F Y') .
    //             ' - ' .
    //             \Carbon\Carbon::parse($tanggal_sampai)->translatedFormat('d F Y');
    //     } else {
    //         $formatted_date_range = \Carbon\Carbon::now()->translatedFormat('d F Y');
    //     }

    //     if (Auth::user()->role == "supervisor") {
    //         return view('supervisor.presensi.rekap-harian', compact('presensi', 'tanggal_dari', 'tanggal_sampai', 'formatted_date_range'));
    //     }

    //     return view('pegawai.presensi.index', compact('presensi', 'tanggal_dari', 'tanggal_sampai', 'formatted_date_range'));
    // }

    public function pegawai(Request $request)
    {
        // Determine default date range
        if (Auth::user()->role == "admin" || Auth::user()->role == "supervisor") {
            $tanggal_dari = $request->input('tanggal_dari', \Carbon\Carbon::now()->subDays(7)->format('Y-m-d'));
            $tanggal_sampai = $request->input('tanggal_sampai', \Carbon\Carbon::now()->format('Y-m-d'));
        } else {
            $tanggal_dari = $request->input('tanggal_dari', date('Y-m-d'));
            $tanggal_sampai = $request->input('tanggal_sampai', date('Y-m-d'));
        }

        $id_pegawai = Auth::user()->id_pegawai;

        // Query logic
        if (empty($request->tanggal_dari) && empty($request->tanggal_sampai)) {
            if (Auth::user()->role == "admin" || Auth::user()->role == "supervisor") {
                $presensi = DB::select(
                    "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
                FROM presensi
                JOIN pegawai ON presensi.id_pegawai = pegawai.id
                ORDER BY tanggal_masuk DESC"
                );
            } else {
                $presensi = DB::select(
                    "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
                FROM presensi
                JOIN pegawai ON presensi.id_pegawai = pegawai.id
                WHERE pegawai.id = ?
                ORDER BY tanggal_masuk DESC",
                    [$id_pegawai]
                );
            }
        } else {
            if (Auth::user()->role == "admin" || Auth::user()->role == "supervisor") {
                $presensi = DB::select(
                    "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
                FROM presensi
                JOIN pegawai ON presensi.id_pegawai = pegawai.id
                AND tanggal_masuk BETWEEN ? AND ?
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
        }

        // Format the date range for display
        $formatted_date_range = \Carbon\Carbon::parse($tanggal_dari)->translatedFormat('d F Y') .
            ' - ' .
            \Carbon\Carbon::parse($tanggal_sampai)->translatedFormat('d F Y');

        // View logic based on role
        if (Auth::user()->role == "supervisor") {
            return view('supervisor.presensi.rekap-harian', compact('presensi', 'tanggal_dari', 'tanggal_sampai', 'formatted_date_range'));
        }

        return view('pegawai.presensi.index', compact('presensi', 'tanggal_dari', 'tanggal_sampai', 'formatted_date_range'));
    }

    public function rekapHarian(Request $request)
    {
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
        return view('supervisor.presensi.rekap-harian', compact('presensi', 'formatted_date_range', 'tanggal_dari', 'tanggal_sampai'));
    }



    public function rekapBulanan(Request $request)
    {
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
            return redirect()->route('supervisor.rekap-bulanan', [
                'bulan' => $currentMonth,
                'tahun' => $currentYear
            ]);
        }

        // Pass data to the view
        return view('supervisor.presensi.rekap-bulanan', compact('presensi', 'formatted_date_range', 'bulan', 'tahun'));
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


    public function data_pegawai()
    {
        $pegawai = Pegawai::join('users', 'users.id_pegawai', '=', 'pegawai.id')
            ->select('users.id_pegawai', 'users.username', 'users.password', 'users.status', 'users.role', 'pegawai.*')
            ->get();

        return view('supervisor.pegawai.index', compact('pegawai'));
    }



    public function data_pegawai_show($id)
    {
        $pegawai = DB::select("
            SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai.*
            FROM users
            JOIN pegawai ON users.id_pegawai = pegawai.id
            WHERE pegawai.id = ?
        ", [$id]);

        $pegawai = $pegawai[0];

        return view('supervisor.pegawai.view', compact('pegawai'));
    }

    public function data_pegawai_destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        return redirect()->route('supervisor.data_pegawai.index')->with('pesan', 'Data berhasil dihapus');
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
        $user = Auth::user();
        // $rules = [
        //     'nama' => 'required|string',
        //     'jenis_kelamin' => 'required|string',
        //     'alamat' => 'required|string',
        //     'no_handphone' => 'required|string',
        //     'jabatan' => 'required|string',
        //     'username' => 'required|string',
        //     'role' => 'required|string',
        //     'status' => 'required|string',
        //     'lokasi_presensi' => 'required|string',
        //     'password' => 'required|string|confirmed', // Confirmed rule checks 'password' and 'password_confirmation'
        //     'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // Max size is in KB (10 MB = 10240 KB)
        // ];

        $results = DB::select("
            select nip from pegawai order by nip desc limit 1");

        if (!empty($results)) {
            $nip_db = $results[0]->nip;
            $nip_parts = explode("-", $nip_db);
            $no_baru = (int)$nip_parts[1] + 1;
            $nip_baru = "PEG-" . str_pad($no_baru, 4, "0", STR_PAD_LEFT);
        } else {
            $nip_baru = "PEG-0001";
        }

        $nama = $request->nama;
        $jenis_kelamin = $request->jenis_kelamin;
        $alamat = $request->alamat;
        $no_handphone = $request->no_handphone;
        $jabatan = $request->jabatan;
        $lokasi_presensi = $request->lokasi_presensi;

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_pegawai', 'public');
        }


        DB::insert(
            "
        INSERT INTO pegawai (nip, nama, jenis_kelamin, alamat, no_handphone, jabatan, lokasi_presensi, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $nip_baru,
                $nama,
                $jenis_kelamin,
                $alamat,
                $no_handphone,
                $jabatan,
                $lokasi_presensi,
                $fotoPath
            ]
        );

        $id_pegawai = Pegawai::where('nip', $nip_baru)->first()->id;


        DB::insert(
            "
            INSERT INTO users (id_pegawai, username, password, status, role)
            VALUES (?, ?, ?, ?, ?)",
            [
                $id_pegawai,
                $request->username,
                Hash::make($request->password),
                $request->status,
                $request->role
            ]
        );
        if ($user->role == "admin") {
            return redirect()->route('admin.pegawai')->with('pesan', 'Data berhasil ditambahkan');
        } elseif ($user->role == "supervisor") {
            return redirect()->route('supervisor.data_pegawai.index')->with('pesan', 'Data berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $lokasi = LokasiPresensi::all();

        $employee = DB::selectOne(
            "SELECT users.*, pegawai.* FROM users
             JOIN pegawai ON users.id_pegawai = pegawai.id
             WHERE pegawai.id = ?",
            [$id]
        );

        // dd($employee);

        $roles = ['admin', 'pegawai', 'supervisor'];
        $positions = DB::select("SELECT jabatan FROM jabatan ORDER BY jabatan ASC");
        $locations = DB::select("SELECT nama_lokasi FROM lokasi_presensi ORDER BY nama_lokasi ASC");

        return view('supervisor.pegawai.edit', [
            'employee' => $employee,
            'roles' => $roles,
            'positions' => array_column($positions, 'jabatan'),
            'locations' => array_column($locations, 'nama_lokasi'),
            'lokasi' => $lokasi
        ]);
        // return view('supervisor.pegawai.edit', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());
        // Prepare employee data
        $user = Auth::user();
        $employeeData = [
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'no_handphone' => $request->no_handphone,
            'jabatan' => $request->jabatan,
            'lokasi_presensi' => $request->lokasi_presensi,
        ];

        if ($request->hasFile('foto_baru')) {
            $filename = $request->file('foto_baru')->store('foto_pegawai', 'public');
            // $fotoPath = $request->file('foto')->store('foto_pegawai', 'public');
            $employeeData['foto'] = $filename;
        } else {
            $employeeData['foto'] = $request->foto_lama;
        }

        // Update employee data with raw query
        DB::update(
            "UPDATE pegawai SET nama = ?, jenis_kelamin = ?, alamat = ?, no_handphone = ?, jabatan = ?, lokasi_presensi = ?, foto = ? WHERE id = ?",
            array_merge(array_values($employeeData), [$request->id_pegawai])
        );

        // Prepare user data
        $userData = [
            'username' => $request->username,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        // Update user data with raw query
        DB::update(
            "UPDATE users SET username = ?, role = ?, status = ?, password = IF(? IS NOT NULL, ?, password) WHERE id_pegawai = ?",
            [
                $userData['username'],
                $userData['role'],
                $userData['status'],
                $request->password ? $userData['password'] : null,
                $request->password ? $userData['password'] : null,
                $request->id_pegawai,
            ]
        );
        // dd($request->all());
        return redirect()->route('supervisor.data_pegawai.index')->with('pesan', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Pegawai $pegawai)
    {
        $pegawai = Pegawai::find($id);
        if ($pegawai) {
            $pegawai->delete();
        } else {
            return response()->json(['message' => 'Pegawai not found.']);
        }
    }

    public function ketidakhadiran()
    {
        $userID = Auth::user()->id;
        // $id = 2;
        $result = DB::select("
            SELECT
                users.id_pegawai,
                users.username,
                users.status,
                users.role,
                pegawai.*
            FROM users
            JOIN pegawai ON users.id_pegawai = pegawai.id
            WHERE users.id = ?
        ", [$userID]);

        $title = "Data Ketidakhadiran";
        $ketidakhadiran = Ketidakhadiran::orderBy('id', 'desc')->where('id_pegawai', $result[0]->id_pegawai)->get();

        return view('pegawai.cuti.index', [
            'title' => $title,
            'ketidakhadiran' => $ketidakhadiran
        ]);
    }

    public function createKetidakhadiran()
    {
        // $title = "Data Ketidakhadiran";
        // $ketidakhadiran = Ketidakhadiran::orderBy('id', 'desc')->where('id_pegawai', $result[0]->id_pegawai)->get();
        return view('pegawai.cuti.create');
    }

    public function detailKetidakhadiran($id)
    {
        $title = "Detail Cuti";
        $ketidakhadiran = Ketidakhadiran::where('id', $id)->first();
        // return dd($ketidakhadiran);

        // Cek apakah data ada dan apakah bukti bernilai null
        if (!$ketidakhadiran || is_null($ketidakhadiran->file)) {
            // Redirect ke halaman tertentu dengan pesan
            return redirect()->route('home-pegawai.ketidakhadiran')
                ->with('error', 'Bukti tidak ditemukan atau belum tersedia.');
        }

        // Jika bukti ada, tampilkan detail
        return view('pegawai.cuti.detail', [
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
        return view('pegawai.cuti.index')->with('success', 'Ketidakhadiran berhasil diubah');
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



    // public function downloadFile($id)
    // {
    //     $ketidakhadiran = Ketidakhadiran::where('id', $id)->first();
    //     $filePath = public_path() . '/assets/ketidakhadiran/' . $ketidakhadiran->file;

    //     if (file_exists($filePath)) {
    //         return response()->download($filePath);
    //     }
    //     return redirect()->route('admin.ketidakhadiran')->with('error', 'File tidak ditemukan.');
    // }
}
