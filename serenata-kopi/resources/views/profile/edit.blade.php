<x-app-layout>
    @php
        $user = $user ?? auth()->user();
        $isAdmin = $user->hasRole('admin');
    @endphp

    <div class="pf-page">
        <div class="pf-container">

            {{-- Ringkasan akun + tombol kembali --}}
            <div class="pf-top">
                <div class="pf-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="pf-top-info">
                    <h1>{{ $user->name }}</h1>
                    <p>{{ $user->email }}</p>
                    <span class="pf-role {{ $isAdmin ? 'pf-role-admin' : '' }}">{{ $isAdmin ? 'Administrator' : 'Pelanggan' }}</span>
                </div>
                <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="pf-back">
                    &larr; {{ $isAdmin ? 'Kembali ke Panel Admin' : 'Kembali ke Dashboard' }}
                </a>
            </div>

            <div class="pf-card">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="pf-card">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Hapus akun disembunyikan untuk admin supaya akun satu-satunya admin tidak terhapus tidak sengaja --}}
            @unless($isAdmin)
                <div class="pf-card pf-card-danger">
                    @include('profile.partials.delete-user-form')
                </div>
            @endunless

        </div>
    </div>

    <style>
        .pf-page{padding:40px 0 90px;}
        .pf-container{max-width:720px;margin:0 auto;padding:0 24px;display:flex;flex-direction:column;gap:20px;}

        /* Ringkasan akun */
        .pf-top{
            display:flex;align-items:center;gap:18px;flex-wrap:wrap;
            padding:6px 0 10px;
        }
        .pf-avatar{
            width:64px;height:64px;border-radius:50%;background:var(--orange);color:#fff;
            display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;flex-shrink:0;
        }
        .pf-top-info{flex:1;min-width:200px;}
        .pf-top-info h1{font-size:22px;color:var(--text-light);line-height:1.2;}
        .pf-top-info p{font-size:14px;color:var(--text-muted);margin:2px 0 8px;}
        .pf-role{
            display:inline-block;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:600;
            background:rgba(245,241,234,0.12);color:var(--text-light);
        }
        .pf-role-admin{background:rgba(232,86,42,0.2);color:var(--orange-light);}
        .pf-back{font-size:14px;font-weight:600;color:var(--orange-light);}
        .pf-back:hover{color:var(--orange);}

        /* Kartu */
        .pf-card{
            background:var(--navy);border-radius:16px;padding:28px;
            border:1px solid rgba(245,241,234,0.08);
        }
        .pf-card-danger{border-color:rgba(240,110,100,0.35);}
        .pf-title{font-size:17px;font-weight:700;color:var(--text-light);margin:0 0 4px;}
        .pf-desc{font-size:13.5px;color:var(--text-muted);margin:0 0 22px;line-height:1.6;}

        /* Form */
        .pf-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
        .pf-field{display:flex;flex-direction:column;gap:6px;margin-bottom:16px;}
        .pf-field label{font-size:13.5px;font-weight:600;color:var(--text-light);}
        .pf-field input{
            width:100%;padding:11px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.2);
            background:var(--navy-soft);color:var(--text-light);font-family:inherit;font-size:14.5px;
        }
        .pf-field input:focus{outline:none;border-color:var(--orange);}

        /* Tombol lihat/sembunyikan password */
        .pf-input-wrap{position:relative;}
        .pf-field .pf-input-wrap input{padding-right:48px;}
        .pf-input-wrap input::-ms-reveal{display:none;} /* sembunyikan ikon mata bawaan Edge supaya tidak dobel */
        .pf-toggle-pw{
            position:absolute;top:50%;right:8px;transform:translateY(-50%);
            width:34px;height:34px;display:flex;align-items:center;justify-content:center;
            background:none;border:none;border-radius:6px;color:var(--text-muted);cursor:pointer;
        }
        .pf-toggle-pw:hover{color:var(--text-light);background:rgba(245,241,234,0.08);}
        .pf-toggle-pw svg{width:19px;height:19px;}
        .pf-toggle-pw .icon-eye-off{display:none;}
        .pf-toggle-pw[aria-pressed="true"] .icon-eye{display:none;}
        .pf-toggle-pw[aria-pressed="true"] .icon-eye-off{display:block;}
        .pf-error{font-size:13px;color:#f4a3a3;margin:0;}
        .pf-note{font-size:13px;color:var(--text-muted);margin:4px 0 0;}
        .pf-link-btn{
            background:none;border:none;padding:0;font:inherit;font-size:13px;color:var(--orange-light);
            text-decoration:underline;cursor:pointer;
        }
        .pf-ok{font-size:13px;color:#8fd19e;margin:4px 0 0;}

        .pf-actions{display:flex;align-items:center;gap:14px;margin-top:6px;}
        .pf-btn{
            display:inline-block;background:var(--orange);color:#fff;border:none;padding:11px 24px;
            border-radius:8px;font-weight:600;font-size:14.5px;cursor:pointer;font-family:inherit;
        }
        .pf-btn:hover{background:var(--orange-light);}
        .pf-btn-danger{background:transparent;border:1px solid #e57b73;color:#f4a3a3;}
        .pf-btn-danger:hover{background:rgba(229,123,115,0.15);}
        .pf-btn-danger-solid{background:#d9534f;color:#fff;border:none;}
        .pf-btn-danger-solid:hover{background:#c9433f;}

        /* "Tersimpan" hilang sendiri tanpa JavaScript */
        .pf-saved{font-size:13.5px;color:#8fd19e;animation:pfFade 3.5s ease forwards;}
        @keyframes pfFade{0%,70%{opacity:1;}100%{opacity:0;}}

        /* Hapus akun (pengguna biasa) */
        .pf-details summary{list-style:none;display:inline-block;}
        .pf-details summary::-webkit-details-marker{display:none;}
        .pf-delete-form{margin-top:18px;padding-top:18px;border-top:1px solid rgba(245,241,234,0.1);}

        @media(max-width:640px){
            .pf-grid{grid-template-columns:1fr;}
            .pf-card{padding:22px;}
        }
    </style>
</x-app-layout>