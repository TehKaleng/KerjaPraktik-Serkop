<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use App\Models\Meja;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /** Pilihan filter tanggal di halaman reservasi admin. */
    public const FILTER = [
        'hari-ini'    => 'Hari Ini',
        'mendatang'   => 'Akan Datang',
        'sebelumnya'  => 'Sebelumnya',
        'semua'       => 'Semua',
    ];

    /** Pilihan urutan daftar reservasi. */
    public const URUTAN = [
        'jam'       => 'Jam',
        'nama'      => 'Nama A–Z',
        'nama-desc' => 'Nama Z–A',
        'terbaru'   => 'Baru masuk',
    ];

    public function index(Request $request)
    {
        $hariIni = now()->toDateString();
        $tglValid = fn ($v) => $v && preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ? $v : null;

        // ---------- Baca filter dari URL ----------
        $cari   = trim((string) $request->query('q', ''));
        $dari   = $tglValid($request->query('dari'));
        $sampai = $tglValid($request->query('sampai'));
        if ($dari && $sampai && $dari > $sampai) {
            [$dari, $sampai] = [$sampai, $dari]; // tertukar, dibalik otomatis
        }
        $status = in_array($request->query('status'), ['pending', 'confirmed'], true) ? $request->query('status') : null;
        $urut   = array_key_exists($request->query('urut'), self::URUTAN) ? $request->query('urut') : 'jam';

        // Tab: default "Hari Ini". Kalau sedang mencari nama tanpa memilih tab, cari di semua tanggal.
        $filter = $request->query('filter');
        if (! array_key_exists($filter, self::FILTER)) {
            $filter = $cari !== '' ? 'semua' : 'hari-ini';
        }
        if ($dari || $sampai) {
            $filter = null; // rentang tanggal menggantikan tab
        }

        // ---------- Susun query ----------
        $query = Reservation::with(['meja', 'items.menu']);

        if ($dari || $sampai) {
            if ($dari)   $query->whereDate('tanggal', '>=', $dari);
            if ($sampai) $query->whereDate('tanggal', '<=', $sampai);
        } elseif ($filter === 'hari-ini') {
            $query->whereDate('tanggal', $hariIni);
        } elseif ($filter === 'mendatang') {
            $query->whereDate('tanggal', '>', $hariIni);
        } elseif ($filter === 'sebelumnya') {
            $query->whereDate('tanggal', '<', $hariIni);
        }

        // Pencarian: nama, nomor WhatsApp, kode meja, atau nomor reservasi (#12)
        if ($cari !== '') {
            $kata = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $cari) . '%';
            $angka = preg_replace('/\D+/', '', $cari);
            $query->where(function ($q) use ($kata, $cari, $angka) {
                $q->where('nama', 'like', $kata)
                  ->orWhereHas('meja', fn ($m) => $m->where('kode', 'like', $kata));
                if (strlen($angka) >= 3) {
                    $q->orWhere('whatsapp', 'like', '%' . $angka . '%');
                }
                if (preg_match('/^#?(\d+)$/', $cari, $m)) {
                    $q->orWhere('id', (int) $m[1]);
                }
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Urutan tanggal: yang lampau dari terbaru, selain itu dari terdekat
        $tanggalTurun = $filter === 'sebelumnya' || $filter === 'semua' || (($dari || $sampai) && ! $dari);
        $tanggalTurun ? $query->orderByDesc('tanggal') : $query->orderBy('tanggal');

        match ($urut) {
            'nama'      => $query->orderBy('nama'),
            'nama-desc' => $query->orderByDesc('nama'),
            'terbaru'   => $query->orderByDesc('created_at'),
            default     => $query->orderBy('jam'),
        };

        $semua = $query->get();

        // Jumlah per tab (tanpa pencarian), ditampilkan sebagai angka kecil di samping nama tab
        $jumlah = [
            'hari-ini'   => Reservation::whereDate('tanggal', $hariIni)->count(),
            'mendatang'  => Reservation::whereDate('tanggal', '>', $hariIni)->count(),
            'sebelumnya' => Reservation::whereDate('tanggal', '<', $hariIni)->count(),
            'semua'      => Reservation::count(),
        ];
        $menungguHariIni = Reservation::whereDate('tanggal', $hariIni)->where('status', 'pending')->count();
        $adaFilterLanjut = $cari !== '' || $dari || $sampai || $status || $urut !== 'jam';

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
                'tanggal_key'  => $r->tanggal->format('Y-m-d'),
                'hari'         => $r->tanggal->locale('id')->translatedFormat('l, d F Y'),
                'jam'          => substr($r->jam, 0, 5),
                'orang'        => $r->jumlah_orang,
                'menu'         => $r->items->map(fn ($i) => ($i->menu->nama ?? 'Menu dihapus') . " x{$i->qty}")->implode(', '),
                'total'        => $r->total_harga,
                'catatan'      => $r->catatan ?: '-',
                'status'       => $r->status,
                'bentrok'      => $bentrok ? $bentrok->nama . ' pukul ' . substr($bentrok->jam, 0, 5) : null,
                'antrean'      => $antrean,
            ];
        });

        // Dikelompokkan per tanggal supaya reservasi beda hari tidak tercampur
        $perTanggal = $reservasis->groupBy('tanggal_key');

        return view('admin.reservasi.index', compact('perTanggal', 'reservasis', 'filter', 'jumlah', 'hariIni', 'menungguHariIni', 'cari', 'dari', 'sampai', 'status', 'urut', 'adaFilterLanjut'));
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

    /** Reservasi terakhir boleh dimulai sekian menit sebelum kafe tutup (sama dengan aturan pelanggan). */
    public const MENIT_SEBELUM_TUTUP = 60;

    /** Hari Sabtu & Minggu memakai jam operasional akhir pekan. */
    private const HARI_WEEKEND = [Carbon::SATURDAY, Carbon::SUNDAY];

    /**
     * Halaman ubah reservasi (khusus reservasi yang sudah dikonfirmasi).
     */
    public function edit(Request $request, Reservation $reservasi)
    {
        if ($reservasi->status !== 'confirmed') {
            return redirect()->route('admin.reservasi.index')
                ->with('error', 'Hanya reservasi yang sudah dikonfirmasi yang dapat diubah.');
        }

        $reservasi->load(['meja', 'items.menu']);
        $meja    = Meja::orderBy('lantai')->orderBy('nomor_meja')->get();
        $kembali = $this->urlKembali($request);

        return view('admin.reservasi.edit', compact('reservasi', 'meja', 'kembali'));
    }

    /**
     * Simpan perubahan reservasi terkonfirmasi.
     * Dicek ulang: jam operasional, kapasitas meja, dan bentrok dengan reservasi terkonfirmasi lain.
     */
    public function update(Request $request, Reservation $reservasi)
    {
        if ($reservasi->status !== 'confirmed') {
            return redirect()->route('admin.reservasi.index')
                ->with('error', 'Hanya reservasi yang sudah dikonfirmasi yang dapat diubah.');
        }

        // Nomor WhatsApp dirapikan dulu (spasi, strip, +62 -> 08...)
        $wa = preg_replace('/\D+/', '', (string) $request->input('whatsapp'));
        if (str_starts_with($wa, '62')) {
            $wa = '0' . substr($wa, 2);
        }
        $request->merge(['whatsapp' => $wa]);

        $data = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'whatsapp'     => ['required', 'regex:/^08\d{8,12}$/'],
            'tanggal'      => ['required', 'date_format:Y-m-d'],
            'jam'          => ['required', 'date_format:H:i'],
            'jumlah_orang' => ['required', 'integer', 'min:1', 'max:50'],
            'meja_id'      => ['required', 'exists:meja,id'],
            'catatan'      => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required'         => 'Nama wajib diisi.',
            'whatsapp.required'     => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex'        => 'Nomor WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx.',
            'tanggal.required'      => 'Tanggal wajib diisi.',
            'jam.required'          => 'Jam wajib diisi.',
            'jam.date_format'       => 'Format jam tidak valid.',
            'jumlah_orang.required' => 'Jumlah orang wajib diisi.',
            'jumlah_orang.min'      => 'Jumlah orang minimal 1.',
            'meja_id.required'      => 'Pilih meja.',
            'meja_id.exists'        => 'Meja tidak ditemukan.',
        ]);

        $waktuBaru  = Carbon::parse($data['tanggal'] . ' ' . $data['jam']);
        $jadwalUbah = $data['tanggal'] !== $reservasi->tanggal->format('Y-m-d')
            || $data['jam'] !== substr($reservasi->jam, 0, 5)
            || (int) $data['meja_id'] !== (int) $reservasi->meja_id;

        // Jadwal hanya boleh dipindah ke waktu yang belum lewat
        if ($jadwalUbah && $waktuBaru->isPast()) {
            return back()->withInput()->withErrors(['jam' => 'Tanggal dan jam yang dipilih sudah lewat.']);
        }

        // Jam operasional sesuai hari (diambil dari Info Kontak)
        if ($pesan = $this->cekJamOperasional($waktuBaru)) {
            return back()->withInput()->withErrors(['jam' => $pesan]);
        }

        $hasil = DB::transaction(function () use ($reservasi, $data) {
            $meja = Meja::whereKey($data['meja_id'])->lockForUpdate()->firstOrFail();

            if ($meja->kapasitas < (int) $data['jumlah_orang']) {
                return ['jumlah_orang', "Meja {$meja->kode} hanya muat {$meja->kapasitas} orang."];
            }

            $bentrok = $meja->reservasiBentrok($data['tanggal'], $data['jam'], ['confirmed'], $reservasi->id)->first();
            if ($bentrok) {
                return ['meja_id', "Meja {$meja->kode} sudah terkonfirmasi untuk {$bentrok->nama} pukul "
                    . substr($bentrok->jam, 0, 5) . ' pada tanggal tersebut. Pilih meja atau jam lain.'];
            }

            $reservasi->update($data);

            return null;
        });

        if ($hasil) {
            return back()->withInput()->withErrors([$hasil[0] => $hasil[1]]);
        }

        return redirect($this->urlKembali($request))
            ->with('success', "Reservasi {$reservasi->nama} berhasil diubah.");
    }

    public function destroy(Reservation $reservasi)
    {
        // Reservasi yang sudah dikonfirmasi tidak boleh dihapus, hanya diubah
        if ($reservasi->status === 'confirmed') {
            return back()->with('error', 'Reservasi yang sudah dikonfirmasi tidak dapat dihapus. Gunakan tombol Ubah.');
        }

        // Item menu ikut terhapus otomatis (cascadeOnDelete di tabel reservation_items)
        $reservasi->delete();

        return back()->with('success', 'Reservasi berhasil dihapus.');
    }

    /** Pesan kesalahan bila jam di luar jam operasional, atau null bila sesuai. */
    private function cekJamOperasional(Carbon $waktu): ?string
    {
        $kontak  = ContactInfo::current();
        $weekend = in_array($waktu->dayOfWeek, self::HARI_WEEKEND, true);

        $buka  = substr((string) ($weekend ? ($kontak->jam_buka_weekend ?? $kontak->jam_buka) : $kontak->jam_buka), 0, 5) ?: '08:00';
        $tutup = substr((string) ($weekend ? ($kontak->jam_tutup_weekend ?? $kontak->jam_tutup) : $kontak->jam_tutup), 0, 5) ?: '23:00';
        $batas = Carbon::createFromFormat('H:i', $tutup)->subMinutes(self::MENIT_SEBELUM_TUTUP)->format('H:i');
        $jam   = $waktu->format('H:i');

        if ($jam < $buka || $jam > $batas) {
            $hari = $weekend ? 'Sabtu–Minggu' : 'Senin–Jumat';

            return "Jam reservasi hari {$hari} adalah " . str_replace(':', '.', $buka) . ' – ' . str_replace(':', '.', $batas) . '.';
        }

        return null;
    }

    /** Kembali ke daftar reservasi dengan filter yang sama seperti sebelum membuka halaman ubah. */
    private function urlKembali(Request $request): string
    {
        $url  = (string) $request->input('kembali', '');
        $daftar = route('admin.reservasi.index');

        return str_starts_with($url, $daftar) ? $url : $daftar;
    }
}
