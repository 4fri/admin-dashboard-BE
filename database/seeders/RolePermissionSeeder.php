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

        $permissions = [
            'users.index',
            'users.store',
            'users.show',
            'users.update',
            'users.destroy',
            'roles.index',
            'roles.store',
            'roles.show',
            'roles.update',
            'roles.destroy',
            'permissions.index',
            'permissions.store',
            'permissions.show',
            'permissions.update',
            'permissions.destroy',
            'routes.index',
            'routes.store',
            'routes.show',
            'routes.update',
            'routes.destroy',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $adminRole->givePermissionTo($permissions);
        $userRole->givePermissionTo(['users.index']);
    }
}
