<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Route as RouteModel;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            // Users Routes
            ['name' => 'users.index', 'method' => 'GET', 'prefix' => 'users', 'url' => '/', 'controller' => 'Admin\UserController', 'function' => 'index'],
            ['name' => 'users.store', 'method' => 'POST', 'prefix' => 'users', 'url' => '/store', 'controller' => 'Admin\UserController', 'function' => 'store'],
            ['name' => 'users.show', 'method' => 'GET', 'prefix' => 'users', 'url' => '/{id}/show', 'controller' => 'Admin\UserController', 'function' => 'show'],
            ['name' => 'users.update', 'method' => 'PUT', 'prefix' => 'users', 'url' => '/{id}/update', 'controller' => 'Admin\UserController', 'function' => 'update'],
            ['name' => 'users.destroy', 'method' => 'DELETE', 'prefix' => 'users', 'url' => '/{id}/destroy', 'controller' => 'Admin\UserController', 'function' => 'destroy'],

            // Roles Routes
            ['name' => 'roles.index', 'method' => 'GET', 'prefix' => 'roles', 'url' => '/', 'controller' => 'Admin\RoleController', 'function' => 'index'],
            ['name' => 'roles.store', 'method' => 'POST', 'prefix' => 'roles', 'url' => '/store', 'controller' => 'Admin\RoleController', 'function' => 'store'],
            ['name' => 'roles.show', 'method' => 'GET', 'prefix' => 'roles', 'url' => '/{id}/show', 'controller' => 'Admin\RoleController', 'function' => 'show'],
            ['name' => 'roles.update', 'method' => 'PUT', 'prefix' => 'roles', 'url' => '/{id}/update', 'controller' => 'Admin\RoleController', 'function' => 'update'],
            ['name' => 'roles.destroy', 'method' => 'DELETE', 'prefix' => 'roles', 'url' => '/{id}/destroy', 'controller' => 'Admin\RoleController', 'function' => 'destroy'],

            // Permissions Routes
            ['name' => 'permissions.index', 'method' => 'GET', 'prefix' => 'permissions', 'url' => '/', 'controller' => 'Admin\PermissionController', 'function' => 'index'],
            ['name' => 'permissions.store', 'method' => 'POST', 'prefix' => 'permissions', 'url' => '/store', 'controller' => 'Admin\PermissionController', 'function' => 'store'],
            ['name' => 'permissions.show', 'method' => 'GET', 'prefix' => 'permissions', 'url' => '/{id}/show', 'controller' => 'Admin\PermissionController', 'function' => 'show'],
            ['name' => 'permissions.update', 'method' => 'PUT', 'prefix' => 'permissions', 'url' => '/{id}/update', 'controller' => 'Admin\PermissionController', 'function' => 'update'],
            ['name' => 'permissions.destroy', 'method' => 'DELETE', 'prefix' => 'permissions', 'url' => '/{id}/destroy', 'controller' => 'Admin\PermissionController', 'function' => 'destroy'],
        ];

        foreach ($routes as $route) {
            RouteModel::create([
                'name' => $route['name'],
                'method' => $route['method'],
                'prefix' => $route['prefix'],
                'url' => $route['url'],
                'controller' => $route['controller'],
                'function' => $route['function'],
            ]);
        }
    }
}
