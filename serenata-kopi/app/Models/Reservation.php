<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'whatsapp',
        'tanggal',
        'jam',
        'jumlah_orang',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
