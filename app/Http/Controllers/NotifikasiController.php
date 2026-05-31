<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NotifikasiController extends Controller
{
    private function getLoggedUser()
    {
        return [
            'id'   => Session::get('user_id'),
            'role' => Session::get('role'), // 'admin' or 'mitra'
        ];
    }

    public function index()
    {
        $user = $this->getLoggedUser();

        $belumDibaca = Notifikasi::where('recipient_id', $user['id'])
            ->where('recipient_role', $user['role'])
            ->where('is_read', false)
            ->latest()
            ->get();

        $sudahDibaca = Notifikasi::where('recipient_id', $user['id'])
            ->where('recipient_role', $user['role'])
            ->where('is_read', true)
            ->latest()
            ->limit(10)
            ->get();

        return view('notifikasi.index', compact('belumDibaca', 'sudahDibaca'));
    }

    public function read($id)
    {
        $user = $this->getLoggedUser();

        $notifikasi = Notifikasi::where('id', $id)
            ->where('recipient_id', $user['id'])
            ->where('recipient_role', $user['role'])
            ->firstOrFail();

        $notifikasi->update([
            'is_read' => true,
        ]);

        return response()->json([
            'success' => true,
            'url' => $notifikasi->url,
        ]);
    }

    public function readAll()
    {
        $user = $this->getLoggedUser();

        Notifikasi::where('recipient_id', $user['id'])
            ->where('recipient_role', $user['role'])
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
