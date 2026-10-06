<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimoniAsliSeeder extends Seeder
{
    /**
     * Ulasan asli pelanggan dari Google Maps.
     * Format: [nama (disingkat), rating, isi ulasan, tampil di halaman depan?]
     *
     * Urutan = dari yang terbaru ke yang lama. Halaman depan menampilkan maksimal 6
     * testimoni yang berstatus tampil.
     *
     * Aman dijalankan berulang: kalau testimoninya sudah ada, tidak diubah sama sekali,
     * jadi hasil edit atau penyembunyian lewat panel admin tetap aman.
     */
    private const DAFTAR = [
        ['Olla R.', 5, 'Gue emang tipe yang simpen masalah sendiri. Tapi entah kenapa, duduk di sini sambil ngopi panas, rasanya plong. Mungkin karena tempatnya adem atau kopinya yang enak. Yang jelas bakal sering balik.', true],
        ['Fiskal H.', 5, 'Tempat bahkan melebihi ekspektasi, yanga awalnya biasa aja, tapi pas coba Dateng kesana sumpah beda banget banget vibe nya, nyaman, adem, asri, intinya recommended banget deh untuk kalian yg blm pernah cobain kesana', true],
        ['Ridho H.', 5, 'Overall oke, paling untuk rooftop di tambah lagi beberapa meja atau kursi dan bagian dinding yg kosong bisa di beri graffiti', false],
        ['Muhammad F.', 5, 'Suasananya menjadi hal yang paling unggul dari seluruh cafe di palembang, bagaimana tidak, pemandangan yang disajikan layaknya di drama film korea, berhadapan dengan lrt, dan jalanan sudirman yang sangat rapih.', true],
        ['Nyayu B.', 5, 'Best local coffee shop in town, small space but feel cozy, ga expect kopinya bakal nyaman di tenggorokan dan ramah di kantong, kalo ke palembang ga pernah skip ke serenata 🙌🏻', true],
        ['Vani A.', 5, 'Tempat yang nyaman untuk bersantai, datang kesini setelah panas-panas an di ampera. Menu banyak dan lengkap', true],
        ['Kurnia Y.', 5, 'Tempat nongkrong yg enak ada life musiknya. Kumpul sama teman2 jadi seru', true],
    ];

    public function run(): void
    {
        foreach (self::DAFTAR as $urutan => [$nama, $rating, $isi, $tampil]) {
            $t = Testimonial::firstOrNew(['nama' => $nama, 'peran' => 'Ulasan Google']);

            if ($t->exists) {
                continue;
            }

            $t->rating     = $rating;
            $t->isi        = $isi;
            $t->tampil     = $tampil;
            $t->created_at = now()->subMinutes($urutan); // menjaga urutan tampil: yang pertama paling baru
            $t->save();
        }
    }
}
