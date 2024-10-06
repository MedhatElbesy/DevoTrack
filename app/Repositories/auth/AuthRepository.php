<?php
namespace App\Repositories\auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function register( $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $accessToken = $user->createToken('Api Token')->accessToken;

        return ['user' => $user, 'access_token' => $accessToken];
    }

    public function login( $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        $accessToken = $user->createToken('Api Token')->accessToken;

        return ['user' => $user, 'access_token' => $accessToken];
    }

    public function logout(User $user)
    {
        $user->tokens()->delete();
        return true;
    }
}
