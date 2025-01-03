<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function verifyLogin(Request $request)
    {
        // dd($request->all());
        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt(['username' => $username, 'password' => $password])) {

            if (Auth::user()->status == "Aktif") {
                $user = Auth::user();
                session::put('Id', $user->id);
                session::put('Role', $user->role);
                session::put('NIP', $user->pegawai->nip);
                session::put('Nama', $user->pegawai->nama);
                session::put('Jabatan', $user->pegawai->jabatan);
                session::put('Lokasi Presensi', $user->pegawai->lokasi_presensi);

                if (Auth::user()->role == "admin") {
                    return redirect()->route('admin.dashboard');
                } else if (Auth::user()->role == "pegawai") {
                    return redirect()->route('home-pegawai.index');
                } else if (Auth::user()->role == "supervisor") {
                    return redirect()->route('home-supervisor.index');
                    // return redirect()->route('home-supervisor.index');
                }
            } else {
                return redirect()->route('login')->with('akun', 'Akun anda tidak aktif');
            }
        } else {
            return redirect()->route('login')->with('error', 'Username atau password salah');
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_baru' => 'required|min:8',
            'ulangi_password_baru' => 'required|same:password_baru',
        ]);

        $user = User::where('id_pegawai', 1)->first();
        // $user = User::where('id_pegawai', auth()->user()->id_pegawai)->first();

        if ($user) {
            $user->timestamps = false;
            $user->password = Hash::make($request->password_baru);
            $user->save();
            $user->timestamps = true;

            Session::flash('berhasil', 'Password berhasil diubah');
            if (Auth::user()->role == "pegawai") {
                return redirect()->route('home-pegawai.index');
            } elseif (Auth::user()->role == "admin") {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role == "supervisor") {
                return redirect()->route('home-supervisor.index');
            }
        }

        Session::flash('validasi', 'Gagal mengubah password');
        return back();
    }
}
