<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Notifications\PengingatReservasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Mengirim email pengingat untuk reservasi yang waktunya sudah dekat.
 *
 * Dijalankan otomatis setiap 5 menit oleh penjadwal Laravel (lihat routes/console.php).
 * Bisa juga dijalankan manual:
 *   php artisan reservasi:kirim-pengingat --pratinjau   (hanya menampilkan, tidak mengirim)
 *   php artisan reservasi:kirim-pengingat               (mengirim)
 *
 * Aturan:
 *  - Hanya reservasi milik akun yang emailnya sudah diverifikasi (tamu tidak punya email).
 *  - Status pending atau confirmed, dan pengingat belum pernah dikirim.
 *  - Waktu reservasi berada dalam JAM_SEBELUM jam ke depan.
 *  - Reservasi yang dibuat kurang dari MENIT_MINIMAL menit sebelum waktunya dilewati
 *    (pelanggan baru saja memesan, jadi tidak perlu diingatkan).
 */
class KirimPengingatReservasi extends Command
{
    /** Pengingat dikirim saat waktu reservasi tinggal sekian jam lagi. */
    public const JAM_SEBELUM = 2;

    /** Jarak minimal antara waktu pemesanan dan waktu reservasi agar pengingat dikirim. */
    public const MENIT_MINIMAL = 30;

    protected $signature = 'reservasi:kirim-pengingat {--pratinjau : Hanya menampilkan reservasi yang akan diingatkan, tanpa mengirim email}';

    protected $description = 'Mengirim email pengingat untuk reservasi yang waktunya sudah dekat';

    public function handle(): int
    {
        $sekarang = now();
        $batas    = $sekarang->copy()->addHours(self::JAM_SEBELUM);

        $kandidat = Reservation::with(['user', 'meja', 'items.menu'])
            ->whereNotNull('user_id')
            ->whereNull('pengingat_dikirim_at')
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('tanggal', '>=', $sekarang->toDateString())
            ->whereDate('tanggal', '<=', $batas->toDateString())
            ->get()
            ->filter(function (Reservation $r) use ($sekarang, $batas) {
                $waktu = $r->waktuMulai();

                return $r->user
                    && $r->user->hasVerifiedEmail()
                    && $waktu->greaterThan($sekarang)
                    && $waktu->lessThanOrEqualTo($batas)
                    && $r->created_at->lessThanOrEqualTo($waktu->copy()->subMinutes(self::MENIT_MINIMAL));
            })
            ->values();

        if ($kandidat->isEmpty()) {
            $this->info('Tidak ada reservasi yang perlu diingatkan saat ini.');

            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Nama', 'Email', 'Waktu', 'Meja'],
            $kandidat->map(fn (Reservation $r) => [
                $r->id, $r->nama, $r->user->email, $r->waktuMulai()->format('d-m-Y H:i'), $r->meja->kode ?? '-',
            ])->all()
        );

        if ($this->option('pratinjau')) {
            $this->warn('Pratinjau saja, belum ada email yang dikirim.');

            return self::SUCCESS;
        }

        $terkirim = 0;

        foreach ($kandidat as $r) {
            try {
                $r->user->notify(new PengingatReservasi($r));
                $r->forceFill(['pengingat_dikirim_at' => now()])->save();
                $terkirim++;
            } catch (\Throwable $e) {
                // Biasanya SMTP bermasalah. Tidak ditandai terkirim, jadi akan dicoba lagi pada putaran berikutnya.
                Log::error("Gagal mengirim pengingat reservasi #{$r->id}: " . $e->getMessage());
                $this->error("Gagal mengirim ke {$r->user->email}: " . $e->getMessage());
            }
        }

        $this->info("Selesai. {$terkirim} email pengingat terkirim.");

        return self::SUCCESS;
    }
}
