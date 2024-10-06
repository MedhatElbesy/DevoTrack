<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\user\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        try {
            $users = $this->userRepository->getAll(10);
            return ApiResponse::sendResponse(200, 'Users retrieved successfully', $users);
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve users', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userRepository->findById($id);
            if (!$user) {
                return ApiResponse::sendResponse(404, 'User not found');
            }
            return ApiResponse::sendResponse(200, 'User retrieved successfully', $user);
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve user', ['error' => $e->getMessage()]);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = $this->userRepository->findById($id);
            if (!$user) {
                return ApiResponse::sendResponse(404, 'User not found');
            }
            $this->userRepository->update($user, $request->validated());
            return ApiResponse::sendResponse(200, 'User updated successfully', $user);
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to update user', ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $user = $this->userRepository->findById($id);
            if (!$user) {
                return ApiResponse::sendResponse(404, 'User not found');
            }
            $this->userRepository->delete($user);
            return ApiResponse::sendResponse(200, 'User deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to delete user', ['error' => $e->getMessage()]);
        }
    }
}
