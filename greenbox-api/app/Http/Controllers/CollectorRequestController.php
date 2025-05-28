<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectorRequest;

class CollectorRequestController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        // Prevent duplicate requests
        if (CollectorRequest::where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Request already submitted'], 409);
        }

        // Create request
        CollectorRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Request to become a collector submitted'], 201);
    }
}
