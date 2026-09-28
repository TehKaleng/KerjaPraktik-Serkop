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
     * Cek apakah meja ini masih kosong di tanggal & jam tertentu.
     * Bentrok kalau selisih waktu dengan reservasi lain < durasi sesi (2 jam).
     * Dibandingkan sebagai datetime penuh, jadi aman untuk jam mendekati tengah malam.
     */
    public function tersediaPada(string $tanggal, string $jam): bool
    {
        $target = Carbon::parse("$tanggal $jam");

        $bentrok = $this->reservations()
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->contains(function ($r) use ($target) {
                $mulai = Carbon::parse($r->tanggal->format('Y-m-d') . ' ' . $r->jam);

                return abs($target->diffInMinutes($mulai)) < self::DURASI_SESI_MENIT;
            });

        return !$bentrok;
    }
}