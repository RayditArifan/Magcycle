<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $admin = DB::table('data_admin')
            ->where('username', $request->username)
            ->first();

        if ($admin && $request->password === $admin->password) {
            Session::put('user_id', $admin->id);
            Session::put('username', $admin->username);
            Session::put('role', 'admin');

            return redirect()->route('dashboard');
        }

        $mitra = DB::table('data_mitra')
            ->where('username', $request->username)
            ->first();

        if ($mitra && $request->password === $mitra->password) {
            Session::put('user_id', $mitra->id_mitra);
            Session::put('username', $mitra->username);
            Session::put('role', 'mitra');

            return redirect('/beranda');
        }

        return back()
            ->withInput($request->only('username'))
            ->with('login_error', 'Username atau password salah.')
            ->with('login_error_popup', true);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()
            ->route('login')
            ->with('logout_success', true);
    }
}
