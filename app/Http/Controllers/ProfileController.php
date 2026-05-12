<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /* Profile Admin */

    public function adminIndex()
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $admin = DB::table('data_admin as da')
            ->leftJoin('kecamatan as kc',  'da.id_kecamatan', '=', 'kc.id_kecamatan')
            ->leftJoin('kab_kota as kt',   'kc.id_kota',      '=', 'kt.id_kota')
            ->leftJoin('provinsi as pv',   'kt.id_provinsi',  '=', 'pv.id_provinsi')
            ->select('da.*', 'kc.nama_kecamatan', 'kt.nama_kab_kota', 'pv.nama_provinsi', 'kc.id_kota')
            ->where('da.id', session('user_id'))
            ->first();

        if (!$admin) {
            return redirect()->route('dashboard')
                ->with('login_error', 'Data admin tidak ditemukan.');
        }

        return view('admin.profile-admin.profile', [
            'profile' => $admin,
            'notificationCount' => 1,
        ]);
    }

    public function adminEdit()
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $admin = DB::table('data_admin as da')
            ->leftJoin('kecamatan as kc', 'da.id_kecamatan', '=', 'kc.id_kecamatan')
            ->leftJoin('kab_kota as kt',  'kc.id_kota',      '=', 'kt.id_kota')
            ->leftJoin('provinsi as pv',  'kt.id_provinsi',  '=', 'pv.id_provinsi')
            ->select('da.*', 'kc.nama_kecamatan', 'kt.nama_kab_kota', 'pv.nama_provinsi', 'kc.id_kota')
            ->where('da.id', session('user_id'))
            ->first();

        if (!$admin) {
            return redirect()->route('dashboard')
                ->with('login_error', 'Data admin tidak ditemukan.');
        }

        $kabKotaList = DB::table('kab_kota')->orderBy('nama_kab_kota')->get();
        $kecamatanList = $admin->id_kota
            ? DB::table('kecamatan')->where('id_kota', $admin->id_kota)->orderBy('nama_kecamatan')->get()
            : collect();

        return view('admin.profile-admin.profile-edit', [
            'profile'       => $admin,
            'kabKotaList'   => $kabKotaList,
            'kecamatanList' => $kecamatanList,
            'notificationCount' => 1,
        ]);
    }

    public function adminUpdate(Request $request)
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $admin = DB::table('data_admin')
            ->where('id', session('user_id'))
            ->first();

        if (!$admin) {
            return redirect()->route('dashboard')
                ->with('login_error', 'Data admin tidak ditemukan.');
        }

        if (
            trim($request->email ?? '') === '' ||
            trim($request->no_hp ?? '') === '' ||
            trim($request->old_password ?? '') === '' ||
            trim($request->alamat ?? '') === '' ||
            !$request->filled('id_kecamatan')
        ) {
            return back()
                ->withInput()
                ->with('error_popup', 'Semua field wajib diisi');
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return back()
                ->withInput()
                ->with('error_popup', "Email tidak valid.\nMasukkan email yang dapat dihubungi");
        }

        if (!preg_match('/^[0-9]+$/', $request->no_hp)) {
            return back()
                ->withInput()
                ->with('error_popup', "Nomor telepon tidak valid.\nNomor telepon hanya boleh angka");
        }

        if ($request->old_password !== $admin->password) {
            return back()
                ->withInput()
                ->with('error_popup', 'Password lama tidak sesuai');
        }

        if ($request->filled('password') && strlen($request->password) < 6) {
            return back()
                ->withInput()
                ->with('error_popup', 'Password baru minimal 6 karakter');
        }


        $data = [
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'alamat'        => $request->alamat,
            'id_kecamatan'  => $request->id_kecamatan,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        DB::table('data_admin')
            ->where('id', session('user_id'))
            ->update($data);

        return redirect()->route('admin.profile')
            ->with('success_popup', 'true');
    }

    /* Profile Mitra */

    public function mitraIndex()
    {
        if (!session()->has('user_id') || session('role') !== 'mitra') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai mitra terlebih dahulu.');
        }

        $mitra = DB::table('data_mitra as dm')
            ->leftJoin('kecamatan as kc', 'dm.id_kecamatan', '=', 'kc.id_kecamatan')
            ->leftJoin('kab_kota as kt',  'kc.id_kota',      '=', 'kt.id_kota')
            ->leftJoin('provinsi as pv',  'kt.id_provinsi',  '=', 'pv.id_provinsi')
            ->select('dm.*', 'kc.nama_kecamatan', 'kt.nama_kab_kota', 'pv.nama_provinsi', 'kc.id_kota')
            ->where('dm.id_mitra', session('user_id'))
            ->first();

        if (!$mitra) {
            return redirect('/beranda')
                ->with('login_error', 'Data mitra tidak ditemukan.');
        }

        return view('mitra.profile', [
            'mitra' => $mitra,
            'notificationCount' => 1,
        ]);
    }

    public function mitraEdit()
    {
        if (!session()->has('user_id') || session('role') !== 'mitra') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai mitra terlebih dahulu.');
        }

        $mitra = DB::table('data_mitra as dm')
            ->leftJoin('kecamatan as kc', 'dm.id_kecamatan', '=', 'kc.id_kecamatan')
            ->leftJoin('kab_kota as kt',  'kc.id_kota',      '=', 'kt.id_kota')
            ->leftJoin('provinsi as pv',  'kt.id_provinsi',  '=', 'pv.id_provinsi')
            ->select('dm.*', 'kc.nama_kecamatan', 'kt.nama_kab_kota', 'pv.nama_provinsi', 'kc.id_kota')
            ->where('dm.id_mitra', session('user_id'))
            ->first();

        if (!$mitra) {
            return redirect()->route('mitra.profile')
                ->with('login_error', 'Data mitra tidak ditemukan.');
        }

        $kabKotaList = DB::table('kab_kota')->orderBy('nama_kab_kota')->get();
        $kecamatanList = $mitra->id_kota
            ? DB::table('kecamatan')->where('id_kota', $mitra->id_kota)->orderBy('nama_kecamatan')->get()
            : collect();

        return view('mitra.profile-edit', [
            'mitra'         => $mitra,
            'kabKotaList'   => $kabKotaList,
            'kecamatanList' => $kecamatanList,
            'notificationCount' => 1,
        ]);
    }

    public function mitraUpdate(Request $request)
    {
        if (!session()->has('user_id') || session('role') !== 'mitra') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai mitra terlebih dahulu.');
        }

        $mitra = DB::table('data_mitra')
            ->where('id_mitra', session('user_id'))
            ->first();

        if (!$mitra) {
            return redirect()->route('mitra.profile')
                ->with('login_error', 'Data mitra tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'username'     => 'required|string|max:100',
            'email'        => 'required|email|max:150',
            'no_hp'        => 'required|regex:/^[0-9]+$/|max:20',
            'old_password' => 'required|string',
            'password'     => 'nullable|string|min:6|max:100',
            'alamat'       => 'required|string|max:255',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('popup', 'invalid');
        }

        $usernameSudahDipakai = DB::table('data_mitra')
            ->where('username', $request->username)
            ->where('id_mitra', '!=', $mitra->id_mitra)
            ->exists();

        if ($usernameSudahDipakai) {
            return back()
                ->withInput()
                ->with('popup', 'registered');
        }

        if (trim($request->old_password ?? '') === '') {
            return back()
                ->withInput()
                ->with('popup', 'password_required');
        }

        if ($request->old_password !== $mitra->password) {
            return back()
                ->withInput()
                ->with('popup', 'wrong_password');
        }

        if ($request->filled('password') && strlen($request->password) < 6) {
            return back()
                ->withInput()
                ->with('popup', 'password_min');
        }

        $data = [
            'username'      => $request->username,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'alamat'        => $request->alamat,
            'id_kecamatan'  => $request->id_kecamatan,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        DB::table('data_mitra')
            ->where('id_mitra', session('user_id'))
            ->update($data);

        session()->put('username', $request->username);

        return redirect()->route('mitra.profile')
            ->with('success_popup', 'true');
    }

    /* Admin Kelola Profile Mitra*/

    public function adminMitraIndex()
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $mitras = DB::table('data_mitra')
            ->join('status_akun', 'data_mitra.id_status', '=', 'status_akun.id_status')
            ->select(
                'data_mitra.id_mitra',
                'data_mitra.username',
                'data_mitra.email',
                'data_mitra.no_hp',
                'data_mitra.alamat',
                'data_mitra.id_status',
                'status_akun.status_akun'
            )
            ->orderBy('data_mitra.id_mitra', 'asc')
            ->paginate(8);

        return view('admin.profile-admin.mitra-profiles', [
            'mitras' => $mitras,
            'notificationCount' => 1,
        ]);
    }

    public function adminMitraShow($id_mitra)
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $mitra = DB::table('data_mitra')
            ->join('status_akun', 'data_mitra.id_status', '=', 'status_akun.id_status')
            ->select(
                'data_mitra.id_mitra',
                'data_mitra.username',
                'data_mitra.password',
                'data_mitra.email',
                'data_mitra.no_hp',
                'data_mitra.alamat',
                'data_mitra.id_status',
                'status_akun.status_akun'
            )
            ->where('data_mitra.id_mitra', $id_mitra)
            ->first();

        if (!$mitra) {
            return redirect()->route('admin.mitra.profiles')
                ->with('error', 'Data mitra tidak ditemukan.');
        }

        $statuses = DB::table('status_akun')
            ->whereIn('status_akun', ['aktif', 'banned'])
            ->orderBy('id_status')
            ->get();

        return view('admin.profile-admin.mitra-profiles-detail', [
            'mitra' => $mitra,
            'statuses' => $statuses,
            'notificationCount' => 1,
        ]);
    }

    public function adminMitraUpdateStatus(Request $request, $id_mitra)
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $request->validate([
            'id_status' => 'required|exists:status_akun,id_status',
        ]);

        DB::table('data_mitra')
            ->where('id_mitra', $id_mitra)
            ->update([
                'id_status' => $request->id_status,
            ]);

        return redirect()
            ->route('admin.mitra.profiles.show', $id_mitra)
            ->with('success_popup', 'true');
    }
}
