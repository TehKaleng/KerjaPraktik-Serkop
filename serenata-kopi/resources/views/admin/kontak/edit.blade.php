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
        <input type="text" name="alamat" value="{{ $kontak->alamat }}">
      </div>
      <div class="field">
        <label>Jam Buka</label>
        <input type="text" name="jam_buka" value="{{ $kontak->jam_buka }}">
      </div>
      <div class="field">
        <label>Jam Tutup</label>
        <input type="text" name="jam_tutup" value="{{ $kontak->jam_tutup }}">
      </div>
      <div class="field">
        <label>Nomor WhatsApp Reservasi</label>
        <input type="text" name="whatsapp" placeholder="628123456789" value="{{ $kontak->whatsapp }}">
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" value="{{ $kontak->email }}">
      </div>
      <div class="field span-2">
        <label>Link Google Maps (Embed URL)</label>
        <input type="text" name="maps_embed" placeholder="https://www.google.com/maps/embed?pb=..." value="{{ $kontak->maps_embed }}">
      </div>
      <div class="field span-2">
        <label>Link Google Maps (untuk tombol "Lihat di Google Maps")</label>
        <input type="text" name="maps_link" placeholder="https://maps.app.goo.gl/xxxxx" value="{{ $kontak->maps_link }}">
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
