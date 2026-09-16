@extends('layouts.admin')
@section('title', 'Info Kontak & Lokasi')

@section('content')

<div class="panel">
  <div class="panel-head"><h2>Edit Info Kontak</h2></div>
  <form method="POST" action="{{ route('admin.kontak.update') }}">
    @csrf
    @method('PUT')
    <div class="form-grid">
      <div class="field span-2">
        <label>Alamat Lengkap</label>
        <input type="text" name="alamat" value="Jl. Melati No. 12, Palembang, Sumatera Selatan">
      </div>
      <div class="field">
        <label>Jam Buka</label>
        <input type="text" name="jam_buka" value="08:00">
      </div>
      <div class="field">
        <label>Jam Tutup</label>
        <input type="text" name="jam_tutup" value="22:00">
      </div>
      <div class="field">
        <label>Nomor WhatsApp Reservasi</label>
        <input type="text" name="whatsapp" placeholder="628123456789" value="628123456789">
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" value="hello@serenatakopi.id">
      </div>
      <div class="field span-2">
        <label>Link Google Maps (Embed URL)</label>
        <input type="text" name="maps_embed" placeholder="https://www.google.com/maps/embed?pb=...">
      </div>
      <div class="field span-2">
        <label>Link Google Maps (untuk tombol "Lihat di Google Maps")</label>
        <input type="text" name="maps_link" placeholder="https://maps.app.goo.gl/xxxxx">
      </div>
    </div>
    <div class="form-foot">
      <button type="submit" class="btn btn-orange">Simpan Perubahan</button>
    </div>
  </form>
</div>

@endsection
