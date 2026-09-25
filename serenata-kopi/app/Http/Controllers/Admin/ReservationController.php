<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservasis = Reservation::with(['meja', 'items.menu'])
            ->latest()
            ->get()
            ->map(fn ($r) => [
                'id'           => $r->id,
                'nama'         => $r->nama,
                'wa'           => $r->whatsapp,
                'meja'         => $r->meja->kode ?? '-',
                'tanggal'      => $r->tanggal->format('d M Y'),
                'jam'          => substr($r->jam, 0, 5),
                'orang'        => $r->jumlah_orang,
                'menu'         => $r->items->map(fn ($i) => "{$i->menu->nama} x{$i->qty}")->implode(', '),
                'total'        => $r->total_harga,
                'catatan'      => $r->catatan ?: '-',
                'status'       => $r->status,
                'metode_bayar' => $r->metode_bayar,
                'status_bayar' => $r->status_bayar,
            ]);

        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function confirm(Reservation $reservasi)
    {
        $reservasi->update(['status' => 'confirmed']);

        return back()->with('success', 'Reservasi dikonfirmasi (hadir).');
    }

    public function confirmBayar(Reservation $reservasi)
    {
        $reservasi->update(['status_bayar' => 'lunas']);

        return back()->with('success', 'Pembayaran dikonfirmasi lunas.');
    }
}
