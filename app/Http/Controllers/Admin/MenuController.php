<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['parent', 'route'])
            ->orderBy('sort_order', 'asc')
            ->paginate(10)
            ->through(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'icon' => $menu->icon,
                    'sort_order' => $menu->sort_order,
                    'route' => $menu->route ? $menu->route->name : null, // Mengambil route.name jika ada
                    'created_at' => $menu->created_at,
                    'updated_at' => $menu->updated_at,
                    'parent' => $menu->parent ? [
                        'id' => $menu->parent->id,
                        'name' => $menu->parent->name,
                        'icon' => $menu->parent->icon,
                        'sort_order' => $menu->parent->sort_order,
                        'route' => $menu->parent->route ? $menu->parent->route->name : null,
                        'created_at' => $menu->created_at,
                        'updated_at' => $menu->updated_at,
                    ] : null, // Menampilkan informasi parent jika ada
                ];
            });

        return response()->json([
            'success' => true,
            'result' => $menus
        ], 200);
    }


    public function getMenuChildren()
    {
        $menus = Menu::with(['children.route'])
            ->whereNull('parent_id')
            ->orderBy('sort_order', 'desc')
            ->get()
            ->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'icon' => $menu->icon,
                    'sort_order' => $menu->sort_order,
                    'route' => $menu->route ? $menu->route->name : null,
                    'children' => $menu->children->sortBy('sort_order')->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'icon' => $child->icon,
                            'sort_order' => $child->sort_order,
                            'route' => $child->route ? $child->route->name : null,
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'result' => $menus
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'route_id' => 'nullable|exists:routes,id',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'nullable|integer'
        ]);

        try {
            $menu = Menu::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Menu created successfully',
                'result' => $menu
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
