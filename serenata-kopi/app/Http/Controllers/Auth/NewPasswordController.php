<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /** Form password baru (dibuka dari tautan di email). */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /** Menyimpan password baru. */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'password.required'  => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min'       => 'Password minimal :min karakter.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password'       => Hash::make($request->password),
                    'remember_token' => Str::random(60), // semua sesi "ingat saya" yang lama ikut keluar
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('status', 'Password berhasil diubah. Silakan masuk dengan password baru.');
        }

        $pesan = match ($status) {
            Password::INVALID_TOKEN => 'Tautan atur ulang sudah tidak berlaku atau sudah pernah dipakai. Minta tautan baru lewat halaman Lupa Password.',
            Password::INVALID_USER  => 'Email tidak cocok dengan tautan ini. Pastikan memakai email yang sama dengan yang menerima tautan.',
            default                 => 'Password belum bisa diubah. Coba lagi.',
        };

        return back()->withInput($request->only('email'))->withErrors(['email' => $pesan]);
    }
}
