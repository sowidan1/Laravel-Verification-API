<?php

namespace App\Http\Services\Facades;


use Illuminate\Support\Facades\Facade;

class TagFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TagService';
    }
}
