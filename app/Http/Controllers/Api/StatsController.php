<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
class StatsController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('stats', 60 * 60, function () {
            return [
                'total_users' => User::count(),
                'total_posts' => Post::count(),
                'users_with_no_posts' => User::doesntHave('posts')->count(),
            ];
        });

        return ApiResponse::success($stats, 'Statistics retrieved successfully.');
    }
}
