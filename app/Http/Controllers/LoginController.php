<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function verifyLogin(Request $request)
    {
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
                    return redirect('/dashboard');
                } elseif (Auth::user()->role == "pegawai") {
                    return redirect('/home-pegawai');
                } elseif (Auth::user()->role == "supervisor") {
                    return "Supervisor";
                }
            } else {
                return redirect('/login');
            }
        } else {
            return redirect('/login');
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/login');
    }
}
