<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Penjadwal (scheduler)
|--------------------------------------------------------------------------
| Lokal  : jalankan "php artisan schedule:work" di terminal terpisah.
| Hosting: pasang cron "* * * * * cd /path/ke/proyek && php artisan schedule:run >> /dev/null 2>&1"
*/

// Email pengingat reservasi (dicek tiap 5 menit, dikirim 2 jam sebelum waktu reservasi)
Schedule::command('reservasi:kirim-pengingat')
    ->everyFiveMinutes()
    ->withoutOverlapping();
