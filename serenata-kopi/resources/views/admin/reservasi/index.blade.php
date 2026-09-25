@extends('layouts.admin')
@section('title', 'Daftar Reservasi')

@section('content')

<div class="panel">
  <div class="panel-head"><h2>Semua Reservasi</h2></div>
  <table>
    <thead>
      <tr>
        <th>Nama</th><th>Meja</th><th>Tanggal</th><th>Jam</th><th>Menu</th>
        <th>Total</th><th>Bayar</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reservasis as $r)
        <tr>
          <td>{{ $r['nama'] }}<br><span style="color:var(--text-gray);font-size:12px;">{{ $r['wa'] }}</span></td>
          <td>{{ $r['meja'] }}</td>
          <td>{{ $r['tanggal'] }}</td>
          <td>{{ $r['jam'] }}</td>
          <td style="max-width:220px;font-size:13px;">{{ $r['menu'] ?: '-' }}</td>
          <td>Rp {{ number_format($r['total'], 0, ',', '.') }}</td>
          <td>
            <span class="badge {{ $r['status_bayar'] === 'lunas' ? 'badge-active' : 'badge-pending' }}">
              {{ strtoupper($r['metode_bayar']) }} ·
              @if($r['status_bayar'] === 'lunas') Lunas
              @elseif($r['status_bayar'] === 'menunggu_konfirmasi') Menunggu
              @else Belum
              @endif
            </span>
          </td>
          <td>
            @if($r['status'] === 'confirmed')
              <span class="badge badge-active">Terkonfirmasi</span>
            @else
              <span class="badge badge-pending">Menunggu</span>
            @endif
          </td>
          <td class="table-actions" style="flex-direction:column;gap:6px;align-items:stretch;">
            @if($r['status'] !== 'confirmed')
              <form method="POST" action="{{ route('admin.reservasi.confirm', $r['id']) }}">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-outline btn-sm" style="width:100%;">Konfirmasi Hadir</button>
              </form>
            @endif
            @if($r['status_bayar'] === 'menunggu_konfirmasi')
              <form method="POST" action="{{ route('admin.reservasi.confirm-bayar', $r['id']) }}">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-orange btn-sm" style="width:100%;">Konfirmasi Bayar</button>
              </form>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="9" style="text-align:center;color:var(--text-gray);">Belum ada reservasi masuk.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
