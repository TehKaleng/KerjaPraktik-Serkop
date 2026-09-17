<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('peran')->nullable(); // contoh: Freelancer, Mahasiswa
            $table->unsignedTinyInteger('rating')->default(5); // 1-5
            $table->text('isi');
            $table->boolean('tampil')->default(true); // tampil di halaman depan atau tidak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
