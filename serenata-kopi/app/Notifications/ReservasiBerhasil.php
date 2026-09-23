<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ReservasiBerhasil extends Notification
{
    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'pesan'          => "Reservasi untuk {$this->reservation->jumlah_orang} orang pada "
                . $this->reservation->tanggal->format('d M Y') . " jam "
                . substr($this->reservation->jam, 0, 5) . " berhasil dibuat.",
            'tanggal'        => $this->reservation->tanggal->format('d M Y'),
            'jam'            => substr($this->reservation->jam, 0, 5),
            'meja'           => $this->reservation->meja->kode ?? '-',
            'status_bayar'   => $this->reservation->status_bayar,
        ];
    }
}
