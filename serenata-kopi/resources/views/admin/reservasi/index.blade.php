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
          <td>{{ $r['nama'] }}
            @if($r['akun'])
              <span class="badge badge-active" title="Dipesan lewat akun terdaftar">Akun</span>
            @else
              <span class="badge" style="background:#EEF0F4;color:#5b6475;" title="Dipesan sebagai tamu, tanpa akun">Tamu</span>
            @endif<br><span style="color:var(--text-gray);font-size:12px;">{{ $r['wa'] }}</span></td>
          <td>
            {{ $r['meja'] }}
            @if($r['bentrok'])
              <div style="margin-top:6px;font-size:12px;color:#C0442B;line-height:1.4;" title="Meja sudah dipakai reservasi terkonfirmasi">
                ⚠ Sudah terkonfirmasi untuk {{ $r['bentrok'] }}
              </div>
            @elseif($r['antrean'] > 0)
              <div style="margin-top:6px;font-size:12px;color:#B4720A;line-height:1.4;" title="Reservasi lain yang masih menunggu di meja dan jam yang sama">
                {{ $r['antrean'] }} reservasi lain menunggu di meja &amp; jam ini
              </div>
            @endif
          </td>
          <td>{{ $r['tanggal'] }}</td>
          <td>{{ $r['jam'] }}</td>
          <td style="max-width:220px;font-size:13px;">{{ $r['menu'] ?: '-' }}</td>
          <td>Rp {{ number_format($r['total'], 0, ',', '.') }}</td>
          <td>
            <span class="badge {{ $r['status_bayar'] === 'lunas' ? 'badge-active' : 'badge-pending' }}">
              {{ $r['metode_bayar'] === 'qris' ? 'QRIS' : 'Kasir' }} ·
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
              @if($r['bentrok'])
                <button type="button" class="btn btn-outline btn-sm" style="width:100%;opacity:.5;cursor:not-allowed;" disabled title="Meja sudah dipakai reservasi terkonfirmasi">Meja Terpakai</button>
              @else
                <form method="POST" action="{{ route('admin.reservasi.confirm', $r['id']) }}" class="confirm-delete"
                      data-message="Konfirmasi reservasi {{ $r['nama'] }} di meja {{ $r['meja'] }}, {{ $r['tanggal'] }} pukul {{ $r['jam'] }}?{{ $r['antrean'] > 0 ? ' Ada '.$r['antrean'].' reservasi lain yang menunggu di meja & jam ini; mereka tidak bisa dikonfirmasi lagi setelah ini.' : '' }}">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn btn-outline btn-sm" style="width:100%;">Konfirmasi</button>
                </form>
              @endif
            @endif
            @if($r['status_bayar'] !== 'lunas')
              <form method="POST" action="{{ route('admin.reservasi.confirm-bayar', $r['id']) }}" class="confirm-delete" data-message="Tandai pembayaran {{ $r['nama'] }} (Rp {{ number_format($r['total'], 0, ',', '.') }}) sebagai LUNAS? Pastikan uangnya sudah diterima di kasir.">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-orange btn-sm" style="width:100%;">{{ $r['status_bayar'] === 'menunggu_konfirmasi' ? 'Konfirmasi Bayar' : 'Tandai Lunas' }}</button>
              </form>
            @endif
            <form method="POST" action="{{ route('admin.reservasi.destroy', $r['id']) }}" class="confirm-delete" data-message="Yakin hapus reservasi {{ $r['nama'] }} ini? Tidak bisa dibatalkan.">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger-ghost btn-sm" style="width:100%;">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="9" style="text-align:center;color:var(--text-gray);">Belum ada reservasi masuk.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection

