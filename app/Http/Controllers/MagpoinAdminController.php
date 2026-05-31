<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MagPoinAdminController extends Controller
{
    private function guardAdmin()
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login');
        }

        return null;
    }

    private function getStatusPenukaranId($namaStatus)
    {
        $status = DB::table('status_penukaran')
            ->whereRaw('LOWER(status_penukaran) = ?', [strtolower($namaStatus)])
            ->first();

        abort_if(!$status, 500, 'Status penukaran belum tersedia di database: ' . $namaStatus);

        return $status->id;
    }

    private function transaksiPoinQuery()
    {
        return DB::table('transaksi_poin as tp')
            ->join('data_mitra as dm', 'tp.id_mitra', '=', 'dm.id_mitra')
            ->join('e_wallet as ew', 'tp.e_wallet_id', '=', 'ew.id')
            ->join('status_penukaran as sp', 'tp.status_penukaran_id', '=', 'sp.id')
            ->select(
                'tp.id',
                'tp.id_mitra',
                'tp.poin_tukar',
                'tp.nominal_uang',
                'tp.nomor_ewallet',
                'tp.status_penukaran_id',
                'tp.created_at',
                'dm.username',
                'ew.nama_ewallet',
                'sp.status_penukaran'
            );
    }

    public function nilaiKonversi()
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $nilaiKonversi = DB::table('konversi_poin')
            ->orderBy('berat_sampah')
            ->get();

        return view('admin.magpoin.nilai-konversi', compact('nilaiKonversi'));
    }

    public function storeNilaiKonversi(Request $request)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $validator = Validator::make($request->all(), [
            'berat_sampah' => ['required', 'numeric', 'gt:0'],
            'konversi_poin' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', 'Data tidak valid. Silahkan isi kembali');
        }

        $beratSampah = (float) $request->berat_sampah;

        $sudahAda = DB::table('konversi_poin')
            ->where('berat_sampah', $beratSampah)
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->with('error', 'Data tidak valid. Silahkan isi kembali');
        }

        DB::table('konversi_poin')->insert([
            'berat_sampah' => $beratSampah,
            'konversi_poin' => (int) $request->konversi_poin,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.magpoin.nilai-konversi')
            ->with('success', 'Nilai Konversi Poin Berhasil Ditambahkan');
    }

    public function updateNilaiKonversi(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $dataLama = DB::table('konversi_poin')
            ->where('id', $id)
            ->first();

        if (!$dataLama) {
            return back()->with('error', 'Data tidak valid. Silahkan isi kembali');
        }

        $validator = Validator::make($request->all(), [
            'berat_sampah' => ['required', 'numeric', 'gt:0'],
            'konversi_poin' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', 'Data tidak valid. Silahkan isi kembali');
        }

        $beratSampah = (float) $request->berat_sampah;

        $duplikat = DB::table('konversi_poin')
            ->where('berat_sampah', $beratSampah)
            ->where('id', '!=', $id)
            ->exists();

        if ($duplikat) {
            return back()
                ->withInput()
                ->with('error', 'Data tidak valid. Silahkan isi kembali');
        }

        DB::table('konversi_poin')
            ->where('id', $id)
            ->update([
                'berat_sampah' => $beratSampah,
                'konversi_poin' => (int) $request->konversi_poin,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.magpoin.nilai-konversi')
            ->with('success', 'Perubahan Berhasil Disimpan');
    }

    public function transaksiPoin()
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $statusMenunggu = $this->getStatusPenukaranId('menunggu konfirmasi');

        $transaksiPoin = $this->transaksiPoinQuery()
            ->where('tp.status_penukaran_id', $statusMenunggu)
            ->orderByDesc('tp.created_at')
            ->paginate(8);

        return view('admin.magpoin.transaksi-poin', compact('transaksiPoin'));
    }

    public function setujuiTransaksi($id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $statusMenunggu = $this->getStatusPenukaranId('menunggu konfirmasi');
        $statusDikonfirmasi = $this->getStatusPenukaranId('dikonfirmasi');

        $transaksi = DB::table('transaksi_poin')
            ->where('id', $id)
            ->where('status_penukaran_id', $statusMenunggu)
            ->first();

        if (!$transaksi) {
            return back()->with('error', 'Data transaksi tidak valid.');
        }

        DB::table('transaksi_poin')
            ->where('id', $id)
            ->update([
                'status_penukaran_id' => $statusDikonfirmasi,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.magpoin.transaksi-poin')
            ->with('success', 'Penukaran Poin Berhasil Dikonfirmasi');
    }

    public function tolakTransaksi($id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $statusMenunggu = $this->getStatusPenukaranId('menunggu konfirmasi');
        $statusDitolak = $this->getStatusPenukaranId('ditolak');

        $transaksi = DB::table('transaksi_poin')
            ->where('id', $id)
            ->where('status_penukaran_id', $statusMenunggu)
            ->first();

        if (!$transaksi) {
            return back()->with('error', 'Data transaksi tidak valid.');
        }

        DB::transaction(function () use ($id, $transaksi, $statusDitolak) {
            DB::table('transaksi_poin')
                ->where('id', $id)
                ->update([
                    'status_penukaran_id' => $statusDitolak,
                    'updated_at' => now(),
                ]);

            DB::table('data_mitra')
                ->where('id_mitra', $transaksi->id_mitra)
                ->increment('saldo_poin', $transaksi->poin_tukar);
        });

        return redirect()
            ->route('admin.magpoin.transaksi-poin')
            ->with('success', 'Penukaran Poin Berhasil Ditolak');
    }

    public function riwayatTransaksi()
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $statusMenunggu = $this->getStatusPenukaranId('menunggu konfirmasi');

        $riwayatTransaksi = $this->transaksiPoinQuery()
            ->where('tp.status_penukaran_id', '!=', $statusMenunggu)
            ->orderByDesc('tp.updated_at')
            ->paginate(8);

        return view('admin.magpoin.riwayat-transaksi', compact('riwayatTransaksi'));
    }
}
