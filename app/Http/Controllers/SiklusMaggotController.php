<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiklusMaggotController extends Controller
{
    public function index(): View
    {
        $this->sinkronisasiStatusOtomatis();

        $siklusMaggot = DB::table('siklus_maggot as s')
            ->leftJoin('detail_siklus_maggot as d', 's.id', '=', 'd.siklus_maggot_id')
            ->leftJoin('status_siklus as st', 'd.status_siklus_id', '=', 'st.id')
            ->select(
                's.id',
                's.nama_batch',
                's.jumlah_masuk',
                's.tanggal_masuk',
                'st.status_siklus as status_label',
                DB::raw('GREATEST(DATEDIFF(CURDATE(), s.tanggal_masuk) + 1, 1) as hari_ke')
            )
            ->orderByDesc('s.tanggal_masuk')
            ->orderByDesc('s.id')
            ->get();

        return view('admin.siklus-maggot.index', compact('siklusMaggot'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_batch' => [
                'required',
                'string',
                'max:30',
                Rule::unique('siklus_maggot', 'nama_batch'),
            ],
            'jumlah_awal' => ['required', 'numeric', 'min:0.1', 'max:99999'],
        ], [
            'nama_batch.required' => 'Nama batch wajib diisi.',
            'nama_batch.max' => 'Nama batch maksimal 30 karakter.',
            'nama_batch.unique' => 'Nama batch sudah digunakan.',
            'jumlah_awal.required' => 'Jumlah awal wajib diisi.',
            'jumlah_awal.numeric' => 'Jumlah awal harus berupa angka.',
            'jumlah_awal.min' => 'Jumlah awal harus lebih dari 0.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_create_modal', true)
                ->with('error_popup', 'Data tidak valid. Silahkan isi kembali');
        }

        $tanggalMasuk = now()->toDateString();
        $statusAwal = $this->statusOtomatisDariTanggal($tanggalMasuk);

        $idSiklus = DB::table('siklus_maggot')->insertGetId([
            'nama_batch' => $request->nama_batch,
            'jumlah_masuk' => $request->jumlah_awal,
            'tanggal_masuk' => $tanggalMasuk,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('detail_siklus_maggot')->insert([
            'siklus_maggot_id' => $idSiklus,
            'status_siklus_id' => $this->statusId($statusAwal),
            'is_manual' => false,
            'jumlah_hidup' => $request->jumlah_awal,
            'jumlah_kasgot' => 0,
            'hasil_panen' => 0,
            'waktu_update' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.siklus-maggot.index')
            ->with('success_popup', 'Data siklus maggot berhasil ditambahkan');
    }

    public function show(int $siklus): View
    {
        $this->sinkronisasiStatusOtomatis($siklus);

        $dataSiklus = $this->findSiklus($siklus);
        abort_if(!$dataSiklus, 404, 'Data siklus maggot tidak ditemukan.');

        return view('admin.siklus-maggot.show', [
            'siklus' => $dataSiklus,
            'statusOptions' => ['Telur', 'Bayi Larva', 'Larva', 'Selesai'],
        ]);
    }

    public function update(Request $request, int $siklus): RedirectResponse
    {
        $dataSiklus = $this->findSiklus($siklus);

        if (!$dataSiklus) {
            return redirect()
                ->route('admin.siklus-maggot.index')
                ->with('error_popup', 'Data siklus maggot tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', Rule::in(['Telur', 'Bayi Larva', 'Larva', 'Selesai'])],
            'jumlah_hidup' => ['required', 'numeric', 'min:0', 'max:99999'],
            'jumlah_kasgot' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'hasil_panen' => ['nullable', 'numeric', 'min:0', 'max:99999'],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'jumlah_hidup.required' => 'Jumlah hidup wajib diisi.',
            'jumlah_hidup.numeric' => 'Jumlah hidup harus berupa angka.',
            'jumlah_kasgot.numeric' => 'Kasgot harus berupa angka.',
            'hasil_panen.numeric' => 'Hasil panen harus berupa angka.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit_modal', true)
                ->with('error_popup', 'Data tidak valid. Silahkan isi kembali');
        }

        if ($request->status === 'Selesai' && ($request->jumlah_kasgot === null || $request->jumlah_kasgot === '' || $request->hasil_panen === null || $request->hasil_panen === '')) {
            return back()
                ->withInput()
                ->with('open_edit_modal', true)
                ->with('error_popup', 'Data kasgot dan hasil panen wajib diisi');
        }

        DB::table('detail_siklus_maggot')->updateOrInsert(
            ['siklus_maggot_id' => $siklus],
            [
                'status_siklus_id' => $this->statusId($request->status),
                'is_manual' => true,
                'jumlah_hidup' => $request->jumlah_hidup,
                'jumlah_kasgot' => $request->jumlah_kasgot ?? 0,
                'hasil_panen' => $request->hasil_panen ?? 0,
                'waktu_update' => now(),
                'updated_at' => now(),
            ]
        );

        return redirect()
            ->route('admin.siklus-maggot.show', $siklus)
            ->with('success_popup', 'Data siklus maggot berhasil diperbarui');
    }

    private function findSiklus(int $siklus): ?object
    {
        return DB::table('siklus_maggot as s')
            ->leftJoin('detail_siklus_maggot as d', 's.id', '=', 'd.siklus_maggot_id')
            ->leftJoin('status_siklus as st', 'd.status_siklus_id', '=', 'st.id')
            ->select(
                's.id',
                's.nama_batch',
                's.jumlah_masuk',
                's.tanggal_masuk',
                'd.jumlah_hidup',
                'd.jumlah_kasgot',
                'd.hasil_panen',
                'd.waktu_update',
                'st.status_siklus as status_label',
                DB::raw('GREATEST(DATEDIFF(CURDATE(), s.tanggal_masuk) + 1, 1) as hari_ke')
            )
            ->where('s.id', $siklus)
            ->first();
    }

    private function sinkronisasiStatusOtomatis(?int $idSiklus = null): void
    {
        $query = DB::table('siklus_maggot')
            ->select('id', 'jumlah_masuk', 'tanggal_masuk');

        if ($idSiklus !== null) {
            $query->where('id', $idSiklus);
        }

        $daftarSiklus = $query->get();

        foreach ($daftarSiklus as $siklus) {
            $detail = DB::table('detail_siklus_maggot')
                ->where('siklus_maggot_id', $siklus->id)
                ->first();

            // Lewati siklus yang statusnya sudah diubah manual
            if ($detail && $detail->is_manual) {
                continue;
            }

            $statusOtomatis = $this->statusOtomatisDariTanggal($siklus->tanggal_masuk);
            $statusId = $this->statusId($statusOtomatis);

            if ($detail) {
                DB::table('detail_siklus_maggot')
                    ->where('siklus_maggot_id', $siklus->id)
                    ->update([
                        'status_siklus_id' => $statusId,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('detail_siklus_maggot')->insert([
                    'siklus_maggot_id' => $siklus->id,
                    'status_siklus_id' => $statusId,
                    'is_manual' => false,
                    'jumlah_hidup' => $siklus->jumlah_masuk,
                    'jumlah_kasgot' => 0,
                    'hasil_panen' => 0,
                    'waktu_update' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function statusOtomatisDariTanggal(string $tanggalMasuk): string
    {
        $hariKe = max(Carbon::parse($tanggalMasuk)->startOfDay()->diffInDays(now()->startOfDay()) + 1, 1);

        if ($hariKe <= 3) {
            return 'Telur';
        }

        if ($hariKe <= 4) {
            return 'Bayi Larva';
        }

        if ($hariKe <= 24) {
            return 'Larva';
        }

        return 'Selesai';
    }

    private function statusId(string $status): int
    {
        $statusSiklus = DB::table('status_siklus')
            ->whereRaw('LOWER(status_siklus) = ?', [strtolower($status)])
            ->first();

        if (!$statusSiklus) {
            abort(500, "Status siklus '{$status}' belum tersedia di database. Jalankan SQL create_status_siklus.sql terlebih dahulu.");
        }

        return (int) $statusSiklus->id;
    }
}
