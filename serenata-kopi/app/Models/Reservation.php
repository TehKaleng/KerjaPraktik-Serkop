<?php

namespace App\Models;

use Carbon\Carbon;
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
        'tanggal'              => 'date',
        'pengingat_dikirim_at' => 'datetime',
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

    /** Tanggal + jam reservasi dalam satu objek waktu (zona waktu aplikasi, WIB). */
    public function waktuMulai(): Carbon
    {
        return Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . substr($this->jam, 0, 5));
    }
}
