<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index($postId)
    {
        try {
            $comments = Comment::where('post_id', $postId)->get();
            return ApiResponse::sendResponse(200, 'Comments retrieved successfully', CommentResource::collection($comments));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve comments', $e->getMessage());
        }
    }

    public function store(CommentRequest $request, Post $id)
    {
        try {
            $data = $request->validated();
            $data['post_id'] = $id->id;
            $data['user_id'] = auth()->id();
            $comment = Comment::create($data);

            return ApiResponse::sendResponse(201, 'Comment created successfully', new CommentResource($comment));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to create comment', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $comment = Comment::find($id);
            if (!$comment) {
                return ApiResponse::sendResponse(404, 'Comment not found');
            }
            return ApiResponse::sendResponse(200, 'Comment retrieved successfully', new CommentResource($comment));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve comment', $e->getMessage());
        }
    }

    public function update(CommentRequest $request, $id)
    {
        try {
            $comment = Comment::find($id);

            if (!$comment) {
                return ApiResponse::sendResponse(404, 'Comment not found');
            }
            $this->authorize('update', $comment);
            $comment->update($request->only('content'));
            return ApiResponse::sendResponse(200, 'Comment updated successfully', new CommentResource($comment));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to update comment', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $comment = Comment::find($id);
            if (!$comment) {
                return ApiResponse::sendResponse(404, 'Comment not found');
            }
            $this->authorize('delete', $comment);
            $comment->delete();
            return ApiResponse::sendResponse(200, 'Comment deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to delete comment', $e->getMessage());
        }
    }
}

