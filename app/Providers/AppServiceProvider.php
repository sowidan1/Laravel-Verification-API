<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Observers\CacheObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        User::observe(CacheObserver::class);
        Post::observe(CacheObserver::class);
    }
}
