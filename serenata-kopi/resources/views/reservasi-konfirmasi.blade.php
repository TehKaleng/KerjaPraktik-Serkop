@extends('layouts.app')
@section('title', 'Konfirmasi Pembayaran')

@section('content')
<section style="padding:60px 0 100px;">
  <div class="wrap" style="max-width:520px;text-align:center;">
    <div class="section-tag">SATU LANGKAH LAGI</div>
    <h2 style="font-family:'Poppins',sans-serif;font-weight:800;font-size:28px;margin-bottom:10px;">
      Scan QRIS untuk Membayar
    </h2>
    <p style="color:var(--text-muted);margin-bottom:28px;">
      Total: <strong style="color:var(--orange-light);">Rp {{ number_format($reservation->total_harga, 0, ',', '.') }}</strong>
    </p>

    <div style="background:#fff;border-radius:16px;padding:20px;margin-bottom:24px;display:inline-block;">
      @if($kontak->qris_image)
        <img src="{{ asset('storage/' . $kontak->qris_image) }}" alt="QRIS Serenata Kopi & Space" style="max-width:280px;width:100%;">
      @else
        <p style="color:#999;padding:40px;">QRIS belum diatur admin.</p>
      @endif
    </div>

    <p style="color:var(--text-muted);font-size:14px;margin-bottom:24px;">
      Setelah bayar, klik tombol di bawah untuk kirim bukti & detail reservasi ke WhatsApp cafe.
    </p>

    <form method="POST" action="{{ route('reservasi.selesai', $reservation) }}">
      @csrf
      <button type="submit" class="btn-primary" style="border:none;cursor:pointer;width:100%;padding:16px;font-size:16px;">
        Saya Sudah Bayar — Lanjut ke WhatsApp
      </button>
    </form>
  </div>
</section>
@endsection
