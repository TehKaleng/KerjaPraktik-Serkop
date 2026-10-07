<?php

namespace App\Models;

use App\Notifications\ResetPasswordSerenata;
use App\Notifications\VerifikasiEmailSerenata;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * implements MustVerifyEmail = akun baru wajib verifikasi email sebelum bisa
 * membuka dashboard dan memesan reservasi memakai akun.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Email "lupa password" memakai versi berbahasa Indonesia, bukan email bawaan Laravel.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordSerenata($token));
    }

    /**
     * Email verifikasi akun memakai versi berbahasa Indonesia, bukan email bawaan Laravel.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifikasiEmailSerenata);
    }
}
