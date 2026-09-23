<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->foreignId('meja_id')->nullable()->after('user_id')->constrained('meja')->nullOnDelete();
            $table->enum('metode_bayar', ['qris', 'cash'])->default('cash')->after('status');
            $table->enum('status_bayar', ['belum_bayar', 'menunggu_konfirmasi', 'lunas'])->default('belum_bayar')->after('metode_bayar');
            $table->unsignedInteger('total_harga')->default(0)->after('status_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['meja_id']);
            $table->dropColumn(['user_id', 'meja_id', 'metode_bayar', 'status_bayar', 'total_harga']);
        });
    }
};
