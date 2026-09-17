<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // 2. Buat akun admin pertama
        $admin = User::firstOrCreate(
            ['email' => 'admin@serenatakopi.id'],
            [
                'name'     => 'Admin Serenata',
                'password' => Hash::make('password123'), // GANTI setelah login pertama!
            ]
        );
        $admin->assignRole($adminRole);

        // 3. (Opsional) Buat 1 akun user contoh buat testing
        $user = User::firstOrCreate(
            ['email' => 'user@serenatakopi.id'],
            [
                'name'     => 'User Contoh',
                'password' => Hash::make('password123'),
            ]
        );
        $user->assignRole($userRole);
    }
}
