<x-guest-layout>
    <h2 style="font-size:20px;font-weight:700;color:var(--text-light);margin:0 0 8px;">Lupa Password</h2>
    <div class="mb-4 text-sm text-gray-600">
        Masukkan email akunmu. Kami akan mengirim tautan untuk membuat password baru.
    </div>

    <!-- Pesan setelah tautan dikirim -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm" href="{{ route('login') }}">&larr; Kembali ke halaman masuk</a>

            <x-primary-button>
                Kirim Tautan
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
