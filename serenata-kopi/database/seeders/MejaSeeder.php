<?php

namespace Database\Seeders;

use App\Models\Meja;
use Illuminate\Database\Seeder;

class MejaSeeder extends Seeder
{
    public function run(): void
    {
        $konfigurasi = [
            1 => ['jumlah' => 6, 'kapasitas' => 2], // lantai 1: 6 meja isi 2 orang
            2 => ['jumlah' => 5, 'kapasitas' => 4], // lantai 2: 5 meja isi 4 orang
            3 => ['jumlah' => 3, 'kapasitas' => 6], // lantai 3: 3 meja isi 6 orang (VIP/rombongan)
        ];

        foreach ($konfigurasi as $lantai => $cfg) {
            for ($i = 1; $i <= $cfg['jumlah']; $i++) {
                $nomor = str_pad($i, 2, '0', STR_PAD_LEFT);
                Meja::firstOrCreate(
                    ['kode' => "L{$lantai}-{$nomor}"],
                    [
                        'lantai'     => $lantai,
                        'nomor_meja' => $nomor,
                        'kapasitas'  => $cfg['kapasitas'],
                    ]
                );
            }
        }
    }
}
