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
     * Urutan = urutan tampil. Halaman depan menampilkan 4 foto terbaru di bagian Suasana:
     * foto pertama di daftar ini menjadi kotak besar di kiri, berikutnya kotak-kotak kecil.
     *
     * Saat seeder dijalankan, file disalin ke penyimpanan (disk "public", folder galeri/) dan dicatat
     * di tabel galeri, sama seperti kalau diunggah lewat panel admin (menu Galeri).
     *
     * Versi utuh: foto di grid dipotong sesuai bentuk kotaknya. Kalau ada file dengan nama sama di
     * database/seeders/foto/galeri/penuh/, file itu (foto tanpa dipotong) ikut disalin ke galeri/penuh/
     * dan dipakai di tampilan besar "Lihat semua foto".
     * Menjalankannya berulang aman: foto yang sudah tercatat tidak digandakan, hanya urutannya
     * dirapikan lagi sesuai daftar ini. Kalau kamu menghapus salah satu foto ini lewat admin lalu
     * menjalankan seeder lagi, foto itu akan muncul kembali.
     */
    private const DAFTAR = [
        ['lantai-2-a.jpg', 'Lantai 2'],
        ['tampak-depan-malam.jpg', 'Tampak depan di malam hari'],
        ['area-bar.jpg', 'Area bar dan halaman depan'],
        ['neon-serenata.jpg', 'Sudut neon Serenata'],
        ['pemandangan-lrt.jpg', 'Pemandangan jalan dan LRT dari lantai atas'],
        ['rooftop-malam.jpg', 'Area terbuka di malam hari'],
        ['ruang-lampu-bola.jpg', 'Sudut lampu bola dan poster'],
        ['ruang-kayu.jpg', 'Ruang berdinding kayu'],
        ['halaman-malam.jpg', 'Area luar di malam hari'],
    ];

    public function run(): void
    {
        foreach (self::DAFTAR as $urutan => [$namaFile, $keterangan]) {
            $tujuan = 'galeri/' . $namaFile;
            $foto = GalleryPhoto::where('foto', $tujuan)->first();

            if ($foto === null) {
                $sumber = database_path('seeders/foto/galeri/' . $namaFile);

                if (! File::exists($sumber)) {
                    continue;
                }

                Storage::disk('public')->put($tujuan, File::get($sumber));

                $foto = new GalleryPhoto(['foto' => $tujuan, 'keterangan' => $keterangan]);
            }

            // Versi utuh (tanpa dipotong) untuk tampilan besar, kalau tersedia
            $sumberPenuh = database_path('seeders/foto/galeri/penuh/' . $namaFile);
            if (File::exists($sumberPenuh)) {
                Storage::disk('public')->put('galeri/penuh/' . $namaFile, File::get($sumberPenuh));
            }

            // Menjaga urutan tampil: yang pertama di daftar paling baru (kotak besar)
            $foto->created_at = now()->subMinutes($urutan);
            $foto->save();
        }
    }
}