<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function getUserHistory($id)
    {
        $user = User::find($id);
        if (! $user) {
            return null;
        }

        $history = $user->pickups()
            ->select('pickup_date', 'location', 'status', 'description')
            ->orderBy('pickup_date', 'desc')
            ->get();
        return $history;
    }

    public function updateUser($id, array $data)
    {
        $user = User::find($id);

        if (! $user) return null;

        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:15',
            'location' => 'sometimes|string|max:255',
            'profile_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'sometimes|in:general,collector,admin',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user->update($validator->validated());

        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (! $user) return false;

        $user->delete();

        return true;
    }
}
