<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (!$token = Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 1500, // Default TTL dalam detik
            'result' => [
                'fullname' => $user->fullname,
                'username' => $user->username,
                'email' => $user->email,
                'avatar_path' => $user->avatar_path,
                'role' => $user->roles->pluck('name')
            ]
        ]);
    }


    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard()->factory()->getTTL() * 1500
        ]);
    }

    public function profile()
    {
        return response()->json([
            'success' => true,
            'result' => Auth::user()
        ]);
    }
}
