@extends('layouts.admin')
@section('title', 'Daftar Reservasi')

@section('content')

{{-- Filter, pencarian, dan urutan --}}
@php($inp = 'padding:8px 11px;border:1px solid var(--border);border-radius:8px;font-family:inherit;font-size:13.5px;background:#fff;')
<div class="panel" style="padding:16px 22px;margin-bottom:20px;">

  {{-- Tab tanggal (pencarian & filter lain tetap terbawa saat pindah tab) --}}
  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px;">
    @foreach(\App\Http\Controllers\Admin\ReservationController::FILTER as $kunci => $teks)
      <a href="{{ route('admin.reservasi.index', array_filter(['filter' => $kunci, 'q' => $cari, 'status' => $status, 'bayar' => $bayar, 'urut' => $urut !== 'jam' ? $urut : null])) }}"
         class="btn btn-sm {{ $filter === $kunci ? 'btn-orange' : 'btn-outline' }}">
        {{ $teks }}
        <span style="margin-left:6px;font-size:11.5px;font-weight:700;padding:1px 7px;border-radius:999px;background:{{ $filter === $kunci ? 'rgba(255,255,255,.25)' : '#EEF0F4' }};">{{ $jumlah[$kunci] }}</span>
      </a>
    @endforeach
  </div>

  <form method="GET" action="{{ route('admin.reservasi.index') }}" class="rsv-filter">
    @if($filter)
      <input type="hidden" name="filter" value="{{ $filter }}">
    @endif

    <div class="rsv-f-cari">
      <label for="q">Cari</label>
      <input type="search" id="q" name="q" value="{{ $cari }}" placeholder="Nama, nomor WA, kode meja, atau #ID" style="{{ $inp }}width:100%;">
    </div>
    <div>
      <label for="dari">Dari tanggal</label>
      <input type="date" id="dari" name="dari" value="{{ $dari }}" style="{{ $inp }}">
    </div>
    <div>
      <label for="sampai">Sampai tanggal</label>
      <input type="date" id="sampai" name="sampai" value="{{ $sampai }}" style="{{ $inp }}">
    </div>
    <div>
      <label for="status">Status</label>
      <select id="status" name="status" style="{{ $inp }}">
        <option value="">Semua</option>
        <option value="pending" @selected($status === 'pending')>Menunggu</option>
        <option value="confirmed" @selected($status === 'confirmed')>Terkonfirmasi</option>
      </select>
    </div>
    <div>
      <label for="bayar">Pembayaran</label>
      <select id="bayar" name="bayar" style="{{ $inp }}">
        <option value="">Semua</option>
        <option value="belum" @selected($bayar === 'belum')>Belum lunas</option>
        <option value="lunas" @selected($bayar === 'lunas')>Lunas</option>
      </select>
    </div>
    <div>
      <label for="urut">Urutkan</label>
      <select id="urut" name="urut" style="{{ $inp }}">
        @foreach(\App\Http\Controllers\Admin\ReservationController::URUTAN as $kunci => $teks)
          <option value="{{ $kunci }}" @selected($urut === $kunci)>{{ $teks }}</option>
        @endforeach
      </select>
    </div>
    <div>
      {{-- Label kosong supaya tombol berada di baris yang sama persis dengan kolom isian --}}
      <label aria-hidden="true">&nbsp;</label>
      <div class="rsv-f-aksi">
      <button type="submit" class="btn btn-orange btn-sm">Terapkan</button>
      @if($adaFilterLanjut)
        <a href="{{ route('admin.reservasi.index') }}" class="btn btn-outline btn-sm">Reset</a>
      @endif
      </div>
    </div>
  </form>

  @if($adaFilterLanjut)
    <div style="margin-top:12px;font-size:13px;color:var(--text-gray);">
      Ditemukan <strong style="color:inherit;">{{ $reservasis->count() }}</strong> reservasi
      @if($cari !== '') untuk "<strong>{{ $cari }}</strong>"@endif
      @if($dari || $sampai)
        pada {{ $dari ? \Carbon\Carbon::parse($dari)->format('d M Y') : 'awal' }} – {{ $sampai ? \Carbon\Carbon::parse($sampai)->format('d M Y') : 'seterusnya' }}
      @elseif($filter)
        di tab {{ \App\Http\Controllers\Admin\ReservationController::FILTER[$filter] }}
      @endif.
    </div>
  @endif

  @if($menungguHariIni > 0 && $filter !== 'hari-ini')
    <div style="margin-top:10px;font-size:13px;color:#B4720A;">
      Ada {{ $menungguHariIni }} reservasi hari ini yang masih menunggu konfirmasi.
      <a href="{{ route('admin.reservasi.index', ['filter' => 'hari-ini']) }}" style="color:#E8562A;font-weight:600;">Lihat</a>
    </div>
  @endif
</div>

<style>
  .rsv-filter{display:grid;grid-template-columns:2fr repeat(5, minmax(0,1fr)) auto;gap:12px;align-items:start;}
  .rsv-filter > div{margin:0;}
  .rsv-filter label{display:block;font-size:12.5px;font-weight:600;color:var(--text-gray);margin:0 0 5px;line-height:18px;height:18px;}
  /* Semua kolom isian & tombol disamakan tingginya supaya sejajar */
  .rsv-filter input, .rsv-filter select{width:100%;height:42px;margin:0;box-sizing:border-box;vertical-align:middle;}
  .rsv-f-aksi{display:flex;gap:8px;align-items:center;height:42px;}
  .rsv-f-aksi .btn{height:42px;margin:0;padding:0 18px;display:inline-flex;align-items:center;justify-content:center;box-sizing:border-box;white-space:nowrap;line-height:1;}
  @media(max-width:1200px){ .rsv-filter{grid-template-columns:repeat(3, minmax(0,1fr));} .rsv-f-cari{grid-column:1 / -1;} }
  @media(max-width:640px){ .rsv-filter{grid-template-columns:1fr 1fr;} }
</style>

@forelse($perTanggal as $tgl => $daftar)
<div class="panel" style="margin-bottom:20px;">
  <div class="panel-head">
    <h2>
      {{ $daftar->first()['hari'] }}
      @if($tgl === $hariIni)
        <span class="badge badge-active" style="margin-left:6px;">Hari ini</span>
      @elseif($tgl < $hariIni)
        <span class="badge" style="margin-left:6px;background:#EEF0F4;color:#5b6475;">Sudah lewat</span>
      @endif
    </h2>
    <span style="font-size:13px;color:var(--text-gray);">{{ $daftar->count() }} reservasi</span>
  </div>
  <div style="overflow-x:auto;">
  <table style="min-width:900px;">
    <thead>
      <tr>
        <th>Nama</th><th>Meja</th><th>Jam</th><th>Menu</th>
        <th>Total</th><th>Bayar</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($daftar as $r)
        <tr>
          <td>{!! $cari !== '' ? preg_replace('/(' . preg_quote(e($cari), '/') . ')/i', '<mark style="background:#FCE3D9;padding:0 2px;border-radius:3px;">$1</mark>', e($r['nama'])) : e($r['nama']) !!}<br><span style="color:var(--text-gray);font-size:11.5px;">#{{ $r['id'] }}</span>
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
            @endforeach
    </tbody>
  </table>
  </div>
</div>
@empty
<div class="panel" style="padding:40px;text-align:center;color:var(--text-gray);">
  @if($adaFilterLanjut)
    Tidak ada reservasi yang cocok dengan pencarian atau filter ini.
    <a href="{{ route('admin.reservasi.index') }}" style="color:#E8562A;font-weight:600;">Reset filter</a>
  @elseif($filter === 'hari-ini')
    Belum ada reservasi untuk hari ini.
  @elseif($filter === 'mendatang')
    Belum ada reservasi untuk hari-hari mendatang.
  @elseif($filter === 'sebelumnya')
    Belum ada riwayat reservasi.
  @else
    Belum ada reservasi masuk.
  @endif
</div>
@endforelse

@endsection