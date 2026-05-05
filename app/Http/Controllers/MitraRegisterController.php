<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MitraRegisterController extends Controller
{
    public function create()
    {
        return view('auth.register-mitra');
    }

    public function store(Request $request)
    {
        if (
            trim($request->username ?? '') === '' ||
            trim($request->email ?? '') === '' ||
            trim($request->no_hp ?? '') === '' ||
            trim($request->password ?? '') === '' ||
            trim($request->alamat ?? '') === '' ||
            trim($request->provinsi ?? '') === '' ||
            trim($request->kab_kota ?? '') === '' ||
            trim($request->kecamatan ?? '') === ''
        ) {
            return back()->withInput()->with('popup', 'required');
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return back()->withInput()->with('popup', 'email_invalid');
        }

        if (!preg_match('/^[0-9]+$/', $request->no_hp)) {
            return back()->withInput()->with('popup', 'phone_invalid');
        }

        if (strlen($request->password) < 6) {
            return back()->withInput()->with('popup', 'password_invalid');
        }

        if (
            preg_match('/[0-9]/', $request->provinsi) ||
            preg_match('/[0-9]/', $request->kab_kota) ||
            preg_match('/[0-9]/', $request->kecamatan)
        ) {
            return back()->withInput()->with('popup', 'wilayah_invalid');
        }

        $usernameSudahAda = DB::table('data_mitra')
            ->where('username', $request->username)
            ->exists();

        if ($usernameSudahAda) {
            return back()->withInput()->with('popup', 'registered');
        }

        $lastNumber = DB::table('data_mitra')
            ->selectRaw("MAX(CAST(SUBSTRING(id_mitra, 2) AS UNSIGNED)) as last_number")
            ->value('last_number') ?? 0;

        $idMitra = 'M' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);

        $alamatLengkap = implode(', ', [
            $request->alamat,
            $request->kecamatan,
            $request->kab_kota,
            $request->provinsi,
        ]);

        DB::table('data_mitra')->insert([
            'id_mitra'  => $idMitra,
            'username'  => $request->username,
            'email'     => $request->email,
            'no_hp'     => $request->no_hp,
            'password'  => $request->password,
            'alamat'    => $alamatLengkap,
            'id_status' => 'ST01',
        ]);

        return redirect()
            ->route('mitra.register')
            ->with('popup', 'success');
    }
}
