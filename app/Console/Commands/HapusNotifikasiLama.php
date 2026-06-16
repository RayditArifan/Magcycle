<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class HapusNotifikasiLama extends Command
{

    protected $signature = 'notifikasi:hapus-lama';


    protected $description = 'Hapus notifikasi yang sudah dibaca dan berumur lebih dari 1 minggu';

    public function handle(): int
    {
        $batasWaktu = Carbon::now()->subWeek();

        $jumlah = Notifikasi::where('is_read', true)
            ->where('updated_at', '<=', $batasWaktu)
            ->delete();

        $this->info("Berhasil menghapus {$jumlah} notifikasi lama yang sudah dibaca.");

        return Command::SUCCESS;
    }
}
