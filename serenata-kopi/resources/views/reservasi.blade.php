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

    {{-- Pilihan akun: tamu boleh langsung memesan; akun mendapat riwayat, notifikasi, dan batas yang lebih longgar --}}
    @guest
      <div class="rsv-akun">
        <div class="rsv-akun-teks">
          <strong>Pesan sebagai tamu, atau pakai akun</strong>
          <span>Tanpa akun pun bisa langsung memesan. Dengan akun, reservasimu tercatat di riwayat, mendapat notifikasi, dan batas pemesananmu lebih longgar.</span>
        </div>
        <div class="rsv-akun-aksi">
          <a href="{{ route('reservasi.masuk') }}" class="btn-ghost">Masuk</a>
          <a href="{{ route('reservasi.daftar') }}" class="btn-primary">Daftar</a>
        </div>
      </div>
    @endguest
    @auth
      <div class="rsv-akun rsv-akun-masuk">
        Memesan sebagai <strong>{{ auth()->user()->name }}</strong>. Reservasi ini tercatat di riwayat akunmu.
      </div>
    @endauth

    @if($errors->any())
      <div style="background:#5c2b22;color:#ffd8cf;padding:14px 18px;border-radius:8px;margin-bottom:20px;">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('reservasi.store') }}" id="form-reservasi">
      @csrf

      {{-- Kolom jebakan bot (disembunyikan dari manusia; jangan dihapus) --}}
      <div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
        <label>Jangan diisi <input type="text" name="referensi_internal" tabindex="-1" autocomplete="off"></label>
      </div>

      <!-- DATA DIRI -->
      <div class="rsv-card">
        <h3>1. Data Diri</h3>
        <div class="rsv-grid-2">
          <div>
            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="{{ old('nama', auth()->user()?->name) }}" required>
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

        @php
            $labelKategori = ['kopi' => 'Kopi', 'non-kopi' => 'Non-Kopi', 'kudapan' => 'Kudapan', 'makanan' => 'Makanan', 'tambahan' => 'Tambahan'];
            $totalMenu = $menusByKategori->sum(fn ($items) => $items->count());
        @endphp

        @if($menusByKategori->isNotEmpty())
          {{-- Cari menu + tab kategori --}}
          <div class="rsv-menu-tools">
            <div class="rsv-menu-cari">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input type="search" id="rsv-cari" placeholder="Cari menu..." aria-label="Cari menu" autocomplete="off" inputmode="search" enterkeyhint="search">
            </div>
            <div class="rsv-menu-tabs">
              <button type="button" class="rsv-mtab active" data-filter="semua" aria-pressed="true">Semua <span class="rsv-mtab-jml">{{ $totalMenu }}</span><b class="rsv-mtab-pilih" hidden>0</b></button>
              @foreach($menusByKategori as $kategori => $items)
                <button type="button" class="rsv-mtab" data-filter="{{ $kategori }}" aria-pressed="false">{{ $labelKategori[$kategori] ?? ucfirst($kategori) }} <span class="rsv-mtab-jml">{{ $items->count() }}</span><b class="rsv-mtab-pilih" hidden>0</b></button>
              @endforeach
            </div>
          </div>

          {{-- Daftar menu: tinggi dibatasi dan bisa digulir sendiri, jadi halaman tidak memanjang --}}
          <div class="rsv-menu-scroll" id="rsv-menu-scroll">
            @foreach($menusByKategori as $kategori => $items)
              <div class="rsv-menu-grup" data-kategori="{{ $kategori }}">
                <div class="rsv-menu-kategori">{{ $labelKategori[$kategori] ?? ucfirst($kategori) }}</div>
                <div class="rsv-menu-list">
                  @foreach($items as $menu)
                    <div class="rsv-menu-item" data-id="{{ $menu->id }}" data-harga="{{ $menu->harga }}" data-nama="{{ $menu->nama }}">
                      <div>
                        <div class="rsv-menu-nama">{{ $menu->nama }}</div>
                        <div class="rsv-menu-harga">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                      </div>
                      <div class="rsv-qty">
                        <button type="button" class="rsv-qty-btn" data-action="kurang" aria-label="Kurangi {{ $menu->nama }}">−</button>
                        <span class="rsv-qty-val">0</span>
                        <button type="button" class="rsv-qty-btn" data-action="tambah" aria-label="Tambah {{ $menu->nama }}">+</button>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach

            <div class="rsv-menu-kosong" id="rsv-menu-kosong" hidden>Tidak ada menu yang cocok. Coba kata lain atau pilih kategori "Semua".</div>
          </div>

          {{-- Ringkasan singkat tepat di bawah daftar: tidak perlu menggulir jauh untuk melihat total --}}
          <div class="rsv-menu-bar">
            <span id="rsv-bar-teks">Belum ada menu dipilih</span>
            <button type="button" id="rsv-bar-lihat" hidden>Lihat ringkasan &darr;</button>
          </div>
        @else
          <p style="color:var(--text-muted);font-size:14px;">Menu belum tersedia.</p>
        @endif
      </div>
      <!-- CATATAN -->
      <div class="rsv-card">
        <h3>5. Catatan (opsional)</h3>
        <textarea name="catatan" rows="2" placeholder="Contoh: dekat colokan listrik">{{ old('catatan') }}</textarea>
      </div>

      <!-- RINGKASAN -->
      <div class="rsv-card" id="ringkasan-card">
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
        <h3>6. Pembayaran</h3>
        @if($qrisAktif ?? false)
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
                <strong>Bayar di Kasir</strong>
                <p>Bayar langsung saat datang ke cafe.</p>
              </div>
            </label>
          </div>
        @else
          <div class="rsv-bayar-opsi rsv-bayar-tunggal">
            <div class="rsv-bayar-card" style="cursor:default;">
              <input type="hidden" name="metode_bayar" value="cash">
              <div>
                <strong>Bayar di Kasir</strong>
                <p>Pembayaran dilakukan di kasir saat kamu datang, bisa lewat QRIS, kartu, atau tunai. Pesananmu sudah tercatat, jadi kasir tinggal memprosesnya.</p>
              </div>
            </div>
          </div>
        @endif
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
  .rsv-bayar-tunggal{grid-template-columns:1fr;}
  .rsv-bayar-card{
    display:flex;gap:10px;align-items:flex-start;padding:14px;border-radius:10px;
    border:1px solid rgba(245,241,234,0.2);cursor:pointer;color:var(--text-light);
  }
  .rsv-bayar-card p{font-size:12.5px;color:var(--text-muted);margin-top:4px;}
  @media(max-width:700px){.rsv-grid-2,.rsv-grid-3,.rsv-bayar-opsi{grid-template-columns:1fr;}}

  /* Responsif HP */
  @media(max-width:480px){
    .rsv-card{padding:18px;}
    .rsv-lantai-tabs{flex-wrap:wrap;}
    .rsv-tab{padding:9px 16px;}
    .rsv-qty-btn{width:36px;height:36px;}
    .rsv-menu-item{gap:12px;}
    .rsv-menu-nama{font-size:14.5px;}
  }

  /* ===== Pilih Menu: ringkas, bisa dicari dan difilter ===== */
  #ringkasan-card{scroll-margin-top:96px;}
  .rsv-menu-tools{display:flex;flex-direction:column;gap:12px;margin-bottom:14px;}
  .rsv-menu-cari{position:relative;}
  .rsv-menu-cari svg{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:17px;height:17px;color:var(--text-muted);pointer-events:none;}
  .rsv-card .rsv-menu-cari input{
    padding:11px 14px 11px 40px;border-radius:999px;
    -webkit-appearance:none;appearance:none;box-shadow:none;
  }
  .rsv-card .rsv-menu-cari input:focus{outline:none;border-color:var(--orange);}
  .rsv-menu-cari input::-webkit-search-decoration,
  .rsv-menu-cari input::-webkit-search-results-button,
  .rsv-menu-cari input::-webkit-search-results-decoration{display:none;}

  /* Semua tab kategori selalu terlihat; kalau tidak muat, turun ke baris berikutnya (tidak perlu digeser) */
  .rsv-menu-tabs{display:flex;flex-wrap:wrap;gap:8px;}
  .rsv-mtab{
    flex-shrink:0;display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:999px;
    border:1px solid rgba(245,241,234,0.25);background:transparent;color:var(--text-light);
    font-family:inherit;font-size:13.5px;font-weight:600;cursor:pointer;
    transition:background .2s ease,border-color .2s ease;
  }
  .rsv-mtab:hover{border-color:var(--orange-light);}
  .rsv-mtab.active{background:var(--orange);border-color:var(--orange);color:#fff;}
  .rsv-mtab-jml{font-size:11.5px;padding:1px 7px;border-radius:999px;background:rgba(245,241,234,0.14);}
  .rsv-mtab.active .rsv-mtab-jml{background:rgba(255,255,255,0.25);}
  .rsv-mtab-pilih{
    display:inline-block;min-width:18px;height:18px;padding:0 5px;border-radius:999px;
    background:var(--orange-light);color:#243A6B;font-size:11px;line-height:18px;text-align:center;
  }
  .rsv-mtab-pilih[hidden]{display:none;}
  .rsv-mtab.active .rsv-mtab-pilih{background:#fff;color:var(--orange);}

  .rsv-menu-scroll{
    max-height:min(60vh,500px);overflow-y:auto;padding-right:8px;margin-right:-8px;
    border-top:1px solid rgba(245,241,234,0.1);
    scrollbar-width:thin;scrollbar-color:rgba(245,241,234,0.3) transparent;
  }
  .rsv-menu-scroll::-webkit-scrollbar{width:8px;}
  .rsv-menu-scroll::-webkit-scrollbar-thumb{background:rgba(245,241,234,0.28);border-radius:8px;}
  .rsv-menu-grup[hidden]{display:none;}
  .rsv-menu-grup .rsv-menu-kategori{
    position:sticky;top:0;z-index:1;margin:0;padding:12px 0 8px;
    background:var(--navy);text-transform:uppercase;
  }
  .rsv-menu-list{display:grid;grid-template-columns:1fr 1fr;column-gap:28px;}
  .rsv-menu-item[hidden]{display:none;}
  .rsv-menu-kosong{padding:28px 8px;text-align:center;color:var(--text-muted);font-size:14px;}
  .rsv-menu-kosong[hidden]{display:none;}

  .rsv-menu-bar{
    display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;
    margin-top:14px;padding:12px 14px;border-radius:10px;background:var(--navy-soft);
    color:var(--text-light);font-size:14px;
  }
  .rsv-menu-bar strong{color:var(--orange-light);}
  .rsv-menu-bar button{
    border:none;background:transparent;color:var(--orange-light);font-family:inherit;
    font-weight:700;font-size:13.5px;cursor:pointer;padding:4px 0;
  }
  .rsv-menu-bar button[hidden]{display:none;}

  @media(max-width:700px){
    .rsv-menu-list{grid-template-columns:1fr;}
    .rsv-menu-scroll{max-height:min(56vh,460px);}
    .rsv-card .rsv-menu-cari input{font-size:16px;} /* mencegah iPhone memperbesar layar saat diketuk */
    .rsv-mtab{padding:7px 12px;font-size:13px;}
  }

  /* Pilihan akun di atas form */
  .rsv-akun{
    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px 24px;
    background:var(--navy);border:1px solid rgba(245,241,234,0.14);border-radius:14px;
    padding:16px 20px;margin-bottom:22px;
  }
  .rsv-akun-teks{display:flex;flex-direction:column;gap:3px;flex:1 1 320px;font-size:14px;line-height:1.5;color:var(--text-muted);}
  .rsv-akun-teks strong, .rsv-akun-masuk strong{color:var(--text-light);}
  .rsv-akun-teks strong{font-size:15px;}
  .rsv-akun-aksi{display:flex;gap:10px;}
  .rsv-akun-aksi a{padding:9px 22px;font-size:14px;}
  .rsv-akun-masuk{display:block;font-size:14px;color:var(--text-muted);}
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
    perbaruiBarMenu(ids, total);
  }

  document.querySelectorAll('.rsv-menu-item').forEach(el => {
    const id = el.dataset.id;
    const harga = parseInt(el.dataset.harga);
    const nama = el.dataset.nama;
    const valEl = el.querySelector('.rsv-qty-val');
    const grup = el.closest('.rsv-menu-grup');
    cart[id] = { qty: 0, harga, nama, kategori: grup ? grup.dataset.kategori : '' };

    el.querySelectorAll('.rsv-qty-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        if (btn.dataset.action === 'tambah') cart[id].qty++;
        else cart[id].qty = Math.max(0, cart[id].qty - 1);
        valEl.textContent = cart[id].qty;
        renderRingkasan();
      });
    });
  });

  // ----- Pilih Menu: filter kategori + pencarian + ringkasan singkat -----
  const kotakMenu = document.getElementById('rsv-menu-scroll');
  const grupMenu = document.querySelectorAll('.rsv-menu-grup');
  const tabMenu = document.querySelectorAll('.rsv-mtab');
  const cariMenu = document.getElementById('rsv-cari');
  const kosongMenu = document.getElementById('rsv-menu-kosong');
  const barTeks = document.getElementById('rsv-bar-teks');
  const barLihat = document.getElementById('rsv-bar-lihat');
  let kategoriMenu = 'semua';

  // Huruf kecil, tanpa aksen, spasi dirapikan
  function normal(teks) {
    let t = (teks || '').toLowerCase();
    if (t.normalize) t = t.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    return t.replace(/\s+/g, ' ').trim();
  }
  document.querySelectorAll('.rsv-menu-item').forEach(el => { el._teks = normal(el.dataset.nama); });

  function terapkanFilterMenu() {
    const q = normal(cariMenu ? cariMenu.value : '');
    const kata = q ? q.split(' ') : [];
    let tampil = 0;
    grupMenu.forEach(g => {
      const cocokKategori = (kategoriMenu === 'semua' || g.dataset.kategori === kategoriMenu);
      let adaDiGrup = 0;
      g.querySelectorAll('.rsv-menu-item').forEach(el => {
        const lolos = cocokKategori && kata.every(k => el._teks.indexOf(k) !== -1);
        el.hidden = !lolos;
        if (lolos) { adaDiGrup++; tampil++; }
      });
      g.hidden = (adaDiGrup === 0);
    });
    if (kosongMenu) kosongMenu.hidden = (tampil !== 0);
    if (kotakMenu) kotakMenu.scrollTop = 0;
  }

  tabMenu.forEach(t => {
    t.addEventListener('click', function () {
      kategoriMenu = t.dataset.filter;
      tabMenu.forEach(x => {
        x.classList.toggle('active', x === t);
        x.setAttribute('aria-pressed', x === t ? 'true' : 'false');
      });
      terapkanFilterMenu();
    });
  });

  if (cariMenu) {
    cariMenu.addEventListener('input', terapkanFilterMenu);
    // Enter di kolom cari tidak boleh mengirim form reservasi
    cariMenu.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') e.preventDefault();
    });
  }

  // Ringkasan singkat di bawah daftar + lencana jumlah pilihan di tiap tab
  function perbaruiBarMenu(ids, total) {
    const jumlah = ids.reduce((a, id) => a + cart[id].qty, 0);
    if (barTeks) {
      barTeks.innerHTML = jumlah
        ? '<strong>' + jumlah + ' item</strong> dipilih &middot; Rp ' + total.toLocaleString('id-ID')
        : 'Belum ada menu dipilih';
    }
    if (barLihat) barLihat.hidden = (jumlah === 0);

    const perKategori = {};
    ids.forEach(id => {
      const k = cart[id].kategori;
      perKategori[k] = (perKategori[k] || 0) + cart[id].qty;
    });
    tabMenu.forEach(t => {
      const lencana = t.querySelector('.rsv-mtab-pilih');
      if (!lencana) return;
      const n = (t.dataset.filter === 'semua') ? jumlah : (perKategori[t.dataset.filter] || 0);
      lencana.textContent = n;
      lencana.hidden = (n === 0);
    });
  }

  if (barLihat) {
    barLihat.addEventListener('click', function () {
      const tujuan = document.getElementById('ringkasan-card');
      if (tujuan) tujuan.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

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