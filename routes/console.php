<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hapus notifikasi yang sudah dibaca & berumur > 1 minggu, berjalan setiap hari tengah malam
Schedule::command('notifikasi:hapus-lama')->daily();
