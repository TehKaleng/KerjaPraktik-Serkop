<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meja', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('lantai'); // 1, 2, atau 3
            $table->string('nomor_meja'); // contoh: "01", "02"
            $table->string('kode')->unique(); // contoh: "L1-01" (lantai 1, meja 01)
            $table->unsignedTinyInteger('kapasitas')->default(2); // muat berapa orang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meja');
    }
};
