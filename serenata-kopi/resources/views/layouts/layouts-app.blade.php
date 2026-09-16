<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      <a href="{{ route('reservasi.form') }}" class="nav-btn">Reservasi</a>
    </nav>
  </div>
</header>

@yield('content')

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
