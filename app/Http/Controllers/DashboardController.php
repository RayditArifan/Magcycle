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

        $latestDetail = DB::table('detail_siklus_maggot')
            ->select(
                'siklus_maggot_id',
                DB::raw('MAX(tanggal_monitoring) as tanggal_terbaru')
            )
            ->groupBy('siklus_maggot_id');

        $monitoringMaggot = DB::table('siklus_maggot as s')
            ->leftJoinSub($latestDetail, 'latest_detail', function ($join) {
                $join->on('s.id', '=', 'latest_detail.siklus_maggot_id');
            })
            ->leftJoin('detail_siklus_maggot as d', function ($join) {
                $join->on('s.id', '=', 'd.siklus_maggot_id')
                    ->on('latest_detail.tanggal_terbaru', '=', 'd.tanggal_monitoring');
            })
            ->leftJoin('status_siklus as st', 'd.status_siklus_id', '=', 'st.id')
            ->select(
                's.id',
                's.nama_batch',
                's.jumlah_masuk',
                's.tanggal_masuk',
                'd.tanggal_monitoring',
                'd.hari_ke',
                'st.status_siklus as status_label'
            )
            ->where('s.admin_id', session('user_id'))
            ->orderByRaw("CAST(REPLACE(LOWER(s.nama_batch), 'batch ', '') AS UNSIGNED) ASC")
            ->orderBy('s.id', 'asc')
            ->limit(4)
            ->get();

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
            ->whereDate('jadwal_pengambilan_sampah.tanggal_pengambilan', now()->toDateString())
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
