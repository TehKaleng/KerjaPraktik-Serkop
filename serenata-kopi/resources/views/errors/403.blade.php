@extends('errors.tema')

@php
    // Pesan yang ditulis sendiri di kode (mis. "Akun admin tidak dapat dihapus.") ditampilkan apa adanya.
    // Pesan bawaan Laravel yang berbahasa Inggris diganti pesan Indonesia di bawah.
    $pesanAsli = trim((string) $exception->getMessage());
    $pesanBawaan = in_array($pesanAsli, ['', 'Forbidden', 'This action is unauthorized.'], true);
@endphp

@section('kode', '403')
@section('judul', 'Akses ditolak')
@section('pesan', $pesanBawaan
    ? 'Kamu tidak punya izin untuk membuka halaman ini. Kalau ini keliru, silakan masuk dengan akun yang sesuai.'
    : $pesanAsli)

@section('aksi')
  <a href="{{ url('/dashboard') }}" class="btn btn-ghost">Dashboard Saya</a>
@endsection
