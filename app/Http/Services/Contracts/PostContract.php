<?php

namespace App\Http\Services\Contracts;

interface PostContract
{
    public function index();
    public function store($request);
    public function show($id);
    public function update($request, $id);
    public function destroy($post);
    public function deletedPosts();
    public function restoreDeletedPost($id);
}
