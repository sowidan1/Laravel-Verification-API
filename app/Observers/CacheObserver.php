<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class CacheObserver
{
    public function saved()
    {
        Cache::forget('stats');
    }

    public function deleted()
    {
        Cache::forget('stats');
    }
}
