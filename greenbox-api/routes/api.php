<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Users
    Route::get('/users', [UserController::class, 'index']); // Fetch all users
    Route::get('get/user/{id}', [UserController::class, 'show']); // Get profile
    Route::get('/users/{id}/history', [UserController::class, 'history']); // Pickup history
    Route::post('update/user/{id}/update', [UserController::class, 'update']); // Update profile
    Route::delete('delete/user/{id}/delete', [UserController::class, 'destroy']); // Delete profile
});


use App\Http\Controllers\PickupController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pickup/request', [PickupController::class, 'requestPickup']);
    Route::get('/pickup/status/{id}', [PickupController::class, 'getStatus']);
    Route::get('/pickup/my-requests', [PickupController::class, 'myRequests']);
    Route::post('/pickup/assign', [PickupController::class, 'assign']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pickup/history', [PickupController::class, 'userHistory']);
});

