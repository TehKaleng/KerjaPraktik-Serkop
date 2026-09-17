@extends('layouts.admin')
@section('title', 'Daftar Reservasi')

@section('content')

<div class="panel">
  <div class="panel-head"><h2>Semua Reservasi</h2></div>
  <table>
    <thead>
      <tr><th>Nama</th><th>No. WhatsApp</th><th>Tanggal</th><th>Jam</th><th>Jumlah Orang</th><th>Catatan</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse(($reservasis ?? [
          ['nama' => 'Rani Anggraini', 'wa' => '0812xxxxxxx', 'tanggal' => '17 Sep 2026', 'jam' => '19:00', 'orang' => 2, 'catatan' => '-', 'status' => 'confirmed'],
          ['nama' => 'Fajar Setiawan', 'wa' => '0813xxxxxxx', 'tanggal' => '17 Sep 2026', 'jam' => '20:30', 'orang' => 4, 'catatan' => 'Dekat colokan listrik', 'status' => 'pending'],
      ]) as $r)
        <tr>
          <td>{{ $r['nama'] }}</td>
          <td>{{ $r['wa'] }}</td>
          <td>{{ $r['tanggal'] }}</td>
          <td>{{ $r['jam'] }}</td>
          <td>{{ $r['orang'] }} orang</td>
          <td>{{ $r['catatan'] }}</td>
          <td>
            @if($r['status'] === 'confirmed')
              <span class="badge badge-active">Terkonfirmasi</span>
            @else
              <span class="badge badge-pending">Menunggu</span>
            @endif
          </td>
          <td class="table-actions">
            <form method="POST" action="{{ route('admin.reservasi.confirm', $r['id']) }}">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-outline btn-sm">Konfirmasi</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" style="text-align:center;color:var(--text-gray);">Belum ada reservasi masuk.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
