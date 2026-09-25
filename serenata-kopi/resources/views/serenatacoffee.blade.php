<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Serenata Kopi & Space — Company Profile</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<header>
  <div class="wrap">
    <nav>
      <div class="brand"><img src="{{ asset('images/SerenataLogoHeader.png') }}" alt="Serenata Kopi & Space"></div>
      <div class="nav-links">
        <a href="#tentang">Tentang</a>
        <a href="#menu">Menu</a>
        <a href="#suasana">Suasana</a>
        <a href="#testimoni">Testimoni</a>
        <a href="#kontak">Kontak</a>
      </div>
      <a href="{{ route('reservasi.form') }}" class="nav-btn">Reservasi</a>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="wrap hero-inner">
    <div>
      <div class="hero-eyebrow">RUANG TENANG DI TENGAH KOTA</div>
      <h1>Kopi yang seirama, ruang yang <span>bersahabat</span>.</h1>
      <p>Serenata Kopi & Space menghadirkan tempat singgah yang hangat — kopi racikan barista, kudapan buatan sendiri, dan suasana yang bikin betah berlama-lama.</p>
      <div class="hero-actions">
        <a href="#menu" class="btn-primary">Lihat Menu</a>
        <a href="#tentang" class="btn-ghost">Tentang Kami</a>
      </div>
    </div>
    <div class="logo-mark">
      <img src="{{ asset('images/SerenataLogo.PNG') }}" alt="Logo Serenata Kopi & Space">
    </div>
  </div>
</section>

<section id="tentang" class="about">
  <div class="wrap about-inner">
    <div class="about-figure"><span>Sejak 2021</span></div>
    <div>
      <div class="section-tag">TENTANG KAMI</div>
      <h2>Dibangun dari kecintaan pada kopi dan ruang berkumpul</h2>
      <div class="about-list">
        <div class="about-item">
          <div class="num">01</div>
          <div>
            <h3>Biji Kopi Pilihan</h3>
            <p>Disangrai lokal dengan profil rasa yang konsisten setiap harinya.</p>
          </div>
        </div>
        <div class="about-item">
          <div class="num">02</div>
          <div>
            <h3>Ruang yang Nyaman</h3>
            <p>Desain interior hangat, cocok untuk kerja, ngobrol, atau sekadar rehat.</p>
          </div>
        </div>
        <div class="about-item">
          <div class="num">03</div>
          <div>
            <h3>Pelayanan Personal</h3>
            <p>Tim kami mengenal pelanggan tetap dan racikan favorit mereka.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="menu" class="menu">
  <div class="wrap">
    <div class="section-head">
      <div class="section-tag">MENU FAVORIT</div>
      <h2>Beberapa yang paling dicari</h2>
      <p>Dari kopi klasik hingga kreasi khas rumah, dibuat dengan bahan segar setiap hari.</p>
    </div>
  </div>
  <div class="menu-grid">
    @forelse($menus as $menu)
      <div class="menu-card">
        <div class="tag">{{ strtoupper($menu->kategori) }}</div>
        <h3>{{ $menu->nama }}</h3>
        <p>{{ $menu->deskripsi }}</p>
        <div class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
      </div>
    @empty
      <div class="menu-card"><p>Menu segera hadir.</p></div>
    @endforelse
  </div>
</section>

<section id="suasana">
  <div class="wrap">
    <div class="section-head">
      <div class="section-tag">SUASANA</div>
      <h2>Sudut-sudut favorit di Serenata</h2>
    </div>
    <div class="ambience-grid">
      @forelse($fotos as $i => $foto)
        <div class="{{ $i === 0 ? 'b1' : 'b' . ($i + 1) }}"
             style="background-image:url('{{ asset('storage/' . $foto->foto) }}');background-size:cover;background-position:center;">
        </div>
      @empty
        <div class="b1"></div><div class="b2"></div><div class="b3"></div><div class="b4"></div>
      @endforelse
    </div>
  </div>
</section>

<section id="testimoni" class="testi">
  <div class="wrap">
    <div class="section-head">
      <div class="section-tag">TESTIMONI</div>
      <h2>Kata mereka yang sudah mampir</h2>
    </div>
    <div class="testi-grid">
      @forelse($testimonis as $t)
        <div class="testi-card">
          <p>"{{ $t->isi }}"</p>
          <div class="testi-name">{{ $t->nama }}</div>
          <div class="testi-role">{{ $t->peran }}</div>
        </div>
      @empty
        <div class="testi-card"><p>Belum ada testimoni.</p></div>
      @endforelse
    </div>
  </div>
</section>

<section id="kontak">
  <div class="wrap">
    <div class="section-head">
      <div class="section-tag">KUNJUNGI KAMI</div>
      <h2>Lokasi & Jam Operasional</h2>
    </div>
    <div class="contact">
      <div>
        <div class="info-row"><div class="k">Alamat</div><div class="v">{{ $kontak->alamat }}</div></div>
        <div class="info-row"><div class="k">Jam Buka</div><div class="v">{{ $kontak->jam_buka }} - {{ $kontak->jam_tutup }}</div></div>
        <div class="info-row"><div class="k">WhatsApp</div><div class="v">{{ $kontak->whatsapp }}</div></div>
        <div class="info-row"><div class="k">Email</div><div class="v">{{ $kontak->email }}</div></div>

        <div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kontak->whatsapp) }}" target="_blank" class="btn-primary">
            Chat via WhatsApp
          </a>
          @if($kontak->maps_link)
            <a href="{{ $kontak->maps_link }}" target="_blank" class="btn-ghost">
              Lihat di Google Maps
            </a>
          @endif
        </div>
      </div>
      <div class="map-block" style="padding:0;overflow:hidden;">
        @if($kontak->maps_embed)
          <iframe src="{{ $kontak->maps_embed }}" width="100%" height="100%"
                  style="border:0;min-height:280px;" allowfullscreen loading="lazy"></iframe>
        @else
          Peta lokasi belum diatur.<br>Atur di halaman admin.
        @endif
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="wrap">
    <div class="footer-top">
      <img src="{{ asset('images/SerenataLogoHeader.png') }}" alt="Serenata Kopi & Space">
      <div class="footer-links">
        <a href="#tentang">Tentang</a>
        <a href="#menu">Menu</a>
        <a href="#suasana">Suasana</a>
        <a href="#kontak">Kontak</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Serenata Kopi & Space. Seluruh hak cipta dilindungi.</span>
      <span>Dibuat sebagai gambaran Company Profile — Kerja Praktik</span>
    </div>
  </div>
</footer>

</body>
</html>