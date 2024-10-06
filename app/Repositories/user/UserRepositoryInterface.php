<?php
namespace App\Repositories\user;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAll(int $perPage): \Illuminate\Pagination\LengthAwarePaginator;
    public function findById($id);
    public function update(User $user,  $data);
    public function delete(User $user);
}
