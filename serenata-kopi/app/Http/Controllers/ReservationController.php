<?php

namespace App\Http\Controllers;

use App\Models\ContactInfo;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservation;
use App\Notifications\ReservasiBerhasil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ReservationController extends Controller
{
    /**
     * Saklar pembayaran QRIS lewat website.
     *   false = semua reservasi dibayar di kasir. QRIS dari mesin EDC dibuat kasir
     *           saat pelanggan datang (karena QRIS EDC bersifat dinamis, bukan gambar tetap).
     *   true  = opsi QRIS di website aktif lagi. Ubah ke true HANYA kalau cafe sudah punya
     *           QRIS statis dan fotonya sudah diupload di /admin/kontak.
     */
    private const QRIS_AKTIF = false;

    /**
     * Reservasi terakhir yang diterima: sekian menit sebelum kafe tutup.
     * Contoh: tutup 23.00 dan batas 60 menit -> jam reservasi paling malam 22.00.
     */
    private const MENIT_SEBELUM_TUTUP = 60;

    public function create()
    {
        // Kelompok menu diurutkan: kopi, non-kopi, kudapan, makanan, tambahan (di dalam kelompok: abjad)
        $urutan = ['kopi', 'non-kopi', 'kudapan', 'makanan', 'tambahan'];
        $menusByKategori = Menu::orderBy('nama')->get()->groupBy('kategori')
            ->sortBy(fn ($items, $kategori) => ($i = array_search($kategori, $urutan)) === false ? 99 : $i);

        $qrisAktif = self::QRIS_AKTIF;

        // Dipakai JavaScript di form untuk membatasi pilihan jam sesuai hari yang dipilih
        $kontak = ContactInfo::current();
        $jamOperasional = [
            'weekday' => $this->rentangReservasi($kontak->jam_buka, $kontak->jam_tutup),
            'weekend' => $this->rentangReservasi($kontak->jam_buka_weekend, $kontak->jam_tutup_weekend),
        ];

        return view('reservasi', compact('menusByKategori', 'qrisAktif', 'jamOperasional'));
    }

    /**
     * Tombol "Masuk" / "Daftar" di halaman reservasi: simpan tujuan semula, supaya setelah
     * login atau daftar pelanggan kembali ke form reservasi (bukan ke dashboard).
     */
    public function masuk()
    {
        session()->put('url.intended', route('reservasi.form'));

        return redirect()->route('login');
    }

    public function daftar()
    {
        session()->put('url.intended', route('reservasi.form'));

        return redirect()->route('register');
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
        // Kolom jebakan untuk bot: manusia tidak melihatnya, bot biasanya mengisinya.
        // Kalau terisi, permintaan dibuang diam-diam (tanpa memberi tahu bot).
        if ($request->filled('referensi_internal')) {
            return redirect()->route('home');
        }

        // Nomor WhatsApp dirapikan dulu (spasi, strip, +62 -> 08...) sebelum diperiksa
        $request->merge(['whatsapp' => $this->normalisasiWhatsapp($request->input('whatsapp'))]);

        $validated = $request->validate([
            'nama'         => 'required|string|max:255',
            'whatsapp'     => ['required', 'string', 'regex:/^08\d{8,12}$/'],
            'tanggal'      => 'required|date|after_or_equal:today',
            'jam'          => 'required|date_format:H:i',
            'jumlah_orang' => 'required|integer|min:1',
            'meja_id'      => 'required|exists:meja,id',
            'catatan'      => 'nullable|string',
            'metode_bayar' => 'nullable|in:qris,cash',
            'menu_id'      => 'required|array|min:1',
            'menu_id.*'    => 'exists:menus,id',
            'qty'          => 'required|array',
            'qty.*'        => 'integer|min:1',
        ], [
            'whatsapp.regex'  => 'Nomor WhatsApp tidak valid. Gunakan nomor HP Indonesia, contoh: 08123456789.',
            'jam.date_format' => 'Format jam tidak valid. Pilih jam lewat kolom jam, contoh 19:00.',
        ]);

        // Jam reservasi harus di dalam jam operasional hari itu (weekday / weekend beda jam tutup)
        $pesanJam = $this->cekJamReservasi($validated['tanggal'], $validated['jam']);
        if ($pesanJam !== null) {
            return back()->withErrors(['jam' => $pesanJam])->withInput();
        }

        // Batas reservasi yang masih menunggu konfirmasi kafe. Tamu dihitung per nomor WhatsApp,
        // pemesan yang login dihitung per akun dan diberi batas lebih longgar.
        $maksimal = auth()->check() ? 5 : 2;
        $menunggu = Reservation::where('status', 'pending')
            ->whereDate('tanggal', '>=', today())
            ->when(
                auth()->check(),
                fn ($q) => $q->where('user_id', auth()->id()),
                fn ($q) => $q->where('whatsapp', $validated['whatsapp'])
            )
            ->count();

        if ($menunggu >= $maksimal) {
            $subjek = auth()->check() ? 'Akunmu' : 'Nomor ini';

            return back()
                ->withErrors(['whatsapp' => "{$subjek} masih punya {$menunggu} reservasi yang belum dikonfirmasi kafe. Tunggu konfirmasi dulu, atau hubungi kami lewat WhatsApp."])
                ->withInput();
        }

        // Selama QRIS website nonaktif, semua reservasi otomatis "bayar di kasir" (disimpan sebagai 'cash').
        $metodeBayar = (self::QRIS_AKTIF && ($validated['metode_bayar'] ?? null) === 'qris') ? 'qris' : 'cash';

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

        $reservation = DB::transaction(function () use ($validated, $total, $itemsData, $metodeBayar) {
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
                'metode_bayar' => $metodeBayar,
                'status_bayar' => $metodeBayar === 'qris' ? 'menunggu_konfirmasi' : 'belum_bayar',
                'total_harga'  => $total,
            ]);

            foreach ($itemsData as $item) {
                $reservation->items()->create($item);
            }

            return $reservation;
        });

        // Notifikasi di dashboard hanya ada untuk pemesan yang login. Pemesan tanpa akun
        // mendapat konfirmasi lewat WhatsApp.
        auth()->user()?->notify(new ReservasiBerhasil($reservation));

        if ($metodeBayar === 'qris') {
            // Halaman konfirmasi dijaga tautan bertanda yang berlaku 3 jam
            return redirect()->to(URL::temporarySignedRoute(
                'reservasi.konfirmasi',
                now()->addHours(3),
                ['reservation' => $reservation->id]
            ));
        }

        return $this->redirectWhatsapp($reservation);
    }

    // Akses ke konfirmasi() dan selesai() dijaga middleware "signed" (tautan bertanda) di routes/web.php,
    // bukan lewat akun, supaya pemesan tanpa login tetap bisa memakainya dan orang lain tidak bisa menebak.
    public function konfirmasi(Reservation $reservation)
    {
        $kontak = ContactInfo::current();

        return view('reservasi-konfirmasi', compact('reservation', 'kontak'));
    }

    public function selesai(Reservation $reservation)
    {
        return $this->redirectWhatsapp($reservation);
    }

    private function redirectWhatsapp(Reservation $reservation)
    {
        $kontak = ContactInfo::current();
        $nomorCafe = preg_replace('/[^0-9]/', '', $kontak->whatsapp);

        $daftarMenu = $reservation->items->map(function ($item) {
            return "- {$item->menu->nama} x{$item->qty} (Rp " . number_format($item->subtotal(), 0, ',', '.') . ")";
        })->implode("\n");

        $labelBayar = $reservation->metode_bayar === 'qris' ? 'QRIS' : 'Bayar di kasir saat datang';

        $pesan = "Halo Serenata Kopi & Space, saya mau konfirmasi reservasi:\n"
            . "Nama: {$reservation->nama}\n"
            . "Meja: " . ($reservation->meja->kode ?? '-') . "\n"
            . "Tanggal: {$reservation->tanggal->format('d M Y')}\n"
            . "Jam: " . substr($reservation->jam, 0, 5) . "\n"
            . "Jumlah orang: {$reservation->jumlah_orang}\n"
            . "Pesanan:\n{$daftarMenu}\n"
            . "Total: Rp " . number_format($reservation->total_harga, 0, ',', '.') . "\n"
            . "Pembayaran: " . $labelBayar . "\n"
            . ($reservation->catatan ? "Catatan: {$reservation->catatan}\n" : '');

        $waUrl = "https://wa.me/{$nomorCafe}?text=" . urlencode($pesan);

        return redirect($waUrl);
    }

    /**
     * Rentang jam yang boleh dipilih untuk reservasi: dari jam buka sampai
     * (jam tutup - MENIT_SEBELUM_TUTUP).
     *
     * @return array{min: string, max: string, buka: string, tutup: string}
     */
    private function rentangReservasi(string $buka, string $tutup): array
    {
        $buka  = substr($buka, 0, 5);
        $tutup = substr($tutup, 0, 5);
        $max   = Carbon::createFromFormat('H:i', $tutup)->subMinutes(self::MENIT_SEBELUM_TUTUP)->format('H:i');

        return ['min' => $buka, 'max' => $max, 'buka' => $buka, 'tutup' => $tutup];
    }

    /**
     * Mengecek jam reservasi terhadap jam operasional hari tersebut.
     * Mengembalikan pesan error, atau null kalau jamnya boleh.
     */
    private function cekJamReservasi(string $tanggal, string $jam): ?string
    {
        $kontak  = ContactInfo::current();
        $jamHari = $kontak->jamPada($tanggal);
        $rentang = $this->rentangReservasi($jamHari['buka'], $jamHari['tutup']);
        $hari    = ContactInfo::adalahWeekend($tanggal) ? 'Sabtu - Minggu' : 'Senin - Jumat';

        if ($jam < $rentang['min'] || $jam > $rentang['max']) {
            return "Jam reservasi untuk {$hari} antara "
                . ContactInfo::formatJam($rentang['min']) . ' dan ' . ContactInfo::formatJam($rentang['max'])
                . ' (kafe buka ' . ContactInfo::formatJam($rentang['buka'])
                . ' - ' . ContactInfo::formatJam($rentang['tutup']) . ').';
        }

        // Reservasi untuk hari ini tidak boleh memilih jam yang sudah lewat
        if (Carbon::parse($tanggal)->isToday() && $jam <= now()->format('H:i')) {
            return 'Jam yang dipilih sudah lewat. Pilih jam yang lebih malam.';
        }

        return null;
    }

    /** Menyeragamkan nomor WhatsApp: hanya angka, dan awalan 62 diubah jadi 0 (62812... -> 0812...). */
    private function normalisasiWhatsapp(?string $nomor): string
    {
        $digit = preg_replace('/\D+/', '', (string) $nomor);

        if (str_starts_with($digit, '62')) {
            $digit = '0' . substr($digit, 2);
        }

        return $digit;
    }
}