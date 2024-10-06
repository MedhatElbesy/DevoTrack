<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\comment\CommentRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index($postId)
    {
        try {
            $comments = $this->commentRepository->getCommentsForPost($postId);
            return ApiResponse::sendResponse(200, 'Comments retrieved successfully', CommentResource::collection($comments));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Post not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve comments', ['error' => $e->getMessage()]);
        }
    }

    public function store(CommentRequest $request, Post $id)
    {
        try {
            $data = $request->validated();
            $data['post_id'] = $id->id;
            // $data['user_id'] = auth()->id();
            $comment = $this->commentRepository->create($data);

            return ApiResponse::sendResponse(201, 'Comment created successfully', new CommentResource($comment));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to create comment', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $comment = $this->commentRepository->find($id);
            return ApiResponse::sendResponse(200, 'Comment retrieved successfully', new CommentResource($comment));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Comment not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve comment', ['error' => $e->getMessage()]);
        }
    }

    public function update(CommentRequest $request, $id)
    {
        try {
            $comment = $this->commentRepository->find($id);
            $this->authorize('update', $comment);
            $comment = $this->commentRepository->update($id, $request->only('content'));
            return ApiResponse::sendResponse(200, 'Comment updated successfully', new CommentResource($comment));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Comment not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to update comment', ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
{
    try {
        $comment = $this->commentRepository->find($id);
        $this->authorize('delete', $comment);
        $this->commentRepository->delete($id);

        return ApiResponse::sendResponse(200, 'Comment deleted successfully');
    } catch (ModelNotFoundException $e) {
        return ApiResponse::sendResponse(404, 'Comment not found');
    } catch (Exception $e) {
        return ApiResponse::sendResponse(500, 'Failed to delete comment', ['error' => $e->getMessage()]);
    }
}
}

