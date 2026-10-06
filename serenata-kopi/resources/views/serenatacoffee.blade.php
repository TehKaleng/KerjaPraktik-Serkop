<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Serenata Kopi & Space — Company Profile</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script>document.documentElement.classList.add('js');</script>
<style>
  :root{
    --nav-h:93px;                              /* tinggi navbar; diperbarui otomatis oleh skrip di bawah */
    --sec-h:calc(100vh - var(--nav-h));        /* tinggi area layar di bawah navbar */
    --sec-pad:clamp(16px, 3.5vh, 44px);        /* jarak atas & bawah semua bagian di navbar */
    --tentang-naik:80px;                       /* seberapa dinaikkan dari tengah: isi naik setengahnya (80px = naik 40px) */
    --footer-h:180px;                          /* tinggi footer; diperbarui otomatis oleh skrip di bawah */
  }
  @supports (height:100svh){ :root{ --sec-h:calc(100svh - var(--nav-h)); } }
  html{scroll-behavior:smooth;}

  /* Navbar sembunyi saat scroll ke bawah & muncul lagi saat scroll ke atas */
  header{transition:transform .3s ease;}
  header.header-hidden{transform:translateY(-100%);}

  /* Bagian yang ada di navbar mengisi satu layar penuh (di bawah navbar), isinya di tengah.
     Untuk membatasi hanya "Tentang" dan "Menu", hapus id lain dari daftar selector di bawah. */
  #tentang, #menu, #suasana, #testimoni, #kontak{
    min-height:var(--sec-h);
    scroll-margin-top:var(--nav-h);
    display:flex;flex-direction:column;justify-content:center;
  }
  #tentang > .wrap, #menu > .wrap, #suasana > .wrap, #testimoni > .wrap, #kontak > .wrap{width:100%;}

  @media (prefers-reduced-motion:reduce){
    html{scroll-behavior:auto;}
    header{transition:none;}
  }

  /* ===== Kartu menu interaktif ===== */
  .mc-tabs{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:28px;}
  .mc-tab{
    display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:999px;
    border:1px solid rgba(245,241,234,0.25);background:transparent;color:var(--text-light);
    font-family:inherit;font-size:14px;font-weight:600;cursor:pointer;
    transition:background .2s ease,border-color .2s ease,color .2s ease;
  }
  .mc-tab span{
    font-size:12px;padding:1px 8px;border-radius:999px;background:rgba(245,241,234,0.14);
  }
  .mc-tab:hover{border-color:var(--orange-light);}
  .mc-tab.active{background:var(--orange);border-color:var(--orange);color:#fff;}
  .mc-tab.active span{background:rgba(255,255,255,0.25);}

  .mc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:20px;}
  .mc-card{
    position:relative;aspect-ratio:4/5;border-radius:16px;overflow:hidden;isolation:isolate;
    background:var(--navy);cursor:pointer;outline:none;
    box-shadow:0 6px 18px rgba(0,0,0,0.22);
    transition:transform .3s ease,box-shadow .3s ease;
  }
  .mc-card.mc-hide{display:none;}
  .mc-card:focus-visible{outline:2px solid var(--orange);outline-offset:3px;}
  @keyframes mcIn{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:none;}}

  .mc-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform .5s ease;}
  .mc-fallback{
    background:linear-gradient(150deg,var(--orange),var(--navy-soft));
    display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.35);
  }
  .mc-fallback svg{width:42%;height:auto;margin-bottom:34px;}

  .mc-overlay{
    position:absolute;left:0;right:0;bottom:0;padding:48px 18px 18px;z-index:1;
    background:linear-gradient(to top,rgba(14,22,48,0.96) 0%,rgba(14,22,48,0.82) 55%,rgba(14,22,48,0) 100%);
  }
  .mc-tag{font-size:11.5px;font-weight:700;letter-spacing:.05em;color:var(--orange-light);text-transform:uppercase;}
  .mc-name{font-size:18px;line-height:1.25;margin:4px 0 0;color:var(--text-light);}
  .mc-detail{max-height:0;opacity:0;overflow:hidden;transition:max-height .4s ease,opacity .3s ease,margin .3s ease;}
  .mc-desc{
    font-size:13.5px;line-height:1.55;color:var(--text-muted);margin:10px 0 0;
    display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden;
  }
  .mc-price{font-size:17px;font-weight:800;color:var(--orange-light);margin-top:10px;}
  .mc-empty{color:var(--text-muted);}

  /* Efek hover hanya untuk perangkat yang punya kursor */
  @media (hover:hover){
    .mc-card:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(0,0,0,0.38);}
    .mc-card:hover .mc-img{transform:scale(1.08);}
  }
  .mc-card:hover .mc-detail,
  .mc-card:focus-visible .mc-detail,
  .mc-card:focus-within .mc-detail{max-height:220px;opacity:1;}

  /* HP/tablet tanpa hover: deskripsi dan harga langsung terlihat */
  @media (hover:none){
    .mc-detail{max-height:none;opacity:1;}
  }

  @media (prefers-reduced-motion:reduce){
    .mc-card,.mc-img,.mc-detail,.mc-tab{transition:none;animation:none;}
  }

  /* ===== Penyesuaian posisi tengah: Tentang, Suasana, Menu ===== */
  #tentang{
    padding-top:var(--sec-pad);
    padding-bottom:calc(var(--sec-pad) + var(--tentang-naik)); /* ruang kosong lebih besar di bawah = isi naik sedikit */
  }
  #suasana, #menu{padding-block:var(--sec-pad);}
  #suasana .section-head, #menu .section-head{margin-bottom:20px;}
  #menu .section-head p{margin-top:8px;}

  @media (min-width:821px){
    /* Galeri Suasana: tingginya ikut tinggi layar supaya selalu muat dan berada di tengah */
    #suasana .ambience-grid{height:clamp(220px, calc(var(--sec-h) - 300px), 440px);}

    /* Menu: daftar kartu punya area gulir sendiri, jadi bagian ini selalu pas satu layar.
       Kalau menunya sedikit, tidak ada gulir dan semuanya rata tengah. */
    .mc-tabs{margin-bottom:18px;}
    .mc-grid{
      grid-template-columns:repeat(auto-fill, minmax(190px, 1fr));
      gap:16px;
      max-height:max(260px, calc(var(--sec-h) - 2 * var(--sec-pad) - 250px));
      overflow-y:auto;
      padding:10px 12px 16px;          /* ruang agar efek naik & bayangan kartu tidak terpotong */
      margin:-10px -12px -16px;
      scrollbar-width:thin;
      scrollbar-color:rgba(245,241,234,0.3) transparent;
    }
    .mc-grid::-webkit-scrollbar{width:8px;}
    .mc-grid::-webkit-scrollbar-thumb{background:rgba(245,241,234,0.28);border-radius:8px;}
    .mc-card{aspect-ratio:1/1;}
    .mc-overlay{padding:36px 14px 14px;}
    .mc-name{font-size:16px;}
    .mc-desc{font-size:13px;margin-top:8px;-webkit-line-clamp:3;}
    .mc-price{font-size:16px;margin-top:8px;}
  }
  @media (min-width:821px) and (hover:hover){
    .mc-card:hover{box-shadow:0 12px 26px rgba(0,0,0,0.38);}
  }

  /* ===== Animasi masuk saat scroll (kelas "rv" diberi "in" oleh skrip saat elemen terlihat) ===== */
  .js .rv{
    opacity:0;transform:translateY(28px);
    transition:opacity .7s ease var(--d,0s), transform .7s cubic-bezier(.2,.7,.2,1) var(--d,0s);
  }
  .js .rv-left{transform:translateX(-24px);}
  .js .rv-right{transform:translateX(24px);}
  .js .rv-zoom{transform:scale(.92);}
  .js .rv.in{opacity:1;transform:none;}

  /* ===== Menu navbar: garis bawah & penanda bagian yang sedang dilihat ===== */
  .nav-links a{position:relative;padding-bottom:4px;transition:color .2s ease;}
  .nav-links a::after{
    content:'';position:absolute;left:0;bottom:-2px;height:2px;width:100%;background:var(--orange);
    transform:scaleX(0);transform-origin:left;transition:transform .3s ease;
  }
  .nav-links a:hover::after, .nav-links a.active::after{transform:scaleX(1);}
  .nav-links a.active{color:var(--orange-light);}

  /* ===== Hero: masuk bertahap, hiasan melayang ===== */
  .hero{position:relative;overflow:hidden;}
  .hero > .wrap{position:relative;z-index:1;}
  /* Teks hero memakai kelas .rv (animasi berulang saat scroll), jadi tidak perlu animasi sekali-jalan lagi */

  .logo-mark img{animation:floaty 6s ease-in-out 1s infinite;}
  @keyframes floaty{0%,100%{transform:translateY(0);}50%{transform:translateY(-12px);}}

  .hero h1 span{position:relative;display:inline-block;}
  .hero h1 span::after{
    content:'';position:absolute;left:0;right:0;bottom:2px;height:8px;border-radius:4px;z-index:-1;
    background:rgba(232,86,42,0.35);transform:scaleX(0);transform-origin:left;
    transition:transform .9s ease .8s;
  }
  .hero h1.in span::after{transform:scaleX(1);}   /* garis menyapu setiap kali judul hero muncul */

  .hero-blob{
    position:absolute;border-radius:50%;filter:blur(70px);opacity:.32;pointer-events:none;
    animation:blobMove 14s ease-in-out infinite alternate;
  }
  .hero-blob.one{width:380px;height:380px;background:var(--orange);top:-130px;right:-90px;}
  .hero-blob.two{width:320px;height:320px;background:#5b86d6;bottom:-150px;left:-70px;animation-delay:-7s;}
  @keyframes blobMove{from{transform:translate(0,0) scale(1);}to{transform:translate(-40px,34px) scale(1.18);}}

  .hero-bean{
    position:absolute;width:var(--s,26px);height:calc(var(--s,26px) * 1.45);
    border-radius:50% 50% 50% 50% / 60% 60% 40% 40%;background:var(--orange);opacity:.2;pointer-events:none;
    animation:beanFloat 9s ease-in-out infinite;animation-delay:var(--d,0s);
  }
  .hero-bean::after{
    content:'';position:absolute;left:50%;top:10%;bottom:10%;width:2px;border-radius:2px;
    background:rgba(36,58,107,0.7);transform:translateX(-50%) rotate(10deg);
  }
  @keyframes beanFloat{
    0%,100%{transform:translateY(0) rotate(var(--r,0deg));}
    50%{transform:translateY(-24px) rotate(calc(var(--r,0deg) + 22deg));}
  }

  /* ===== Pita kata berjalan ===== */
  .marquee{background:var(--orange);color:#243A6B;overflow:hidden;white-space:nowrap;padding:14px 0;}
  .marquee-track{display:flex;width:max-content;animation:marquee 34s linear infinite;}
  .marquee:hover .marquee-track{animation-play-state:paused;}
  .marquee-group{display:flex;align-items:center;gap:40px;padding-right:40px;}
  .marquee-group span{font-weight:800;font-size:14px;letter-spacing:.14em;text-transform:uppercase;}
  .marquee-group i{width:8px;height:8px;border-radius:50%;background:#243A6B;opacity:.55;flex-shrink:0;}
  @keyframes marquee{from{transform:translateX(0);}to{transform:translateX(-50%);}}

  /* ===== Angka singkat ===== */
  .stats{background:var(--navy);border-bottom:1px solid rgba(245,241,234,0.08);padding:34px 0;}
  .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;text-align:center;}
  .stat + .stat{border-left:1px solid rgba(245,241,234,0.1);}
  .stat-num{font-size:clamp(28px,3.6vw,42px);font-weight:800;line-height:1.15;color:var(--orange-light);}
  .stat-num-text{font-size:clamp(22px,2.6vw,30px);padding-top:6px;}
  .stat-label{font-size:13.5px;color:var(--text-muted);margin-top:6px;}
  .stat-sub{font-size:12px;color:var(--text-muted);opacity:.8;margin-top:2px;}   /* baris kecil jam weekend */

  /* ===== Jam operasional di bagian Kontak: weekday & weekend bertumpuk ===== */
  .jam-list{display:grid;gap:4px;}
  .jam-list span{display:inline-block;min-width:122px;color:var(--text-muted);}

  /* ===== Ajakan reservasi ===== */
  .cta-band{background:linear-gradient(120deg,var(--orange),#c9421d);padding:48px 0;}
  .cta-inner{display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap;}
  .cta-inner h2{font-size:clamp(22px,2.8vw,30px);color:#fff;margin:0 0 6px;}
  .cta-inner p{color:rgba(255,255,255,0.92);margin:0;max-width:52ch;}
  .cta-band .btn-primary{
    background:#fff;color:#243A6B;box-shadow:0 8px 20px rgba(0,0,0,0.2);
    transition:transform .2s ease, background .2s ease;
  }
  .cta-band .btn-primary:hover{background:#fff4ee;transform:translateY(-2px);}

  /* ===== Tombol WhatsApp melayang ===== */
  .wa-float{
    position:fixed;right:22px;bottom:22px;z-index:30;width:56px;height:56px;border-radius:50%;
    background:#25D366;color:#fff;display:flex;align-items:center;justify-content:center;
    box-shadow:0 10px 24px rgba(0,0,0,0.35);transition:transform .25s ease;
  }
  .wa-float:hover{transform:scale(1.08);}
  .wa-float svg{width:28px;height:28px;position:relative;}
  .wa-float::before{
    content:'';position:absolute;inset:0;border-radius:50%;background:#25D366;opacity:.5;
    animation:waPulse 2.2s ease-out infinite;
  }
  @keyframes waPulse{0%{transform:scale(1);opacity:.5;}100%{transform:scale(1.7);opacity:0;}}

  @media (max-width:640px){
    .stats-grid{grid-template-columns:repeat(2,1fr);row-gap:26px;}
    .stat:nth-child(3){border-left:none;}
    .wa-float{right:16px;bottom:16px;}
  }

  /* Pengguna yang memilih "kurangi gerakan": semua efek gerak dimatikan */
  @media (prefers-reduced-motion:reduce){
    .js .rv{opacity:1;transform:none;transition:none;}
    .hero-eyebrow, .hero h1, .hero p, .hero-actions, .logo-mark, .logo-mark img,
    .hero-blob, .hero-bean, .marquee-track, .wa-float::before{animation:none;}
    .hero h1 span::after{animation:none;transform:scaleX(1);}
    .nav-links a::after{transition:none;}
  }

  /* =====================================================================
     RESPONSIF halaman depan (tablet & HP)
     ===================================================================== */
  body{overflow-x:clip;} /* jaring pengaman: tidak ada geser horizontal */

  @media (max-width:1024px){
    .nav-links a::after{display:none;}          /* garis bawah penanda hanya untuk menu desktop */
    .nav-links a{padding:14px 2px;}              /* menimpa aturan menu desktop di atas */
    .nav-links a.active{color:var(--orange-light);}
  }

  @media (max-width:820px){
    :root{--sec-pad:clamp(28px,5vh,44px);--tentang-naik:0px;}
    .js .rv-left{transform:translateX(-12px);}
    .js .rv-right{transform:translateX(12px);}

    .hero-blob.one{width:260px;height:260px;top:-110px;right:-120px;}
    .hero-blob.two{width:240px;height:240px;bottom:-130px;left:-110px;}
    .hero-bean{opacity:.12;}
    .logo-mark{padding:12px;}
    .logo-mark img{max-width:min(190px,56vw);}

    .marquee{padding:10px 0;}
    .marquee-group{gap:28px;padding-right:28px;}
    .marquee-group span{font-size:12px;letter-spacing:.12em;}
    .stats{padding:26px 0;}

    .about-figure{min-height:200px;}

    /* Menu: tab bisa digeser, kartu 2 kolom ringkas, deskripsi & harga langsung terlihat */
    .mc-tabs{
      flex-wrap:nowrap;overflow-x:auto;margin:0 -20px 18px;padding:0 20px 4px;
      scrollbar-width:none;-webkit-overflow-scrolling:touch;
    }
    .mc-tabs::-webkit-scrollbar{display:none;}
    .mc-tab{flex-shrink:0;}
    .mc-grid{grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:12px;}
    .mc-card{aspect-ratio:3/4;border-radius:14px;}
    .mc-overlay{padding:30px 10px 10px;}
    .mc-name{font-size:14px;margin-top:2px;}
    .mc-tag{font-size:10px;}
    .mc-desc{font-size:11.5px;margin-top:6px;-webkit-line-clamp:2;}
    .mc-price{font-size:14px;margin-top:6px;}

    .cta-band{padding:36px 0;}
    .cta-inner{flex-direction:column;align-items:stretch;text-align:center;}
    .cta-inner p{margin:0 auto;}
    .cta-inner .btn-primary{text-align:center;}

    .map-block{min-height:240px;}
  }

  @media (max-width:480px){
    .info-row{flex-direction:column;gap:4px;padding:14px 0;}
    .info-row .k{min-width:0;}
    .logo-mark img{max-width:min(160px,50vw);}
    .wa-float{width:52px;height:52px;right:14px;bottom:calc(14px + env(safe-area-inset-bottom));}
    .jam-list span{min-width:112px;}
  }

  /* Kartu menu masuk bertahap setiap kali daftar menu muncul di layar.
     fill-mode "backwards" (bukan "both") agar efek hover naik tetap berfungsi setelah animasi selesai. */
  .js .mc-grid.in .mc-card{animation:mcIn .5s ease backwards;animation-delay:var(--d,0s);}
  @media (prefers-reduced-motion:reduce){ .js .mc-grid.in .mc-card{animation:none;} }

  /* ===== Testimoni & Kontak: diseragamkan dengan Tentang, Suasana, Menu ===== */
  #testimoni, #kontak{padding-block:var(--sec-pad);}
  #testimoni .section-head, #kontak .section-head{margin-bottom:20px;}

  /* Kontak + footer dibuat pas satu layar: saat menu Kontak diklik, halaman bisa digulir sampai
     bagian ini tepat di bawah navbar, dan isinya rata tengah di ruang di atas footer. */
  #kontak{min-height:calc(var(--sec-h) - var(--footer-h));}

  @media (min-width:821px){
    /* Ukuran ikut tinggi layar supaya kartu testimoni & info kontak tetap muat di laptop yang pendek */
    #testimoni .testi-grid{gap:clamp(12px, 2.2vh, 28px);}
    #testimoni .testi-card{padding:clamp(16px, 2.8vh, 30px);}
    #testimoni .testi-name{margin-top:clamp(10px, 1.8vh, 18px);}
    #kontak .info-row{padding:clamp(8px, 1.8vh, 18px) 0;}
  }

  /* ===== Pencarian menu ===== */
  .mc-toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px 24px;margin-bottom:12px;}
  .mc-toolbar .mc-tabs{margin-bottom:0;}
  .mc-search{position:relative;flex:1 1 240px;max-width:340px;}
  .mc-search-icon{
    position:absolute;left:15px;top:50%;transform:translateY(-50%);
    width:18px;height:18px;color:var(--text-muted);pointer-events:none;
  }
  .mc-search input{
    width:100%;padding:11px 42px 11px 42px;border-radius:999px;
    border:1px solid rgba(245,241,234,0.25);background:var(--navy);color:var(--text-light);
    font-family:inherit;font-size:14.5px;transition:border-color .2s ease;
  }
  .mc-search input::placeholder{color:var(--text-muted);}
  .mc-search input:focus{outline:none;border-color:var(--orange);}
  .mc-search input::-webkit-search-cancel-button{display:none;}   /* pakai tombol hapus sendiri */
  .mc-search-clear{
    position:absolute;right:8px;top:50%;transform:translateY(-50%);
    width:28px;height:28px;border-radius:50%;border:none;display:none;
    align-items:center;justify-content:center;font-size:18px;line-height:1;
    background:rgba(245,241,234,0.14);color:var(--text-light);cursor:pointer;
  }
  .mc-search-clear:hover{background:rgba(245,241,234,0.26);}
  .mc-search.ada-isi .mc-search-clear{display:flex;}

  .mc-info{font-size:13px;color:var(--text-muted);margin:0 0 14px;min-height:20px;}
  .mc-tab-kosong{opacity:.45;}

  .mc-kosong{text-align:center;padding:40px 16px;color:var(--text-muted);}
  .mc-kosong[hidden]{display:none;}
  .mc-kosong strong{color:var(--text-light);word-break:break-word;}
  .mc-reset{
    margin-top:14px;padding:9px 20px;border-radius:999px;border:1px solid var(--orange);
    background:transparent;color:var(--orange-light);font-family:inherit;font-size:14px;font-weight:600;cursor:pointer;
    transition:background .2s ease,color .2s ease;
  }
  .mc-reset:hover{background:var(--orange);color:#fff;}

  @media (max-width:820px){
    /* nowrap + lebar 100%: kolom pencarian pas selebar layar (tidak ikut melebar mengikuti deretan tab yang digeser) */
    .mc-toolbar{flex-direction:column;flex-wrap:nowrap;align-items:stretch;gap:12px;margin-bottom:10px;}
    .mc-search{order:-1;flex:0 0 auto;width:100%;max-width:none;min-width:0;}
    .mc-search input{font-size:16px;max-width:100%;}   /* 16px: mencegah iPhone memperbesar layar saat diketuk */
  }

  /* ===== Kolom pencarian: samakan tampilan di semua browser & perangkat ===== */
  .teks-sentuh{display:none;}
  @media (hover:none){
    .teks-desktop{display:none;}
    .teks-sentuh{display:inline;}
  }

  .mc-search input{
    -webkit-appearance:none;appearance:none;   /* iPhone/Safari: pakai gaya kita, bukan gaya bawaan kolom pencarian */
    box-shadow:none;min-height:46px;caret-color:var(--orange-light);
  }
  .mc-search input::-webkit-search-decoration,
  .mc-search input::-webkit-search-results-button,
  .mc-search input::-webkit-search-results-decoration{display:none;}   /* ikon kaca pembesar bawaan iPhone */

  @media (hover:none){
    .mc-search-clear{width:34px;height:34px;right:6px;font-size:20px;}  /* lebih mudah diketuk dengan jari */
  }

  /* ===== Foto bagian Tentang: utuh (sampai lantai), tidak dipotong, ukuran mengikuti tinggi layar ===== */
  .about-figure{
    position:relative;padding:0;display:block;background:none;overflow:visible;
    aspect-ratio:399 / 501;                       /* sama dengan bentuk foto asli (tegak) */
  }
  .about-figure img{
    position:absolute;inset:0;width:100%;height:100%;
    object-fit:cover;object-position:center;border-radius:16px;
    box-shadow:0 18px 40px rgba(0,0,0,0.35);
  }
  .about-figure span{                             /* chip menggantung di tepi bawah foto, tidak menutupi lantai */
    position:absolute;left:16px;bottom:-14px;z-index:1;
    padding:7px 16px;border-radius:999px;
    background:rgba(232,86,42,0.95);color:#fff;font-size:15px;font-weight:600;
    box-shadow:0 6px 16px rgba(0,0,0,0.25);
  }

  @media (min-width:821px){
    #tentang{
      /* Tinggi foto: selebar mungkin tapi tetap muat satu layar, maksimal 640px,
         dan lebarnya tidak lebih dari separuh lebar isi (supaya teks di kanan tidak sempit) */
      --foto-h:min(
        clamp(360px, calc(var(--sec-h) - 2 * var(--sec-pad) - var(--tentang-naik)), 640px),
        calc((min(1100px, 100vw) - 64px) * 0.5 / 0.7964)
      );
    }
    .about-inner{grid-template-columns:auto 1fr;align-items:center;}
    .about-figure{height:var(--foto-h);width:calc(var(--foto-h) * 0.7964);min-height:0;}
  }
  /* Layar pendek (laptop kecil): angkat-ke-atas dimatikan supaya fotonya bisa lebih besar */
  @media (min-width:821px) and (max-height:720px){ #tentang{--tentang-naik:0px;} }

  @media (max-width:820px){
    .about-figure{width:min(100%,420px);height:auto;min-height:0;justify-self:center;margin-bottom:14px;}
  }

  /* ===== Galeri Suasana: grid 5 foto, tombol, dan tampilan besar ===== */
  .ambience-grid .b5{background:linear-gradient(200deg,var(--ink),var(--orange-light));}
  .ambience-grid div[data-galeri-i]{cursor:zoom-in;transition:filter .25s ease;}
  .ambience-grid div[data-galeri-i]:hover{filter:brightness(1.1);}
  .ambience-grid div[data-galeri-i]:focus-visible{outline:2px solid var(--orange);outline-offset:3px;}
  .gal-aksi{display:flex;justify-content:flex-end;margin-top:14px;}
  .gal-semua{
    padding:9px 20px;border-radius:999px;border:1px solid var(--orange);background:transparent;
    color:var(--orange-light);font-family:inherit;font-size:14px;font-weight:600;cursor:pointer;
    transition:background .2s ease,color .2s ease;
  }
  .gal-semua:hover{background:var(--orange);color:#fff;}

  .lightbox{
    position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;
    background:#080c1c;padding:24px 72px;overflow:hidden;
  }
  .lb-fon{                                        /* latar: foto yang sama, diburamkan, mengisi seluruh layar */
    position:absolute;inset:-40px;pointer-events:none;
    background-size:cover;background-position:center;
    filter:blur(36px) brightness(0.38) saturate(1.1);
  }
  .lightbox[hidden]{display:none;}
  body.lb-open{overflow:hidden;}
  /* Bingkai berukuran tetap: semua foto (tegak maupun lebar) tampil seragam dan utuh, tidak terpotong */
  .lb-isi{
    position:relative;z-index:1;margin:0;width:min(92vw,1100px);
    height:calc(100vh - 150px);height:calc(100dvh - 150px);
    display:flex;flex-direction:column;align-items:center;gap:12px;
  }
  .lb-isi img{
    flex:1 1 0;min-height:0;width:100%;display:block;object-fit:contain;
    filter:drop-shadow(0 12px 30px rgba(0,0,0,0.45));
  }
  .lb-isi figcaption{flex:0 0 auto;display:flex;gap:16px;align-items:center;justify-content:center;color:var(--text-light);font-size:14px;text-align:center;}
  #lbNo{color:var(--text-muted);font-size:13px;}
  .lb-tutup, .lb-nav{
    position:absolute;z-index:2;display:flex;align-items:center;justify-content:center;
    width:46px;height:46px;border-radius:50%;border:1px solid rgba(245,241,234,0.3);
    background:rgba(36,58,107,0.85);color:var(--text-light);font-family:inherit;cursor:pointer;
    transition:background .2s ease,border-color .2s ease;
  }
  .lb-tutup:hover, .lb-nav:hover{background:var(--orange);border-color:var(--orange);}
  .lb-tutup{top:18px;right:18px;font-size:28px;line-height:1;}
  .lb-nav{top:50%;transform:translateY(-50%);font-size:34px;line-height:1;padding-bottom:4px;}
  .lb-prev{left:16px;}
  .lb-next{right:16px;}

  @media (max-width:820px){
    #suasana .ambience-grid .b1{grid-column:1 / -1;height:240px;}   /* foto besar selebar layar, sisanya berpasangan */
    .gal-aksi{justify-content:center;}
    .lightbox{padding:60px 10px 84px;}
    .lb-isi{width:100%;height:calc(100vh - 144px);height:calc(100dvh - 144px);}
    .lb-nav{top:auto;bottom:18px;transform:none;}
    .lb-prev{left:calc(50% - 60px);}
    .lb-next{right:calc(50% - 60px);}
  }
</style>
</head>
<body>

<header>
  <div class="wrap">
    <nav>
      <div class="brand"><img src="{{ asset('images/SerenataLogoHeader.png') }}" alt="Serenata Kopi & Space"></div>
      <div class="nav-links" id="navLinks">
        <a href="#tentang">Tentang</a>
        <a href="#menu">Menu</a>
        <a href="#suasana">Suasana</a>
        <a href="#testimoni">Testimoni</a>
        <a href="#kontak">Kontak</a>
        {{-- Hanya tampil di menu HP/tablet (di desktop link ini ada di sebelah tombol Reservasi) --}}
        @auth
          @unless(auth()->user()->hasRole('admin'))
            <a class="nav-dash-m" href="{{ route('dashboard') }}">Dashboard Saya</a>
          @endunless
        @endauth
      </div>
      <div class="nav-actions">
        {{-- Link "Masuk" dan "Panel Admin" sengaja tidak ditampilkan di halaman depan.
             Admin masuk lewat alamat /login. Link di bawah hanya muncul untuk pelanggan yang sudah login. --}}
        @auth
          @unless(auth()->user()->hasRole('admin'))
            <a class="nav-dash" href="{{ route('dashboard') }}">Dashboard Saya</a>
          @endunless
        @endauth
        <a href="{{ route('reservasi.form') }}" class="nav-btn">Reservasi</a>
      </div>
      <button type="button" class="nav-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="navLinks">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </div>
</header>

<section class="hero">
  {{-- Hiasan latar: cahaya lembut & biji kopi yang melayang pelan --}}
  <div class="hero-blob one" aria-hidden="true"></div>
  <div class="hero-blob two" aria-hidden="true"></div>
  <span class="hero-bean" aria-hidden="true" style="--s:30px;--r:-18deg;--d:0s;top:14%;left:6%;"></span>
  <span class="hero-bean" aria-hidden="true" style="--s:20px;--r:24deg;--d:-3s;top:62%;left:12%;"></span>
  <span class="hero-bean" aria-hidden="true" style="--s:26px;--r:8deg;--d:-5s;top:20%;left:46%;"></span>
  <span class="hero-bean" aria-hidden="true" style="--s:34px;--r:-30deg;--d:-2s;top:70%;left:52%;"></span>
  <span class="hero-bean" aria-hidden="true" style="--s:22px;--r:36deg;--d:-6s;top:12%;right:8%;"></span>
  <span class="hero-bean" aria-hidden="true" style="--s:28px;--r:-12deg;--d:-4s;bottom:12%;right:5%;"></span>

  <div class="wrap hero-inner">
    <div>
      <div class="hero-eyebrow rv">RUANG TENANG DI TENGAH KOTA</div>
      <h1 class="rv" style="--d:.1s">Kopi yang seirama, ruang yang <span>bersahabat</span>.</h1>
      <p class="rv" style="--d:.2s"> tempat singgah yang hangat — kopi racikan barista, kudapan buatan sendiri, dan suasana yang bikin betah berlama-lama.</p>
      <div class="hero-actions rv" style="--d:.3s">
        <a href="#menu" class="btn-primary">Lihat Menu</a>
        <a href="#tentang" class="btn-ghost">Tentang Kami</a>
      </div>
    </div>
    <div class="logo-mark rv rv-zoom" style="--d:.15s">
      <img src="{{ asset('images/SerenataLogo.PNG') }}" alt="Logo Serenata Kopi & Space">
    </div>
  </div>
</section>

{{-- Pita kata berjalan --}}
@php
    $kataPita = ['Kopi Pilihan', 'Ruang Nyaman', 'Pelayanan Personal', 'Reservasi Mudah', 'Serenata Kopi & Space', 'Sejak 2021'];
@endphp
<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
    @for($grup = 0; $grup < 4; $grup++)
      <div class="marquee-group">
        @foreach($kataPita as $kata)
          <span>{{ $kata }}</span><i></i>
        @endforeach
      </div>
    @endfor
  </div>
</div>

{{-- Angka-angka singkat (menghitung naik saat terlihat) --}}
<div class="stats">
  <div class="wrap stats-grid">
    <div class="stat rv">
      <div class="stat-num"><span data-count="{{ max(now()->year - 2021, 1) }}">{{ max(now()->year - 2021, 1) }}</span>+</div>
      <div class="stat-label">Tahun melayani</div>
    </div>
    <div class="stat rv" style="--d:.1s">
      <div class="stat-num"><span data-count="{{ $menus->count() }}">{{ $menus->count() }}</span></div>
      <div class="stat-label">Pilihan menu</div>
    </div>
    <div class="stat rv" style="--d:.2s">
      <div class="stat-num"><span data-count="3">3</span></div>
      <div class="stat-label">Lantai untuk bersantai</div>
    </div>
    {{-- Jam operasional: angka besar = Senin-Jumat, baris kecil = Sabtu-Minggu (diatur di /admin/kontak) --}}
    <div class="stat rv" style="--d:.3s">
      <div class="stat-num stat-num-text">{{ \App\Models\ContactInfo::formatJam($kontak->jam_buka) }}&ndash;{{ \App\Models\ContactInfo::formatJam($kontak->jam_tutup) }}</div>
      <div class="stat-label">Jam operasional Sen&ndash;Jum</div>
      <div class="stat-sub">Sab&ndash;Min: {{ $kontak->teksJamWeekend() }}</div>
    </div>
  </div>
</div>

<section id="tentang" class="about">
  <div class="wrap about-inner">
    <div class="about-figure rv rv-left">
        <img src="{{ asset('images/tentang.jpg') }}" alt="Tampak depan Serenata Kopi &amp; Space" loading="lazy">
        <span>Sejak 2021</span>
      </div>
    <div>
      <div class="section-tag rv">TENTANG KAMI</div>
      <h2 class="rv" style="--d:.08s">Dibangun dari kecintaan pada kopi dan ruang berkumpul</h2>
      <div class="about-list">
        <div class="about-item rv" style="--d:0.16s">
          <div class="num">01</div>
          <div>
            <h3>Biji Kopi Pilihan</h3>
            <p>Disangrai lokal dengan profil rasa yang konsisten setiap harinya.</p>
          </div>
        </div>
        <div class="about-item rv" style="--d:0.24s">
          <div class="num">02</div>
          <div>
            <h3>Ruang yang Nyaman</h3>
            <p>Desain interior hangat, cocok untuk kerja, ngobrol, atau sekadar rehat.</p>
          </div>
        </div>
        <div class="about-item rv" style="--d:0.32s">
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
    <div class="section-head rv">
      <div class="section-tag">MENU FAVORIT</div>
      <h2>Beberapa yang paling dicari</h2>
      <p>Dari kopi klasik hingga kreasi khas rumah, dibuat dengan bahan segar setiap hari.
        <span class="teks-desktop">Arahkan kursor ke menu untuk melihat deskripsi dan harga.</span>
        <span class="teks-sentuh">Cari menu atau pilih kategori untuk menemukan favoritmu.</span></p>
    </div>

    @php
        $labelKategori = ['kopi' => 'Kopi', 'non-kopi' => 'Non-Kopi', 'kudapan' => 'Kudapan'];
        $kategoriAda = $menus->pluck('kategori')->unique()->values();
    @endphp

    @if($menus->count())
      {{-- Tab filter kategori + kolom pencarian --}}
      <div class="mc-toolbar">
        <div class="mc-tabs rv" style="--d:.1s">
          <button type="button" class="mc-tab active" data-filter="semua" aria-pressed="true">
            Semua <span>{{ $menus->count() }}</span>
          </button>
          @foreach($kategoriAda as $kat)
            <button type="button" class="mc-tab" data-filter="{{ $kat }}" aria-pressed="false">
              {{ $labelKategori[$kat] ?? ucfirst($kat) }} <span>{{ $menus->where('kategori', $kat)->count() }}</span>
            </button>
          @endforeach
        </div>

        <div class="mc-search rv" style="--d:.15s" role="search">
          <svg class="mc-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="search" id="mcCari" placeholder="Cari menu favoritmu..." aria-label="Cari menu" autocomplete="off" inputmode="search" enterkeyhint="search">
          <button type="button" class="mc-search-clear" id="mcCariHapus" aria-label="Hapus pencarian">&times;</button>
        </div>
      </div>
      <p class="mc-info" id="mcInfo" aria-live="polite"></p>

      {{-- Kartu menu: nama selalu terlihat, deskripsi + harga muncul saat di-hover --}}
      <div class="mc-grid rv" style="--d:.2s">
        @foreach($menus as $menu)
          <article class="mc-card" data-kategori="{{ $menu->kategori }}" tabindex="0" style="--d:{{ number_format(min($loop->index, 9) * 0.05, 2) }}s">
            @if($menu->foto)
              <img class="mc-img" src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama }}" loading="lazy">
            @else
              <div class="mc-img mc-fallback" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10h13a4 4 0 010 8H3z"/><path d="M16 10V6a2 2 0 012-2 2 2 0 012 2v2"/><path d="M6 3v2M9 3v2"/></svg>
              </div>
            @endif

            <div class="mc-overlay">
              <span class="mc-tag">{{ $labelKategori[$menu->kategori] ?? ucfirst($menu->kategori) }}</span>
              <h3 class="mc-name">{{ $menu->nama }}</h3>
              <div class="mc-detail">
                @if($menu->deskripsi)
                  <p class="mc-desc">{{ $menu->deskripsi }}</p>
                @endif
                <div class="mc-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      {{-- Tampil jika tidak ada menu yang cocok dengan pencarian --}}
      <div class="mc-kosong" id="mcKosong" hidden>
        <p>Tidak ada menu yang cocok dengan &ldquo;<strong id="mcKataCari"></strong>&rdquo;.</p>
        <button type="button" class="mc-reset" id="mcReset">Hapus pencarian</button>
      </div>
    @else
      <p class="mc-empty">Menu segera hadir.</p>
    @endif
  </div>
</section>

<section id="suasana">
  <div class="wrap">
    <div class="section-head rv">
      <div class="section-tag">SUASANA</div>
      <h2>Sudut-sudut favorit di Serenata</h2>
    </div>
    {{-- Grid menampilkan 5 foto pertama (kotak besar + 4 kotak kecil). Semua foto bisa dilihat lewat tampilan besar. --}}
    <div class="ambience-grid">
      @forelse($fotos->take(5) as $i => $foto)
        <div class="{{ $i === 0 ? 'b1' : 'b' . ($i + 1) }} rv rv-zoom" data-galeri-i="{{ $i }}" role="button" tabindex="0"
             aria-label="Buka foto{{ $foto->keterangan ? ': ' . $foto->keterangan : '' }}"
             style="--d:{{ number_format($i * 0.12, 2) }}s;background-image:url('{{ asset('storage/' . $foto->foto) }}');background-size:cover;background-position:center;">
        </div>
      @empty
        <div class="b1 rv rv-zoom"></div><div class="b2 rv rv-zoom" style="--d:.12s"></div><div class="b3 rv rv-zoom" style="--d:.24s"></div><div class="b4 rv rv-zoom" style="--d:.36s"></div><div class="b5 rv rv-zoom" style="--d:.48s"></div>
      @endforelse
    </div>

    @if($fotos->count() > 0)
      <div class="gal-aksi">
        <button type="button" class="gal-semua" id="galBuka">Lihat semua foto ({{ $fotos->count() }}) &rarr;</button>
      </div>
    @endif
  </div>
</section>

<section id="testimoni" class="testi">
  <div class="wrap">
    <div class="section-head rv">
      <div class="section-tag">TESTIMONI</div>
      <h2>Kata mereka yang sudah mampir</h2>
    </div>
    <div class="testi-grid">
      @forelse($testimonis as $t)
        <div class="testi-card rv" style="--d:{{ number_format($loop->index * 0.12, 2) }}s">
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

{{-- Ajakan reservasi --}}
<div class="cta-band rv">
  <div class="wrap cta-inner">
    <div>
      <h2>Siap duduk santai bersama kami?</h2>
      <p>Pilih meja dan menu favoritmu dari sekarang, tanpa antre dan tanpa chat bolak-balik.</p>
    </div>
    <a href="{{ route('reservasi.form') }}" class="btn-primary">Reservasi Sekarang</a>
  </div>
</div>

<section id="kontak">
  <div class="wrap">
    <div class="section-head rv">
      <div class="section-tag">KUNJUNGI KAMI</div>
      <h2>Lokasi & Jam Operasional</h2>
    </div>
    <div class="contact">
      <div>
        <div class="info-row rv" style="--d:0s"><div class="k">Alamat</div><div class="v">{{ $kontak->alamat }}</div></div>
        {{-- Jam weekday & weekend dari /admin/kontak --}}
        <div class="info-row rv" style="--d:0.08s">
          <div class="k">Jam Buka</div>
          <div class="v jam-list">
            <div><span>Senin - Jumat</span>{{ $kontak->teksJamWeekday() }}</div>
            <div><span>Sabtu - Minggu</span>{{ $kontak->teksJamWeekend() }}</div>
          </div>
        </div>
        <div class="info-row rv" style="--d:0.16s"><div class="k">WhatsApp</div><div class="v">{{ $kontak->whatsapp }}</div></div>
        <div class="info-row rv" style="--d:0.24s"><div class="k">Email</div><div class="v">{{ $kontak->email }}</div></div>

        <div class="rv" style="--d:.32s;margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
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
      <div class="map-block rv rv-right" style="padding:0;overflow:hidden;">
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

{{-- Tombol chat WhatsApp melayang --}}
<a class="wa-float" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kontak->whatsapp) }}" target="_blank" rel="noopener" aria-label="Chat via WhatsApp" title="Chat via WhatsApp">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.4 8.4 0 01-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.4 8.4 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
</a>

{{-- Tampilan besar galeri Suasana --}}
@php
    // Tampilan besar memakai versi utuh (galeri/penuh/...) kalau ada; kalau tidak, foto yang sama dengan di grid
    $dataGaleri = $fotos->map(function ($f) {
        $penuh = 'galeri/penuh/' . basename($f->foto);
        $berkas = \Illuminate\Support\Facades\Storage::disk('public')->exists($penuh) ? $penuh : $f->foto;

        return ['src' => asset('storage/' . $berkas), 'ket' => $f->keterangan];
    })->values();
@endphp
<script type="application/json" id="data-galeri">@json($dataGaleri)</script>
<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Galeri foto suasana Serenata" hidden>
  <div class="lb-fon" id="lbFon" aria-hidden="true"></div>
  <button type="button" class="lb-tutup" aria-label="Tutup galeri">&times;</button>
  <button type="button" class="lb-nav lb-prev" aria-label="Foto sebelumnya">&lsaquo;</button>
  <figure class="lb-isi">
    <img id="lbFoto" alt="">
    <figcaption><span id="lbKet"></span><span id="lbNo"></span></figcaption>
  </figure>
  <button type="button" class="lb-nav lb-next" aria-label="Foto berikutnya">&rsaquo;</button>
</div>


<script>
(function () {
  var header = document.querySelector('header');
  if (!header) return;

  var root = document.documentElement;
  var lastY = window.pageYOffset || 0;
  var ticking = false;
  var anchorScrolling = false; // true selama halaman bergulir akibat klik link menu (#tentang, #menu, ...)
  var unlockTimer = null;
  var DELTA = 8;               // abaikan gerakan scroll yang sangat kecil

  // Samakan tinggi navbar dengan variabel CSS, supaya bagian yang dituju pas di bawah navbar
  function setNavHeight() {
    root.style.setProperty('--nav-h', header.offsetHeight + 'px');
    var footer = document.querySelector('footer');
    if (footer) root.style.setProperty('--footer-h', footer.offsetHeight + 'px');
  }

  // Setelah klik link menu, navbar dibiarkan tampil sampai gulir selesai
  function holdVisible() {
    anchorScrolling = true;
    header.classList.remove('header-hidden');
    clearTimeout(unlockTimer);
    unlockTimer = setTimeout(function () {
      anchorScrolling = false;
      lastY = window.pageYOffset || 0;
    }, 200);
  }

  function update() {
    var y = window.pageYOffset || 0;

    // Menu HP sedang terbuka: navbar jangan disembunyikan
    if (header.classList.contains('nav-open')) { lastY = y; ticking = false; return; }

    if (y <= 0) {
      header.classList.remove('header-hidden');   // di paling atas: selalu tampil
      lastY = 0;
    } else if (Math.abs(y - lastY) > DELTA) {
      if (y > lastY && y > header.offsetHeight) {
        header.classList.add('header-hidden');    // scroll ke bawah -> sembunyi
      } else if (y < lastY) {
        header.classList.remove('header-hidden'); // scroll ke atas -> muncul
      }
      lastY = y;
    }
    ticking = false;
  }

  window.addEventListener('scroll', function () {
    if (anchorScrolling) { holdVisible(); return; }
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(update);
  }, { passive: true });

  // Klik link ke bagian lain di halaman ini (navbar, tombol hero, footer)
  document.addEventListener('click', function (e) {
    var link = e.target.closest ? e.target.closest('a[href^="#"]') : null;
    if (link) holdVisible();
  });

  // Pengguna keyboard: navbar muncul saat Tab masuk ke navbar (klik mouse tidak ikut menghitung)
  header.addEventListener('focusin', function (e) {
    try {
      if (e.target.matches(':focus-visible')) header.classList.remove('header-hidden');
    } catch (err) { /* browser lama: abaikan */ }
  });

  window.addEventListener('resize', setNavHeight);
  window.addEventListener('load', setNavHeight);
  setNavHeight();
  if (window.location.hash) holdVisible(); // dibuka langsung dengan alamat seperti /#menu
})();
</script>

<script>
// Filter menu: kategori + pencarian (keduanya berjalan bersamaan)
(function () {
  var tabs = document.querySelectorAll('.mc-tab');
  var cards = document.querySelectorAll('.mc-card');
  if (!tabs.length || !cards.length) return;

  var input = document.getElementById('mcCari');
  var kotakCari = input ? input.parentNode : null;
  var tombolHapus = document.getElementById('mcCariHapus');
  var info = document.getElementById('mcInfo');
  var kosong = document.getElementById('mcKosong');
  var kataCari = document.getElementById('mcKataCari');
  var tombolReset = document.getElementById('mcReset');
  var grid = document.querySelector('.mc-grid');
  var kategori = 'semua';
  var total = cards.length;

  // Huruf kecil, tanpa aksen, spasi dirapikan: "Cappuccino " cocok dengan "cappuccino"
  function normal(teks) {
    var t = (teks || '').toLowerCase();
    if (t.normalize) t = t.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    return t.replace(/\s+/g, ' ').trim();
  }

  // Teks yang dicari tiap kartu: nama + deskripsi (dihitung sekali)
  cards.forEach(function (c) {
    var nama = c.querySelector('.mc-name');
    var desk = c.querySelector('.mc-desc');
    c._teks = normal((nama ? nama.textContent : '') + ' ' + (desk ? desk.textContent : ''));
  });

  function cocok(card, kata) {
    for (var i = 0; i < kata.length; i++) {
      if (card._teks.indexOf(kata[i]) === -1) return false; // semua kata harus ada
    }
    return true;
  }

  function terapkan() {
    var mentah = input ? input.value : '';
    var q = normal(mentah);
    var kata = q ? q.split(' ') : [];
    var tampil = 0;
    var hitung = { semua: 0 };

    cards.forEach(function (c) {
      var ok = cocok(c, kata);
      var kat = c.dataset.kategori;
      if (ok) { hitung.semua++; hitung[kat] = (hitung[kat] || 0) + 1; }
      var lolos = ok && (kategori === 'semua' || kat === kategori);
      c.classList.toggle('mc-hide', !lolos);
      if (lolos) tampil++;
    });

    // Angka di tiap tab ikut menunjukkan jumlah menu yang cocok dengan pencarian
    tabs.forEach(function (t) {
      var n = hitung[t.dataset.filter] || 0;
      var angka = t.querySelector('span');
      if (angka) angka.textContent = n;
      t.classList.toggle('mc-tab-kosong', n === 0);
    });

    if (info) {
      info.textContent = (kata.length || kategori !== 'semua')
        ? 'Menampilkan ' + tampil + ' dari ' + total + ' menu'
        : total + ' menu tersedia';
    }
    if (kosong) {
      kosong.hidden = (tampil !== 0);
      if (tampil === 0 && kataCari) kataCari.textContent = mentah.trim();
    }
    if (kotakCari) kotakCari.classList.toggle('ada-isi', mentah.length > 0);
    if (grid) grid.scrollTop = 0;
  }

  function hapusPencarian() {
    if (input) { input.value = ''; input.focus(); }
    terapkan();
  }

  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      kategori = tab.dataset.filter;
      tabs.forEach(function (t) {
        var aktif = (t === tab);
        t.classList.toggle('active', aktif);
        t.setAttribute('aria-pressed', aktif ? 'true' : 'false');
      });
      terapkan();
    });
  });

  if (input) {
    input.addEventListener('input', terapkan);
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && input.value) { e.preventDefault(); hapusPencarian(); }
    });
  }
  if (tombolHapus) tombolHapus.addEventListener('click', hapusPencarian);
  if (tombolReset) tombolReset.addEventListener('click', hapusPencarian);

  terapkan(); // isi teks info jumlah menu saat halaman dibuka
})();
</script>

<script>
// Animasi saat scroll: muncul bertahap, hitung naik angka, dan penanda menu navbar
(function () {
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var bisaObserve = 'IntersectionObserver' in window;
  var items = document.querySelectorAll('.rv');
  var counters = document.querySelectorAll('[data-count]');

  var putaranBerikut = 0;
  function hitungNaik(el) {
    var target = parseInt(el.dataset.count, 10) || 0;
    var durasi = 1400, mulai = null;
    var id = ++putaranBerikut;
    el._putaran = id;                                   // hitungan lama otomatis berhenti
    function langkah(waktu) {
      if (el._putaran !== id) return;                   // dibatalkan atau digantikan hitungan baru
      if (mulai === null) mulai = waktu;
      var p = Math.min((waktu - mulai) / durasi, 1);
      el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
      if (p < 1) window.requestAnimationFrame(langkah);
    }
    window.requestAnimationFrame(langkah);
  }

  if (!bisaObserve || reduce) {
    // Tanpa animasi: langsung tampilkan semuanya
    items.forEach(function (el) { el.classList.add('in'); });
  } else {
    // Animasi BERULANG: muncul saat masuk layar, kembali tersembunyi saat benar-benar keluar layar,
    // sehingga animasinya main lagi setiap kali bagian itu terlihat (scroll turun maupun naik).
    var munculkan = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var cukupTerlihat = en.intersectionRatio >= 0.15 ||
          (en.isIntersecting && en.intersectionRect && en.intersectionRect.height >= window.innerHeight * 0.4);
        if (en.isIntersecting && cukupTerlihat) {
          en.target.classList.add('in');
        } else if (!en.isIntersecting) {
          en.target.classList.remove('in');
        }
        // terlihat sebagian (di antara dua kondisi di atas): biarkan apa adanya, supaya tidak berkedip
      });
    }, { threshold: [0, 0.15], rootMargin: '0px 0px -6% 0px' });
    items.forEach(function (el) { munculkan.observe(el); });

    // Angka menghitung naik dari 0 setiap kali strip angka muncul lagi
    var angka = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var el = en.target;
        if (en.isIntersecting && en.intersectionRatio >= 0.6) {
          hitungNaik(el);
        } else if (!en.isIntersecting) {
          el._putaran = 0;                              // batalkan hitungan yang sedang berjalan
          el.textContent = '0';
        }
      });
    }, { threshold: [0, 0.6] });
    counters.forEach(function (el) { el.textContent = '0'; angka.observe(el); });
  }

  // Penanda menu navbar: tandai link bagian yang sedang berada di tengah layar
  var tautan = {};
  document.querySelectorAll('.nav-links a[href^="#"]').forEach(function (a) {
    tautan[a.getAttribute('href').slice(1)] = a;
  });
  var daftarId = Object.keys(tautan);
  if (bisaObserve && daftarId.length) {
    var pengamat = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var a = tautan[en.target.id];
        if (!a) return;
        if (en.isIntersecting) {
          daftarId.forEach(function (id) { tautan[id].classList.toggle('active', id === en.target.id); });
        } else {
          a.classList.remove('active');
        }
      });
    }, { rootMargin: '-45% 0px -45% 0px' });
    daftarId.forEach(function (id) {
      var bagian = document.getElementById(id);
      if (bagian) pengamat.observe(bagian);
    });
  }
})();
</script>

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

<script>
// Tampilan besar galeri Suasana: panah, keyboard, geser sentuh, dan klik di luar foto
(function () {
  var dataEl = document.getElementById('data-galeri');
  var kotak = document.getElementById('lightbox');
  if (!dataEl || !kotak) return;

  var foto = [];
  try { foto = JSON.parse(dataEl.textContent); } catch (e) { foto = []; }
  if (!foto.length) return;

  var gambar = document.getElementById('lbFoto');
  var ket = document.getElementById('lbKet');
  var nomor = document.getElementById('lbNo');
  var fon = document.getElementById('lbFon');
  var tutup = kotak.querySelector('.lb-tutup');
  var sebelum = kotak.querySelector('.lb-prev');
  var sesudah = kotak.querySelector('.lb-next');
  var indeks = 0;
  var pemicu = null;

  function tampilkan(i) {
    indeks = (i + foto.length) % foto.length;             // melingkar: setelah foto terakhir kembali ke pertama
    var f = foto[indeks];
    gambar.src = f.src;
    gambar.alt = f.ket || 'Foto suasana Serenata';
    ket.textContent = f.ket || '';
    nomor.textContent = (indeks + 1) + ' / ' + foto.length;
    if (fon) fon.style.backgroundImage = 'url("' + f.src.replace(/"/g, '%22') + '")';
    // muat foto tetangga lebih dulu supaya perpindahan terasa cepat
    [indeks + 1, indeks - 1].forEach(function (j) {
      var p = new Image();
      p.src = foto[(j + foto.length) % foto.length].src;
    });
  }

  function buka(i, dari) {
    pemicu = dari || null;
    tampilkan(i);
    kotak.hidden = false;
    document.body.classList.add('lb-open');
    tutup.focus();
  }

  function tutupGaleri() {
    kotak.hidden = true;
    document.body.classList.remove('lb-open');
    gambar.removeAttribute('src');
    if (fon) fon.style.backgroundImage = '';
    if (pemicu && pemicu.focus) pemicu.focus();
  }

  // Pemicu: klik/Enter/Spasi pada kotak foto, dan tombol "Lihat semua foto"
  document.querySelectorAll('[data-galeri-i]').forEach(function (el) {
    el.addEventListener('click', function () { buka(parseInt(el.dataset.galeriI, 10) || 0, el); });
    el.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); buka(parseInt(el.dataset.galeriI, 10) || 0, el); }
    });
  });
  var tombolSemua = document.getElementById('galBuka');
  if (tombolSemua) tombolSemua.addEventListener('click', function () { buka(0, tombolSemua); });

  sebelum.addEventListener('click', function () { tampilkan(indeks - 1); });
  sesudah.addEventListener('click', function () { tampilkan(indeks + 1); });
  tutup.addEventListener('click', tutupGaleri);
  kotak.addEventListener('click', function (e) { if (e.target === kotak) tutupGaleri(); });

  // Keyboard: panah kiri/kanan, Esc, dan Tab dijaga tetap di dalam tampilan besar
  document.addEventListener('keydown', function (e) {
    if (kotak.hidden) return;
    if (e.key === 'Escape') { tutupGaleri(); }
    else if (e.key === 'ArrowLeft') { tampilkan(indeks - 1); }
    else if (e.key === 'ArrowRight') { tampilkan(indeks + 1); }
    else if (e.key === 'Tab') {
      var urut = [tutup, sebelum, sesudah];
      var sekarang = urut.indexOf(document.activeElement);
      e.preventDefault();
      urut[(sekarang + (e.shiftKey ? -1 : 1) + urut.length) % urut.length].focus();
    }
  });

  // Geser sentuh di HP (tombol panah tetap tersedia untuk yang memakai mouse)
  var awalX = null;
  kotak.addEventListener('touchstart', function (e) { awalX = e.changedTouches[0].clientX; }, { passive: true });
  kotak.addEventListener('touchend', function (e) {
    if (awalX === null) return;
    var selisih = e.changedTouches[0].clientX - awalX;
    awalX = null;
    if (Math.abs(selisih) > 50) tampilkan(indeks + (selisih < 0 ? 1 : -1));
  }, { passive: true });
})();
</script>

</body>
</html>