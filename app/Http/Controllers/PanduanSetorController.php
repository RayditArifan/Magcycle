<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PanduanSetorController extends Controller
{
    public function index(): View
    {
        $panduanSetor = DB::table('panduan_setor_sampah')
            ->orderByDesc('id_panduan')
            ->get();

        return view('admin.panduan-setor.index', compact('panduanSetor'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'max:120'],
            'deskripsi' => ['required', 'string', 'max:540'],
            'video_panduan' => ['required', 'file', 'mimes:mp4,webm', 'mimetypes:video/mp4,video/webm', 'max:51200'],
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 120 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.max' => 'Deskripsi maksimal 540 karakter.',
            'video_panduan.required' => 'Video panduan wajib diunggah.',
            'video_panduan.mimes' => 'Video harus berformat MP4 atau WEBM.',
            'video_panduan.mimetypes' => 'Video harus memiliki MIME type video/mp4 atau video/webm.',
            'video_panduan.max' => 'Ukuran video maksimal 50 MB.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_popup', 'Data tidak valid. Silahkan isi kembali')
                ->with('open_create_panduan', true);
        }

        $videoPath = $request->file('video_panduan')->store('panduan-setor', 'public');

        DB::table('panduan_setor_sampah')->insert([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'video_panduan' => $videoPath,
        ]);

        return redirect()
            ->route('admin.panduan-setor.index')
            ->with('panduan_success_popup', 'Panduan Setor Sampah berhasil diunggah');
    }

    public function update(Request $request, int $idPanduan): RedirectResponse
    {
        $panduan = DB::table('panduan_setor_sampah')
            ->where('id_panduan', $idPanduan)
            ->first();

        if (! $panduan) {
            return redirect()
                ->route('admin.panduan-setor.index')
                ->with('error_popup', 'Panduan setor sampah tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'max:120'],
            'deskripsi' => ['required', 'string', 'max:540'],
            'video_panduan' => ['nullable', 'file', 'mimes:mp4,webm', 'mimetypes:video/mp4,video/webm', 'max:51200'],
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 120 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.max' => 'Deskripsi maksimal 540 karakter.',
            'video_panduan.mimes' => 'Video harus berformat MP4 atau WEBM.',
            'video_panduan.mimetypes' => 'Video harus memiliki MIME type video/mp4 atau video/webm.',
            'video_panduan.max' => 'Ukuran video maksimal 50 MB.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_popup', 'Data tidak valid. Silahkan isi kembali')
                ->with('open_edit_panduan', $idPanduan);
        }

        $videoPath = $panduan->video_panduan;

        if ($request->hasFile('video_panduan')) {
            if ($videoPath && Storage::disk('public')->exists($videoPath)) {
                Storage::disk('public')->delete($videoPath);
            }

            $videoPath = $request->file('video_panduan')->store('panduan-setor', 'public');
        }

        DB::table('panduan_setor_sampah')
            ->where('id_panduan', $idPanduan)
            ->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'video_panduan' => $videoPath,
            ]);

        return redirect()
            ->route('admin.panduan-setor.index')
            ->with('panduan_success_popup', 'Perubahan berhasil disimpan');
    }

    public function destroy(int $idPanduan): RedirectResponse
    {
        $panduan = DB::table('panduan_setor_sampah')
            ->where('id_panduan', $idPanduan)
            ->first();

        if (! $panduan) {
            return redirect()
                ->route('admin.panduan-setor.index')
                ->with('error_popup', 'Panduan setor sampah tidak ditemukan.');
        }

        if ($panduan->video_panduan && Storage::disk('public')->exists($panduan->video_panduan)) {
            Storage::disk('public')->delete($panduan->video_panduan);
        }

        DB::table('panduan_setor_sampah')
            ->where('id_panduan', $idPanduan)
            ->delete();

        return redirect()
            ->route('admin.panduan-setor.index')
            ->with('panduan_success_popup', 'Panduan Setor Sampah berhasil dihapus');
    }
}
