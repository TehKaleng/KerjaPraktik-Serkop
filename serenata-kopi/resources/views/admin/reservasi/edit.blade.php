@extends('layouts.admin')
@section('title', 'Ubah Reservasi')

@section('content')

<div class="panel">
  <div class="panel-head">
    <h2>Ubah Reservasi #{{ $reservasi->id }}</h2>
    <span class="badge badge-active">Terkonfirmasi</span>
  </div>

  @if($errors->any())
    <div style="margin:18px 22px 0;padding:12px 16px;border-radius:8px;background:#FDECEA;color:#B42318;font-size:14px;">
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('admin.reservasi.update', $reservasi) }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="kembali" value="{{ old('kembali', $kembali) }}">

    <div class="form-grid">
      <div class="field">
        <label for="nama">Nama Pemesan</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama', $reservasi->nama) }}" required maxlength="255">
      </div>
      <div class="field">
        <label for="whatsapp">Nomor WhatsApp</label>
        <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $reservasi->whatsapp) }}" required placeholder="08xxxxxxxxxx">
      </div>
      <div class="field">
        <label for="tanggal">Tanggal</label>
        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $reservasi->tanggal->format('Y-m-d')) }}" required>
      </div>
      <div class="field">
        <label for="jam">Jam</label>
        <input type="time" id="jam" name="jam" value="{{ old('jam', substr($reservasi->jam, 0, 5)) }}" required>
      </div>
      <div class="field">
        <label for="jumlah_orang">Jumlah Orang</label>
        <input type="number" id="jumlah_orang" name="jumlah_orang" min="1" max="50" value="{{ old('jumlah_orang', $reservasi->jumlah_orang) }}" required>
      </div>
      <div class="field">
        <label for="meja_id">Meja</label>
        <select id="meja_id" name="meja_id" required>
          @foreach($meja->groupBy('lantai') as $lantai => $daftarMeja)
            <optgroup label="Lantai {{ $lantai }}">
              @foreach($daftarMeja as $m)
                <option value="{{ $m->id }}" @selected((int) old('meja_id', $reservasi->meja_id) === $m->id)>
                  {{ $m->kode }} — {{ $m->kapasitas }} orang
                </option>
              @endforeach
            </optgroup>
          @endforeach
        </select>
      </div>
      <div class="field span-2">
        <label for="catatan">Catatan</label>
        <textarea id="catatan" name="catatan" rows="3" maxlength="500">{{ old('catatan', $reservasi->catatan) }}</textarea>
      </div>

      <div class="field span-2">
        <label>Pesanan Menu</label>
        @if($reservasi->items->count())
          <ul style="margin:0;padding-left:18px;font-size:14px;color:var(--text-gray);line-height:1.8;">
            @foreach($reservasi->items as $item)
              <li>{{ $item->menu->nama ?? 'Menu dihapus' }} x{{ $item->qty }}</li>
            @endforeach
          </ul>
          <div style="font-size:14px;margin-top:4px;"><strong>Total: Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</strong></div>
        @else
          <div style="font-size:14px;color:var(--text-gray);">Tidak ada pesanan menu.</div>
        @endif
      </div>
    </div>

    <div style="padding:0 22px 4px;font-size:13px;color:var(--text-gray);">
      Saat tanggal, jam, atau meja diubah, sistem memeriksa ulang jam operasional, kapasitas meja, dan jadwal reservasi terkonfirmasi lain.
    </div>

    <div class="form-foot">
      <a href="{{ old('kembali', $kembali) }}" class="btn btn-outline">Batal</a>
      <button type="submit" class="btn btn-orange">Simpan Perubahan</button>
    </div>
  </form>
</div>

@endsection
