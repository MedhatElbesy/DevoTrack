<?php

namespace App\Providers;

use App\Repositories\auth\AuthRepository;
use App\Repositories\auth\AuthRepositoryInterface;
use App\Repositories\category\CategoryRepository;
use App\Repositories\category\CategoryRepositoryInterface;
use App\Repositories\comment\CommentRepository;
use App\Repositories\comment\CommentRepositoryInterface;
use App\Repositories\post\PostRepository;
use App\Repositories\post\PostRepositoryInterface;
use App\Repositories\user\UserRepository ;
use App\Repositories\user\UserRepositoryInterface ;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
