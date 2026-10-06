@extends('layouts.admin')
@section('title', 'Info Kontak & Lokasi')

@section('content')

<div class="panel">
  <div class="panel-head"><h2>Edit Info Kontak</h2></div>
  <form method="POST" action="{{ route('admin.kontak.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-grid">
      <div class="field span-2">
        <label>Alamat Lengkap</label>
        <input type="text" name="alamat" value="{{ old('alamat', $kontak->alamat) }}">
      </div>

      {{-- Jam operasional dibedakan weekday & weekend, sesuai papan di kafe --}}
      <div class="field span-2">
        <label style="font-weight:600;">Jam Operasional</label>
        <p style="font-size:13px;color:var(--text-gray);margin:4px 0 0;">
          Dipakai di halaman depan dan untuk membatasi jam reservasi pelanggan.
        </p>
      </div>
      <div class="field">
        <label>Jam Buka (Senin - Jumat)</label>
        <input type="time" name="jam_buka" value="{{ old('jam_buka', substr($kontak->jam_buka, 0, 5)) }}" required>
      </div>
      <div class="field">
        <label>Jam Tutup (Senin - Jumat)</label>
        <input type="time" name="jam_tutup" value="{{ old('jam_tutup', substr($kontak->jam_tutup, 0, 5)) }}" required>
      </div>
      <div class="field">
        <label>Jam Buka (Sabtu - Minggu)</label>
        <input type="time" name="jam_buka_weekend" value="{{ old('jam_buka_weekend', substr($kontak->jam_buka_weekend, 0, 5)) }}" required>
      </div>
      <div class="field">
        <label>Jam Tutup (Sabtu - Minggu)</label>
        <input type="time" name="jam_tutup_weekend" value="{{ old('jam_tutup_weekend', substr($kontak->jam_tutup_weekend, 0, 5)) }}" required>
      </div>

      <div class="field">
        <label>Nomor WhatsApp Reservasi</label>
        <input type="text" name="whatsapp" placeholder="628123456789" value="{{ old('whatsapp', $kontak->whatsapp) }}">
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $kontak->email) }}">
      </div>
      <div class="field span-2">
        <label>Link Google Maps (Embed URL)</label>
        <input type="text" name="maps_embed" placeholder="https://www.google.com/maps/embed?pb=..." value="{{ old('maps_embed', $kontak->maps_embed) }}">
      </div>
      <div class="field span-2">
        <label>Link Google Maps (untuk tombol "Lihat di Google Maps")</label>
        <input type="text" name="maps_link" placeholder="https://maps.app.goo.gl/xxxxx" value="{{ old('maps_link', $kontak->maps_link) }}">
      </div>
      <div class="field span-2">
        <label>Foto QRIS Pembayaran</label>
        <div style="display:flex;gap:20px;align-items:center;flex-wrap:wrap;margin-top:6px;">
          @if($kontak->qris_image)
            <img src="{{ asset('storage/' . $kontak->qris_image) }}" alt="QRIS" style="width:120px;border-radius:8px;border:1px solid var(--border);">
          @else
            <div class="upload-box" style="width:120px;height:120px;display:flex;align-items:center;justify-content:center;padding:8px;font-size:12px;">
              Belum ada QRIS
            </div>
          @endif
          <input type="file" name="qris_image" accept="image/*" style="flex:1;min-width:200px;">
        </div>
      </div>
    </div>
    <div class="form-foot">
      <button type="submit" class="btn btn-orange">Simpan Perubahan</button>
    </div>
  </form>
</div>

@endsection