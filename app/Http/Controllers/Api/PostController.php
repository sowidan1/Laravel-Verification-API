<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Post\{
    StorePostRequest,
    UpdatePostRequest,
};

use App\Http\Services\Facades\PostFacade;

class PostController extends Controller
{
    public function index()
    {
        return PostFacade::index();
    }

    public function store(StorePostRequest $request)
    {
        return PostFacade::store($request);
    }

    public function show($id)
    {
        return PostFacade::show($id);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        return PostFacade::update($request, $id);
    }

    public function destroy($id)
    {
        return PostFacade::destroy($id);
    }

    public function deletedPosts()
    {
        return PostFacade::deletedPosts();
    }

    public function restoreDeletedPost($id)
    {
        return PostFacade::restoreDeletedPost($id);
    }
}
