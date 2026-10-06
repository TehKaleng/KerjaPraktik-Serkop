<?php

namespace Database\Seeders;

use App\Models\ContactInfo;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Menu asli kafe (lihat MenuAsliSeeder)
        $this->call(MenuAsliSeeder::class);

        // Testimoni: ulasan asli pelanggan dari Google Maps (lihat TestimoniAsliSeeder)
        $this->call(TestimoniAsliSeeder::class);

        // Foto suasana kafe untuk bagian Suasana (lihat GaleriAsliSeeder)
        $this->call(GaleriAsliSeeder::class);

        // Info kontak default (cuma 1 baris)
        ContactInfo::current();
    }
}