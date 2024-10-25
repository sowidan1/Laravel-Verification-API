<?php

namespace App\Http\Services\Contracts;

interface TagContract
{
    public function index();
    public function store($request);
    public function show($id);
    public function update($request, $tag);
    public function destroy($tag);
}
