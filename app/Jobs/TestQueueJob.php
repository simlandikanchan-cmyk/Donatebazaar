<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TestQueueJob implements ShouldQueue
{
    use Queueable;
    public int $tries = 3;

    public int $timeout = 120;

    public array $backoff = [60, 300, 900];

    public function handle(): void
    {
        Log::info('Queue Working Perfectly!');
    }
}
