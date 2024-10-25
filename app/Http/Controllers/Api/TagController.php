<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tag\{
    StoreTagRequest,
    UpdateTagRequest,
};
use App\Http\Services\Facades\TagFacade;

class TagController extends Controller
{

    public function index()
    {
        return TagFacade::index();
    }

    public function store(StoreTagRequest $request)
    {
        return TagFacade::store($request);
    }

    public function show($id)
    {
        return TagFacade::show($id);
    }

    public function update(UpdateTagRequest $request, $id)
    {
        return TagFacade::update($request, $id);
    }

    public function destroy($id)
    {
        return TagFacade::destroy($id);
    }
}
