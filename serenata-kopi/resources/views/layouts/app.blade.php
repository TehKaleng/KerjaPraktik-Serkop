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
      <div class="brand"><img src="{{ asset('images/SerenataLogoHeader.png') }}" alt="Serenata Kopi & Space"></div>
      <div class="nav-links">
        <a href="{{ route('home') }}#tentang">Tentang</a>
        <a href="{{ route('home') }}#menu">Menu</a>
        <a href="{{ route('home') }}#suasana">Suasana</a>
        <a href="{{ route('home') }}#testimoni">Testimoni</a>
        <a href="{{ route('home') }}#kontak">Kontak</a>
      </div>
      <div style="display:flex;align-items:center;gap:16px;">
        @auth
          @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.dashboard') }}" style="color:var(--text-light);font-size:14px;font-weight:600;">Panel Admin</a>
          @else
            <a href="{{ route('dashboard') }}" style="color:var(--text-light);font-size:14px;font-weight:600;">Dashboard Saya</a>
          @endif
        @else
          <a href="{{ route('login') }}" style="color:var(--text-light);font-size:14px;font-weight:600;">Masuk</a>
        @endauth
        <a href="{{ route('reservasi.form') }}" class="nav-btn">Reservasi</a>
      </div>
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

</body>
</html>
