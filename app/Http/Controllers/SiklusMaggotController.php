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
    private array $urutanStatus = [
        'Telur' => 1,
        'Bayi Larva' => 2,
        'Larva' => 3,
        'Selesai' => 4,
    ];

    public function index(Request $request): View
    {
        $adminId = session('user_id');
        $search = trim($request->input('search', ''));

        // Kalau kamu sudah pakai generateDetailHarian, biarkan ini.
        // Kalau method ini belum ada di controller kamu, ganti ke method sinkronisasi yang kamu pakai.
        if (method_exists($this, 'generateDetailHarian')) {
            $this->generateDetailHarian(adminId: $adminId);
        }

        $latestDetail = DB::table('detail_siklus_maggot')
            ->select(
                'siklus_maggot_id',
                DB::raw('MAX(id) as latest_detail_id')
            )
            ->groupBy('siklus_maggot_id');

        $query = DB::table('siklus_maggot as s')
            ->leftJoinSub($latestDetail, 'latest_detail', function ($join) {
                $join->on('s.id', '=', 'latest_detail.siklus_maggot_id');
            })
            ->leftJoin('detail_siklus_maggot as d', 'd.id', '=', 'latest_detail.latest_detail_id')
            ->leftJoin('status_siklus as st', 'd.status_siklus_id', '=', 'st.id')
            ->select(
                's.id',
                's.nama_batch',
                's.jumlah_masuk',
                's.tanggal_masuk',
                DB::raw("COALESCE(st.status_siklus, 'Telur') as status_label"),
                DB::raw('COALESCE(d.hari_ke, GREATEST(DATEDIFF(CURDATE(), s.tanggal_masuk) + 1, 1)) as hari_ke')
            )
            ->where('s.admin_id', $adminId);

        if ($search !== '') {
            $query->where('s.nama_batch', 'like', '%' . $search . '%');
        }

        $siklusMaggot = $query
            ->orderByRaw("CAST(REPLACE(LOWER(s.nama_batch), 'batch ', '') AS UNSIGNED) ASC")
            ->orderBy('s.id', 'asc')
            ->get();

        return view('admin.siklus-maggot.index', compact('siklusMaggot', 'search'));
        }

    public function store(Request $request): RedirectResponse
    {
        $adminId = $this->currentAdminId($request);

        $validator = Validator::make($request->all(), [
            'nama_batch' => [
                'required',
                'string',
                'max:30',
                Rule::unique('siklus_maggot', 'nama_batch')->where(function ($query) use ($adminId) {
                    return $query->where('admin_id', $adminId);
                }),
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

        DB::transaction(function () use ($request, $adminId) {
            $tanggalMasuk = now()->toDateString();
            $hariKe = 1;
            $statusAwal = $this->statusOtomatisDariHariKe($hariKe);

            $idSiklus = DB::table('siklus_maggot')->insertGetId([
                'admin_id' => $adminId,
                'nama_batch' => $request->nama_batch,
                'jumlah_masuk' => $request->jumlah_awal,
                'tanggal_masuk' => $tanggalMasuk,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('detail_siklus_maggot')->insert([
                'siklus_maggot_id' => $idSiklus,
                'status_siklus_id' => $this->statusId($statusAwal),
                'tanggal_monitoring' => $tanggalMasuk,
                'hari_ke' => $hariKe,
                'jumlah_hidup' => $request->jumlah_awal,
                'jumlah_kasgot' => 0,
                'hasil_panen' => 0,
                'is_manual' => false,
                'waktu_update' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.siklus-maggot.index')
            ->with('success_popup', 'Data siklus maggot berhasil ditambahkan');
    }

    public function show(Request $request, int $siklus): View
    {
        $adminId = $this->currentAdminId($request);

        $this->generateDetailHarian(idSiklus: $siklus, adminId: $adminId);

        $dataSiklus = $this->findSiklus($siklus, $adminId);
        abort_if(!$dataSiklus, 404, 'Data siklus maggot tidak ditemukan.');

        $detailSiklus = $this->detailSiklus($siklus, $adminId);
        $detailTerbaru = $detailSiklus->last();

        return view('admin.siklus-maggot.show', [
            'siklus' => $dataSiklus,
            'detailSiklus' => $detailSiklus,
            'detailTerbaru' => $detailTerbaru,
            'statusOptions' => $this->statusOptionsDariStatus($detailTerbaru->status_label ?? 'Telur'),
        ]);
    }

    public function update(Request $request, int $siklus): RedirectResponse
    {
        $adminId = $this->currentAdminId($request);

        $this->generateDetailHarian(idSiklus: $siklus, adminId: $adminId);

        $dataSiklus = $this->findSiklus($siklus, $adminId);

        if (!$dataSiklus) {
            return redirect()
                ->route('admin.siklus-maggot.index')
                ->with('error_popup', 'Data siklus maggot tidak ditemukan.');
        }

        $detailTerbaru = $this->detailTerbaru($siklus, $adminId);

        if (!$detailTerbaru) {
            return back()->with('error_popup', 'Data detail siklus maggot belum tersedia.');
        }

        $statusOptions = $this->statusOptionsDariStatus($detailTerbaru->status_label);

        $validator = Validator::make($request->all(), [
            'status' => ['required', Rule::in($statusOptions)],
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

        if ($request->status === 'Selesai' && ((float) str_replace(',', '.', $request->jumlah_kasgot) <= 0 || (float) str_replace(',', '.', $request->hasil_panen) <= 0)) {
            return back()
                ->withInput()
                ->with('open_edit_modal', true)
                ->with('error_popup', 'Data kasgot dan hasil panen wajib diisi dan tidak boleh 0');
        }

        DB::table('detail_siklus_maggot')
            ->where('id', $detailTerbaru->id)
            ->update([
                'status_siklus_id' => $this->statusId($request->status),
                'jumlah_hidup' => $request->jumlah_hidup,
                'jumlah_kasgot' => $request->jumlah_kasgot ?? 0,
                'hasil_panen' => $request->hasil_panen ?? 0,
                'is_manual' => true,
                'waktu_update' => now(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.siklus-maggot.show', $siklus)
            ->with('success_popup', 'Data siklus maggot berhasil diperbarui');
    }

    private function findSiklus(int $siklus, int $adminId): ?object
    {
        $latestDetailSub = DB::table('detail_siklus_maggot')
            ->select('siklus_maggot_id', DB::raw('MAX(tanggal_monitoring) as tanggal_terbaru'))
            ->groupBy('siklus_maggot_id');

        return DB::table('siklus_maggot as s')
            ->leftJoinSub($latestDetailSub, 'ld', function ($join) {
                $join->on('s.id', '=', 'ld.siklus_maggot_id');
            })
            ->leftJoin('detail_siklus_maggot as d', function ($join) {
                $join->on('s.id', '=', 'd.siklus_maggot_id')
                    ->on('d.tanggal_monitoring', '=', 'ld.tanggal_terbaru');
            })
            ->leftJoin('status_siklus as st', 'd.status_siklus_id', '=', 'st.id')
            ->select(
                's.id',
                's.nama_batch',
                's.jumlah_masuk',
                's.tanggal_masuk',
                'd.id as detail_id_terbaru',
                'd.tanggal_monitoring',
                'd.hari_ke',
                'd.jumlah_hidup',
                'd.jumlah_kasgot',
                'd.hasil_panen',
                'd.waktu_update',
                'd.is_manual',
                DB::raw("COALESCE(st.status_siklus, 'Telur') as status_label")
            )
            ->where('s.id', $siklus)
            ->where('s.admin_id', $adminId)
            ->first();
    }

    private function detailSiklus(int $siklus, int $adminId)
    {
        return DB::table('detail_siklus_maggot as d')
            ->join('siklus_maggot as s', 'd.siklus_maggot_id', '=', 's.id')
            ->leftJoin('status_siklus as st', 'd.status_siklus_id', '=', 'st.id')
            ->select(
                'd.id',
                'd.siklus_maggot_id',
                'd.tanggal_monitoring',
                'd.hari_ke',
                'd.jumlah_hidup',
                'd.jumlah_kasgot',
                'd.hasil_panen',
                'd.waktu_update',
                'd.is_manual',
                'st.status_siklus as status_label'
            )
            ->where('d.siklus_maggot_id', $siklus)
            ->where('s.admin_id', $adminId)
            ->orderBy('d.tanggal_monitoring')
            ->get();
    }

    private function detailTerbaru(int $siklus, int $adminId): ?object
    {
        return DB::table('detail_siklus_maggot as d')
            ->join('siklus_maggot as s', 'd.siklus_maggot_id', '=', 's.id')
            ->leftJoin('status_siklus as st', 'd.status_siklus_id', '=', 'st.id')
            ->select(
                'd.id',
                'd.siklus_maggot_id',
                'd.tanggal_monitoring',
                'd.hari_ke',
                'd.jumlah_hidup',
                'd.jumlah_kasgot',
                'd.hasil_panen',
                'd.waktu_update',
                'd.is_manual',
                'st.status_siklus as status_label'
            )
            ->where('d.siklus_maggot_id', $siklus)
            ->where('s.admin_id', $adminId)
            ->orderByDesc('d.tanggal_monitoring')
            ->first();
    }

    private function generateDetailHarian(?int $idSiklus = null, ?int $adminId = null): void
    {
        $query = DB::table('siklus_maggot')
            ->select('id', 'admin_id', 'jumlah_masuk', 'tanggal_masuk');

        if ($idSiklus !== null) {
            $query->where('id', $idSiklus);
        }

        if ($adminId !== null) {
            $query->where('admin_id', $adminId);
        }

        $daftarSiklus = $query->get();
        $hariIni = now()->startOfDay();

        foreach ($daftarSiklus as $siklus) {
            $tanggalMasuk = Carbon::parse($siklus->tanggal_masuk)->startOfDay();

            if ($tanggalMasuk->greaterThan($hariIni)) {
                continue;
            }

            $lastDetail = DB::table('detail_siklus_maggot as d')
                ->leftJoin('status_siklus as st', 'd.status_siklus_id', '=', 'st.id')
                ->select('d.*', 'st.status_siklus as status_label')
                ->where('d.siklus_maggot_id', $siklus->id)
                ->orderByDesc('d.tanggal_monitoring')
                ->first();

            $tanggalMulai = $lastDetail
                ? Carbon::parse($lastDetail->tanggal_monitoring)->addDay()->startOfDay()
                : $tanggalMasuk->copy();

            while ($tanggalMulai->lessThanOrEqualTo($hariIni)) {
                $hariKe = max($tanggalMasuk->diffInDays($tanggalMulai) + 1, 1);
                $statusOtomatis = $this->statusOtomatisDariHariKe($hariKe);
                $statusAcuanSebelumnya = $lastDetail->status_label ?? null;
                $statusBaru = $this->statusPalingMaju($statusOtomatis, $statusAcuanSebelumnya);

                $jumlahHidup = $lastDetail->jumlah_hidup ?? $siklus->jumlah_masuk;
                $jumlahKasgot = $lastDetail->jumlah_kasgot ?? 0;
                $hasilPanen = $lastDetail->hasil_panen ?? 0;

                DB::table('detail_siklus_maggot')->insert([
                    'siklus_maggot_id' => $siklus->id,
                    'status_siklus_id' => $this->statusId($statusBaru),
                    'tanggal_monitoring' => $tanggalMulai->toDateString(),
                    'hari_ke' => $hariKe,
                    'jumlah_hidup' => $jumlahHidup,
                    'jumlah_kasgot' => $jumlahKasgot,
                    'hasil_panen' => $hasilPanen,
                    'is_manual' => false,
                    'waktu_update' => $tanggalMulai->copy()->setTime(0, 0, 0),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $lastDetail = (object) [
                    'tanggal_monitoring' => $tanggalMulai->toDateString(),
                    'status_label' => $statusBaru,
                    'jumlah_hidup' => $jumlahHidup,
                    'jumlah_kasgot' => $jumlahKasgot,
                    'hasil_panen' => $hasilPanen,
                ];

                $tanggalMulai->addDay();
            }
        }
    }

    private function statusOtomatisDariHariKe(int $hariKe): string
    {
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

    private function statusPalingMaju(string $statusA, ?string $statusB): string
    {
        if (!$statusB) {
            return $statusA;
        }

        $nilaiA = $this->urutanStatus[$statusA] ?? 0;
        $nilaiB = $this->urutanStatus[$statusB] ?? 0;

        return $nilaiB > $nilaiA ? $statusB : $statusA;
    }

    private function statusOptionsDariStatus(string $statusSekarang): array
    {
        $nilaiSekarang = $this->urutanStatus[$statusSekarang] ?? 1;

        return collect($this->urutanStatus)
            ->filter(fn ($nilai) => $nilai >= $nilaiSekarang)
            ->keys()
            ->values()
            ->all();
    }

    private function statusId(string $status): int
    {
        $statusSiklus = DB::table('status_siklus')
            ->whereRaw('LOWER(status_siklus) = ?', [strtolower($status)])
            ->first();

        if (!$statusSiklus) {
            abort(500, "Status siklus '{$status}' belum tersedia di database.");
        }

        return (int) $statusSiklus->id;
    }

    private function currentAdminId(Request $request): int
    {
        $adminId = $request->session()->get('admin_id')
            ?? $request->session()->get('id_admin')
            ?? $request->session()->get('user_id');

        if ($adminId) {
            return (int) $adminId;
        }

        if (auth()->check()) {
            return (int) auth()->id();
        }

        abort(403, 'Session admin tidak ditemukan. Pastikan login admin menyimpan session user_id/admin_id.');
    }
}
