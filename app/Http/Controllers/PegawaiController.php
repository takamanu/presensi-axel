<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        dd($results);
        return view('pegawai.index');
    }

    public function profile(Request $request)
    {
        $id = 2;
        $result = DB::select("
            SELECT 
                users.id_pegawai, 
                users.username, 
                users.status, 
                users.role, 
                pegawai.*
            FROM users 
            JOIN pegawai ON users.id_pegawai = pegawai.id 
            WHERE pegawai.id = ?
        ", [$id]);


        return view('pegawai.profile.view', ['pegawai' => $result[0]]);
    }

    public function pegawai(Request $request)
    {
        $tanggal_dari = $request->input('tanggal_dari', date('Y-m-d'));
        $tanggal_sampai = $request->input('tanggal_sampai', date('Y-m-d'));

        if (empty($request->tanggal_dari)) {
            $presensi = DB::select(
                "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi 
                FROM presensi 
                JOIN pegawai ON presensi.id_pegawai = pegawai.id 
                ORDER BY tanggal_masuk DESC",
            );
        } else {
            $tanggal_dari = $request->tanggal_dari;
            $tanggal_sampai = $request->tanggal_sampai;

            $presensi = DB::select(
                "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi 
                FROM presensi 
                JOIN pegawai ON presensi.id_pegawai = pegawai.id 
                WHERE tanggal_masuk BETWEEN ? AND ? 
                ORDER BY tanggal_masuk DESC",
                [$tanggal_dari, $tanggal_sampai]
            );
        };

        return view('pegawai.presensi.index', compact('presensi', 'tanggal_dari', 'tanggal_sampai'));
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

        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('profile/image');
        }

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

        // // Define custom messages
        // $messages = [
        //     'required' => "<i class='fa-solid fa-check'></i> :attribute wajib diisi",
        //     'password.confirmed' => "<i class='fa-solid fa-check'></i> Password tidak cocok",
        //     'foto.mimes' => "<i class='fa-solid fa-check'></i> Hanya file JPG, JPEG dan PNG yang diperbolehkan",
        //     'foto.max' => "<i class='fa-solid fa-check'></i> Ukuran file melebihi 10 MB",
        // ];

        // // Validate request
        // $validatedData = $request->validate($rules, $messages);

        // // If validation passes, proceed to handle the data
        // // e.g., save to the database or return a success response
        // return response()->json(['message' => 'Validation passed!']);

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

        // $nip = $request->input('nip');
        // $nama = $request->input('nama');
        // $jenis_kelamin = $request->input('jenis_kelamin');
        // $alamat = $request->input('alamat');
        // $no_handphone = $request->input('no_handphone');
        // $jabatan = $request->input('jabatan');
        // $lokasi_presensi = $request->input('lokasi_presensi');
        // $foto = $request->input('foto');

        $nip = $nip_baru;
        $nama = 'nama';
        $jenis_kelamin = 'kelamin';
        $alamat = 'alamat';
        $no_handphone = 'no_handphone';
        $jabatan = 'jabatan';
        $lokasi_presensi = 'lokasi_presensi';
        $foto = 'foto';
        $username = 'vito';
        $password = Hash::make($request->input('admin123'));
        $role = 'admin';
        $status = 'Aktif';

        DB::insert(
            "
        INSERT INTO pegawai (nip, nama, jenis_kelamin, alamat, no_handphone, jabatan, lokasi_presensi, foto) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $nip,
                $nama,
                $jenis_kelamin,
                $alamat,
                $no_handphone,
                $jabatan,
                $lokasi_presensi,
                $foto
            ]
        );

        $id_pegawai = Pegawai::where('nip', 'PD-0005')->first()->id;


        DB::insert(
            "
            INSERT INTO users (id_pegawai, username, password, status, role) 
            VALUES (?, ?, ?, ?, ?)",
            [
                $id_pegawai,
                $username,
                $password,
                $status,
                $role
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $pegawai)
    {
        //
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
    public function update(Request $request, Pegawai $pegawai)
    {
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

        // // Define custom messages
        // $messages = [
        //     'required' => "<i class='fa-solid fa-check'></i> :attribute wajib diisi",
        //     'password.confirmed' => "<i class='fa-solid fa-check'></i> Password tidak cocok",
        //     'foto.mimes' => "<i class='fa-solid fa-check'></i> Hanya file JPG, JPEG dan PNG yang diperbolehkan",
        //     'foto.max' => "<i class='fa-solid fa-check'></i> Ukuran file melebihi 10 MB",
        // ];

        // // Validate request
        // $validatedData = $request->validate($rules, $messages);

        // // If validation passes, proceed to handle the data
        // // e.g., save to the database or return a success response
        // return response()->json(['message' => 'Validation passed!']);

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

        // $nip = $request->input('nip');
        // $nama = $request->input('nama');
        // $jenis_kelamin = $request->input('jenis_kelamin');
        // $alamat = $request->input('alamat');
        // $no_handphone = $request->input('no_handphone');
        // $jabatan = $request->input('jabatan');
        // $lokasi_presensi = $request->input('lokasi_presensi');
        // $foto = $request->input('foto');

        $nip = $nip_baru;
        $nama = 'nama';
        $jenis_kelamin = 'kelamin';
        $alamat = 'alamat';
        $no_handphone = 'no_handphone';
        $jabatan = 'jabatan';
        $lokasi_presensi = 'lokasi_presensi';
        $foto = 'foto';
        $username = 'vito';
        $password = Hash::make($request->input('admin123'));
        $role = 'admin';
        $status = 'Aktif';

        DB::insert(
            "
        INSERT INTO pegawai (nip, nama, jenis_kelamin, alamat, no_handphone, jabatan, lokasi_presensi, foto) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $nip,
                $nama,
                $jenis_kelamin,
                $alamat,
                $no_handphone,
                $jabatan,
                $lokasi_presensi,
                $foto
            ]
        );

        $id_pegawai = Pegawai::where('nip', 'PD-0005')->first()->id;


        DB::insert(
            "
            INSERT INTO users (id_pegawai, username, password, status, role) 
            VALUES (?, ?, ?, ?, ?)",
            [
                $id_pegawai,
                $username,
                $password,
                $status,
                $role
            ]
        );
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
}
