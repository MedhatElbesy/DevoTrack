<?php
namespace App\Repositories\comment;

interface CommentRepositoryInterface
{
    public function getCommentsForPost($postId);
    public function find($id);
    public function create( $data);
    public function update($id,  $data);
    public function delete($id);
}
