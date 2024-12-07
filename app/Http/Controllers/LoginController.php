<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function verifyLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            if (Auth::user()->status == "Aktif") {
                if (Auth::user()->role == "admin") {
                    return redirect('/dashboard');
                } elseif (Auth::user()->role == "pegawai") {
                    return "Pegawai";
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
        Auth::logout();
        return redirect('/login');
    }
}
