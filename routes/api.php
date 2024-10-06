<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::middleware(['auth:api', 'role:author'])->group(function () {
    Route::post('logout',[AuthController::class,'logout']);
    Route::apiResource('categories', CategoryController::class)->only(['index','show']);

    Route::apiResource('posts', PostController::class);

    Route::controller(CommentController::class)->group(function (){
        Route::get('posts/{id}/comments','index');
        Route::get('comments/{id}','show');
        Route::post('posts/{id}/comments','store');
        Route::put('comments/{id}','update');
        Route::delete('comments/{id}','destroy');
    });

});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::apiResource('categories', CategoryController::class)->only(['store','update','destroy']);


});
