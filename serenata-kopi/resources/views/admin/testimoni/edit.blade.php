@extends('layouts.admin')
@section('title', 'Edit Testimoni')

@section('content')
<div class="panel">
  <div class="panel-head">
    <h2>Edit Testimoni: {{ $testimoni->nama }}</h2>
    <a href="{{ route('admin.testimoni.index') }}" class="btn btn-outline btn-sm">Kembali</a>
  </div>
  <form method="POST" action="{{ route('admin.testimoni.update', $testimoni) }}">
    @csrf
    @method('PUT')
    <div class="form-grid">
      <div class="field">
        <label>Nama Pelanggan</label>
        <input type="text" name="nama" value="{{ $testimoni->nama }}" required>
      </div>
      <div class="field">
        <label>Peran/Status</label>
        <input type="text" name="peran" value="{{ $testimoni->peran }}">
      </div>
      <div class="field">
        <label>Rating</label>
        <select name="rating">
          @for($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}" @selected($testimoni->rating == $i)>{{ str_repeat('⭐', $i) }} ({{ $i }})</option>
          @endfor
        </select>
      </div>
      <div class="field">
        <label>Tampilkan di Website?</label>
        <select name="status">
          <option value="1" @selected($testimoni->tampil)>Ya, tampilkan</option>
          <option value="0" @selected(!$testimoni->tampil)>Sembunyikan</option>
        </select>
      </div>
      <div class="field span-2">
        <label>Isi Testimoni</label>
        <textarea name="isi" rows="3" required>{{ $testimoni->isi }}</textarea>
      </div>
    </div>
    <div class="form-foot">
      <button type="submit" class="btn btn-orange">Simpan Perubahan</button>
    </div>
  </form>
</div>
@endsection