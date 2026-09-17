<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel ini sengaja dirancang cuma nyimpen SATU baris data (single row settings)
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id();
            $table->string('alamat');
            $table->string('jam_buka')->default('08:00');
            $table->string('jam_tutup')->default('22:00');
            $table->string('whatsapp');
            $table->string('email')->nullable();
            $table->text('maps_embed')->nullable();
            $table->string('maps_link')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_infos');
    }
};
