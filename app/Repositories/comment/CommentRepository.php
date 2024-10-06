<?php
namespace App\Repositories\comment;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentRepository implements CommentRepositoryInterface
{
    public function getCommentsForPost($postId)
    {
        $post = Post::with('comments.user')->findOrFail($postId);
        return $post->comments;
    }

    public function find($id)
    {
        return Comment::findOrFail($id);
    }

    public function create( $data)
    {
        return Comment::create($data);
    }

    public function update($id,  $data)
    {
        $comment = $this->find($id);
        $comment->update($data);
        return $comment;
    }

    public function delete($id)
    {
        $comment = $this->find($id);
        $comment->delete();
    }
}
