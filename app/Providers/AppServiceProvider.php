<?php

namespace App\Providers;

use App\Http\Services\Services\{
    AuthService,
    PostService,
    TagService,
};

use App\Models\{
    Post,
    User,
};

use App\Observers\CacheObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind('AuthService', function() {
            return new AuthService;
        });

        $this->app->bind('TagService', function() {
            return new TagService;
        });

        $this->app->bind('PostService', function() {
            return new PostService;
        });
    }

    public function boot(): void
    {
        User::observe(CacheObserver::class);
        Post::observe(CacheObserver::class);
    }
}
