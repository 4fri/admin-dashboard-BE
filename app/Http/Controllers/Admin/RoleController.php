<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                        ];
                    }),
                ];
            });

        $permissions = Permission::get()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
            ];
        });

        return response()->json([
            'success' => true,
            'result' => [
                'roles' => $roles,
                'permissions' => $permissions
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            $role = Role::create([
                'name' => $request->name,
                // 'guard_name' => 'web',
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'result' => $role->name
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role creation failed'
            ], 500);
        }
    }

    public function show($id)
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'result' => $role
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            $role->update(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'result' => $role->name
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role update failed'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        try {
            $role->permissions()->detach();
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role delete failed'
            ], 500);
        }
    }
}
