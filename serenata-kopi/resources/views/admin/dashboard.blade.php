@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

<div class="stat-grid">
  <div class="stat-card">
    <div class="label">Total Menu</div>
    <div class="value">{{ $totalMenu ?? 12 }}</div>
    <span class="tag">3 kategori</span>
  </div>
  <div class="stat-card">
    <div class="label">Total Foto Galeri</div>
    <div class="value">{{ $totalGaleri ?? 8 }}</div>
    <span class="tag">Suasana cafe</span>
  </div>
  <div class="stat-card">
    <div class="label">Testimoni Tampil</div>
    <div class="value">{{ $totalTestimoni ?? 6 }}</div>
    <span class="tag">4.8 rata-rata</span>
  </div>
  <div class="stat-card">
    <div class="label">Reservasi Hari Ini</div>
    <div class="value">{{ $reservasiHariIni ?? 5 }}</div>
    <span class="tag">2 menunggu konfirmasi</span>
  </div>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Reservasi Terbaru</h2>
    <a href="{{ route('admin.reservasi.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
  </div>
  <table>
    <thead>
      <tr><th>Nama</th><th>No. WhatsApp</th><th>Tanggal</th><th>Jam</th><th>Jumlah Orang</th><th>Status</th></tr>
    </thead>
    <tbody>
      @forelse(($reservasiTerbaru ?? [
          ['nama' => 'Rani Anggraini', 'wa' => '0812xxxxxxx', 'tanggal' => '17 Sep 2026', 'jam' => '19:00', 'orang' => 2, 'status' => 'confirmed'],
          ['nama' => 'Fajar Setiawan', 'wa' => '0813xxxxxxx', 'tanggal' => '17 Sep 2026', 'jam' => '20:30', 'orang' => 4, 'status' => 'pending'],
          ['nama' => 'Dinda Putri', 'wa' => '0857xxxxxxx', 'tanggal' => '18 Sep 2026', 'jam' => '18:00', 'orang' => 3, 'status' => 'pending'],
      ]) as $r)
        <tr>
          <td>{{ $r['nama'] }}</td>
          <td>{{ $r['wa'] }}</td>
          <td>{{ $r['tanggal'] }}</td>
          <td>{{ $r['jam'] }}</td>
          <td>{{ $r['orang'] }} orang</td>
          <td>
            @if($r['status'] === 'confirmed')
              <span class="badge badge-active">Terkonfirmasi</span>
            @else
              <span class="badge badge-pending">Menunggu</span>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align:center;color:var(--text-gray);">Belum ada reservasi.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
