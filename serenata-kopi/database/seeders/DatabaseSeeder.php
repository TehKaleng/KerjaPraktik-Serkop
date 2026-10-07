<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // 2. Buat akun admin pertama.
        //    Password dibaca dari .env (ADMIN_PASSWORD), TIDAK ditulis di kode.
        //    Kalau kosong, dibuat password acak dan ditampilkan sekali di terminal.
        $passwordAdmin = config('serenata.admin_password');
        $passwordAcak  = false;

        if (blank($passwordAdmin)) {
            $passwordAdmin = Str::random(16);
            $passwordAcak  = true;
        }

        $admin = User::firstOrCreate(
            ['email' => config('serenata.admin_email', 'admin@serenatakopi.id')],
            [
                'name'     => 'Admin Serenata',
                'password' => Hash::make($passwordAdmin),
            ]
        );
        $admin->assignRole($adminRole);

        // Akun admin dibuat oleh sistem, jadi emailnya langsung dianggap terverifikasi
        if (! $admin->hasVerifiedEmail()) {
            $admin->markEmailAsVerified();
        }

        if ($admin->wasRecentlyCreated && $passwordAcak && $this->command) {
            $this->command->warn("Akun admin dibuat: {$admin->email}");
            $this->command->warn("Password acak (catat sekarang): {$passwordAdmin}");
            $this->command->warn('Ganti lewat Edit Profil, atau isi ADMIN_PASSWORD di file .env.');
        }

        // 3. Akun pelanggan contoh: HANYA dibuat di lingkungan lokal (APP_ENV=local),
        //    supaya tidak ada akun dengan password bawaan di server sungguhan.
        if (app()->environment('local')) {
            $user = User::firstOrCreate(
                ['email' => 'user@serenatakopi.id'],
                [
                    'name'     => 'User Contoh',
                    'password' => Hash::make('password123'),
                ]
            );
            $user->assignRole($userRole);

            if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
        }

        // 4. Isi data contoh: menu, testimoni, kontak
        $this->call(MenuSeeder::class);
        $this->call(MejaSeeder::class);
    }
}

