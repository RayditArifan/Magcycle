<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MagPoinMitraController extends Controller
{
    private function guardMitra()
    {
        if (!session()->has('user_id') || session('role') !== 'mitra') {
            return redirect()->route('login');
        }

        return null;
    }

    private function getMitraLogin()
    {
        $mitra = DB::table('data_mitra')
            ->where('id_mitra', session('user_id'))
            ->first();

        abort_if(!$mitra, 403, 'Data mitra tidak ditemukan.');

        return $mitra;
    }

    private function getStatusPenukaranId($namaStatus)
    {
        $status = DB::table('status_penukaran')
            ->whereRaw('LOWER(status_penukaran) = ?', [strtolower($namaStatus)])
            ->first();

        abort_if(!$status, 500, 'Status penukaran belum tersedia: ' . $namaStatus);

        return $status->id;
    }

    public function index()
    {
        if ($redirect = $this->guardMitra()) {
            return $redirect;
        }

        $mitra = $this->getMitraLogin();

        $eWallets = DB::table('e_wallet')
            ->orderBy('nama_ewallet')
            ->get();

        $riwayatTransaksi = DB::table('transaksi_poin as tp')
            ->join('e_wallet as ew', 'tp.e_wallet_id', '=', 'ew.id')
            ->join('status_penukaran as sp', 'tp.status_penukaran_id', '=', 'sp.id')
            ->where('tp.id_mitra', $mitra->id_mitra)
            ->select(
                'tp.id',
                'tp.poin_tukar',
                'tp.nominal_uang',
                'tp.nomor_ewallet',
                'tp.created_at',
                'ew.nama_ewallet',
                'sp.status_penukaran'
            )
            ->orderByDesc('tp.created_at')
            ->limit(5)
            ->get();

        return view('mitra.magpoin.index', compact('mitra', 'eWallets', 'riwayatTransaksi'));
    }

    public function storeTukarPoin(Request $request)
    {
        if ($redirect = $this->guardMitra()) {
            return $redirect;
        }

        $mitra = $this->getMitraLogin();

        $validator = Validator::make($request->all(), [
            'poin_tukar' => ['required', 'integer', 'min:1'],
            'e_wallet_id' => ['required', 'integer', 'exists:e_wallet,id'],
            'nomor_ewallet' => ['required', 'string', 'max:30'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', 'Data tidak valid. Silahkan isi kembali');
        }

        $poinTukar = (int) $request->poin_tukar;

        if ($poinTukar > (int) $mitra->saldo_poin) {
            return back()
                ->withInput()
                ->with('error', 'Saldo poin tidak mencukupi.');
        }

        $statusMenunggu = $this->getStatusPenukaranId('menunggu konfirmasi');

        DB::transaction(function () use ($mitra, $request, $poinTukar, $statusMenunggu) {
            $mitraTerkunci = DB::table('data_mitra')
                ->where('id_mitra', $mitra->id_mitra)
                ->lockForUpdate()
                ->first();

            if (!$mitraTerkunci || (int) $mitraTerkunci->saldo_poin < $poinTukar) {
                abort(422, 'Saldo poin tidak mencukupi.');
            }

            DB::table('data_mitra')
                ->where('id_mitra', $mitra->id_mitra)
                ->decrement('saldo_poin', $poinTukar);

            DB::table('transaksi_poin')->insert([
                'id_mitra' => $mitra->id_mitra,
                'poin_tukar' => $poinTukar,
                'nominal_uang' => $poinTukar,
                'e_wallet_id' => $request->e_wallet_id,
                'nomor_ewallet' => $request->nomor_ewallet,
                'status_penukaran_id' => $statusMenunggu,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('mitra.magpoin.index')
            ->with('success', 'Pengajuan Penukaran Poin Berhasil Dilakukan');
    }
}
