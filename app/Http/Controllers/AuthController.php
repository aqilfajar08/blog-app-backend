<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', 
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // Membuat user baru
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Mengembalikan respons dengan user & token               
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
            'message' => 'user berhasil didaftarkan'
        ], 201); // Status kode HTTP untuk created
    }

    public function login(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        // email
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password not match'
            ], 404);
        }

        // Mengembalikan respons dengan user dan token
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
            'message' => 'user berhasil login'
        ], 200); // Kode status 200 untuk sukses
    }

    // Logout user
    public function logout(Request $request)
    {
        // Menghapus semua token pengguna yang sedang login
        $request -> user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout success.',
        ], 200); // Kode status 200 untuk logout berhasil
    }

}
