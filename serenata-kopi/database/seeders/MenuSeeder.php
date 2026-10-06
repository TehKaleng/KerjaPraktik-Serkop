<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\ContactInfo;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Menu asli kafe (lihat MenuAsliSeeder)
        $this->call(MenuAsliSeeder::class);

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