<?php

namespace App\Repositories\auth;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function register( $data);
    public function login( $credentials);
    public function logout(User $user);
}
