<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CollectorService;

class CollectorController extends Controller
{
    protected $collectorService;

    public function __construct(CollectorService $collectorService)
    {
        $this->collectorService = $collectorService;
    }

    public function index()
    {
        return response()->json($this->collectorService->getAllCollectors());
    }

    public function show($id)
    {
        $collector = $this->collectorService->getCollectorById($id);

        if (!$collector) {
            return response()->json(['message' => 'Collector not found'], 404);
        }

        return response()->json($collector);
    }

    public function assignedPickups(Request $request)
    {
        $pickups = $this->collectorService->getAssignedPickups($request->user()->id);

        return response()->json($pickups);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,in-progress,completed']);

        $pickup = $this->collectorService->updatePickupStatus($request->user()->id, $id, $request->status);

        return response()->json([
            'message' => 'Pickup status updated',
            'pickup' => $pickup
        ]);
    }

    public function pickupHistory(Request $request)
    {
        $history = $this->collectorService->getCompletedPickups($request->user()->id);

        return response()->json($history);
    }
}
