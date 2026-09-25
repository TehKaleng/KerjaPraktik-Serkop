@extends('layouts.admin')
@section('title', 'Kelola Meja')

@section('content')

<div class="panel" style="margin-bottom:24px;">
  <div class="panel-head"><h2>Tambah Meja Baru</h2></div>
  <form method="POST" action="{{ route('admin.meja.store') }}">
    @csrf
    <div class="form-grid">
      <div class="field">
        <label>Lantai</label>
        <select name="lantai" required>
          <option value="1">Lantai 1</option>
          <option value="2">Lantai 2</option>
          <option value="3">Lantai 3</option>
        </select>
      </div>
      <div class="field">
        <label>Nomor Meja</label>
        <input type="text" name="nomor_meja" placeholder="Contoh: 07" required>
      </div>
      <div class="field">
        <label>Kapasitas (orang)</label>
        <input type="number" name="kapasitas" min="1" value="2" required>
      </div>
    </div>
    <div class="form-foot">
      <button type="submit" class="btn btn-orange">Tambah Meja</button>
    </div>
  </form>
</div>

@foreach([1, 2, 3] as $lantai)
  <div class="panel" style="margin-bottom:24px;">
    <div class="panel-head"><h2>Lantai {{ $lantai }}</h2></div>
    <table>
      <thead>
        <tr><th>Kode</th><th>Nomor</th><th>Kapasitas</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        @forelse(($mejaByLantai[$lantai] ?? []) as $meja)
          <tr>
            <td>{{ $meja->kode }}</td>
            <td>{{ $meja->nomor_meja }}</td>
            <td>
              <form method="POST" action="{{ route('admin.meja.update', $meja) }}" style="display:flex;gap:8px;align-items:center;">
                @csrf
                @method('PUT')
                <input type="number" name="kapasitas" value="{{ $meja->kapasitas }}" min="1" style="width:70px;padding:6px 10px;border-radius:6px;border:1px solid var(--border);">
                <button type="submit" class="btn btn-outline btn-sm">Simpan</button>
              </form>
            </td>
            <td>
              <form method="POST" action="{{ route('admin.meja.destroy', $meja) }}" onsubmit="return confirm('Yakin hapus meja {{ $meja->kode }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger-ghost btn-sm">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" style="text-align:center;color:var(--text-gray);">Belum ada meja di lantai ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endforeach

@endsection
