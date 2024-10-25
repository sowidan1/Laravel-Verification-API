<?php
namespace App\Http\Services\Services;

use App\Http\Responses\ApiResponse;
use App\Http\Services\Contracts\TagContract;
use App\Models\Tag;

class TagService implements TagContract
{
    public function index()
    {
        $data = Tag::simplePaginate();

        return ApiResponse::success($data, 'Tags retrieved successfully.');
    }

    public function store($request)
    {
        $validated = $request->validated();

        $tag = Tag::create($validated);

        return ApiResponse::success($tag, 'Tag created successfully.');
    }

    public function show($id)
    {
        $tag = Tag::findOrFail($id);

        if (!$tag) {
            return ApiResponse::error('Tag not found.', [],404);
        }

        return ApiResponse::success($tag, 'Tag retrieved successfully.');
    }

    public function update($request, $tag)
    {
        $validated = $request->validated();

        $tag = Tag::find($tag);

        if (!$tag) {
            return ApiResponse::error('Tag not found.', [], 404);
        }

        $tag->update($validated);

        return ApiResponse::success($tag, 'Tag updated successfully.');
    }

    public function destroy($tag)
    {
        $tag = Tag::find($tag);

        if (!$tag) {
            return ApiResponse::error('Tag not found.', [], 404);
        }

        $tag->delete();

        return ApiResponse::success([], 'Tag deleted successfully.');
    }
}
