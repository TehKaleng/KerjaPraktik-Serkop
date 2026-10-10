<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index()
    {
        $semua = Reservation::with(['meja', 'items.menu'])->latest()->get();

        $reservasis = $semua->map(function (Reservation $r) {
            $bentrok = null;   // reservasi terkonfirmasi yang sudah memakai meja & jam ini
            $antrean = 0;      // reservasi lain yang masih menunggu di meja & jam yang sama

            if ($r->status !== 'confirmed' && $r->meja) {
                $tanggal = $r->tanggal->format('Y-m-d');
                $bentrok = $r->meja->reservasiBentrok($tanggal, $r->jam, ['confirmed'], $r->id)->first();
                $antrean = $r->meja->reservasiBentrok($tanggal, $r->jam, ['pending'], $r->id)->count();
            }

            return [
                'id'           => $r->id,
                'nama'         => $r->nama,
                'akun'         => $r->user_id !== null, // true = dipesan lewat akun, false = tamu
                'wa'           => $r->whatsapp,
                'meja'         => $r->meja->kode ?? '-',
                'tanggal'      => $r->tanggal->format('d M Y'),
                'jam'          => substr($r->jam, 0, 5),
                'orang'        => $r->jumlah_orang,
                'menu'         => $r->items->map(fn ($i) => ($i->menu->nama ?? 'Menu dihapus') . " x{$i->qty}")->implode(', '),
                'total'        => $r->total_harga,
                'catatan'      => $r->catatan ?: '-',
                'status'       => $r->status,
                'metode_bayar' => $r->metode_bayar,
                'status_bayar' => $r->status_bayar,
                'bentrok'      => $bentrok ? $bentrok->nama . ' pukul ' . substr($bentrok->jam, 0, 5) : null,
                'antrean'      => $antrean,
            ];
        });

        return view('admin.reservasi.index', compact('reservasis'));
    }

    /**
     * Konfirmasi reservasi. Ditolak bila meja & jamnya sudah dipakai reservasi lain yang terkonfirmasi.
     * Dikunci dalam transaksi supaya dua admin yang menekan Konfirmasi bersamaan tidak sama-sama lolos.
     */
    public function confirm(Reservation $reservasi)
    {
        $hasil = DB::transaction(function () use ($reservasi) {
            $reservasi = Reservation::whereKey($reservasi->id)->lockForUpdate()->firstOrFail();

            if ($reservasi->status === 'confirmed') {
                return ['ok', 'Reservasi ini sudah dikonfirmasi sebelumnya.'];
            }

            if ($reservasi->meja_id) {
                $meja = Meja::whereKey($reservasi->meja_id)->lockForUpdate()->first();
                $bentrok = $meja?->reservasiBentrok($reservasi->tanggal->format('Y-m-d'), $reservasi->jam, ['confirmed'], $reservasi->id)->first();

                if ($bentrok) {
                    return ['gagal', "Meja {$meja->kode} sudah terkonfirmasi untuk {$bentrok->nama} pukul " . substr($bentrok->jam, 0, 5)
                        . ". Hubungi {$reservasi->nama} lewat WhatsApp untuk pindah meja atau jam, lalu hapus reservasi ini bila dibatalkan."];
                }
            }

            $reservasi->update(['status' => 'confirmed']);

            return ['ok', 'Reservasi dikonfirmasi. Meja kini terkunci untuk pelanggan lain di jam tersebut.'];
        });

        return back()->with($hasil[0] === 'ok' ? 'success' : 'error', $hasil[1]);
    }

    public function confirmBayar(Reservation $reservasi)
    {
        $reservasi->update(['status_bayar' => 'lunas']);

        return back()->with('success', 'Pembayaran dikonfirmasi lunas.');
    }

    public function destroy(Reservation $reservasi)
    {
        // Item menu ikut terhapus otomatis (cascadeOnDelete di tabel reservation_items)
        $reservasi->delete();

        return back()->with('success', 'Reservasi berhasil dihapus.');
    }
}
