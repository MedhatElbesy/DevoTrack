<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return ApiResponse::sendResponse(200,'Categories retrieved successfully',CategoryResource::collection($categories));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve categories', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return ApiResponse::sendResponse(200,'Category retrieved successfully',new CategoryResource($category));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Category not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to retrieve category', ['error' => $e->getMessage()]);
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());
            return ApiResponse::sendResponse(201,'Category created successfully',new CategoryResource($category));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to create category', ['error' => $e->getMessage()]);
        }
    }

    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->update($request->validated());
            return ApiResponse::sendResponse(200,'Category updated successfully',new CategoryResource($category));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Category not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to update category', ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return ApiResponse::sendResponse(200, 'Category deleted successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::sendResponse(404, 'Category not found');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to delete category', ['error' => $e->getMessage()]);
        }
    }
}

