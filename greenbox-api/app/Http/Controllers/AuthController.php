<?php

namespace App\Http\Controllers;

use App\Services\AppLogger;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $auth;
    protected $logger;

    public function __construct(AuthService $auth, AppLogger $logger)
    {
        $this->auth = $auth;
        $this->logger = $logger;
    }

    public function register(Request $request)
    {
        try {
            $result = $this->auth->register($request->all());


            return response()->json([
                'message' => 'User registered successfully',
                'user' => $result['user'],
                'token' => $result['token'],
            ], 201);
        } catch (ValidationException $e) {
            $this->logger->warning('Registration validation failed', ['errors' => $e->errors()]);
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            $this->logger->error('Registration error', ['message' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $result = $this->auth->login($request->all());
            $this->logger->info('User logged in successfully', ['user_id' => $result['user']->id]);

            return response()->json([
                'user' => $result['user'],
                'token' => $result['token'],
            ]);
        } catch (ValidationException $e) {
            $this->logger->warning('Login validation failed', ['errors' => $e->errors()]);
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            $this->logger->error('Login error', ['message' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 401);
        }
    }

    public function logout(Request $request)
    {
        $this->auth->logout($request->user());
        $this->logger->info('User logged out', ['user_id' => $request->user()->id]);

        return response()->json(['message' => 'Logged out successfully']);
        
    }
}
