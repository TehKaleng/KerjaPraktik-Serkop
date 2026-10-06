<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    /** Hari yang memakai jam weekend (Sabtu & Minggu). */
    public const HARI_WEEKEND = [Carbon::SATURDAY, Carbon::SUNDAY];

    protected $fillable = [
        'alamat',
        'jam_buka',           // Senin - Jumat
        'jam_tutup',          // Senin - Jumat
        'jam_buka_weekend',   // Sabtu - Minggu
        'jam_tutup_weekend',  // Sabtu - Minggu
        'whatsapp',
        'email',
        'maps_embed',
        'maps_link',
        'qris_image',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'alamat'            => 'Jl. Melati No. 12, Palembang, Sumatera Selatan',
            'jam_buka'          => '08:00',
            'jam_tutup'         => '23:00',
            'jam_buka_weekend'  => '08:00',
            'jam_tutup_weekend' => '23:30',
            'whatsapp'          => '628123456789',
            'email'             => 'hello@serenatakopi.id',
        ]);
    }

    /** True kalau tanggal tersebut jatuh di hari Sabtu atau Minggu. */
    public static function adalahWeekend($tanggal): bool
    {
        return in_array(Carbon::parse($tanggal)->dayOfWeek, self::HARI_WEEKEND, true);
    }

    /**
     * Jam buka & tutup yang berlaku pada tanggal tertentu.
     *
     * @return array{buka: string, tutup: string}
     */
    public function jamPada($tanggal): array
    {
        return self::adalahWeekend($tanggal)
            ? ['buka' => $this->jam_buka_weekend, 'tutup' => $this->jam_tutup_weekend]
            : ['buka' => $this->jam_buka, 'tutup' => $this->jam_tutup];
    }

    /** "08:00" -> "08.00" (format jam yang lazim di Indonesia). */
    public static function formatJam(?string $jam): string
    {
        return str_replace(':', '.', substr((string) $jam, 0, 5));
    }

    /** Contoh: "08.00 - 23.00" */
    public function teksJamWeekday(): string
    {
        return self::formatJam($this->jam_buka) . ' - ' . self::formatJam($this->jam_tutup);
    }

    /** Contoh: "08.00 - 23.30" */
    public function teksJamWeekend(): string
    {
        return self::formatJam($this->jam_buka_weekend) . ' - ' . self::formatJam($this->jam_tutup_weekend);
    }
}