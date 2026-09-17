<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Serenata Kopi & Space</title>
</head>
<body style="font-family:sans-serif;padding:40px;">
    <h2>Dashboard</h2>
    <p>Kamu sudah login sebagai: <strong>{{ auth()->user()->name }}</strong></p>
    <p>
        <a href="{{ route('home') }}">Ke Halaman Depan</a> |
        <a href="{{ route('profile.edit') }}">Edit Profil</a> |
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="border:none;background:none;color:blue;text-decoration:underline;cursor:pointer;padding:0;font:inherit;">Logout</button>
        </form>
    </p>
</body>
</html>