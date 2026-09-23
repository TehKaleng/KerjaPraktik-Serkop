<?php

namespace App\Models;

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

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'meja_id');
    }

    /**
     * Cek apakah meja ini masih kosong di tanggal & jam tertentu.
     * Asumsi 1 sesi reservasi = 2 jam.
     */
    public function tersediaPada(string $tanggal, string $jam): bool
    {
        $jamMulai = \Carbon\Carbon::parse("$tanggal $jam")->subHours(2);
        $jamSelesai = \Carbon\Carbon::parse("$tanggal $jam")->addHours(2);

        return !$this->reservations()
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('jam', [$jamMulai->format('H:i'), $jamSelesai->format('H:i')])
            ->exists();
    }
}
