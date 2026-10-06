<?php

namespace Database\Seeders;

use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GaleriAsliSeeder extends Seeder
{
    /**
     * Foto suasana kafe yang disimpan di repo (folder database/seeders/foto/galeri/).
     * Format: [nama file, keterangan]
     *
     * Urutan = dari yang paling depan. Halaman depan menampilkan 4 foto terbaru di bagian Suasana,
     * dan foto paling baru menjadi kotak besar di kiri, jadi foto pertama di daftar ini yang paling menonjol.
     *
     * Saat seeder dijalankan, file disalin ke penyimpanan (disk "public", folder galeri/) dan dicatat
     * di tabel galeri, sama seperti kalau diunggah lewat panel admin (menu Galeri).
     * Foto yang sudah tercatat dilewati, jadi aman dijalankan berulang. Kalau kamu menghapus salah satu
     * foto ini lewat admin lalu menjalankan seeder ini lagi, foto itu akan muncul kembali.
     */
    private const DAFTAR = [
        ['lantai-2-a.jpg', 'Lantai 2'],
    ];

    public function run(): void
    {
        foreach (self::DAFTAR as $urutan => [$namaFile, $keterangan]) {
            $sumber = database_path('seeders/foto/galeri/' . $namaFile);
            $tujuan = 'galeri/' . $namaFile;

            if (! File::exists($sumber) || GalleryPhoto::where('foto', $tujuan)->exists()) {
                continue;
            }

            Storage::disk('public')->put($tujuan, File::get($sumber));

            $foto = new GalleryPhoto(['foto' => $tujuan, 'keterangan' => $keterangan]);
            $foto->created_at = now()->subMinutes($urutan); // menjaga urutan: yang pertama paling baru
            $foto->save();
        }
    }
}
