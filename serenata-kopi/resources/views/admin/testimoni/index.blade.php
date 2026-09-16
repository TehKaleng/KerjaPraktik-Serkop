@extends('layouts.admin')
@section('title', 'Kelola Testimoni')

@section('content')

<div class="panel" style="margin-bottom:24px;">
  <div class="panel-head"><h2>Tambah Testimoni</h2></div>
  <form method="POST" action="{{ route('admin.testimoni.store') }}">
    @csrf
    <div class="form-grid">
      <div class="field">
        <label>Nama Pelanggan</label>
        <input type="text" name="nama" placeholder="Rani A." required>
      </div>
      <div class="field">
        <label>Peran/Status</label>
        <input type="text" name="peran" placeholder="Freelancer">
      </div>
      <div class="field">
        <label>Rating</label>
        <select name="rating">
          <option value="5">⭐⭐⭐⭐⭐ (5)</option>
          <option value="4">⭐⭐⭐⭐ (4)</option>
          <option value="3">⭐⭐⭐ (3)</option>
        </select>
      </div>
      <div class="field">
        <label>Tampilkan di Website?</label>
        <select name="status">
          <option value="1">Ya, tampilkan</option>
          <option value="0">Sembunyikan</option>
        </select>
      </div>
      <div class="field span-2">
        <label>Isi Testimoni</label>
        <textarea name="isi" rows="3" placeholder="Tempat favorit buat kerja remote..." required></textarea>
      </div>
    </div>
    <div class="form-foot">
      <button type="submit" class="btn btn-orange">Simpan Testimoni</button>
    </div>
  </form>
</div>

<div class="panel">
  <div class="panel-head"><h2>Daftar Testimoni</h2></div>
  <table>
    <thead>
      <tr><th>Nama</th><th>Peran</th><th>Rating</th><th>Isi</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse(($testimonis ?? [
          ['nama' => 'Rani A.', 'peran' => 'Freelancer', 'rating' => 5, 'isi' => 'Tempat favorit buat kerja remote, wifi kencang dan kopinya konsisten enak.', 'tampil' => true],
          ['nama' => 'Fajar S.', 'peran' => 'Pemilik Usaha', 'rating' => 5, 'isi' => 'Suasananya tenang banget, cocok buat meeting santai sama klien.', 'tampil' => true],
          ['nama' => 'Dinda P.', 'peran' => 'Mahasiswa', 'rating' => 4, 'isi' => 'Croissant-nya juara, staff-nya ramah.', 'tampil' => false],
      ]) as $t)
        <tr>
          <td>{{ $t['nama'] }}</td>
          <td>{{ $t['peran'] }}</td>
          <td>{{ str_repeat('⭐', $t['rating']) }}</td>
          <td style="max-width:260px;">{{ \Illuminate\Support\Str::limit($t['isi'], 60) }}</td>
          <td>
            @if($t['tampil'])
              <span class="badge badge-active">Tampil</span>
            @else
              <span class="badge badge-pending">Tersembunyi</span>
            @endif
          </td>
          <td class="table-actions">
            <button class="btn btn-outline btn-sm">Edit</button>
            <button class="btn btn-danger-ghost btn-sm">Hapus</button>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align:center;color:var(--text-gray);">Belum ada testimoni.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
