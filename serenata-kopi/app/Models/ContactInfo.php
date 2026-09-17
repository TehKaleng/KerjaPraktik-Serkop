<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'alamat',
        'jam_buka',
        'jam_tutup',
        'whatsapp',
        'email',
        'maps_embed',
        'maps_link',
    ];

    /**
     * Karena tabel ini cuma 1 baris, helper ini ambil (atau bikin default)
     * baris itu tanpa perlu tau ID-nya.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'alamat'   => 'Jl. Melati No. 12, Palembang, Sumatera Selatan',
            'whatsapp' => '628123456789',
            'email'    => 'hello@serenatakopi.id',
        ]);
    }
}
