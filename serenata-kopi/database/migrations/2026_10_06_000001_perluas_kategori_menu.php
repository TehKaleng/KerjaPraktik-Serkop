<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Menambah dua kategori menu: 'makanan' (Main Course) dan 'tambahan' (Add-ons).
 * Kolom kategori berupa ENUM di MySQL/MariaDB, jadi nilainya harus diperluas.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE menus MODIFY kategori ENUM('kopi','non-kopi','kudapan','makanan','tambahan') NOT NULL DEFAULT 'kopi'");
        }
    }

    public function down(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            // Menu 'makanan' dan 'tambahan' dipindah dulu ke 'kudapan' supaya tidak ada data yang melanggar ENUM lama
            DB::table('menus')->whereIn('kategori', ['makanan', 'tambahan'])->update(['kategori' => 'kudapan']);
            DB::statement("ALTER TABLE menus MODIFY kategori ENUM('kopi','non-kopi','kudapan') NOT NULL DEFAULT 'kopi'");
        }
    }
};
