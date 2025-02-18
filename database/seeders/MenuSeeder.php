<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Route;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuLabel = Menu::create([
            "name" => "User Management",
            // "icon"=> "fa fa-user",
            // "route_id"=>"users.index",
            // "parent_id"=>0,
            "sort_order" => 1
        ]);

        $routes = Route::whereIn('prefix', ['users', 'roles', 'permissions', 'routes'])
            ->where('name', 'ILIKE', '%index%')
            ->get();

        $order = 1;
        foreach ($routes as $route) {
            $name = ucfirst(explode('.', $route->name)[0]);

            Menu::create([
                "name" => $name,
                "route_id" => $route->id,
                "parent_id" => $menuLabel->id,
                "sort_order" => $order++,
            ]);
        }
    }
}
