<?php

namespace App\Http\Controllers;

use App\Models\ContactInfo;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create()
    {
        return view('reservasi');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:255',
            'whatsapp'     => 'required|string|max:20',
            'tanggal'      => 'required|date',
            'jam'          => 'required',
            'jumlah_orang' => 'required|integer|min:1',
            'catatan'      => 'nullable|string',
        ]);

        Reservation::create($validated);

        $kontak = ContactInfo::current();
        $nomorCafe = preg_replace('/[^0-9]/', '', $kontak->whatsapp); // bersihin format nomor

        $pesan = "Halo Serenata Kopi & Space, saya mau konfirmasi reservasi:\n"
            . "Nama: {$validated['nama']}\n"
            . "Tanggal: {$validated['tanggal']}\n"
            . "Jam: {$validated['jam']}\n"
            . "Jumlah orang: {$validated['jumlah_orang']}\n"
            . ($validated['catatan'] ? "Catatan: {$validated['catatan']}\n" : '');

        $waUrl = "https://wa.me/{$nomorCafe}?text=" . urlencode($pesan);

        return redirect($waUrl);
    }
}
