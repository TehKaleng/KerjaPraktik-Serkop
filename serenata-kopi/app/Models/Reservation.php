<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Reservation extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'meja_id',
        'nama',
        'whatsapp',
        'tanggal',
        'jam',
        'jumlah_orang',
        'catatan',
        'status',
        'metode_bayar',
        'status_bayar',
        'total_harga',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id');
    }

    public function items()
    {
        return $this->hasMany(ReservationItem::class);
    }
}
