@extends('layouts.app')
@section('title', 'Reservasi')

@section('content')
<section style="padding:60px 0 100px;">
  <div class="wrap" style="max-width:820px;">
    <div class="section-tag">RESERVASI MEJA</div>
    <h2 style="font-family:'Poppins',sans-serif;font-weight:800;font-size:clamp(26px,3.2vw,34px);margin-bottom:10px;">
      Pesan meja & menu sekaligus
    </h2>
    <p style="color:var(--text-muted);margin-bottom:32px;">
      Pilih jadwal, meja, dan menu favoritmu. Setelah checkout, kamu akan diarahkan untuk konfirmasi ke WhatsApp kami.
    </p>

    @if($errors->any())
      <div style="background:#5c2b22;color:#ffd8cf;padding:14px 18px;border-radius:8px;margin-bottom:20px;">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('reservasi.store') }}" id="form-reservasi">
      @csrf

      <!-- DATA DIRI -->
      <div class="rsv-card">
        <h3>1. Data Diri</h3>
        <div class="rsv-grid-2">
          <div>
            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="{{ old('nama', auth()->user()->name) }}" required>
          </div>
          <div>
            <label>Nomor WhatsApp</label>
            <input type="text" name="whatsapp" placeholder="08xxxxxxxxxx" value="{{ old('whatsapp') }}" required>
          </div>
        </div>
      </div>

      <!-- JADWAL -->
      <div class="rsv-card">
        <h3>2. Jadwal & Lantai</h3>
        <div class="rsv-grid-3">
          <div>
            <label>Tanggal</label>
            <input type="date" name="tanggal" id="input-tanggal" value="{{ old('tanggal') }}" required>
          </div>
          <div>
            <label>Jam</label>
            <input type="time" name="jam" id="input-jam" value="{{ old('jam') }}" required>
          </div>
          <div>
            <label>Jumlah Orang</label>
            <input type="number" name="jumlah_orang" id="input-orang" min="1" value="{{ old('jumlah_orang', 2) }}" required>
          </div>
        </div>
        <div style="margin-top:16px;">
          <label>Pilih Lantai</label>
          <div class="rsv-lantai-tabs">
            <button type="button" class="rsv-tab active" data-lantai="1">Lantai 1</button>
            <button type="button" class="rsv-tab" data-lantai="2">Lantai 2</button>
            <button type="button" class="rsv-tab" data-lantai="3">Lantai 3</button>
          </div>
        </div>
      </div>

      <!-- PILIH MEJA -->
      <div class="rsv-card">
        <h3>3. Pilih Meja</h3>
        <p id="meja-hint" style="color:var(--text-muted);font-size:14px;margin-bottom:12px;">
          Isi tanggal &amp; jam dulu di atas untuk melihat meja yang kosong.
        </p>
        <div id="meja-grid" class="rsv-meja-grid"></div>
        <input type="hidden" name="meja_id" id="input-meja-id">
      </div>

      <!-- PILIH MENU -->
      <div class="rsv-card">
        <h3>4. Pilih Menu</h3>
        @foreach($menusByKategori as $kategori => $items)
          <div class="rsv-menu-kategori">{{ strtoupper($kategori) }}</div>
          <div class="rsv-menu-list">
            @foreach($items as $menu)
              <div class="rsv-menu-item" data-id="{{ $menu->id }}" data-harga="{{ $menu->harga }}" data-nama="{{ $menu->nama }}">
                <div>
                  <div class="rsv-menu-nama">{{ $menu->nama }}</div>
                  <div class="rsv-menu-harga">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                </div>
                <div class="rsv-qty">
                  <button type="button" class="rsv-qty-btn" data-action="kurang">−</button>
                  <span class="rsv-qty-val">0</span>
                  <button type="button" class="rsv-qty-btn" data-action="tambah">+</button>
                </div>
              </div>
            @endforeach
          </div>
        @endforeach
      </div>

      <!-- CATATAN -->
      <div class="rsv-card">
        <h3>5. Catatan (opsional)</h3>
        <textarea name="catatan" rows="2" placeholder="Contoh: dekat colokan listrik">{{ old('catatan') }}</textarea>
      </div>

      <!-- RINGKASAN -->
      <div class="rsv-card">
        <h3>Ringkasan Pesanan</h3>
        <div id="ringkasan-kosong" style="color:var(--text-muted);font-size:14px;">Belum ada menu dipilih.</div>
        <div id="ringkasan-list"></div>
        <div class="rsv-total">
          <span>Total</span>
          <span id="ringkasan-total">Rp 0</span>
        </div>
      </div>

      <!-- PEMBAYARAN -->
      <div class="rsv-card">
        <h3>6. Metode Pembayaran</h3>
        <div class="rsv-bayar-opsi">
          <label class="rsv-bayar-card">
            <input type="radio" name="metode_bayar" value="qris" checked>
            <div>
              <strong>QRIS</strong>
              <p>Scan &amp; bayar sekarang, kode QR muncul setelah checkout.</p>
            </div>
          </label>
          <label class="rsv-bayar-card">
            <input type="radio" name="metode_bayar" value="cash">
            <div>
              <strong>Bayar di Tempat</strong>
              <p>Bayar langsung pas datang ke cafe.</p>
            </div>
          </label>
        </div>
      </div>

      <button type="submit" class="btn-primary" style="border:none;cursor:pointer;width:100%;padding:16px;font-size:16px;">
        Checkout Reservasi
      </button>
    </form>
  </div>
</section>

<style>
  .rsv-card{background:var(--navy);border-radius:16px;padding:24px;margin-bottom:20px;}
  .rsv-card h3{font-size:17px;margin-bottom:16px;color:var(--text-light);}
  .rsv-card label{display:block;font-size:13.5px;font-weight:600;margin-bottom:6px;color:var(--text-light);}
  .rsv-card input, .rsv-card textarea{
    width:100%;padding:11px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.25);
    background:var(--navy-soft);color:var(--text-light);font-family:inherit;
  }
  .rsv-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
  .rsv-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;}
  .rsv-lantai-tabs{display:flex;gap:10px;}
  .rsv-tab{
    padding:10px 20px;border-radius:999px;border:1px solid rgba(245,241,234,0.25);
    background:transparent;color:var(--text-light);cursor:pointer;font-weight:600;font-size:14px;
  }
  .rsv-tab.active{background:var(--orange);border-color:var(--orange);}
  .rsv-meja-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:12px;}
  .rsv-meja-btn{
    padding:14px 8px;border-radius:10px;border:1px solid rgba(245,241,234,0.2);
    background:var(--navy-soft);color:var(--text-light);text-align:center;cursor:pointer;font-size:13px;
  }
  .rsv-meja-btn.terpakai{opacity:.35;cursor:not-allowed;text-decoration:line-through;}
  .rsv-meja-btn.dipilih{background:var(--orange);border-color:var(--orange);}
  .rsv-menu-kategori{color:var(--orange-light);font-size:13px;font-weight:700;letter-spacing:.04em;margin:16px 0 8px;}
  .rsv-menu-item{
    display:flex;justify-content:space-between;align-items:center;padding:12px 0;
    border-bottom:1px solid rgba(245,241,234,0.1);
  }
  .rsv-menu-nama{font-weight:600;color:var(--text-light);}
  .rsv-menu-harga{font-size:13px;color:var(--text-muted);}
  .rsv-qty{display:flex;align-items:center;gap:12px;}
  .rsv-qty-btn{
    width:30px;height:30px;border-radius:50%;border:1px solid rgba(245,241,234,0.3);
    background:transparent;color:var(--text-light);cursor:pointer;font-size:16px;
  }
  .rsv-qty-val{min-width:16px;text-align:center;color:var(--text-light);font-weight:700;}
  #ringkasan-list .rsv-ring-item{display:flex;justify-content:space-between;padding:6px 0;font-size:14px;color:var(--text-light);}
  .rsv-total{display:flex;justify-content:space-between;padding-top:14px;margin-top:10px;border-top:1px solid rgba(245,241,234,0.15);font-weight:800;font-size:17px;color:var(--text-light);}
  .rsv-bayar-opsi{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
  .rsv-bayar-card{
    display:flex;gap:10px;align-items:flex-start;padding:14px;border-radius:10px;
    border:1px solid rgba(245,241,234,0.2);cursor:pointer;color:var(--text-light);
  }
  .rsv-bayar-card p{font-size:12.5px;color:var(--text-muted);margin-top:4px;}
  @media(max-width:700px){.rsv-grid-2,.rsv-grid-3,.rsv-bayar-opsi{grid-template-columns:1fr;}}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  let lantaiAktif = 1;
  const cart = {}; // { menuId: {qty, harga, nama} }

  const tglInput = document.getElementById('input-tanggal');
  const jamInput = document.getElementById('input-jam');
  const mejaGrid = document.getElementById('meja-grid');
  const mejaHint = document.getElementById('meja-hint');
  const mejaIdInput = document.getElementById('input-meja-id');

  function muatMeja() {
    if (!tglInput.value || !jamInput.value) {
      mejaHint.textContent = 'Isi tanggal & jam dulu di atas untuk melihat meja yang kosong.';
      mejaGrid.innerHTML = '';
      return;
    }
    mejaHint.textContent = 'Memuat data meja...';

    const url = "{{ route('reservasi.meja-tersedia') }}?tanggal=" + tglInput.value + "&jam=" + jamInput.value + "&lantai=" + lantaiAktif;
    fetch(url)
      .then(r => r.json())
      .then(data => {
        mejaHint.textContent = 'Klik meja yang masih kosong:';
        mejaGrid.innerHTML = '';
        mejaIdInput.value = '';
        data.forEach(m => {
          const btn = document.createElement('div');
          btn.className = 'rsv-meja-btn' + (m.tersedia ? '' : ' terpakai');
          btn.innerHTML = '<strong>' + m.kode + '</strong><br>' + m.kapasitas + ' orang';
          if (m.tersedia) {
            btn.addEventListener('click', function () {
              document.querySelectorAll('.rsv-meja-btn').forEach(el => el.classList.remove('dipilih'));
              btn.classList.add('dipilih');
              mejaIdInput.value = m.id;
            });
          }
          mejaGrid.appendChild(btn);
        });
      });
  }

  document.querySelectorAll('.rsv-tab').forEach(tab => {
    tab.addEventListener('click', function () {
      document.querySelectorAll('.rsv-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      lantaiAktif = tab.dataset.lantai;
      muatMeja();
    });
  });
  tglInput.addEventListener('change', muatMeja);
  jamInput.addEventListener('change', muatMeja);

  function renderRingkasan() {
    const list = document.getElementById('ringkasan-list');
    const kosong = document.getElementById('ringkasan-kosong');
    const totalEl = document.getElementById('ringkasan-total');
    list.innerHTML = '';
    let total = 0;
    const ids = Object.keys(cart).filter(id => cart[id].qty > 0);
    kosong.style.display = ids.length ? 'none' : 'block';
    ids.forEach(id => {
      const item = cart[id];
      const subtotal = item.qty * item.harga;
      total += subtotal;
      const row = document.createElement('div');
      row.className = 'rsv-ring-item';
      row.innerHTML = '<span>' + item.nama + ' x' + item.qty + '</span><span>Rp ' + subtotal.toLocaleString('id-ID') + '</span>';
      list.appendChild(row);
    });
    totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
  }

  document.querySelectorAll('.rsv-menu-item').forEach(el => {
    const id = el.dataset.id;
    const harga = parseInt(el.dataset.harga);
    const nama = el.dataset.nama;
    const valEl = el.querySelector('.rsv-qty-val');
    cart[id] = { qty: 0, harga, nama };

    el.querySelectorAll('.rsv-qty-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        if (btn.dataset.action === 'tambah') cart[id].qty++;
        else cart[id].qty = Math.max(0, cart[id].qty - 1);
        valEl.textContent = cart[id].qty;
        renderRingkasan();
      });
    });
  });

  document.getElementById('form-reservasi').addEventListener('submit', function (e) {
    if (!mejaIdInput.value) {
      e.preventDefault();
      alert('Pilih meja dulu ya.');
      return;
    }
    const ids = Object.keys(cart).filter(id => cart[id].qty > 0);
    if (ids.length === 0) {
      e.preventDefault();
      alert('Pilih minimal 1 menu.');
      return;
    }
    ids.forEach(id => {
      const inputId = document.createElement('input');
      inputId.type = 'hidden'; inputId.name = 'menu_id[]'; inputId.value = id;
      const inputQty = document.createElement('input');
      inputQty.type = 'hidden'; inputQty.name = 'qty[]'; inputQty.value = cart[id].qty;
      this.appendChild(inputId);
      this.appendChild(inputQty);
    });
  });
});
</script>
@endsection
