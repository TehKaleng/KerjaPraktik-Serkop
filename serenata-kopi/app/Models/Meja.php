<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';

    protected $fillable = [
        'lantai',
        'nomor_meja',
        'kode',
        'kapasitas',
    ];

    /** Lama 1 sesi reservasi (menit). */
    public const DURASI_SESI_MENIT = 120;

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'meja_id');
    }

    /**
     * Reservasi lain di meja ini yang waktunya bentrok (selisih < durasi sesi) dengan tanggal & jam tertentu.
     * Dibandingkan sebagai datetime penuh, jadi aman untuk jam mendekati tengah malam.
     *
     * @param  array<int, string>  $status    status reservasi yang diperhitungkan
     * @param  int|null            $kecualiId reservasi yang tidak dihitung (mis. reservasi yang sedang diperiksa)
     */
    public function reservasiBentrok(string $tanggal, string $jam, array $status, ?int $kecualiId = null)
    {
        $target = Carbon::parse($tanggal . ' ' . substr($jam, 0, 5));

        return $this->reservations()
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', $status)
            ->when($kecualiId, fn ($q) => $q->where('id', '!=', $kecualiId))
            ->get()
            ->filter(function ($r) use ($target) {
                $mulai = Carbon::parse($r->tanggal->format('Y-m-d') . ' ' . substr($r->jam, 0, 5));

                return abs($target->diffInMinutes($mulai)) < self::DURASI_SESI_MENIT;
            })
            ->values();
    }

    /**
     * Meja dianggap terpakai HANYA oleh reservasi yang sudah dikonfirmasi admin.
     * Reservasi yang masih "pending" tidak mengunci meja, jadi beberapa pelanggan
     * bisa mengajukan meja dan jam yang sama; admin memilih mana yang dikonfirmasi.
     */
    public function tersediaPada(string $tanggal, string $jam, ?int $kecualiId = null): bool
    {
        return $this->reservasiBentrok($tanggal, $jam, ['confirmed'], $kecualiId)->isEmpty();
    }
}
