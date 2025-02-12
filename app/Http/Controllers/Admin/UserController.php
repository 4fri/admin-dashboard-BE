<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->whereNull('deleted_at')
            ->paginate(10)
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                ];
            });

        return response()->json([
            'success' => true,
            'result' => $users
        ], 200);
    }

    public function show($id)
    {
        $user = User::with('roles')
            ->where('deleted_at', null)
            ->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'result' => $user
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'fullname' => 'required|string|max:255',
            'username' => 'required|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',          // Minimal 8 karakter
                'confirmed',      // Harus cocok dengan password_confirmation
                'regex:/[a-z]/',  // Harus mengandung huruf kecil
                'regex:/[A-Z]/',  // Harus mengandung huruf besar
                'regex:/[0-9]/',  // Harus mengandung angka
                'regex:/[@$!%*?&#]/', // Harus mengandung simbol spesial
            ],
            'roles' => 'required|array|min:1',
        ]);

        try {
            //code...
            $user = User::create([
                'fullname' => $validated['fullname'],
                'username' => $validated['username'], // Ambil username sebagai username default. Sebaiknya diganti dengan username yang aman.
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $defineRole = [];
            foreach ($validated['roles'] as $roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $user->assignRole($role);
                }
                $defineRole[] = $role->name;
            }

            $data = [
                'fullname' => $user->fullname,
                'email' => $user->email,
                'username' => $user->username,
                'password' => $request->password, // Inisialisasi password secara acak. Sebaiknya diganti dengan password yang aman.
                'roles' => $defineRole,
            ];

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'result' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User create failed'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::with('roles')
            ->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validated = $this->validate($request, [
            'fullname' => 'required|string|max:255',
            'roles' => 'required|array|min:1',
        ]);

        try {

            $user->fullname = $validated['fullname'];

            $user->save();

            $user->roles()->detach();

            $defineRole = [];
            foreach ($validated['roles'] as $roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $user->assignRole($role);
                }
                $defineRole[] = $role->name;
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'result' => [
                    'fullname' => $user->fullname,
                    'roles' => $defineRole,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User update failed'
            ], 500);
        }
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        try {
            $user->syncRoles([]);
            $user->deleted_at = date('Y-m-d H:i:s');
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User delete failed'
            ], 500);
        }
    }
}
