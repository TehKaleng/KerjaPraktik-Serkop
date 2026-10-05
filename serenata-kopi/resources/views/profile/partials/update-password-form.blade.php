@php
    $iconMata = '<svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>'
              . '<svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
@endphp

<div>
    <h2 class="pf-title">Ganti Password</h2>
    <p class="pf-desc">Gunakan password yang panjang dan sulit ditebak supaya akunmu tetap aman.</p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="pf-field">
            <label for="update_password_current_password">Password Saat Ini</label>
            <div class="pf-input-wrap">
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password">
                <button type="button" class="pf-toggle-pw" data-target="update_password_current_password" aria-label="Tampilkan password" aria-pressed="false">{!! $iconMata !!}</button>
            </div>
            @foreach($errors->updatePassword->get('current_password') as $message)
                <p class="pf-error">{{ $message }}</p>
            @endforeach
        </div>

        <div class="pf-grid">
            <div class="pf-field">
                <label for="update_password_password">Password Baru</label>
                <div class="pf-input-wrap">
                    <input id="update_password_password" name="password" type="password" autocomplete="new-password">
                    <button type="button" class="pf-toggle-pw" data-target="update_password_password" aria-label="Tampilkan password" aria-pressed="false">{!! $iconMata !!}</button>
                </div>
                @foreach($errors->updatePassword->get('password') as $message)
                    <p class="pf-error">{{ $message }}</p>
                @endforeach
            </div>

            <div class="pf-field">
                <label for="update_password_password_confirmation">Ulangi Password Baru</label>
                <div class="pf-input-wrap">
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                    <button type="button" class="pf-toggle-pw" data-target="update_password_password_confirmation" aria-label="Tampilkan password" aria-pressed="false">{!! $iconMata !!}</button>
                </div>
                @foreach($errors->updatePassword->get('password_confirmation') as $message)
                    <p class="pf-error">{{ $message }}</p>
                @endforeach
            </div>
        </div>

        <div class="pf-actions">
            <button type="submit" class="pf-btn">Simpan Password</button>

            @if (session('status') === 'password-updated')
                <span class="pf-saved">Tersimpan.</span>
            @endif
        </div>
    </form>

    {{-- Tombol mata: ganti tipe kolom antara "password" (titik-titik) dan "text" (terlihat) --}}
    <script>
        document.querySelectorAll('.pf-toggle-pw').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.dataset.target);
                var tampil = input.type === 'password';
                input.type = tampil ? 'text' : 'password';
                btn.setAttribute('aria-pressed', tampil ? 'true' : 'false');
                btn.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });
    </script>
</div>