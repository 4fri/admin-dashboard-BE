<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Buat peran
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Buat izin
        // $editArticles = Permission::firstOrCreate(['name' => 'edit articles']);
        // $viewArticles = Permission::firstOrCreate(['name' => 'view articles']);

        // // Berikan izin ke peran
        // $adminRole->givePermissionTo([$editArticles, $viewArticles]);
        // $userRole->givePermissionTo($viewArticles);
    }
}
