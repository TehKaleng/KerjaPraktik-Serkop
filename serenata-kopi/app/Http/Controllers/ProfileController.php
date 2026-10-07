<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Ganti email = wajib verifikasi ulang untuk email yang baru
        $emailBerubah = $user->isDirty('email');

        if ($emailBerubah) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailBerubah) {
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim email verifikasi setelah ganti email: ' . $e->getMessage());
            }

            return Redirect::route('profile.edit')->with('status', 'verification-link-sent');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Akun admin tidak boleh dihapus lewat halaman profil, supaya panel admin tidak terkunci
        // tanpa admin. Dicek paling awal, sebelum password diperiksa.
        abort_if($request->user()->hasRole('admin'), 403, 'Akun admin tidak dapat dihapus.');

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
