<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Batas pembuatan reservasi. Tamu (tanpa akun) dibatasi lebih ketat daripada pemesan yang login.
        // Pemesan login tetap dibatasi per alamat internet juga, supaya tidak bisa menyiasati
        // dengan membuat banyak akun.
        RateLimiter::for('reservasi', function (Request $request) {
            $ip = 'ip:' . $request->ip();

            if ($user = $request->user()) {
                return [
                    Limit::perHour(15)->by('akun:' . $user->id),
                    Limit::perHour(30)->by($ip),
                ];
            }

            return Limit::perHour(4)->by($ip);
        });
    }
}