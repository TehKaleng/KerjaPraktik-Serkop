<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mencatat kapan email pengingat reservasi dikirim,
 * supaya satu reservasi hanya menerima satu kali pengingat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->timestamp('pengingat_dikirim_at')->nullable()->after('total_harga');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('pengingat_dikirim_at');
        });
    }
};
