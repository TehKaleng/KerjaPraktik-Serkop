@extends('errors.tema')

@php
    // Pesan yang ditulis sendiri di kode (mis. "Akun admin tidak dapat dihapus.") ditampilkan apa adanya.
    // Pesan bawaan Laravel yang berbahasa Inggris diganti pesan Indonesia di bawah.
    $pesanAsli = trim((string) $exception->getMessage());
    $pesanBawaan = in_array($pesanAsli, ['', 'Forbidden', 'This action is unauthorized.'], true);
    // Tautan konfirmasi reservasi yang sudah kedaluwarsa atau tidak valid
    $tautanTidakValid = $pesanAsli === 'Invalid signature.';
@endphp

@section('kode', '403')
@section('judul', 'Akses ditolak')
@section('pesan', $tautanTidakValid
    ? 'Tautan ini sudah kedaluwarsa atau tidak valid. Silakan kembali ke halaman reservasi dan buat reservasi lagi.'
    : ($pesanBawaan
        ? 'Kamu tidak punya izin untuk membuka halaman ini. Kalau ini keliru, silakan masuk dengan akun yang sesuai.'
        : $pesanAsli))

@section('aksi')
  @if($tautanTidakValid)
    <a href="{{ url('/reservasi') }}" class="btn btn-ghost">Buat Reservasi</a>
  @else
    <a href="{{ url('/dashboard') }}" class="btn btn-ghost">Dashboard Saya</a>
  @endif
@endsection