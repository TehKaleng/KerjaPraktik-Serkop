<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Akun admin awal
    |--------------------------------------------------------------------------
    | Dipakai oleh DatabaseSeeder untuk membuat akun admin pertama.
    | Nilainya dibaca dari file .env (yang TIDAK ikut ke GitHub), jadi password
    | tidak perlu ditulis di dalam kode.
    |
    | Kalau ADMIN_PASSWORD dikosongkan, seeder membuat password acak dan
    | menampilkannya sekali di terminal.
    */

    'admin_email'    => env('ADMIN_EMAIL', 'admin@serenatakopi.id'),
    'admin_password' => env('ADMIN_PASSWORD'),

];
