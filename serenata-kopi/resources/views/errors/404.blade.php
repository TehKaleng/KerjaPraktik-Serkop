@extends('errors.tema')

@section('kode', '404')
@section('judul', 'Halaman tidak ditemukan')
@section('pesan', 'Alamat yang kamu buka tidak ada atau sudah dipindahkan. Periksa kembali alamatnya, atau kembali ke beranda untuk melihat menu dan melakukan reservasi.')

@section('aksi')
  <a href="{{ url('/#menu') }}" class="btn btn-ghost">Lihat Menu</a>
@endsection
