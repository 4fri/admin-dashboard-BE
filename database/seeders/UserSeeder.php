<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {

        // Buat pengguna admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'fullname' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'avatar_path' => null
            ]
        );

        // Buat pengguna biasa
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'fullname' => 'User',
                'username' => 'user',
                'password' => Hash::make('password'),
                'avatar_path' => null
            ]
        );

        // Assign role ke user
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }
    }
}
