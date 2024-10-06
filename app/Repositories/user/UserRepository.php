<?php
namespace App\Repositories\user;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function getAll(int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function findById($id): ?User
    {
        return User::find($id);
    }

    public function update(User $user,  $data)
    {
        return $user->update($data);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}
