<?php
namespace App\Http\Controllers;

use App\Models\Pickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PickupController extends Controller
{
    // POST /pickup/request
    public function requestPickup(Request $request)
    {
        $data = $request->validate([
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'pickup_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

       // Handle image upload if provided
    if ($request->hasFile('pickup_image')) {
        $imagePath = $request->file('pickup_image')->store('pickups', 'public');
        $data['pickup_image'] = $imagePath;
    }

    $pickup = $request->user()->pickups()->create([
        'pickup_date' => now(),
        'location' => $data['location'],
        'description' => $data['description'] ?? null,
        'pickup_image' => $data['pickup_image'] ?? null,
        'status' => 'pending', 
    ]);
        return response()->json([
            'message' => 'Pickup request submitted.',
            'pickup' => $pickup
        ], 201);
    }

    // GET /pickup/status/{id}
    public function getStatus($id)
    {
        $pickup = Pickup::find($id);

        if (!$pickup) {
            return response()->json(['message' => 'Pickup not found'], 404);
        }

        return response()->json($pickup);
    }

    // GET /pickup/my-requests
    public function myRequests()
    {
        $pickups = Pickup::where('user_id', Auth::id())->get();

        return response()->json($pickups);
    }

    // POST /pickup/assign (Admin only)
    public function assign(Request $request)
    {
        $data = $request->validate([
            'pickup_id' => 'required|exists:pickups,id',
            'collector_id' => 'required|exists:users,id',
        ]);

        $pickup = Pickup::find($data['pickup_id']);
        $pickup->collector_id = $data['collector_id'];
        $pickup->status = 'assigned';
        $pickup->save();

        return response()->json([
            'message' => 'Pickup assigned to collector.',
            'pickup' => $pickup,
        ]);
    }


    public function userHistory(Request $request)
{
    $user = $request->user();

    //  filter by category
    $status = $request->query('status');
    $dateRequested = $request->query('date_requested'); 

    $query = Pickup::where('user_id', $user->id);

    if ($status) {
        $query->where('status', $status);
    }

    if ($dateRequested) {
        $query->whereDate('created_at', $dateRequested);
    }

    $history = $query->orderBy('created_at', 'desc')->get();

    return response()->json([
        'message' => 'Pickup history retrieved successfully.',
        'data' => $history,
    ]);
}

}
