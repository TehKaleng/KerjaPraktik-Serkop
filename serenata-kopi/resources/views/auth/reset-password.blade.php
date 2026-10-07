<x-guest-layout>
    <h2 style="font-size:20px;font-weight:700;color:var(--text-light);margin:0 0 8px;">Buat Password Baru</h2>
    <div class="mb-4 text-sm text-gray-600">
        Isi password baru untuk akunmu. Minimal 8 karakter.
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token dari tautan di email -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password baru -->
        <div class="mt-4">
            <x-input-label for="password" value="Password Baru" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Ulangi Password Baru" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm" href="{{ route('login') }}">&larr; Batal</a>

            <x-primary-button>
                Simpan Password
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
