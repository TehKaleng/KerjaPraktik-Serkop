<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Email "lupa password" berbahasa Indonesia.
 * Salam penutup ada di resources/views/vendor/notifications/email.blade.php.
 */
class ResetPasswordSerenata extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Tautan ke form password baru: /reset-password/{token}?email=...
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $menit = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);

        return (new MailMessage)
            ->subject('Atur Ulang Password Akun ' . config('app.name'))
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kami menerima permintaan untuk mengatur ulang password akun ' . config('app.name') . ' milikmu.')
            ->action('Atur Ulang Password', $url)
            ->line("Tautan ini berlaku selama {$menit} menit.")
            ->line('Kalau kamu tidak merasa meminta ini, abaikan saja email ini. Password-mu tidak akan berubah.');
    }
}