<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchRandomUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::get('https://randomuser.me/api/');

        if ($response->successful()) {
            $results = $response->json('results');

            Log::info('Fetched Random User Data:', ['results' => $results]);
        } else {
            Log::error('Failed to fetch data from randomuser.me API', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }
}
