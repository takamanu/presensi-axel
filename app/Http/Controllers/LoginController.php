<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function verifyLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            if (Auth::user()->status == "Aktif") {
                if (Auth::user()->role == "admin") {
                    return "Admin";
                } elseif (Auth::user()->role == "pegawai") {
                    return "Pegawai";
                } elseif (Auth::user()->role == "supervisor") {
                    return "Supervisor";
                }
            } else {
                return "tidak aktif";
            }
        } else {
            return "email atau password salah";
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_baru' => 'required|min:8',
            'ulangi_password_baru' => 'required|same:password_baru',
        ]);

        $user = User::where('id_pegawai', 2)->first();
        // $user = User::where('id_pegawai', auth()->user()->id_pegawai)->first();

        if ($user) {
            $user->timestamps = false;
            $user->password = Hash::make($request->password_baru);
            $user->save();
            $user->timestamps = true;

            Session::flash('berhasil', 'Password berhasil diubah');
            return redirect()->route('home');
        }

        Session::flash('validasi', 'Gagal mengubah password');
        return back();
    }
}
