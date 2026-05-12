<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login');
        }

        $stocks = DB::table('stok_produk')
            ->orderBy('tanggal_input', 'desc')
            ->limit(3)
            ->get();

        $monitoringMaggot = collect();

        $jadwalSampah = DB::table('jadwal_pengambilan_sampah')
            ->join('data_mitra', 'jadwal_pengambilan_sampah.id_mitra', '=', 'data_mitra.id_mitra')
            ->join('status_pengambilan_sampah', 'jadwal_pengambilan_sampah.status_pengambilan_id', '=', 'status_pengambilan_sampah.id')
            ->select(
                'jadwal_pengambilan_sampah.id',
                'jadwal_pengambilan_sampah.tanggal_pengambilan',
                'jadwal_pengambilan_sampah.berat_sampah',
                'jadwal_pengambilan_sampah.alamat_pengambilan',
                'data_mitra.username',
                'status_pengambilan_sampah.status_pengambilan as status_label',
                DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan) as status_text')
            )
            ->whereIn(DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan)'), [
                'menunggu konfirmasi',
                'disetujui',
            ])
            ->orderByDesc('jadwal_pengambilan_sampah.tanggal_pengambilan')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'stocks' => $stocks,
            'monitoringMaggot' => $monitoringMaggot,
            'jadwalSampah' => $jadwalSampah,
            'notificationCount' => 1,
        ]);
    }
}
