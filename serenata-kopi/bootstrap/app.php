<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Diperlukan saat diakses lewat ngrok / reverse proxy: Laravel baru tahu
        // request aslinya https dari header X-Forwarded-Proto. Tanpa ini, link CSS/gambar
        // bisa ke-generate http:// dan diblokir browser (mixed content).
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Halaman "419 Page Expired" (token form / sesi kedaluwarsa) diganti dengan pesan ramah:
        // pengunjung dikembalikan ke halaman sebelumnya dan diminta mencoba lagi.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() !== 419) {
                return null; // error lain tetap ditangani seperti biasa
            }

            $pesan = 'Sesi halaman sudah habis. Silakan coba lagi.';

            if ($request->is('login')) {
                // form login hanya menampilkan error di bawah kolom email
                return redirect()->route('login')
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => $pesan]);
            }

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation', 'current_password', '_token'))
                ->withErrors(['sesi' => $pesan]);
        });
    })->create();