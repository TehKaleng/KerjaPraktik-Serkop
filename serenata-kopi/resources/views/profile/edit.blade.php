<x-app-layout>
    <x-slot name="header">
        Edit Profil
    </x-slot>

    <div style="padding:40px 0 80px;">
        <div class="wrap" style="max-width:640px;display:flex;flex-direction:column;gap:20px;">

            <div class="profile-card">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="profile-card">
                @include('profile.partials.update-password-form')
            </div>

            <div class="profile-card" style="border-color:rgba(240,90,90,0.35);">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>

    <style>
        .profile-card{
            background:var(--navy);border-radius:16px;padding:28px;border:1px solid rgba(245,241,234,0.08);
        }
        .profile-card h2{font-size:17px;font-weight:700;color:var(--text-light);margin-bottom:6px;}
        .profile-card p.mt-1{color:var(--text-muted);font-size:13.5px;margin-bottom:18px;}
        .profile-card label{display:block;font-size:13.5px;font-weight:600;color:var(--text-light);margin-bottom:6px;margin-top:14px;}
        .profile-card label:first-of-type{margin-top:0;}
        .profile-card input[type=text],
        .profile-card input[type=email],
        .profile-card input[type=password]{
            width:100%;padding:10px 14px;border-radius:8px;border:1px solid rgba(245,241,234,0.2);
            background:var(--navy-soft);color:var(--text-light);font-family:inherit;font-size:14.5px;
        }
        .profile-card input:focus{outline:none;border-color:var(--orange);}
        .profile-card button{
            background:var(--orange);color:#fff;border:none;padding:10px 22px;border-radius:8px;
            font-weight:600;font-size:14px;cursor:pointer;font-family:inherit;margin-top:16px;
        }
        .profile-card button:hover{background:var(--orange-light);}
        .profile-card .text-sm.text-gray-600, .profile-card .text-sm.text-gray-800{color:var(--text-muted);font-size:13px;}
        .profile-card .text-red-600{color:#f0a0a0;font-size:13px;margin-top:4px;}
        .profile-card .text-green-600{color:#8fd19e;font-size:13px;margin-left:10px;}
    </style>
</x-app-layout>
