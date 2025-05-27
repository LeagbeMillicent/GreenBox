<?php

use App\Services\AppLogger;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $userService;
    protected $logger;

    public function __construct(UserService $userService, AppLogger $logger)
    {
        $this->userService = $userService;
        $this->logger = $logger;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        if ($users->isEmpty()) {
            $this->logger->info('No users found');
            return response()->json(['message' => 'No users found'], 404);
        }

        AppLogger::info('All users retrieved successfully');

        return response()->json([

            'message' => 'All users retrieved successfully, [' . count($users) . ' users found]',
            'data' => $users,
        ]);
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);

        if (! $user) {
            AppLogger::warning("User with ID {$id} not found, ['user_id' => $id]);");
            return response()->json(['message' => 'User not found'], 404);
        }
        AppLogger::info("User profile retrieved successfully, ['user_id' => $id]);");

        return response()->json([
            'message' => 'User profile retrieved successfully',
            'data' => $user,
        ]);
    }

    public function history($id)
    {
        $history = $this->userService->getUserHistory($id);

        if (! $history) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'message' => 'Pickup history retrieved successfully',
            'data' => $history,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $this->userService->updateUser($id, $request->all());

            if (! $user) {
                AppLogger::warning('User update failed, user not found', ['user_id' => $id]);
                return response()->json(['message' => 'User not found'], 404);
            }

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user,
            ]);
        } catch (ValidationException $e) {
            AppLogger::error('User update validation failed', [
                'user_id' => $id,
                'errors' => $e->errors()
            ]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function destroy($id)
    {
        $deleted = $this->userService->deleteUser($id);

        if (! $deleted) {
            AppLogger::warning('User not found', ['user_id' => $id]);
            return response()->json(['message' => 'User not found'], 404);
        }
        AppLogger::info('User deleted successfully', ['user_id' => $id]);

        return response()->json(['message' => 'User deleted successfully']);
    }
}
