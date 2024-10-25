<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\StorePostRequest;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts()->with('tags')->orderByDesc('pinned')->simplePaginate();

        $posts->getCollection()->transform(function ($post) {
            $post->cover_image = url('storage/' . $post->cover_image);
            return $post;
        });

        return ApiResponse::success($posts, 'Posts retrieved successfully.');
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $image_path = $request->file('cover_image')->store('cover_images', 'public');

        $post = auth()->user()->posts()->create(
            array_merge($validated, ['cover_image' => $image_path])
        );

        $post->tags()->attach($validated['tags']);

        $post->cover_image = asset('storage/' . $image_path);

        return ApiResponse::success($post, 'Post created successfully.');
    }

    public function show($id)
    {
        $user = auth()->user();

        $post = Post::find($id);

        if (!$post) {
            return ApiResponse::error('Post not found', [], 404);
        }

        if ($user->id !== $post->user_id) {
            return ApiResponse::error('Unauthorized access', [],403);
        }

        $post->cover_image = asset('storage/' . $post->cover_image);

        return ApiResponse::success($post->load('tags'), 'Post retrieved successfully.');
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $user = auth()->user();

        $post = Post::find($id);

        if (!$post) {
            return ApiResponse::error('Post not found', [], 404);
        }

        if ($user->id !== $post->user_id) {
            return ApiResponse::error('Unauthorized access', [], 403);
        }

        $validated = $request->validated();

        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        $post->update($validated);

        if ($tags) {
            $post->tags()->sync($tags);
        }

        $post->cover_image = asset('storage/' . $post->cover_image);

        return ApiResponse::success($post, 'Post updated successfully.');
    }

    public function destroy($id)
    {

        $post = Post::find($id);

        if (!$post) {
            return ApiResponse::error('Post not found', [], 404);
        }

        $user = auth()->user();

        if ($user->id !== $post->user_id) {
            return ApiResponse::error('Unauthorized access', [],403);
        }

        $deleted_post = $post;

        $post->delete();

        return ApiResponse::success($deleted_post, 'Post deleted successfully.');
    }

    public function deletedPosts()
    {
        $user = auth()->user();

        $posts = $user->posts()->onlyTrashed()->get();

        if ($posts->isEmpty()) {
            return ApiResponse::error('No deleted posts found.', [], 404);
        }

        return ApiResponse::success($posts, 'Deleted posts retrieved successfully.');
    }

    public function restoreDeletedPost($id)
    {
        $post = auth()->user()->posts()->onlyTrashed()->find($id);

        if (!$post) {
            return ApiResponse::error('Post not found.', [], 404);
        }

        $post->restore();

        return ApiResponse::success($post, 'Post restored successfully.');
    }
}
