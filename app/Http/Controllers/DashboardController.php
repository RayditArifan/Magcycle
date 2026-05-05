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
        $jadwalSampah = collect();

        return view('dashboard', [
            'stocks' => $stocks,
            'monitoringMaggot' => $monitoringMaggot,
            'jadwalSampah' => $jadwalSampah,
            'notificationCount' => 1,
        ]);
    }
}
