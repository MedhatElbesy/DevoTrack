<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return ApiResponse::sendResponse(200, 'Users retrieved successfully', UserResource::collection($users));
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return ApiResponse::sendResponse(404, 'User not found');
        }

        return ApiResponse::sendResponse(200, 'User retrieved successfully', new UserResource($user));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return ApiResponse::sendResponse(404, 'User not found');
        }

        $user->update($request->only('name', 'email', 'role'));

        return ApiResponse::sendResponse(200, 'User updated successfully', new UserResource($user));
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return ApiResponse::sendResponse(404, 'User not found');
        }

        $user->delete();

        return ApiResponse::sendResponse(200, 'User deleted successfully');
    }
}
