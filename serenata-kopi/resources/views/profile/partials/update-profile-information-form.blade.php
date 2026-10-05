@php($user = $user ?? auth()->user())

<div>
    <h2 class="pf-title">Informasi Profil</h2>
    <p class="pf-desc">Perbarui nama dan alamat email akunmu.</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="pf-grid">
            <div class="pf-field">
                <label for="name">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name">
                @foreach($errors->get('name') as $message)
                    <p class="pf-error">{{ $message }}</p>
                @endforeach
            </div>

            <div class="pf-field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @foreach($errors->get('email') as $message)
                    <p class="pf-error">{{ $message }}</p>
                @endforeach
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <p class="pf-note">
                Alamat email kamu belum diverifikasi.
                <button form="send-verification" class="pf-link-btn">Kirim ulang email verifikasi.</button>
            </p>

            @if (session('status') === 'verification-link-sent')
                <p class="pf-ok">Tautan verifikasi baru sudah dikirim ke email kamu.</p>
            @endif
        @endif

        <div class="pf-actions">
            <button type="submit" class="pf-btn">Simpan Perubahan</button>

            @if (session('status') === 'profile-updated')
                <span class="pf-saved">Tersimpan.</span>
            @endif
        </div>
    </form>
</div>