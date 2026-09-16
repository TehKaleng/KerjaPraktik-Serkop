@extends('layouts.admin')
@section('title', 'Kelola Galeri')

@section('content')

<div class="panel" style="margin-bottom:24px;">
  <div class="panel-head"><h2>Upload Foto Suasana</h2></div>
  <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="padding:22px;">
      <div class="upload-box">
        <input type="file" name="foto" accept="image/*" style="margin-bottom:8px;">
        <div><strong>Klik untuk pilih foto</strong> atau tarik file ke sini</div>
        <div style="margin-top:4px;font-size:12.5px;">Format JPG/PNG, maks 2MB</div>
      </div>
      <div class="field" style="margin-top:14px;">
        <label>Keterangan Foto (opsional)</label>
        <input type="text" name="keterangan" placeholder="Contoh: Sudut baca lantai 2">
      </div>
    </div>
    <div class="form-foot">
      <button type="submit" class="btn btn-orange">Upload Foto</button>
    </div>
  </form>
</div>

<div class="panel">
  <div class="panel-head"><h2>Galeri Saat Ini</h2></div>
  <div class="gallery-grid">
    @forelse(($fotos ?? [
        ['keterangan' => 'Sudut baca lantai 2'],
        ['keterangan' => 'Meja outdoor'],
        ['keterangan' => 'Bar kopi'],
        ['keterangan' => 'Area komunal'],
    ]) as $foto)
      <div class="gallery-item">
        <div class="gallery-photo">Foto belum diunggah<br>(placeholder)</div>
        <div class="gallery-caption">
          <span>{{ $foto['keterangan'] }}</span>
          <button class="btn btn-danger-ghost btn-sm">Hapus</button>
        </div>
      </div>
    @empty
      <div style="grid-column:1/-1;text-align:center;color:var(--text-gray);padding:20px;">Belum ada foto.</div>
    @endforelse
  </div>
</div>

@endsection
