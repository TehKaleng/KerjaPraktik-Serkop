@extends('layouts.app')
@section('title', 'Reservasi')

@section('content')
<section style="padding:80px 0;min-height:70vh;">
  <div class="wrap" style="max-width:560px;">
    <div class="section-tag">RESERVASI MEJA</div>
    <h2 style="font-family:'Poppins',sans-serif;font-weight:800;font-size:clamp(26px,3.2vw,34px);margin-bottom:10px;">
      Amankan tempat duduk kamu
    </h2>
    <p style="color:var(--text-muted);margin-bottom:32px;">
      Isi form di bawah, nanti kamu bakal diarahkan langsung ke WhatsApp kami buat konfirmasi.
    </p>

    <form method="POST" action="{{ route('reservasi.store') }}" style="display:flex;flex-direction:column;gap:16px;">
      @csrf
      <div>
        <label style="font-size:14px;font-weight:600;display:block;margin-bottom:6px;">Nama Lengkap</label>
        <input type="text" name="nama" required
          style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.25);background:var(--navy);color:var(--text-light);">
      </div>
      <div>
        <label style="font-size:14px;font-weight:600;display:block;margin-bottom:6px;">Nomor WhatsApp</label>
        <input type="text" name="whatsapp" placeholder="08xxxxxxxxxx" required
          style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.25);background:var(--navy);color:var(--text-light);">
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
          <label style="font-size:14px;font-weight:600;display:block;margin-bottom:6px;">Tanggal</label>
          <input type="date" name="tanggal" required
            style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.25);background:var(--navy);color:var(--text-light);">
        </div>
        <div>
          <label style="font-size:14px;font-weight:600;display:block;margin-bottom:6px;">Jam</label>
          <input type="time" name="jam" required
            style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.25);background:var(--navy);color:var(--text-light);">
        </div>
      </div>
      <div>
        <label style="font-size:14px;font-weight:600;display:block;margin-bottom:6px;">Jumlah Orang</label>
        <input type="number" name="jumlah_orang" min="1" value="2" required
          style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.25);background:var(--navy);color:var(--text-light);">
      </div>
      <div>
        <label style="font-size:14px;font-weight:600;display:block;margin-bottom:6px;">Catatan (opsional)</label>
        <textarea name="catatan" rows="2" placeholder="Contoh: dekat colokan listrik"
          style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.25);background:var(--navy);color:var(--text-light);"></textarea>
      </div>
      <button type="submit" class="btn-primary" style="border:none;cursor:pointer;margin-top:8px;">
        Lanjut ke WhatsApp
      </button>
    </form>
  </div>
</section>
@endsection
