<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dipakai di halaman reservasi yang boleh diakses tamu.
 *
 * Beda dengan middleware 'verified' bawaan Laravel (yang juga menolak tamu):
 *  - Tamu (tidak login)            -> boleh lewat
 *  - Login, email sudah diverifikasi -> boleh lewat
 *  - Login, email belum diverifikasi -> diarahkan ke halaman verifikasi
 *
 * Alamat halaman yang dituju disimpan, jadi setelah verifikasi pelanggan langsung
 * dikembalikan ke form reservasi.
 */
class WajibVerifikasiJikaLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            // Hanya halaman (GET) yang disimpan sebagai tujuan; kiriman form (POST) tidak bisa diulang
            if ($request->isMethod('GET')) {
                redirect()->setIntendedUrl($request->fullUrl());
            }

            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
