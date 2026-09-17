<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Testimonial;
use App\Models\ContactInfo;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Menu contoh
        Menu::firstOrCreate(
            ['nama' => 'Serenata Signature'],
            ['kategori' => 'kopi', 'harga' => 28000, 'deskripsi' => 'Espresso, susu oat, sirup gula aren.']
        );
        Menu::firstOrCreate(
            ['nama' => 'Matcha Latte'],
            ['kategori' => 'non-kopi', 'harga' => 26000, 'deskripsi' => 'Matcha grade ceremonial dengan susu segar.']
        );
        Menu::firstOrCreate(
            ['nama' => 'Butter Croissant'],
            ['kategori' => 'kudapan', 'harga' => 22000, 'deskripsi' => 'Dipanggang setiap pagi, renyah di luar, lembut di dalam.']
        );

        // Testimoni contoh
        Testimonial::firstOrCreate(
            ['nama' => 'Rani A.'],
            ['peran' => 'Freelancer', 'rating' => 5, 'isi' => 'Tempat favorit buat kerja remote, wifi kencang dan kopinya konsisten enak.', 'tampil' => true]
        );
        Testimonial::firstOrCreate(
            ['nama' => 'Fajar S.'],
            ['peran' => 'Pemilik Usaha', 'rating' => 5, 'isi' => 'Suasananya tenang banget, cocok buat meeting santai sama klien.', 'tampil' => true]
        );

        // Info kontak default (cuma 1 baris)
        ContactInfo::current();
    }
}
