<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->where('deleted_at', null)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'), // Mengembalikan array role
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

        $usernameGenerate = explode('@', $validated['email']);

        try {
            //code...
            $user = User::create([
                'fullname' => $validated['fullname'],
                'username' => $usernameGenerate[0], // Ambil username sebagai username default. Sebaiknya diganti dengan username yang aman.
                'email' => $validated['email'],
                'password' => Hash::make($request->password),
            ]);

            foreach ($validated['roles'] as $role) {
                $user->assignRole($role); // Mengasumsikan $role adalah string nama role
            }

            $data = [
                'fullname' => $user->fullname,
                'email' => $user->email,
                'username' => $user->username,
                'password' => $request->password, // Inisialisasi password secara acak. Sebaiknya diganti dengan password yang aman.
                'roles' => $validated['roles'],
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
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validated = $this->validate($request, [
            'fullname' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'roles' => 'required|array|min:1',
        ]);

        try {
            //code...
            $user->update([
                'fullname' => $validated['fullname'],
                // 'email' => $validated['email'],
            ]);

            $user->syncRoles($validated['roles']); // Hapus peran lama & tambahkan yang baru

            $data = [
                'fullname' => $user->fullname,
                'email' => $user->email,
                'username' => $user->username,
                // 'password' => $request->password, // Inisialisasi password secara acak. Sebaiknya diganti dengan password yang aman.
                'roles' => $validated['roles'],
            ];

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'result' => $data
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
