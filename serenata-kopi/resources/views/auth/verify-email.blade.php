<x-guest-layout>
    <h2 style="font-size:20px;font-weight:700;color:var(--text-light);margin:0 0 8px;">Verifikasi Email</h2>
    <div class="mb-4 text-sm text-gray-600">
        Kami sudah mengirim tautan verifikasi ke
        <strong style="color:var(--text-light);">{{ auth()->user()->email }}</strong>.
        Buka email tersebut dan klik tombol <strong style="color:var(--text-light);">Verifikasi Email</strong>
        untuk mengaktifkan akunmu. Cek juga folder Spam kalau belum terlihat.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm" style="color:#7ee08a;">
            Tautan verifikasi baru sudah dikirim. Tautan lama otomatis tidak dipakai lagi.
        </div>
    @endif

    @foreach ($errors->get('verifikasi') as $pesan)
        <div class="mb-4 text-sm" style="color:#f0a0a0;">{{ $pesan }}</div>
    @endforeach

    <div class="flex items-center justify-between mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Kirim Ulang Email
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;padding:0;color:var(--orange-light);font-size:13.5px;text-decoration:underline;cursor:pointer;font-family:inherit;">
                Keluar
            </button>
        </form>
    </div>

    <p class="mt-6 text-sm text-gray-600" style="margin-top:22px;">
        Salah ketik email? Keluar, lalu daftar ulang dengan email yang benar.
        Ingin memesan tanpa akun? Keluar, lalu buka halaman reservasi sebagai tamu.
    </p>
</x-guest-layout>
