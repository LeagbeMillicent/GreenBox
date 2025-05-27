<?php

namespace App\Services;

use App\Models\User;
use App\Models\Pickup;


class CollectorService
{
    public function getAllCollectors()
    {
        return User::where('role', 'collector')->get();
    }

    public function getCollectorById($id)
    {
        return User::where('role', 'collector')->find($id);
    }

    public function getAssignedPickups($collectorId)
    {
        return Pickup::where('collector_id', $collectorId)
                     ->where('status', '!=', 'completed')
                     ->get();
    }

    public function updatePickupStatus($collectorId, $pickupId, $status)
    {
        $pickup = Pickup::where('id', $pickupId)
                        ->where('collector_id', $collectorId)
                        ->firstOrFail();

        $pickup->status = $status;
        $pickup->save();

        return $pickup;
    }

    public function getCompletedPickups($collectorId)
    {
        return Pickup::where('collector_id', $collectorId)
                     ->where('status', 'completed')
                     ->get();
    }
}
