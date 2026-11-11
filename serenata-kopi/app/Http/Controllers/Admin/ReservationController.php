<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Reservation;
use Illuminate\Http\Request;
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
        $bayar  = in_array($request->query('bayar'), ['belum', 'lunas'], true) ? $request->query('bayar') : null;
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
        if ($bayar === 'lunas') {
            $query->where('status_bayar', 'lunas');
        } elseif ($bayar === 'belum') {
            $query->where('status_bayar', '!=', 'lunas');
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
        $adaFilterLanjut = $cari !== '' || $dari || $sampai || $status || $bayar || $urut !== 'jam';

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
                'metode_bayar' => $r->metode_bayar,
                'status_bayar' => $r->status_bayar,
                'bentrok'      => $bentrok ? $bentrok->nama . ' pukul ' . substr($bentrok->jam, 0, 5) : null,
                'antrean'      => $antrean,
            ];
        });

        // Dikelompokkan per tanggal supaya reservasi beda hari tidak tercampur
        $perTanggal = $reservasis->groupBy('tanggal_key');

        return view('admin.reservasi.index', compact('perTanggal', 'reservasis', 'filter', 'jumlah', 'hariIni', 'menungguHariIni', 'cari', 'dari', 'sampai', 'status', 'bayar', 'urut', 'adaFilterLanjut'));
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