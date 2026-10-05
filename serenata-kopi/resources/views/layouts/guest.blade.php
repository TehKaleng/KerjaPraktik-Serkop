<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Serenata Kopi & Space') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .guest-wrap{
            min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;
            background:var(--navy-soft);padding:24px;
        }
        .guest-logo{margin-bottom:28px;}
        .guest-logo img{height:52px;}
        .guest-card{
            width:100%;max-width:420px;background:var(--navy);border-radius:16px;
            padding:32px;box-shadow:0 20px 50px rgba(0,0,0,0.35);
        }
        .guest-card label{display:block;font-size:13.5px;font-weight:600;color:var(--text-light);margin-bottom:6px;}
        .guest-card input[type=text],
        .guest-card input[type=email],
        .guest-card input[type=password]{
            width:100%;padding:11px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.2);
            background:var(--navy-soft);color:var(--text-light);font-family:inherit;font-size:14.5px;
        }
        .guest-card input:focus{outline:none;border-color:var(--orange);}
        .guest-card .block.mt-4 { margin-top:18px; }
        .guest-card .flex.items-center.justify-between{
            display:flex;align-items:center;justify-content:space-between;margin-top:20px;gap:12px;flex-wrap:wrap;
        }
        .guest-card a{color:var(--orange-light);font-size:13.5px;}
        .guest-card a:hover{color:var(--orange);}
        .guest-card button, .guest-card .btn-primary{
            background:var(--orange);color:#fff;border:none;padding:11px 24px;border-radius:8px;
            font-weight:600;font-size:14.5px;cursor:pointer;font-family:inherit;
        }
        .guest-card button:hover{background:var(--orange-light);}
        .guest-card .text-sm.text-gray-600{color:var(--text-muted);font-size:13.5px;}
        .guest-card [role=alert], .guest-card .font-medium.text-sm.text-red-600{
            color:#f0a0a0;font-size:13px;margin-top:6px;
        }
        .guest-card .mb-4{margin-bottom:16px;}
        .guest-card input[type=checkbox]{accent-color:var(--orange);}
        .guest-back{margin-top:20px;}
        .guest-back a{color:var(--text-muted);font-size:13.5px;}
        .guest-back a:hover{color:var(--text-light);}
    
        /* Responsif HP */
        @media(max-width:480px){
            .guest-wrap{padding:28px 16px;justify-content:flex-start;}
            .guest-logo{margin-bottom:20px;}
            .guest-logo img{height:40px;}
            .guest-card{padding:22px;}
        }
    </style>
</head>
<body>
    <div class="guest-wrap">
        <div class="guest-logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/SerenataLogoHeader.png') }}" alt="Serenata Kopi & Space">
            </a>
        </div>

        <div class="guest-card">
            {{ $slot }}
        </div>

        <div class="guest-back">
            <a href="{{ route('home') }}">← Kembali ke halaman depan</a>
        </div>
    </div>
</body>
</html>