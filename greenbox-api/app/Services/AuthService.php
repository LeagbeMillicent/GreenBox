<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;

class AuthService
{
    public function register(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
            'phone' => 'nullable|string|max:15',
            'location' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'nullable|in:general,collector,admin',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $fields = $validator->validated();

        // Handle image upload if provided
        $profilePicturePath = null;
        if (isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile) {
            $profilePicturePath = $data['profile_picture']->store('profile_pictures', 'public');
        }

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'phone' => $fields['phone'] ?? null,
            'location' => $fields['location'] ?? null,
            'profile_picture' => $profilePicturePath,
            'role' => $fields['role'] ?? 'general',
        ]);

        $token = $user->createToken('green_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $fields = $validator->validated();

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            throw new \Exception('Invalid credentials', 401);
        }

        $token = $user->createToken('green_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout($user)
    {
        $user->tokens()->delete();
    }
}
