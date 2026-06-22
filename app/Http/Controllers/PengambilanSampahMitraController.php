<?php

namespace App\Http\Controllers;
use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengambilanSampahMitraController extends Controller
{
    public function index(Request $request): View
    {
        $idMitra = $this->currentMitraId($request);

        $mitra = DB::table('data_mitra')
            ->join('status_akun', 'data_mitra.id_status', '=', 'status_akun.id_status')
            ->select('status_akun.status_akun')
            ->where('data_mitra.id_mitra', $idMitra)
            ->first();
        $isBanned = $mitra && strtolower($mitra->status_akun) === 'banned';

        $jadwalPengambilan = DB::table('jadwal_pengambilan_sampah as jp')
            ->join('status_pengambilan_sampah', 'jp.status_pengambilan_id', '=', 'status_pengambilan_sampah.id')
            ->leftJoin('kecamatan as kc', 'jp.id_kecamatan', '=', 'kc.id_kecamatan')
            ->leftJoin('kab_kota as kt',  'kc.id_kota',      '=', 'kt.id_kota')
            ->leftJoin('provinsi as pv',  'kt.id_provinsi',  '=', 'pv.id_provinsi')
            ->select(
                'jp.id',
                'jp.alamat_pengambilan',
                'jp.tanggal_pengambilan',
                'jp.berat_sampah',
                'jp.catatan',
                'kc.nama_kecamatan',
                'kt.nama_kab_kota',
                'pv.nama_provinsi',
                'status_pengambilan_sampah.status_pengambilan as status_label',
                DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan) as status_text')
            )
            ->where('jp.id_mitra', $idMitra)
            ->whereIn(DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan)'), [
                'menunggu konfirmasi',
                'disetujui',
            ])
            ->orderByDesc('jp.tanggal_pengambilan')
            ->orderByDesc('jp.updated_at')
            ->get();

        $kabKotaList = DB::table('kab_kota')->orderBy('nama_kab_kota')->get();

        return view('mitra.pengambilan-sampah.index', compact('jadwalPengambilan', 'kabKotaList', 'isBanned'));
    }

    public function store(Request $request): RedirectResponse
    {
        $idMitra = $this->currentMitraId($request);

        $mitra = DB::table('data_mitra')
            ->join('status_akun', 'data_mitra.id_status', '=', 'status_akun.id_status')
            ->select('status_akun.status_akun')
            ->where('data_mitra.id_mitra', $idMitra)
            ->first();

        if ($mitra && strtolower($mitra->status_akun) === 'banned') {
            return back()->with('error_popup', "Akun Anda ditangguhkan (banned).\nAnda tidak dapat mengajukan pengambilan sampah.");
        }

        $validated = $request->validate([
            'tanggal_pengambilan' => ['required', 'date', 'after_or_equal:today'],
            'berat_sampah'        => ['required', 'numeric', 'min:0.1', 'max:99999'],
            'alamat'              => ['required', 'string', 'max:100'],
            'id_kecamatan'        => ['required', 'integer', 'exists:kecamatan,id_kecamatan'],
        ], [
            'tanggal_pengambilan.required'    => 'Tanggal pengambilan wajib diisi.',
            'tanggal_pengambilan.after_or_equal' => 'Tanggal pengambilan tidak boleh sebelum hari ini.',
            'berat_sampah.required'           => 'Berat sampah wajib diisi.',
            'berat_sampah.numeric'            => 'Berat sampah harus berupa angka.',
            'alamat.required'                 => 'Alamat wajib diisi.',
            'id_kecamatan.required'           => 'Kecamatan wajib dipilih.',
            'id_kecamatan.exists'             => 'Kecamatan tidak valid.',
        ]);

        DB::table('jadwal_pengambilan_sampah')->insert([
            'id_mitra'            => $idMitra,
            'alamat_pengambilan'  => $validated['alamat'],
            'id_kecamatan'        => $validated['id_kecamatan'],
            'tanggal_pengambilan' => $validated['tanggal_pengambilan'],
            'berat_sampah'        => $validated['berat_sampah'],
            'status_pengambilan_id' => $this->statusId('menunggu konfirmasi'),
            'catatan'             => null,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        // Kirim notifikasi ke admin yang berada di kota/kabupaten yang sama
        $usernameMitra = $request->session()->get('username', 'Mitra');

        // Ambil id_kota dari kecamatan yang dipilih mitra
        $kecamatan = DB::table('kecamatan')
            ->where('id_kecamatan', $validated['id_kecamatan'])
            ->first();

        if ($kecamatan) {
            // Ambil semua admin yang kecamatannya berada di kota/kabupaten yang sama
            $admins = DB::table('data_admin as a')
                ->join('kecamatan as kc', 'a.id_kecamatan', '=', 'kc.id_kecamatan')
                ->where('kc.id_kota', $kecamatan->id_kota)
                ->whereNotNull('a.id_kecamatan')
                ->select('a.id')
                ->get();

            // Fallback: jika tidak ada admin di kota tersebut, kirim ke semua admin
            if ($admins->isEmpty()) {
                $admins = DB::table('data_admin')->select('id')->get();
            }

            foreach ($admins as $admin) {
                Notifikasi::buat(
                    recipientId:   $admin->id,
                    recipientRole: 'admin',
                    judul:         'Pengajuan Jadwal Baru',
                    pesan:         "{$usernameMitra} mengajukan jadwal pengambilan sampah pada {$validated['tanggal_pengambilan']}.",
                    kategori:      'Pengambilan Sampah',
                    url:           route('admin.pengambilan-sampah.index'),
                );
            }
        }

        return redirect()
            ->route('mitra.pengambilan-sampah.index')
            ->with('success_popup', 'Jadwal pengambilan sampah berhasil dikirim');
    }

    public function show(Request $request, int $jadwal): View
    {
        $idMitra = $this->currentMitraId($request);
        $dataJadwal = $this->findJadwalMitra($jadwal, $idMitra);

        abort_if(!$dataJadwal, 404, 'Jadwal pengambilan sampah tidak ditemukan.');

        return view('mitra.pengambilan-sampah.show', [
            'jadwal' => $dataJadwal,
        ]);
    }

    public function batal(Request $request, int $jadwal): RedirectResponse
    {
        $idMitra = $this->currentMitraId($request);
        $dataJadwal = $this->findJadwalMitra($jadwal, $idMitra);

        if (!$dataJadwal) {
            return redirect()
                ->route('mitra.pengambilan-sampah.index')
                ->with('error_popup', 'Jadwal pengambilan sampah tidak ditemukan.');
        }

        if (!in_array($dataJadwal->status_text, ['menunggu konfirmasi', 'disetujui'], true)) {
            return back()->with('error_popup', 'Jadwal ini tidak dapat dibatalkan.');
        }

        DB::table('jadwal_pengambilan_sampah')
            ->where('id', $jadwal)
            ->where('id_mitra', $idMitra)
            ->update([
                'status_pengambilan_id' => $this->statusId('batal'),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('mitra.pengambilan-sampah.index')
            ->with('success_popup', 'Jadwal pengambilan sampah berhasil dibatalkan');
    }

    public function riwayat(Request $request): View
    {
        $idMitra = $this->currentMitraId($request);

        $riwayatPengambilan = DB::table('jadwal_pengambilan_sampah as jp')
            ->join('status_pengambilan_sampah', 'jp.status_pengambilan_id', '=', 'status_pengambilan_sampah.id')
            ->leftJoin('kecamatan as kc', 'jp.id_kecamatan', '=', 'kc.id_kecamatan')
            ->leftJoin('kab_kota as kt',  'kc.id_kota',      '=', 'kt.id_kota')
            ->leftJoin('provinsi as pv',  'kt.id_provinsi',  '=', 'pv.id_provinsi')
            ->select(
                'jp.id',
                'jp.alamat_pengambilan',
                'jp.tanggal_pengambilan',
                'jp.berat_sampah',
                'jp.catatan',
                'kc.nama_kecamatan',
                'kt.nama_kab_kota',
                'pv.nama_provinsi',
                'status_pengambilan_sampah.status_pengambilan as status_label',
                DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan) as status_text')
            )
            ->where('jp.id_mitra', $idMitra)
            ->whereIn(DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan)'), [
                'selesai', 'ditolak', 'batal', 'dibatalkan',
            ])
            ->orderByDesc('jp.tanggal_pengambilan')
            ->orderByDesc('jp.updated_at')
            ->paginate(8)
            ->withQueryString();

        return view('mitra.pengambilan-sampah.riwayat', compact('riwayatPengambilan'));
    }

    private function findJadwalMitra(int $jadwal, string $idMitra): ?object
    {
        return DB::table('jadwal_pengambilan_sampah as jp')
            ->join('status_pengambilan_sampah', 'jp.status_pengambilan_id', '=', 'status_pengambilan_sampah.id')
            ->leftJoin('kecamatan as kc', 'jp.id_kecamatan', '=', 'kc.id_kecamatan')
            ->leftJoin('kab_kota as kt',  'kc.id_kota',      '=', 'kt.id_kota')
            ->leftJoin('provinsi as pv',  'kt.id_provinsi',  '=', 'pv.id_provinsi')
            ->select(
                'jp.id',
                'jp.alamat_pengambilan',
                'jp.tanggal_pengambilan',
                'jp.berat_sampah',
                'jp.catatan',
                'kc.nama_kecamatan',
                'kt.nama_kab_kota',
                'pv.nama_provinsi',
                'status_pengambilan_sampah.status_pengambilan as status_label',
                DB::raw('LOWER(status_pengambilan_sampah.status_pengambilan) as status_text')
            )
            ->where('jp.id', $jadwal)
            ->where('jp.id_mitra', $idMitra)
            ->first();
    }

    private function statusId(string $status): int
    {
        $statusPengambilan = DB::table('status_pengambilan_sampah')
            ->whereRaw('LOWER(status_pengambilan) = ?', [strtolower($status)])
            ->first();

        if (!$statusPengambilan) {
            abort(500, "Status pengambilan sampah '{$status}' belum tersedia di database.");
        }

        return (int) $statusPengambilan->id;
    }

    private function currentMitraId(Request $request): string
    {
        $idMitra = $request->session()->get('id_mitra')
            ?? $request->session()->get('mitra_id')
            ?? $request->session()->get('user_id');

        if ($idMitra) {
            return (string) $idMitra;
        }

        if (auth()->check()) {
            $user = auth()->user();

            if (isset($user->id_mitra) && $user->id_mitra) {
                return (string) $user->id_mitra;
            }

            if (isset($user->email) && $user->email) {
                $mitra = DB::table('data_mitra')
                    ->where('email', $user->email)
                    ->first();

                if ($mitra) {
                    return (string) $mitra->id_mitra;
                }
            }
        }

        abort(403, 'Session mitra tidak ditemukan. Pastikan proses login mitra menyimpan session id_mitra.');
    }
}
