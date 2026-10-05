<div>
    <h2 class="pf-title">Hapus Akun</h2>
    <p class="pf-desc">
        Setelah akun dihapus, seluruh data dan riwayat reservasimu akan dihapus permanen
        dan tidak dapat dikembalikan.
    </p>

    {{-- Pakai <details> bawaan HTML (tanpa JavaScript/Alpine) --}}
    <details class="pf-details" @if($errors->userDeletion->isNotEmpty()) open @endif>
        <summary class="pf-btn pf-btn-danger">Hapus Akun Saya</summary>

        <form method="post" action="{{ route('profile.destroy') }}" class="pf-delete-form">
            @csrf
            @method('delete')

            <div class="pf-field">
                <label for="delete_password">Masukkan password untuk konfirmasi</label>
                <input id="delete_password" name="password" type="password" placeholder="Password">
                @foreach($errors->userDeletion->get('password') as $message)
                    <p class="pf-error">{{ $message }}</p>
                @endforeach
            </div>

            <button type="submit" class="pf-btn pf-btn-danger-solid">Ya, Hapus Akun Saya Secara Permanen</button>
        </form>
    </details>
</div>