<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $stocks = DB::table('stok_produk')
            ->orderBy('tanggal_input', 'desc')
            ->orderBy('id_stok', 'desc')
            ->get();

        return view('admin.manajemen-stok', [
            'stocks' => $stocks,
            'notificationCount' => 1,
        ]);
    }

    public function store(Request $request)
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $request->merge([
            'jumlah_stok' => str_replace(',', '.', $request->jumlah_stok),
        ]);

        if (
            trim($request->jenis_produk ?? '') === '' ||
            trim($request->jumlah_stok ?? '') === ''
        ) {
            return redirect()
                ->route('admin.stok.index')
                ->withInput()
                ->with('popup', 'required')
                ->with('open_modal', 'add');
        }

        $validator = Validator::make($request->all(), [
            'jenis_produk' => 'required|string|max:30',
            'jumlah_stok'  => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.stok.index')
                ->withInput()
                ->with('popup', 'invalid')
                ->with('open_modal', 'add');
        }

        DB::table('stok_produk')->insert([
            'jenis_produk'  => $request->jenis_produk,
            'jumlah_stok'   => $request->jumlah_stok,
            'satuan'        => 'kg',
            'tanggal_input' => now()->toDateString(),
        ]);

        return redirect()
            ->route('admin.stok.index')
            ->with('popup', 'success_add');
    }

    public function update(Request $request, $id_stok)
    {
        if (!session()->has('user_id') || session('role') !== 'admin') {
            return redirect()->route('login')
                ->with('login_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $request->merge([
            'jumlah_stok' => str_replace(',', '.', $request->jumlah_stok),
        ]);

        if (
            trim($request->jenis_produk ?? '') === '' ||
            trim($request->jumlah_stok ?? '') === ''
        ) {
            return redirect()
                ->route('admin.stok.index')
                ->withInput()
                ->with('popup', 'required')
                ->with('open_modal', 'edit')
                ->with('edit_id', $id_stok);
        }

        $validator = Validator::make($request->all(), [
            'jenis_produk' => 'required|string|max:30',
            'jumlah_stok'  => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.stok.index')
                ->withInput()
                ->with('popup', 'invalid')
                ->with('open_modal', 'edit')
                ->with('edit_id', $id_stok);
        }

        DB::table('stok_produk')
            ->where('id_stok', $id_stok)
            ->update([
                'jenis_produk'  => $request->jenis_produk,
                'jumlah_stok'   => $request->jumlah_stok,
                'tanggal_input' => now()->toDateString(),
            ]);

        return redirect()
            ->route('admin.stok.index')
            ->with('popup', 'success_edit');
    }
}
