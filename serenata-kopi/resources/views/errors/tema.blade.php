<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('kode') &ndash; @yield('judul') | Serenata Kopi &amp; Space</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --navy:#2E4A80;
    --navy-soft:#243A6B;
    --orange:#E8562A;
    --orange-light:#F0946B;
    --text-light:#F5F1EA;
    --text-muted:#AEB9D6;
  }
  body{
    font-family:'Poppins',sans-serif;background:var(--navy-soft);color:var(--text-light);
    min-height:100vh;display:flex;align-items:center;justify-content:center;
    padding:32px 20px;overflow-x:hidden;position:relative;line-height:1.6;
  }

  /* Cahaya latar lembut, senada dengan halaman depan */
  .blob{
    position:fixed;border-radius:50%;filter:blur(80px);opacity:.28;pointer-events:none;z-index:0;
    animation:blobMove 14s ease-in-out infinite alternate;
  }
  .blob.satu{width:420px;height:420px;background:var(--orange);top:-160px;right:-120px;}
  .blob.dua{width:340px;height:340px;background:#5b86d6;bottom:-160px;left:-100px;animation-delay:-7s;}
  @keyframes blobMove{from{transform:translate(0,0) scale(1);}to{transform:translate(-40px,34px) scale(1.18);}}

  .err{position:relative;z-index:1;width:100%;max-width:560px;text-align:center;animation:masuk .7s cubic-bezier(.2,.7,.2,1) both;}
  @keyframes masuk{from{opacity:0;transform:translateY(24px);}to{opacity:1;transform:none;}}

  .err-logo{display:inline-block;margin-bottom:22px;}
  .err-logo img{height:44px;width:auto;display:block;}

  /* Cangkir kopi berasap */
  .cup{width:92px;height:auto;margin:0 auto 6px;display:block;}
  .steam{
    fill:none;stroke:rgba(245,241,234,0.6);stroke-width:3;stroke-linecap:round;
    animation:uap 3s ease-in-out infinite;
  }
  .steam.dua{animation-delay:.8s;}
  .steam.tiga{animation-delay:1.6s;}
  @keyframes uap{
    0%{opacity:0;transform:translateY(8px);}
    50%{opacity:.85;}
    100%{opacity:0;transform:translateY(-10px);}
  }

  .err-kode{
    font-size:clamp(84px,22vw,150px);font-weight:800;line-height:1;letter-spacing:-.03em;
    background:linear-gradient(135deg,var(--orange),var(--orange-light));
    -webkit-background-clip:text;background-clip:text;color:transparent;
  }
  h1{font-size:clamp(22px,4.6vw,30px);font-weight:800;margin:10px 0 12px;}
  p{color:var(--text-muted);font-size:15.5px;line-height:1.7;max-width:46ch;margin:0 auto;}

  .err-aksi{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-top:30px;}
  .btn{
    display:inline-flex;align-items:center;justify-content:center;padding:13px 26px;border-radius:999px;
    font-family:inherit;font-size:15px;font-weight:600;line-height:1.2;cursor:pointer;text-decoration:none;
    transition:background .2s ease,border-color .2s ease,color .2s ease,transform .2s ease;
  }
  .btn-primary{background:var(--orange);border:1px solid var(--orange);color:#243A6B;}
  .btn-primary:hover{background:var(--orange-light);border-color:var(--orange-light);transform:translateY(-2px);}
  .btn-ghost{background:transparent;border:1px solid rgba(245,241,234,0.3);color:var(--text-light);}
  .btn-ghost:hover{border-color:var(--orange-light);color:var(--orange-light);}

  .err-foot{margin-top:34px;font-size:12.5px;color:rgba(174,185,214,0.7);}

  @media (max-width:480px){
    .err-aksi .btn{flex:1 1 100%;}
    .cup{width:78px;}
  }
  @media (prefers-reduced-motion:reduce){
    .blob,.steam,.err{animation:none;}
    .steam{opacity:.6;}
  }
</style>
</head>
<body>

<div class="blob satu" aria-hidden="true"></div>
<div class="blob dua" aria-hidden="true"></div>

<main class="err">
  <a class="err-logo" href="{{ url('/') }}" aria-label="Ke beranda Serenata Kopi &amp; Space">
    <img src="{{ asset('images/SerenataLogoHeader.png') }}" alt="Serenata Kopi &amp; Space">
  </a>

  <svg class="cup" viewBox="0 0 120 112" fill="none" aria-hidden="true">
    <path class="steam"      d="M42 36c-5-7 5-11 0-19"/>
    <path class="steam dua"  d="M60 36c-5-7 5-11 0-19"/>
    <path class="steam tiga" d="M78 36c-5-7 5-11 0-19"/>
    <path d="M24 46h62v22a26 26 0 01-26 26H50a26 26 0 01-26-26z" fill="#E8562A"/>
    <ellipse cx="55" cy="46" rx="31" ry="5" fill="#F0946B"/>
    <path d="M86 52h6a12 12 0 010 24h-8" stroke="#E8562A" stroke-width="7" stroke-linecap="round"/>
    <rect x="14" y="100" width="82" height="6" rx="3" fill="rgba(245,241,234,0.25)"/>
  </svg>

  <div class="err-kode">@yield('kode')</div>
  <h1>@yield('judul')</h1>
  <p>@yield('pesan')</p>

  <div class="err-aksi">
    <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
    @yield('aksi')
    <button type="button" class="btn btn-ghost" id="kembali">Halaman Sebelumnya</button>
  </div>

  <div class="err-foot">Serenata Kopi &amp; Space</div>
</main>

<script>
  // "Halaman Sebelumnya": kembali kalau ada riwayat, kalau tidak (halaman dibuka langsung) ke beranda
  document.getElementById('kembali').addEventListener('click', function () {
    if (window.history.length > 1) { window.history.back(); }
    else { window.location.href = @json(url('/')); }
  });
</script>

</body>
</html>
