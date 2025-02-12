<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Route as RouteModel;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    // Get all routes with pagination (10 per page)
    public function index()
    {
        $routes = RouteModel::paginate(10)->through(function ($route) {
            return [
                "id" => $route->id,
                "name" => $route->name,
                "url" => $route->url,
                "method" => $route->method,
                "prefix" => $route->prefix,
            ];
        });

        return response()->json([
            'success' => true,
            'result' => $routes
        ], 200);
    }

    // Store new route
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required|string|unique:routes,name',
            'method' => 'required|string',
            'prefix' => 'nullable|string',
            'url' => 'required|string',
            'controller' => 'required|string',
            'function' => 'required|string',
        ]);

        $parsData = [
            'name' => $validated['name'],
            'method' => strtoupper($validated['method']),
            'prefix' => $validated['prefix'],
            'url' => $validated['url'],
            'controller' => $validated['controller'],
            'function' => $validated['function'],
        ];

        try {
            $route = RouteModel::create($parsData);

            $permission = Permission::create([
                'name' => $route->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Route created successfully',
                // 'result' => $routes
            ], status: 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route creation failed',
            ], status: 500);
        }
    }

    // Get single route by ID
    public function show($id)
    {
        $route = RouteModel::find($id);

        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'Route not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'result' => $route
        ], 200);
    }

    // Update route
    public function update(Request $request, $id)
    {
        $route = RouteModel::find($id);

        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'Route not found'
            ], 404);
        }

        $validated = $this->validate($request, [
            'name' => 'required|string|unique:routes,name,' . $id,
            'method' => 'required|string',
            'prefix' => 'nullable|string',
            'url' => 'required|string',
            'controller' => 'required|string',
            'function' => 'required|string',
        ]);

        $parsData = [
            'name' => $validated['name'],
            'method' => strtoupper($validated['method']),
            'prefix' => $validated['prefix'],
            'url' => $validated['url'],
            'controller' => $validated['controller'],
            'function' => $validated['function'],
        ];

        try {
            $route->update($parsData);

            $permission = Permission::where('name', $validated['name'])->first();
            $permission->update([
                'name' => $route->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Route updated successfully',
                'result' => $route
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route update failed',
            ], 500);
        }
    }

    // Delete route
    public function destroy($id)
    {
        $route = RouteModel::find($id);
        $permission = Permission::where('name', $route->name)->first();

        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'Route not found'
            ], 404);
        }

        try {
            $permission->roles()->detach();
            $permission->delete();
            $route->delete();

            return response()->json([
                'success' => true,
                'message' => 'Route deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route delete failed',
            ], 500);
        }
    }
}
