<?php
namespace App\Repositories\post;

use App\Models\Post;
use Carbon\Carbon;

class PostRepository implements PostRepositoryInterface
{
    public function all(array $filters)
    {
        $query = Post::with('author', 'category');

        if (isset($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('name', $filters['category']);
            });
        }

        if (isset($filters['author'])) {
            $query->whereHas('author', function ($q) use ($filters) {
                $q->where('name', $filters['author']);
            });
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $startDate = Carbon::parse($filters['start_date'])->startOfDay();
            $endDate = Carbon::parse($filters['end_date'])->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->paginate(10);
    }

    public function find($id)
    {
        return Post::with('author', 'category')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function update($id, array $data)
    {
        $post = $this->find($id);
        $post->update($data);
        return $post;
    }

    public function delete($id)
    {
        $post = $this->find($id);
        $post->delete();
    }
}
