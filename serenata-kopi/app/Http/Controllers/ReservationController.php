<?php

namespace App\Http\Controllers;

use App\Models\ContactInfo;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservation;
use App\Notifications\ReservasiBerhasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function create()
    {
        $menusByKategori = Menu::orderBy('nama')->get()->groupBy('kategori');

        return view('reservasi', compact('menusByKategori'));
    }

    /**
     * Endpoint AJAX: cek meja mana yang kosong di lantai + tanggal + jam tertentu.
     */
    public function mejaTersedia(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam'     => 'required',
            'lantai'  => 'required|in:1,2,3',
        ]);

        $meja = Meja::where('lantai', $request->lantai)->orderBy('nomor_meja')->get();

        $hasil = $meja->map(function ($m) use ($request) {
            return [
                'id'         => $m->id,
                'kode'       => $m->kode,
                'nomor_meja' => $m->nomor_meja,
                'kapasitas'  => $m->kapasitas,
                'tersedia'   => $m->tersediaPada($request->tanggal, $request->jam),
            ];
        });

        return response()->json($hasil);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:255',
            'whatsapp'     => 'required|string|max:20',
            'tanggal'      => 'required|date|after_or_equal:today',
            'jam'          => 'required',
            'jumlah_orang' => 'required|integer|min:1',
            'meja_id'      => 'required|exists:meja,id',
            'catatan'      => 'nullable|string',
            'metode_bayar' => 'required|in:qris,cash',
            'menu_id'      => 'required|array|min:1',
            'menu_id.*'    => 'exists:menus,id',
            'qty'          => 'required|array',
            'qty.*'        => 'integer|min:1',
        ]);

        $meja = Meja::findOrFail($validated['meja_id']);

        if (!$meja->tersediaPada($validated['tanggal'], $validated['jam'])) {
            return back()
                ->withErrors(['meja_id' => 'Meja yang dipilih ternyata sudah dipesan orang lain. Silakan pilih meja lain.'])
                ->withInput();
        }

        $total = 0;
        $itemsData = [];
        foreach ($validated['menu_id'] as $i => $menuId) {
            $menu = Menu::findOrFail($menuId);
            $qty = (int) $validated['qty'][$i];
            $total += $menu->harga * $qty;
            $itemsData[] = [
                'menu_id'        => $menu->id,
                'qty'            => $qty,
                'harga_saat_itu' => $menu->harga,
            ];
        }

        $reservation = DB::transaction(function () use ($validated, $total, $itemsData) {
            $reservation = Reservation::create([
                'user_id'      => auth()->id(),
                'meja_id'      => $validated['meja_id'],
                'nama'         => $validated['nama'],
                'whatsapp'     => $validated['whatsapp'],
                'tanggal'      => $validated['tanggal'],
                'jam'          => $validated['jam'],
                'jumlah_orang' => $validated['jumlah_orang'],
                'catatan'      => $validated['catatan'] ?? null,
                'status'       => 'pending',
                'metode_bayar' => $validated['metode_bayar'],
                'status_bayar' => $validated['metode_bayar'] === 'qris' ? 'menunggu_konfirmasi' : 'belum_bayar',
                'total_harga'  => $total,
            ]);

            foreach ($itemsData as $item) {
                $reservation->items()->create($item);
            }

            return $reservation;
        });

        auth()->user()->notify(new ReservasiBerhasil($reservation));

        if ($validated['metode_bayar'] === 'qris') {
            return redirect()->route('reservasi.konfirmasi', $reservation);
        }

        return $this->redirectWhatsapp($reservation);
    }

    public function konfirmasi(Reservation $reservation)
    {
        abort_unless($reservation->user_id === auth()->id(), 403);

        $kontak = ContactInfo::current();

        return view('reservasi-konfirmasi', compact('reservation', 'kontak'));
    }

    public function selesai(Reservation $reservation)
    {
        abort_unless($reservation->user_id === auth()->id(), 403);

        return $this->redirectWhatsapp($reservation);
    }

    private function redirectWhatsapp(Reservation $reservation)
    {
        $kontak = ContactInfo::current();
        $nomorCafe = preg_replace('/[^0-9]/', '', $kontak->whatsapp);

        $daftarMenu = $reservation->items->map(function ($item) {
            return "- {$item->menu->nama} x{$item->qty} (Rp " . number_format($item->subtotal(), 0, ',', '.') . ")";
        })->implode("\n");

        $pesan = "Halo Serenata Kopi & Space, saya mau konfirmasi reservasi:\n"
            . "Nama: {$reservation->nama}\n"
            . "Meja: " . ($reservation->meja->kode ?? '-') . "\n"
            . "Tanggal: {$reservation->tanggal->format('d M Y')}\n"
            . "Jam: " . substr($reservation->jam, 0, 5) . "\n"
            . "Jumlah orang: {$reservation->jumlah_orang}\n"
            . "Pesanan:\n{$daftarMenu}\n"
            . "Total: Rp " . number_format($reservation->total_harga, 0, ',', '.') . "\n"
            . "Metode bayar: " . strtoupper($reservation->metode_bayar) . "\n"
            . ($reservation->catatan ? "Catatan: {$reservation->catatan}\n" : '');

        $waUrl = "https://wa.me/{$nomorCafe}?text=" . urlencode($pesan);

        return redirect($waUrl);
    }
}
