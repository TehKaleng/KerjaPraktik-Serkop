<?php

namespace App\Notifications;

use App\Models\ContactInfo;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Pengingat reservasi yang dikirim beberapa jam sebelum waktu kedatangan.
 * Dikirim lewat email dan juga disimpan sebagai notifikasi di dashboard pelanggan.
 * Dipanggil oleh perintah: php artisan reservasi:kirim-pengingat
 */
class PengingatReservasi extends Notification
{
    use Queueable;

    public function __construct(public Reservation $reservation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $r       = $this->reservation;
        $waktu   = $r->waktuMulai()->locale('id');
        $kontak  = ContactInfo::current();
        $status  = $r->status === 'confirmed' ? 'Sudah dikonfirmasi kafe' : 'Menunggu konfirmasi kafe';
        $bayar   = $r->metode_bayar === 'qris' ? 'QRIS' : 'Bayar di kasir saat datang';

        $pesan = (new MailMessage)
            ->subject('Pengingat Reservasi ' . config('app.name') . ' — ' . $waktu->translatedFormat('l, d F Y') . ' pukul ' . $waktu->format('H.i'))
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kami mengingatkan bahwa kamu memiliki reservasi di ' . config('app.name') . ' dalam waktu dekat. Berikut rinciannya:')
            ->line('**Tanggal:** ' . $waktu->translatedFormat('l, d F Y'))
            ->line('**Jam:** ' . $waktu->format('H.i') . ' WIB')
            ->line('**Meja:** ' . ($r->meja ? $r->meja->kode . ' (Lantai ' . $r->meja->lantai . ')' : '-'))
            ->line('**Jumlah orang:** ' . $r->jumlah_orang)
            ->line('**Status:** ' . $status);

        if ($r->items->count()) {
            $daftar = $r->items->map(fn ($item) => ($item->menu->nama ?? 'Menu dihapus') . ' x' . $item->qty)->implode(', ');
            $pesan->line('**Pesanan:** ' . $daftar)
                ->line('**Total:** Rp ' . number_format($r->total_harga, 0, ',', '.') . ' (' . $bayar . ')');
        }

        $pesan->action('Lihat Reservasi Saya', route('dashboard'));

        $wa = preg_replace('/\D+/', '', (string) $kontak->whatsapp);
        if ($wa !== '') {
            $pesan->line('Berhalangan hadir atau ingin mengubah jadwal? Hubungi kami lewat WhatsApp di https://wa.me/' . $wa . ' supaya meja bisa diberikan ke pelanggan lain.');
        }

        return $pesan->line('Sampai jumpa di ' . config('app.name') . '!');
    }

    public function toArray(object $notifiable): array
    {
        $waktu = $this->reservation->waktuMulai()->locale('id');

        return [
            'reservation_id' => $this->reservation->id,
            'pesan'          => 'Pengingat: reservasi kamu ' . $waktu->translatedFormat('l, d F Y') . ' pukul ' . $waktu->format('H.i')
                . ' di meja ' . ($this->reservation->meja->kode ?? '-') . '. Sampai jumpa!',
        ];
    }
}
