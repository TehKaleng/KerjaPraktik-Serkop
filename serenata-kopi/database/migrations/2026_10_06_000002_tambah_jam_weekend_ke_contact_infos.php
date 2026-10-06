<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Jam operasional dibedakan weekday dan weekend (sesuai papan di kafe):
 *   - jam_buka / jam_tutup                 = Senin - Jumat (kolom lama, maknanya dipertegas)
 *   - jam_buka_weekend / jam_tutup_weekend = Sabtu - Minggu (kolom baru)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_infos', function (Blueprint $table) {
            $table->string('jam_buka_weekend')->default('08:00')->after('jam_tutup');
            $table->string('jam_tutup_weekend')->default('23:30')->after('jam_buka_weekend');
        });

        // Samakan data yang sudah ada dengan papan di kafe: weekday 08.00 - 23.00
        DB::table('contact_infos')->update([
            'jam_buka'  => '08:00',
            'jam_tutup' => '23:00',
        ]);
    }

    public function down(): void
    {
        Schema::table('contact_infos', function (Blueprint $table) {
            $table->dropColumn(['jam_buka_weekend', 'jam_tutup_weekend']);
        });
    }
};
