<?php

namespace App\Services;

use App\Models\Pickup;
use Illuminate\Http\Request;

class PickupService
{
    public function requestPickup(Request $request)
    {
        $data = $request->validate([
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'pickup_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('pickup_image')) {
            $data['pickup_image'] = $request->file('pickup_image')->store('pickups', 'public');
        }

        return $request->user()->pickups()->create([
            'pickup_date' => now(),
            'location' => $data['location'],
            'description' => $data['description'] ?? null,
            'pickup_image' => $data['pickup_image'] ?? null,
            'status' => 'pending',
        ]);
    }

    public function getStatus($id)
    {
        return Pickup::find($id);
    }

    public function myRequests($userId)
    {
        return Pickup::where('user_id', $userId)->get();
    }

    public function assignCollector(array $data)
    {
        $pickup = Pickup::findOrFail($data['pickup_id']);
        $pickup->collector_id = $data['collector_id'];
        $pickup->status = 'assigned';
        $pickup->save();

        return $pickup;
    }

    public function getUserHistory($userId, $filters = [])
    {
        $query = Pickup::where('user_id', $userId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_requested'])) {
            $query->whereDate('created_at', $filters['date_requested']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function cancelPickup($id, $userId, array $data)
    {
        $pickup = Pickup::findOrFail($id);

        if ($pickup->status === 'cancelled') {
            return ['error' => 'Pickup already cancelled'];
        }

        $pickup->status = 'cancelled';
        $pickup->cancel_reason = $data['cancel_reason'];
        $pickup->cancelled_by = $userId;
        $pickup->cancelled_at = now();
        $pickup->save();

        return $pickup;
    }

    public function getCancelledPickups()
    {
        return Pickup::where('status', 'cancelled')->with('user')->latest()->get();
    }
}
