<?php

namespace App\Services;

use App\Models\DropOffCenter;

class DropOffCenterService
{
    public function getAllActive()
    {
        return DropOffCenter::where('is_active', true)->get();
    }

    public function getById($id)
    {
        return DropOffCenter::findOrFail($id);
    }

    public function create(array $data)
    {
        // Check if name and address already exists
        $existing = DropOffCenter::where('name', $data['name'])
                                 ->where('address', $data['address'])
                                 ->first();

        if ($existing) {
            return $existing; 
        }

        return DropOffCenter::create($data);
    }

    public function update($id, array $data)
    {
        $center = DropOffCenter::findOrFail($id);
        $center->update($data);
        return $center;
    }

    public function delete($id)
    {
        $center = DropOffCenter::findOrFail($id);
        $center->delete();
        return true;
    }
}
