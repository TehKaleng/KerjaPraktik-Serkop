@extends('layouts.admin')
@section('title', 'Kelola Menu')

@section('content')

<div class="panel" style="margin-bottom:24px;">
  <div class="panel-head">
    <h2>Tambah Menu Baru</h2>
  </div>
  <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-grid">
      <div class="field">
        <label>Nama Menu</label>
        <input type="text" name="nama" placeholder="Contoh: Serenata Signature" required>
      </div>
      <div class="field">
        <label>Kategori</label>
        <select name="kategori">
          <option value="kopi">Kopi</option>
          <option value="non-kopi">Non-Kopi</option>
          <option value="kudapan">Kudapan</option>
        </select>
      </div>
      <div class="field">
        <label>Harga (Rp)</label>
        <input type="number" name="harga" placeholder="28000" required>
      </div>
      <div class="field">
        <label>Foto Menu</label>
        <input type="file" name="foto" accept="image/*">
      </div>
      <div class="field span-2">
        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat menu..."></textarea>
      </div>
    </div>
    <div class="form-foot">
      <button type="submit" class="btn btn-orange">Simpan Menu</button>
    </div>
  </form>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Daftar Menu</h2>
  </div>
  <table>
    <thead>
      <tr><th>Foto</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse(($menus ?? [
          ['nama' => 'Serenata Signature', 'kategori' => 'Kopi', 'harga' => 28000],
          ['nama' => 'Matcha Latte', 'kategori' => 'Non-Kopi', 'harga' => 26000],
          ['nama' => 'Butter Croissant', 'kategori' => 'Kudapan', 'harga' => 22000],
      ]) as $menu)
        <tr>
          <td>
            @if(!empty($menu['foto']))
              <img src="{{ asset('storage/' . $menu['foto']) }}" class="thumb" alt="{{ $menu['nama'] }}">
            @else
              <div class="thumb"></div>
            @endif
          </td>
          <td>{{ $menu['nama'] }}</td>
          <td>{{ $menu['kategori'] }}</td>
          <td>Rp {{ number_format($menu['harga'], 0, ',', '.') }}</td>
          <td class="table-actions">
            <a href="{{ route('admin.menu.edit', $menu['id']) }}" class="btn btn-outline btn-sm">Edit</a>
            <form method="POST" action="{{ route('admin.menu.destroy', $menu['id']) }}" onsubmit="return confirm('Yakin hapus menu ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger-ghost btn-sm">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" style="text-align:center;color:var(--text-gray);">Belum ada menu. Tambahkan menu pertama di atas.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
