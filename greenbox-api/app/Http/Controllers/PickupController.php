<?php
use App\Services\PickupService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    protected $pickupService;

    public function __construct(PickupService $pickupService)
    {
        $this->pickupService = $pickupService;
    }

    public function requestPickup(Request $request)
    {
        $pickup = $this->pickupService->requestPickup($request);

        return response()->json([
            'message' => 'Pickup request submitted.',
            'pickup' => $pickup,
        ], 201);
    }

    public function getStatus($id)
    {
        $pickup = $this->pickupService->getStatus($id);

        if (!$pickup) {
            return response()->json(['message' => 'Pickup not found'], 404);
        }

        return response()->json($pickup);
    }

    public function myRequests()
    {
        $pickups = $this->pickupService->myRequests(auth()->id());

        return response()->json($pickups);
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'pickup_id' => 'required|exists:pickups,id',
            'collector_id' => 'required|exists:users,id',
        ]);

        $pickup = $this->pickupService->assignCollector($data);

        return response()->json([
            'message' => 'Pickup assigned to collector.',
            'pickup' => $pickup,
        ]);
    }

    public function userHistory(Request $request)
    {
        $filters = $request->only(['status', 'date_requested']);
        $history = $this->pickupService->getUserHistory($request->user()->id, $filters);

        return response()->json([
            'message' => 'Pickup history retrieved successfully.',
            'data' => $history,
        ]);
    }

    public function cancelPickup(Request $request, $id)
    {
        $data = $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ]);

        $result = $this->pickupService->cancelPickup($id, $request->user()->id, $data);

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], 400);
        }

        return response()->json([
            'message' => 'Pickup request cancelled successfully',
            'pickup' => $result,
        ]);
    }

    public function getCancelledPickups()
    {
        $cancelled = $this->pickupService->getCancelledPickups();

        return response()->json([
            'message' => 'Cancelled pickups fetched successfully',
            'data' => $cancelled,
        ]);
    }
}
