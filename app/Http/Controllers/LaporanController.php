<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    private function guardAdmin()
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login');
        }

        return null;
    }

    private function getAdminContext()
    {
        $admin = DB::table('data_admin as da')
            ->leftJoin('kecamatan as k', 'da.id_kecamatan', '=', 'k.id_kecamatan')
            ->leftJoin('kab_kota as kk', 'k.id_kota', '=', 'kk.id_kota')
            ->where('da.id', session('user_id'))
            ->select(
                'da.id',
                'da.username',
                'da.id_kecamatan',
                'k.id_kota',
                'kk.nama_kab_kota'
            )
            ->first();

        abort_if(!$admin, 403, 'Data admin tidak ditemukan.');
        abort_if(!$admin->id_kota, 403, 'Lokasi admin belum diatur. Isi data_admin.id_kecamatan terlebih dahulu.');

        return $admin;
    }

    private function pengambilanSelesaiQuery($adminKotaId)
    {
        return DB::table('jadwal_pengambilan_sampah as jp')
            ->join('data_mitra as dm', 'jp.id_mitra', '=', 'dm.id_mitra')
            ->join('status_pengambilan_sampah as sp', 'jp.status_pengambilan_id', '=', 'sp.id')
            ->join('kecamatan as k', function ($join) {
                $join->on('k.id_kecamatan', '=', DB::raw('COALESCE(jp.id_kecamatan, dm.id_kecamatan)'));
            })
            ->where('k.id_kota', $adminKotaId)
            ->where(DB::raw('LOWER(sp.status_pengambilan)'), 'selesai');
    }

    private function hitungPoin($beratValid)
    {
        $konversiList = DB::table('konversi_poin')
            ->where('berat_sampah', '>', 0)
            ->where('konversi_poin', '>', 0)
            ->orderByDesc('berat_sampah')
            ->get();

        if ($konversiList->isEmpty()) {
            return 0;
        }

        $sisaBerat = (float) $beratValid;
        $totalPoin = 0;

        foreach ($konversiList as $konversi) {
            $beratKonversi = (float) $konversi->berat_sampah;
            $poinKonversi = (int) $konversi->konversi_poin;

            if ($sisaBerat >= $beratKonversi) {
                $jumlahPaket = floor($sisaBerat / $beratKonversi);

                $totalPoin += $jumlahPaket * $poinKonversi;
                $sisaBerat -= $jumlahPaket * $beratKonversi;
            }
        }

        return (int) $totalPoin;
    }

    public function buatLaporanSampah()
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $admin = $this->getAdminContext();

        $dataPengambilan = $this->pengambilanSelesaiQuery($admin->id_kota)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('laporan_sampah as ls')
                    ->whereColumn('ls.pengambilan_sampah_id', 'jp.id');
            })
            ->select(
                'jp.id',
                'jp.tanggal_pengambilan',
                'jp.berat_sampah',
                'jp.alamat_pengambilan',
                'dm.id_mitra',
                'dm.username',
                'k.nama_kecamatan'
            )
            ->orderByDesc('jp.tanggal_pengambilan')
            ->paginate(8);

        return view('admin.laporanku.buat-laporan-sampah', compact('dataPengambilan', 'admin'));
    }

    public function storeLaporanSampah(Request $request)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $admin = $this->getAdminContext();

        $validator = Validator::make($request->all(), [
            'pengambilan_sampah_id' => ['required', 'integer'],
            'berat_valid' => ['required', 'numeric', 'gt:0'],
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('error_popup', 'Data tidak valid. Silahkan isi kembali');
        }

        $pengambilanId = (int) $request->pengambilan_sampah_id;
        $beratValid = (float) $request->berat_valid;

        $jadwal = $this->pengambilanSelesaiQuery($admin->id_kota)
            ->where('jp.id', $pengambilanId)
            ->select(
                'jp.id',
                'jp.id_mitra',
                'jp.tanggal_pengambilan',
                'jp.berat_sampah',
                'dm.username'
            )
            ->first();

        if (!$jadwal) {
            return back()->withInput()->with('error_popup', 'Data tidak valid. Silahkan isi kembali');
        }

        if ($beratValid > (float) $jadwal->berat_sampah) {
            return back()->withInput()->with('error_popup', 'Data tidak valid. Silahkan isi kembali');
        }

        $sudahAda = DB::table('laporan_sampah')
            ->where('pengambilan_sampah_id', $jadwal->id)
            ->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error_popup', 'Laporan sampah untuk jadwal ini sudah dibuat.');
        }

        DB::transaction(function () use ($jadwal, $admin, $beratValid) {
            $poinDidapat = $this->hitungPoin($beratValid);

            DB::table('laporan_sampah')->insert([
                'pengambilan_sampah_id' => $jadwal->id,
                'admin_id' => $admin->id,
                'berat_pengajuan' => $jadwal->berat_sampah,
                'berat_valid' => $beratValid,
                'poin_didapat' => $poinDidapat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($poinDidapat > 0) {
                DB::table('data_mitra')
                    ->where('id_mitra', $jadwal->id_mitra)
                    ->increment('saldo_poin', $poinDidapat);
            }
        });

        return redirect()
            ->route('admin.laporanku.laporan-sampah')
            ->with('success_popup', 'Laporan sampah berhasil disimpan.');
    }

    public function lihatLaporanSampah(Request $request)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $admin = $this->getAdminContext();

        $query = DB::table('laporan_sampah as ls')
            ->join('jadwal_pengambilan_sampah as jp', 'ls.pengambilan_sampah_id', '=', 'jp.id')
            ->join('data_mitra as dm', 'jp.id_mitra', '=', 'dm.id_mitra')
            ->join('kecamatan as k', function ($join) {
                $join->on('k.id_kecamatan', '=', DB::raw('COALESCE(jp.id_kecamatan, dm.id_kecamatan)'));
            })
            ->where('k.id_kota', $admin->id_kota)
            ->select(
                'ls.id',
                'ls.berat_pengajuan',
                'ls.berat_valid',
                'ls.poin_didapat',
                'jp.tanggal_pengambilan',
                'dm.username'
            );

        if ($request->filled('search')) {
            $query->where('dm.username', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('jp.tanggal_pengambilan', $request->tanggal);
        }

        $laporanSampah = $query
            ->orderByDesc('jp.tanggal_pengambilan')
            ->paginate(8)
            ->appends($request->query());

        return view('admin.laporanku.laporan-sampah', compact('laporanSampah', 'admin'));
    }

    public function lihatLaporanProduksi(Request $request)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $admin = $this->getAdminContext();

        $latestDetail = DB::table('detail_siklus_maggot')
            ->select(
                'siklus_maggot_id',
                DB::raw('MAX(tanggal_monitoring) as tanggal_terbaru')
            )
            ->groupBy('siklus_maggot_id');

        $query = DB::table('siklus_maggot as sm')
            ->joinSub($latestDetail, 'ld', function ($join) {
                $join->on('sm.id', '=', 'ld.siklus_maggot_id');
            })
            ->join('detail_siklus_maggot as d', function ($join) {
                $join->on('sm.id', '=', 'd.siklus_maggot_id')
                    ->on('ld.tanggal_terbaru', '=', 'd.tanggal_monitoring');
            })
            ->join('data_admin as da', 'sm.admin_id', '=', 'da.id')
            ->join('kecamatan as k', 'da.id_kecamatan', '=', 'k.id_kecamatan')
            ->where('k.id_kota', $admin->id_kota)
            ->where(function ($q) {
                $q->where('d.hasil_panen', '>', 0)
                    ->orWhere('d.jumlah_kasgot', '>', 0);
            });

        // Filter rentang tanggal berdasarkan bulan dan tahun
        $dari = $request->input('dari'); // format: mm/yyyy
        $sampai = $request->input('sampai'); // format: mm/yyyy

        if ($dari && preg_match('/^(\d{2})\/(\d{4})$/', $dari, $m)) {
            $query->whereRaw('(YEAR(d.tanggal_monitoring) > ? OR (YEAR(d.tanggal_monitoring) = ? AND MONTH(d.tanggal_monitoring) >= ?))', [
                $m[2], $m[2], (int) $m[1]
            ]);
        }

        if ($sampai && preg_match('/^(\d{2})\/(\d{4})$/', $sampai, $m)) {
            $query->whereRaw('(YEAR(d.tanggal_monitoring) < ? OR (YEAR(d.tanggal_monitoring) = ? AND MONTH(d.tanggal_monitoring) <= ?))', [
                $m[2], $m[2], (int) $m[1]
            ]);
        }

        $laporanProduksi = $query
            ->select(
                DB::raw('MONTH(d.tanggal_monitoring) as bulan'),
                DB::raw('YEAR(d.tanggal_monitoring) as tahun'),
                DB::raw('SUM(COALESCE(d.hasil_panen, 0)) as total_panen'),
                DB::raw('SUM(COALESCE(d.jumlah_kasgot, 0)) as total_kasgot')
            )
            ->groupBy(
                DB::raw('MONTH(d.tanggal_monitoring)'),
                DB::raw('YEAR(d.tanggal_monitoring)')
            )
            ->orderByDesc(DB::raw('YEAR(d.tanggal_monitoring)'))
            ->orderByDesc(DB::raw('MONTH(d.tanggal_monitoring)'))
            ->paginate(8)
            ->withQueryString();

        return view('admin.laporanku.laporan-produksi', compact('laporanProduksi', 'admin', 'dari', 'sampai'));
    }
}
