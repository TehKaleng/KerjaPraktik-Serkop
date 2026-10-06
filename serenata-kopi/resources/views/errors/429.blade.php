@extends('errors.tema')

@section('kode', '429')
@section('judul', 'Terlalu banyak percobaan')
@section('pesan', 'Kamu mengirim terlalu banyak permintaan dalam waktu singkat. Tunggu beberapa saat lalu coba lagi. Kalau butuh bantuan segera, hubungi kafe lewat WhatsApp.')

@section('aksi')
  <a href="{{ url('/reservasi') }}" class="btn btn-ghost">Kembali ke Reservasi</a>
@endsection
