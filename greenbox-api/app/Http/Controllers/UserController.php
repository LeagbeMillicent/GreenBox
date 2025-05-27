<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * GET /users — Fetch all users
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'message' => 'All users retrieved successfully',
            'data' => $users
        ], 200);
    }

    /**
     * GET /users/{id} — Get user profile
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'message' => 'User profile retrieved successfully',
            'data' => $user
        ]);
    }

    /**
     * GET /users/{id}/history — Get user's pickup history 
     */
    public function history($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Placeholder for actual pickup data
        $history = [
            ['date' => '2025-01-01', 'location' => 'East Town', 'status' => 'Completed'],
            ['date' => '2025-02-10', 'location' => 'West End', 'status' => 'Pending']
        ];

        return response()->json([
            'message' => 'Pickup history retrieved successfully',
            'data' => $history
        ]);
    }

    /**
     * PUT /users/{id}/update — Update user profile
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $fields = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:15',
            'location' => 'sometimes|string|max:255',
            'profile_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'sometimes|in:general,collector,admin',
        ]);

        $user->update($fields);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    /**
     * DELETE /users/{id}/delete — Delete user account
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
