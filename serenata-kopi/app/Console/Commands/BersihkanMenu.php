<?php

namespace App\Console\Commands;

use App\Models\Menu;
use App\Models\ReservationItem;
use Database\Seeders\MenuAsliSeeder;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class BersihkanMenu extends Command
{
    protected $signature = 'menu:bersihkan {--hapus : Benar-benar menghapus. Tanpa opsi ini hanya menampilkan pratinjau}';

    protected $description = 'Menampilkan atau menghapus menu yang tidak ada di buku menu (daftarnya ada di MenuAsliSeeder)';

    public function handle(): int
    {
        // Nama menu resmi diambil dari daftar buku menu di MenuAsliSeeder
        $resmi = collect(MenuAsliSeeder::DAFTAR)
            ->map(fn (array $menu) => mb_strtolower(trim($menu[0])))
            ->all();

        $asing = Menu::orderBy('id')->get()
            ->filter(fn (Menu $menu) => ! in_array(mb_strtolower(trim($menu->nama)), $resmi, true))
            ->values();

        if ($asing->isEmpty()) {
            $this->info('Semua menu sudah sesuai buku menu. Tidak ada yang perlu dihapus.');

            return self::SUCCESS;
        }

        // Menu yang pernah dipesan tidak boleh dihapus (database melarangnya, dan riwayat reservasi harus utuh)
        $dipakai = $asing->mapWithKeys(fn (Menu $menu) => [
            $menu->id => ReservationItem::where('menu_id', $menu->id)->count(),
        ]);

        $this->table(
            ['ID', 'Nama', 'Kategori', 'Harga', 'Status'],
            $asing->map(fn (Menu $menu) => [
                $menu->id,
                $menu->nama,
                $menu->kategori,
                'Rp ' . number_format($menu->harga, 0, ',', '.'),
                $dipakai[$menu->id] > 0
                    ? "tidak dihapus: dipakai di {$dipakai[$menu->id]} item reservasi"
                    : 'bisa dihapus',
            ])->all()
        );

        $bisaDihapus = $asing->filter(fn (Menu $menu) => $dipakai[$menu->id] === 0)->values();

        if ($asing->count() > $bisaDihapus->count()) {
            $this->warn('Menu yang dipakai di reservasi dilewati. Hapus dulu reservasi ujinya lewat panel admin, lalu jalankan perintah ini lagi.');
        }

        if (! $this->option('hapus')) {
            $this->warn('Ini hanya pratinjau, belum ada yang dihapus. Tambahkan --hapus untuk menghapus menu berstatus "bisa dihapus".');

            return self::SUCCESS;
        }

        if ($bisaDihapus->isEmpty()) {
            $this->warn('Tidak ada menu yang bisa dihapus.');

            return self::SUCCESS;
        }

        if (! $this->confirm("Hapus {$bisaDihapus->count()} menu berstatus \"bisa dihapus\" di atas? Tindakan ini tidak bisa dibatalkan.", false)) {
            $this->warn('Dibatalkan. Tidak ada yang dihapus.');

            return self::SUCCESS;
        }

        $terhapus = 0;

        foreach ($bisaDihapus as $menu) {
            $nama = $menu->nama;
            $foto = $menu->foto;

            try {
                $menu->delete();
            } catch (QueryException $e) {
                $this->error("Gagal menghapus \"{$nama}\": {$e->getMessage()}");

                continue;
            }

            // File foto ikut dihapus, kecuali masih dipakai menu lain
            if ($foto && ! Menu::where('foto', $foto)->exists()) {
                Storage::disk('public')->delete($foto);
            }

            $this->line("Dihapus: {$nama}");
            $terhapus++;
        }

        $this->info("Selesai. {$terhapus} menu dihapus.");

        return self::SUCCESS;
    }
}
