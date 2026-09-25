<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') — Serenata Kopi & Space</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="admin-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <img src="{{ asset('images/SerenataLogoHeader.png') }}" alt="Serenata">
    </div>
    <nav class="sidebar-nav">
      <a href="{{ route('admin.meja.index') }}" class="{{ request()->routeIs('admin.meja.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
          Meja
      </a>
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
        Dashboard
      </a>
      <a href="{{ route('admin.menu.index') }}" class="{{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h13a4 4 0 010 8H3z"/><path d="M16 10V6a2 2 0 012-2 2 2 0 012 2v2"/><path d="M6 3v2M9 3v2"/></svg>
        Menu
      </a>
      <a href="{{ route('admin.galeri.index') }}" class="{{ request()->routeIs('admin.galeri.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="M21 16l-5-5-9 9"/></svg>
        Galeri
      </a>
      <a href="{{ route('admin.testimoni.index') }}" class="{{ request()->routeIs('admin.testimoni.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.4 8.4 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.4 8.4 0 01-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.4 8.4 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
        Testimoni
      </a>
      <a href="{{ route('admin.kontak.edit') }}" class="{{ request()->routeIs('admin.kontak.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6 19.8 19.8 0 01-3.1-8.7A2 2 0 014.1 2h3a2 2 0 012 1.7c.1.9.3 1.8.6 2.7a2 2 0 01-.4 2.1L8.1 9.7a16 16 0 006 6l1.2-1.2a2 2 0 012.1-.4c.9.3 1.8.5 2.7.6a2 2 0 011.9 2.2z"/></svg>
        Info Kontak
      </a>
      <a href="{{ route('admin.reservasi.index') }}" class="{{ request()->routeIs('admin.reservasi.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        Reservasi
      </a>
    </nav>
    <div class="sidebar-foot">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
          Keluar
        </a>
      </form>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <h1>@yield('title', 'Dashboard')</h1>
      <div class="admin-user">
        <span>{{ auth()->user()->name ?? 'Admin Serenata' }}</span>
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
      </div>
    </div>
    <div class="content">
      @if(session('success'))
        <div style="background:#E6F4EA;color:#1E7B3E;padding:12px 18px;border-radius:8px;margin-bottom:20px;font-size:14px;">
          {{ session('success') }}
        </div>
      @endif
      @yield('content')
    </div>
  </div>

</div>

</body>
</html>
