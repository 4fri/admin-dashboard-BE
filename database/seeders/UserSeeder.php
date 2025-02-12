<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

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

        $faker = Faker::create();

        // Buat 50 pengguna random
        for ($i = 1; $i <= 50; $i++) {
            $user = User::firstOrCreate(
                ['email' => $faker->unique()->safeEmail],
                [
                    'fullname' => $faker->name,
                    'username' => $faker->userName,
                    'password' => Hash::make('password'),
                    'avatar_path' => null
                ]
            );

            // 5 User pertama menjadi admin, sisanya user
            if ($i <= 5) {
                if (!$user->hasRole('admin')) {
                    $user->assignRole('admin');
                }
            } else {
                if (!$user->hasRole('user')) {
                    $user->assignRole('user');
                }
            }
        }
    }
}
