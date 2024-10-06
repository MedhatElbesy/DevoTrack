<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\auth\AuthRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $result = $this->authRepository->register($data);

            return ApiResponse::sendResponse(201, "Created Successfully",
                (new UserResource($result['user']))->additional(['access_token' => $result['access_token']])
            );
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to register user', ['error' => $e->getMessage()]);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            
            $credentials = $request->validated();
            $result = $this->authRepository->login($credentials);
            if (!$result) {
                return ApiResponse::sendResponse(401, 'Invalid credentials');
            }

            return ApiResponse::sendResponse(200, "Login Successfully",
                (new UserResource($result['user']))->additional(['access_token' => $result['access_token']])
            );
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to login', ['error' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $this->authRepository->logout($user);

            return ApiResponse::sendResponse(200, 'Logged out successfully');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to logout', ['error' => $e->getMessage()]);
        }
    }
}

