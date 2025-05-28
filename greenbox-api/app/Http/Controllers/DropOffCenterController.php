<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DropOffCenterService;

class DropOffCenterController extends Controller
{
    protected $service;

    public function __construct(DropOffCenterService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAllActive());
    }

    public function show($id)
    {
        return response()->json($this->service->getById($id));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string',
        'location' => 'nullable|string',
        'contact_number' => 'nullable|string|max:20',
        'operating_hours' => 'nullable|string',
    ]);

    $existing = $this->service->create($data);

    $isNew = $existing->wasRecentlyCreated ?? false;

    return response()->json([
        'message' => $isNew ? 'Drop-off center created successfully.' : 'Drop-off center already exists.',
        'center' => $existing
    ], $isNew ? 201 : 200);
}

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'location' => 'sometimes|string',
            'contact_number' => 'sometimes|string|max:20',
            'operating_hours' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $center = $this->service->update($id, $data);

        return response()->json([
            'message' => 'Drop-off center updated successfully',
            'center' => $center,
        ]);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Drop-off center deleted successfully']);
    }
}
