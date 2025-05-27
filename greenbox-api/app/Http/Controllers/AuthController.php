<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller{

    public function register(Request $request)
    {
        $fields = $request ->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'phone' => 'nullable|string|max:15',
            'location' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'nullable|in:general,collector,admin',
        ], [
            'email.unique' => 'The email has already been taken.',
            'profile_picture.image' => 'The profile picture must be an image.',
            'profile_picture.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg, gif, svg.',
            'role.in' => 'The role must be one of the following: general, collector, admin.',
        ]);

        $user = User::Create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'phone'=> $fields['phone'],
            'location'=> $fields['location'],
            'profile_picture' => $fields['profile_picture'] ?? null,
            'role' => $fields['role'] ?? 'general',
        ]);

        $token = $user->createToken('green_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);

        
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();
        if (!$user || !password_verify($fields['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('green_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}

