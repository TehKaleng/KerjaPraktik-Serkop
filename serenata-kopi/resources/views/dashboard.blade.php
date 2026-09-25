@extends('layouts.app')
@section('title', 'Dashboard Saya')

@section('content')
<section style="padding:50px 0 100px;">
  <div class="wrap">

    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:32px;">
      <div>
        <div class="section-tag">DASHBOARD SAYA</div>
        <h2 style="font-family:'Poppins',sans-serif;font-weight:800;font-size:clamp(24px,3vw,32px);">
          Halo, {{ auth()->user()->name }}
        </h2>
      </div>
      <div style="display:flex;gap:12px;">
        <a href="{{ route('reservasi.form') }}" class="btn-primary">+ Reservasi Baru</a>
        <a href="{{ route('profile.edit') }}" class="btn-ghost">Edit Profil</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn-ghost" style="border:none;cursor:pointer;font:inherit;">Logout</button>
        </form>
      </div>
    </div>

    <!-- NOTIFIKASI -->
    <div class="dash-card" style="margin-bottom:28px;">
      <h3>🔔 Notifikasi</h3>
      @forelse($notifikasi as $notif)
        <div class="dash-notif {{ is_null($notif->read_at) ? 'belum-dibaca' : '' }}">
          <p>{{ $notif->data['pesan'] ?? 'Ada pembaruan reservasi.' }}</p>
          <span>{{ $notif->created_at->diffForHumans() }}</span>
        </div>
      @empty
        <p style="color:var(--text-muted);font-size:14px;">Belum ada notifikasi.</p>
      @endforelse
    </div>

    <!-- RIWAYAT RESERVASI -->
    <div class="dash-card">
      <h3>📅 Riwayat Reservasi Saya</h3>
      @forelse($reservasis as $r)
        <div class="dash-reservasi">
          <div class="dash-reservasi-head">
            <div>
              <strong>{{ $r->tanggal->format('d M Y') }}</strong> · {{ substr($r->jam, 0, 5) }}
              @if($r->meja)
                · Meja {{ $r->meja->kode }}
              @endif
            </div>
            <div style="display:flex;gap:8px;">
              <span class="dash-badge status-{{ $r->status }}">
                {{ $r->status === 'confirmed' ? 'Terkonfirmasi' : 'Menunggu' }}
              </span>
              <span class="dash-badge bayar-{{ $r->status_bayar }}">
                @if($r->status_bayar === 'lunas') Lunas
                @elseif($r->status_bayar === 'menunggu_konfirmasi') Menunggu Verifikasi Bayar
                @else Belum Bayar
                @endif
              </span>
            </div>
          </div>

          @if($r->items->count())
            <ul class="dash-item-list">
              @foreach($r->items as $item)
                <li>{{ $item->menu->nama ?? 'Menu dihapus' }} x{{ $item->qty }} — Rp {{ number_format($item->subtotal(), 0, ',', '.') }}</li>
              @endforeach
            </ul>
          @endif

          <div class="dash-reservasi-foot">
            <span>{{ $r->jumlah_orang }} orang · {{ strtoupper($r->metode_bayar) }}</span>
            <strong>Total: Rp {{ number_format($r->total_harga, 0, ',', '.') }}</strong>
          </div>
        </div>
      @empty
        <p style="color:var(--text-muted);font-size:14px;">Kamu belum pernah melakukan reservasi.</p>
      @endforelse
    </div>

  </div>
</section>

<style>
  .dash-card{background:var(--navy);border-radius:16px;padding:24px;}
  .dash-card h3{font-size:17px;margin-bottom:16px;color:var(--text-light);}
  .dash-notif{padding:12px 0;border-bottom:1px solid rgba(245,241,234,0.1);color:var(--text-light);font-size:14px;}
  .dash-notif:last-child{border-bottom:none;}
  .dash-notif span{display:block;color:var(--text-muted);font-size:12px;margin-top:4px;}
  .dash-notif.belum-dibaca{border-left:3px solid var(--orange);padding-left:12px;}

  .dash-reservasi{background:var(--navy-soft);border-radius:12px;padding:18px;margin-bottom:14px;}
  .dash-reservasi:last-child{margin-bottom:0;}
  .dash-reservasi-head{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;color:var(--text-light);margin-bottom:10px;}
  .dash-badge{padding:4px 10px;border-radius:999px;font-size:11.5px;font-weight:600;}
  .status-pending{background:#4a3a1a;color:#f0c674;}
  .status-confirmed{background:#1e4a2a;color:#7ee08a;}
  .bayar-belum_bayar{background:#4a2a2a;color:#f09a9a;}
  .bayar-menunggu_konfirmasi{background:#4a3a1a;color:#f0c674;}
  .bayar-lunas{background:#1e4a2a;color:#7ee08a;}
  .dash-item-list{list-style:none;padding:0;margin:0 0 12px;font-size:13.5px;color:var(--text-muted);}
  .dash-item-list li{padding:3px 0;}
  .dash-reservasi-foot{display:flex;justify-content:space-between;align-items:center;font-size:13.5px;color:var(--text-muted);padding-top:10px;border-top:1px solid rgba(245,241,234,0.1);}
  .dash-reservasi-foot strong{color:var(--orange-light);font-size:15px;}
</style>
@endsection
