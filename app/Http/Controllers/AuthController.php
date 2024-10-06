<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->only('name', 'email', 'password', 'role');
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $accessToken = $user->createToken('Api Token')->accessToken;
        return ApiResponse::sendResponse(201,"Created Successfully",(new UserResource($user))->additional(['access_token' => $accessToken])
    );
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return ApiResponse::sendResponse(401,'Invalid credentials');
        }

        $user = Auth::user();
        $accessToken = $user->createToken('Api Token')->accessToken;
        return ApiResponse::sendResponse(200,"Login Successfully",(new UserResource($user))->additional(['access_token' => $accessToken]));

    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return ApiResponse::sendResponse(200,'Logged out successfully');
    }
}
