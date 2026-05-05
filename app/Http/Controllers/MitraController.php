<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MitraController extends Controller
{
    public function beranda()
    {
        if (Session::get('role') !== 'mitra') {
            return redirect()->route('login');
        }

        $panduanSetor = DB::table('panduan_setor_sampah')
            ->orderBy('id_panduan')
            ->get();

        return view('mitra.beranda', [
            'username' => strtoupper(Session::get('username', 'MITRA')),
            'notificationCount' => 1,
            'panduanSetor' => $panduanSetor,
        ]);
    }
}
