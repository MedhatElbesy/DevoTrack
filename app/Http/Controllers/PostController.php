<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\post\PostRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only('category', 'author', 'start_date', 'end_date');
            $cacheKey = 'posts_' . md5(json_encode($filters));

            $posts = Cache::remember($cacheKey, 3600, function () use ($filters) {
                return $this->postRepository->all($filters);
            });

            return ApiResponse::sendResponse(200, 'Posts retrieved successfully', PostResource::collection($posts));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve posts', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $post = $this->postRepository->find($id);
            return ApiResponse::sendResponse(200, 'Post retrieved successfully', new PostResource($post));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Post not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve post', ['error' => $e->getMessage()]);
        }
    }

    public function store(PostRequest $request)
    {

        try {
            $data = $request->validated();
            // dd(vars: $data);
            // $data['author_id'] = auth()->id();
            $post = $this->postRepository->create($data);

            return ApiResponse::sendResponse(201, 'Post created successfully', new PostResource($post));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to create post', ['error' => $e->getMessage()]);
        }
    }

    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $post = $this->postRepository->find($id);
            $this->authorize('update', $post);
            $post = $this->postRepository->update($id, $request->only('title', 'content', 'category_id'));

            return ApiResponse::sendResponse(200, 'Post updated successfully', new PostResource($post));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Post not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to update post', ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $post = $this->postRepository->find($id);
            $this->authorize('delete', $post);
            $this->postRepository->delete($id);

            return ApiResponse::sendResponse(200, 'Post deleted successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Post not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to delete post', ['error' => $e->getMessage()]);
        }
    }
}

