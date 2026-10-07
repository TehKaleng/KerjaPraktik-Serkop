<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fitur verifikasi email baru diaktifkan.
 * Akun yang sudah ada sebelum fitur ini dianggap terverifikasi, supaya admin dan
 * pelanggan lama tidak tiba-tiba terkunci. Akun yang mendaftar setelah ini wajib verifikasi.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        // Sengaja dikosongkan: tidak bisa dibedakan lagi mana akun yang tadinya belum terverifikasi
    }
};
