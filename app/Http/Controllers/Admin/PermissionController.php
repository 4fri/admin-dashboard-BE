<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::get();

        return response()->json([
            'success' => true,
            'result' => $permissions
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        try {
            $permission = Permission::create([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully',
                'result' => $permission
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Permission create failed'
            ], 500);
        }
    }

    public function show($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'result' => $permission
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        try {
            $permission->update([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully',
                'result' => $permission
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Permission update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        try {
            $permission->roles()->detach();
            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Permission delete failed'
            ], 500);
        }
    }
}
