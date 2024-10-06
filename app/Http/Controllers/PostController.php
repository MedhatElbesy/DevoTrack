<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::with('author', 'category')->paginate(10);
            return ApiResponse::sendResponse(
                200,
                'Posts retrieved successfully',
                PostResource::collection($posts)
            );
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve posts', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::with('author', 'category')->findOrFail($id);
            return ApiResponse::sendResponse(
                200,
                'Post retrieved successfully',
                new PostResource($post)
            );
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
            $data['author_id'] = auth()->id();
            $post = Post::create($data);

            return ApiResponse::sendResponse(
                201,
                'Post created successfully',
                new PostResource($post)
            );
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to create post', ['error' => $e->getMessage()]);
        }
    }

    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->author_id !== auth()->id() && auth()->user()->role !== 'admin') {
                return ApiResponse::sendResponse(403, 'Unauthorized to update this post');
            }

            $post->update($request->only('title', 'content', 'category_id'));

            return ApiResponse::sendResponse(
                200,
                'Post updated successfully',
                new PostResource($post)
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Post not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to update post', ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->author_id !== auth()->id() && auth()->user()->role !== 'admin') {
                return ApiResponse::sendResponse(403, 'Unauthorized to delete this post');
            }

            $post->delete();

            return ApiResponse::sendResponse(200, 'Post deleted successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Post not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to delete post', ['error' => $e->getMessage()]);
        }
    }
}

