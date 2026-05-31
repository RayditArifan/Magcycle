<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'recipient_id',
        'recipient_role',
        'judul',
        'pesan',
        'kategori',
        'url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public static function buat($recipientId, $recipientRole, $judul, $pesan, $kategori = 'Sistem', $url = null)
    {
        return self::create([
            'recipient_id' => $recipientId,
            'recipient_role' => $recipientRole,
            'judul' => $judul,
            'pesan' => $pesan,
            'kategori' => $kategori,
            'url' => $url,
            'is_read' => false,
        ]);
    }
}
