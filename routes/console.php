<?php

use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\FetchRandomUserJob;
use App\Jobs\DeleteOldSoftDeletedPosts;

app(Schedule::class)->job(FetchRandomUserJob::class)->everySixHours();
app(Schedule::class)->job(DeleteOldSoftDeletedPosts::class)->daily();
