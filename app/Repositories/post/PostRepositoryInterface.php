<?php
namespace App\Repositories\post;

interface PostRepositoryInterface
{
    public function all(array $filters);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
