<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengambilanSampahController extends Controller
{
    public function index(): View
    {
        $jadwalPengambilan = DB::table('jadwal_pengambilan_sampah')
            ->join('data_mitra', 'jadwal_pengambilan_sampah.id_mitra', '=', 'data_mitra.id_mitra')
            ->join('status_pengambilan_sampah', 'jadwal_pengambilan_sampah.status_pengambilan_id', '=', 'status_pengambilan_sampah.id')
            ->select(
                'jadwal_pengambilan_sampah.id',
                'jadwal_pengambilan_sampah.tanggal_pengambilan',
                'jadwal_pengambilan_sampah.berat_sampah',
                'jadwal_pengambilan_sampah.alamat_pengambilan',
                'jadwal_pengambilan_sampah.catatan',
                'data_mitra.username',
                'status_pengambilan_sampah.status_pengambilan as status_label',
                DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan) as status_text')
            )
            ->whereIn(DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan)'), [
                'menunggu konfirmasi',
                'disetujui',
            ])
            ->orderByDesc('jadwal_pengambilan_sampah.tanggal_pengambilan')
            ->get();

        return view('admin.pengambilan-sampah.index', compact('jadwalPengambilan'));
    }

    public function updateStatus(Request $request, $jadwal): RedirectResponse
    {
        $action = $request->input('action');

        $dataJadwal = DB::table('jadwal_pengambilan_sampah')
            ->join('status_pengambilan_sampah', 'jadwal_pengambilan_sampah.status_pengambilan_id', '=', 'status_pengambilan_sampah.id')
            ->select(
                'jadwal_pengambilan_sampah.id',
                DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan) as status_text')
            )
            ->where('jadwal_pengambilan_sampah.id', $jadwal)
            ->first();

        if (!$dataJadwal) {
            return back()->with('error_popup', 'Jadwal pengambilan sampah tidak ditemukan.');
        }

        if ($action === 'setuju') {
            if ($dataJadwal->status_text !== 'menunggu konfirmasi') {
                return back()->with('error_popup', 'Jadwal ini tidak dapat disetujui.');
            }

            DB::table('jadwal_pengambilan_sampah')
                ->where('id', $jadwal)
                ->update([
                    'status_pengambilan_id' => $this->statusId('disetujui'),
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('admin.pengambilan-sampah.index')
                ->with('success_popup', 'Pengambilan jadwal sampah disetujui');
        }

        if ($action === 'tolak') {
            if ($dataJadwal->status_text !== 'menunggu konfirmasi') {
                return back()->with('error_popup', 'Jadwal ini tidak dapat ditolak.');
            }

            DB::table('jadwal_pengambilan_sampah')
                ->where('id', $jadwal)
                ->update([
                    'status_pengambilan_id' => $this->statusId('ditolak'),
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('admin.pengambilan-sampah.index')
                ->with('success_popup', 'Pengambilan jadwal sampah ditolak');
        }

        if ($action === 'selesai') {
            if ($dataJadwal->status_text !== 'disetujui') {
                return back()->with('error_popup', 'Hanya jadwal disetujui yang dapat diselesaikan.');
            }

            DB::table('jadwal_pengambilan_sampah')
                ->where('id', $jadwal)
                ->update([
                    'status_pengambilan_id' => $this->statusId('selesai'),
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('admin.pengambilan-sampah.index')
                ->with('success_popup', 'Jadwal pengambilan sampah selesai');
        }

        return back()->with('error_popup', 'Aksi tidak valid.');
    }

    public function riwayat(Request $request): View
    {
        $query = DB::table('jadwal_pengambilan_sampah')
            ->join('data_mitra', 'jadwal_pengambilan_sampah.id_mitra', '=', 'data_mitra.id_mitra')
            ->join('status_pengambilan_sampah', 'jadwal_pengambilan_sampah.status_pengambilan_id', '=', 'status_pengambilan_sampah.id')
            ->select(
                'jadwal_pengambilan_sampah.id',
                'jadwal_pengambilan_sampah.tanggal_pengambilan',
                'jadwal_pengambilan_sampah.berat_sampah',
                'jadwal_pengambilan_sampah.alamat_pengambilan',
                'jadwal_pengambilan_sampah.catatan',
                'data_mitra.username',
                'status_pengambilan_sampah.status_pengambilan as status_label',
                DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan) as status_text')
            )
            ->whereIn(DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan)'), [
                'selesai',
                'ditolak',
                'batal',
                'dibatalkan',
            ]);

        if ($request->filled('search')) {
            $query->where('data_mitra.username', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('jadwal_pengambilan_sampah.tanggal_pengambilan', $request->tanggal);
        }

        $riwayatPengambilan = $query
            ->orderByDesc('jadwal_pengambilan_sampah.tanggal_pengambilan')
            ->paginate(8)
            ->withQueryString();

        return view('admin.pengambilan-sampah.riwayat', compact('riwayatPengambilan'));
    }

    public function simpanCatatan(Request $request, $jadwal): RedirectResponse
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:100'],
        ], [
            'catatan.required' => 'Catatan wajib diisi.',
            'catatan.max' => 'Catatan maksimal 100 karakter.',
        ]);

        DB::table('jadwal_pengambilan_sampah')
            ->where('id', $jadwal)
            ->update([
                'catatan' => $request->catatan,
                'updated_at' => now(),
            ]);

        $jadwalData = DB::table('jadwal_pengambilan_sampah')->where('id', $jadwal)->first();
        if ($jadwalData) {
            $catatanCount = DB::table('jadwal_pengambilan_sampah')
                ->where('id_mitra', $jadwalData->id_mitra)
                ->whereNotNull('catatan')
                ->where('catatan', '!=', '')
                ->count();

            if ($catatanCount > 2) {
                $bannedStatus = DB::table('status_akun')->where('status_akun', 'banned')->first();
                if ($bannedStatus) {
                    DB::table('data_mitra')
                        ->where('id_mitra', $jadwalData->id_mitra)
                        ->update(['id_status' => $bannedStatus->id_status]);
                }
            }
        }

        return redirect()
            ->route('admin.pengambilan-sampah.riwayat')
            ->with('success_popup', 'Catatan berhasil ditambahkan');
    }

    private function statusId(string $status): int
    {
        $statusPengambilan = DB::table('status_pengambilan_sampah')
            ->where('status_pengambilan', $status)
            ->first();

        if (!$statusPengambilan) {
            abort(500, "Status pengambilan sampah '{$status}' belum tersedia di database.");
        }

        return $statusPengambilan->id;
    }
}