<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Serenata Kopi & Space')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<header>
  <div class="wrap">
    <nav>
      <div class="brand">
        <a href="{{ route('home') }}" aria-label="Ke halaman depan"><img src="{{ asset('images/SerenataLogoHeader.png') }}" alt="Serenata Kopi & Space"></a>
      </div>
      <div class="nav-links" id="navLinks">
        <a href="{{ route('home') }}#tentang">Tentang</a>
        <a href="{{ route('home') }}#menu">Menu</a>
        <a href="{{ route('home') }}#suasana">Suasana</a>
        <a href="{{ route('home') }}#testimoni">Testimoni</a>
        <a href="{{ route('home') }}#kontak">Kontak</a>
        {{-- Hanya tampil di menu HP/tablet --}}
        @auth
          @if(auth()->user()->hasRole('admin'))
            <a class="nav-dash-m" href="{{ route('admin.dashboard') }}">Panel Admin</a>
          @else
            <a class="nav-dash-m" href="{{ route('dashboard') }}">Dashboard Saya</a>
          @endif
        @else
          <a class="nav-dash-m" href="{{ route('login') }}">Masuk</a>
        @endauth
      </div>
      <div class="nav-actions">
        @auth
          @if(auth()->user()->hasRole('admin'))
            <a class="nav-dash" href="{{ route('admin.dashboard') }}">Panel Admin</a>
          @else
            <a class="nav-dash" href="{{ route('dashboard') }}">Dashboard Saya</a>
          @endif
        @else
          <a class="nav-dash" href="{{ route('login') }}">Masuk</a>
        @endauth
        <a href="{{ route('reservasi.form') }}" class="nav-btn">Reservasi</a>
      </div>
      <button type="button" class="nav-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="navLinks">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </div>
</header>

{{-- Sebagian halaman (Edit Profil bawaan Breeze) pakai <x-app-layout> dengan <x-slot name="header">,
     sebagian lagi (Reservasi, Dashboard custom) pakai @extends + @section('content').
     Blok di bawah ini dukung dua-duanya sekaligus. --}}
@isset($header)
  <div style="background:var(--navy);padding:22px 0;border-bottom:1px solid rgba(245,241,234,0.1);">
    <div class="wrap" style="color:var(--text-light);font-size:20px;font-weight:700;">
      {{ $header }}
    </div>
  </div>
@endisset

@yield('content')
{{ $slot ?? '' }}

<footer>
  <div class="wrap">
    <div class="footer-top">
      <img src="{{ asset('images/SerenataLogoHeader.png') }}" alt="Serenata Kopi & Space">
      <div class="footer-links">
        <a href="{{ route('home') }}#tentang">Tentang</a>
        <a href="{{ route('home') }}#menu">Menu</a>
        <a href="{{ route('home') }}#suasana">Suasana</a>
        <a href="{{ route('home') }}#kontak">Kontak</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Serenata Kopi & Space. Seluruh hak cipta dilindungi.</span>
      <span>Dibuat sebagai gambaran Company Profile — Kerja Praktik</span>
    </div>
  </div>
</footer>

<script>
// Menu hamburger (tablet & HP)
(function () {
  var header = document.querySelector('header');
  var tombol = document.querySelector('.nav-toggle');
  if (!header || !tombol) return;

  function atur(buka) {
    header.classList.toggle('nav-open', buka);
    tombol.setAttribute('aria-expanded', buka ? 'true' : 'false');
    tombol.setAttribute('aria-label', buka ? 'Tutup menu' : 'Buka menu');
  }

  tombol.addEventListener('click', function () {
    atur(!header.classList.contains('nav-open'));
  });

  // Pilih salah satu menu -> panel menutup
  header.addEventListener('click', function (e) {
    if (e.target.closest && e.target.closest('.nav-links a')) atur(false);
  });

  // Ketuk di luar navbar / tekan Esc -> panel menutup
  document.addEventListener('click', function (e) {
    if (header.classList.contains('nav-open') && !header.contains(e.target)) atur(false);
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') atur(false);
  });

  // Layar dilebarkan lagi (mis. HP diputar): pastikan panel tertutup
  window.addEventListener('resize', function () {
    if (window.innerWidth > 1024) atur(false);
  });
})();
</script>

</body>
</html>