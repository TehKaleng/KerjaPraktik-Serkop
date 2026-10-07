<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Email verifikasi akun berbahasa Indonesia.
 * Turunan dari VerifyEmail bawaan Laravel, jadi pembuatan tautan bertanda tangan (signed URL)
 * tetap memakai logika Laravel; yang diganti hanya isi emailnya.
 * Dipanggil dari User::sendEmailVerificationNotification().
 */
class VerifikasiEmailSerenata extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $url   = $this->verificationUrl($notifiable);
        $menit = config('auth.verification.expire', 60);

        return (new MailMessage)
            ->subject('Verifikasi Email Akun ' . config('app.name'))
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Terima kasih sudah mendaftar di ' . config('app.name') . '. Satu langkah lagi: klik tombol di bawah untuk memastikan email ini benar milikmu.')
            ->action('Verifikasi Email', $url)
            ->line("Tautan ini berlaku selama {$menit} menit. Kalau sudah lewat, minta tautan baru dari halaman verifikasi.")
            ->line('Kalau kamu tidak merasa mendaftar, abaikan saja email ini.');
    }
}
