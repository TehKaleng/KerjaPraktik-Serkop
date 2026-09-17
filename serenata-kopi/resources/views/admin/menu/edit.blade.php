@extends('layouts.admin')
@section('title', 'Edit Menu')

@section('content')
<div class="panel">
  <div class="panel-head">
    <h2>Edit Menu: {{ $menu->nama }}</h2>
    <a href="{{ route('admin.menu.index') }}" class="btn btn-outline btn-sm">Kembali</a>
  </div>
  <form method="POST" action="{{ route('admin.menu.update', $menu) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-grid">
      <div class="field">
        <label>Nama Menu</label>
        <input type="text" name="nama" value="{{ $menu->nama }}" required>
      </div>
      <div class="field">
        <label>Kategori</label>
        <select name="kategori">
          <option value="kopi" @selected($menu->kategori === 'kopi')>Kopi</option>
          <option value="non-kopi" @selected($menu->kategori === 'non-kopi')>Non-Kopi</option>
          <option value="kudapan" @selected($menu->kategori === 'kudapan')>Kudapan</option>
        </select>
      </div>
      <div class="field">
        <label>Harga (Rp)</label>
        <input type="number" name="harga" value="{{ $menu->harga }}" required>
      </div>
      <div class="field">
        <label>Ganti Foto (opsional)</label>
        <input type="file" name="foto" accept="image/*">
      </div>
      <div class="field span-2">
        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="2">{{ $menu->deskripsi }}</textarea>
      </div>
    </div>
    <div class="form-foot">
      <button type="submit" class="btn btn-orange">Simpan Perubahan</button>
    </div>
  </form>
</div>
@endsection