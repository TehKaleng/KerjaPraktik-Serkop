<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Mengirim ulang email verifikasi (tombol "Kirim Ulang").
     * Dibatasi 6 kali per menit lewat throttle di routes/auth.php.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            // Biasanya karena pengaturan SMTP di .env salah atau internet putus
            Log::error('Gagal mengirim email verifikasi: ' . $e->getMessage());

            return back()->withErrors([
                'verifikasi' => 'Email verifikasi belum bisa dikirim saat ini. Coba lagi beberapa saat lagi.',
            ]);
        }

        return back()->with('status', 'verification-link-sent');
    }
}
