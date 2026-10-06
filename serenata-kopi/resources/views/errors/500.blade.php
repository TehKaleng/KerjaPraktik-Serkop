@extends('errors.tema')

{{-- Halaman ini sengaja tidak membaca database, sesi, atau data pengguna:
     saat error 500 terjadi, bisa jadi justru bagian itulah yang bermasalah.
     Pesan error asli juga tidak ditampilkan agar tidak membocorkan detail internal. --}}

@section('kode', '500')
@section('judul', 'Terjadi gangguan di server')
@section('pesan', 'Maaf, ada kendala di sisi kami sehingga halaman ini belum bisa ditampilkan. Silakan muat ulang beberapa saat lagi. Kalau masih terjadi, kabari pihak kafe agar segera kami perbaiki.')

@section('aksi')
  <button type="button" class="btn btn-ghost" onclick="window.location.reload()">Muat Ulang</button>
@endsection
