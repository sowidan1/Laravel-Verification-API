<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tag\StoreTagRequest;
use App\Http\Requests\Api\Tag\UpdateTagRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Tag;

class TagController extends Controller
{

    public function index()
    {
        $data = Tag::simplePaginate();

        return ApiResponse::success($data, 'Tags retrieved successfully.');
    }

    public function store(StoreTagRequest $request)
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

    public function update(UpdateTagRequest $request, $id)
    {
        $validated = $request->validated();

        $tag = Tag::find($id);

        if (!$tag) {
            return ApiResponse::error('Tag not found.', [], 404);
        }

        $tag->update($validated);

        return ApiResponse::success($tag, 'Tag updated successfully.');
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return ApiResponse::error('Tag not found.', [], 404);
        }

        $deleted_tag = $tag;

        $tag->delete();

        return ApiResponse::success($deleted_tag, 'Tag deleted successfully.');
    }
}
