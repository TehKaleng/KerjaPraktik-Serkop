<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservasis = Reservation::latest()->get()->map(fn ($r) => [
            'id'       => $r->id,
            'nama'     => $r->nama,
            'wa'       => $r->whatsapp,
            'tanggal'  => $r->tanggal->format('d M Y'),
            'jam'      => substr($r->jam, 0, 5),
            'orang'    => $r->jumlah_orang,
            'catatan'  => $r->catatan ?: '-',
            'status'   => $r->status,
        ]);

        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function confirm(Reservation $reservasi)
    {
        $reservasi->update(['status' => 'confirmed']);

        return back()->with('success', 'Reservasi dikonfirmasi.');
    }
}
