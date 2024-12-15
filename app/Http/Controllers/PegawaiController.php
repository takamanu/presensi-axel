<?php

namespace App\Http\Controllers;

use App\Models\LokasiPresensi;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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

    public function pegawai(Request $request)
    {
        $tanggal_dari = $request->input('tanggal_dari', date('Y-m-d'));
        $tanggal_sampai = $request->input('tanggal_sampai', date('Y-m-d'));

        if (empty($request->tanggal_dari)) {
            $presensi = DB::select(
                "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
            FROM presensi
            JOIN pegawai ON presensi.id_pegawai = pegawai.id
            ORDER BY tanggal_masuk DESC"
            );
        } else {
            $presensi = DB::select(
                `SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi
            FROM presensi
            JOIN pegawai ON presensi.id_pegawai = pegawai.id
            WHERE tanggal_masuk BETWEEN ? AND ?
            ORDER BY tanggal_masuk DESC`,
                [$tanggal_dari, $tanggal_sampai]
            );
        }

        return view('pegawai.presensi.index', compact('presensi', 'tanggal_dari', 'tanggal_sampai'));
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
            return redirect()->route('pegawai.data_pegawai.index')->with('pesan', 'Data berhasil ditambahkan');
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
        if ($user->role == "admin") {
            return redirect()->route('admin.pegawai')->with('pesan', 'Data berhasil diubah');
        } elseif ($user->role == "supervisor") {
            return redirect()->route('pegawai.data_pegawai.index')->with('pesan', 'Data berhasil diubah');
        }
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
