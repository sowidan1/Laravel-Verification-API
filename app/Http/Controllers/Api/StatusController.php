<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\Facades\AuthFacade;

class StatusController extends Controller
{
    public function index()
    {
        return AuthFacade::index();
    }
}
