<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectorRequest;
use App\Models\User;

class AdminCollectorRequestController extends Controller
    {
        public function index()
    {
        $requests = CollectorRequest::with('user')->latest()->get();
        return response()->json($requests);
    }

    public function approve($id)
    {
        $request = CollectorRequest::findOrFail($id);

        if ($request->status !== 'pending') {
            return response()->json(['message' => 'Request already processed'], 400);
        }

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Approve the request and update user role
        $request->status = 'approved';
        $request->save();

        $user->role = 'collector';
        $user->save();

        return response()->json(['message' => 'Collector request approved', 'user' => $user]);
    }

    public function reject($id)
    {
        $request = CollectorRequest::findOrFail($id);

        if ($request->status !== 'pending') {
            return response()->json(['message' => 'Request already processed'], 400);
        }

        $request->update(['status' => 'rejected']);

        return response()->json(['message' => 'Collector request rejected']);
    }
}
