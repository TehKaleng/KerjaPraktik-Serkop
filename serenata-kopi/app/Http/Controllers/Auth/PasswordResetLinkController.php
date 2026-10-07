<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /** Halaman form "Lupa password". */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Mengirim tautan atur ulang password ke email.
     *
     * Pesan sukses sengaja SAMA, baik email terdaftar maupun tidak, supaya orang lain
     * tidak bisa memakai form ini untuk mengecek email siapa saja yang punya akun.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            // Biasanya karena pengaturan SMTP di .env salah atau internet putus
            Log::error('Gagal mengirim email lupa password: ' . $e->getMessage());

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email belum bisa dikirim saat ini. Coba lagi beberapa saat lagi, atau hubungi kafe lewat WhatsApp.']);
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Tautan baru saja dikirim. Tunggu sekitar 1 menit sebelum meminta lagi.']);
        }

        // RESET_LINK_SENT atau INVALID_USER: pesannya sama
        return back()->with('status', 'Kalau email tersebut terdaftar, tautan untuk mengatur ulang password sudah kami kirim. Cek kotak masuk atau folder spam.');
    }
}
