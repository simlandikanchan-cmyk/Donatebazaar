<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'status' => 'ok',
            'cache' => $this->checkCache(),
            'db' => $this->checkDb(),
            'queue' => $this->checkQueue(),
            'redis' => $this->checkRedis(),
        ];

        $healthy = collect($checks)->every(fn ($v) => $v === 'ok' || $v === 'available');

        return response()->json($checks, $healthy ? 200 : 503);
    }

    protected function checkCache(): string
    {
        try {
            Cache::put('health:check', true, 10);
            Cache::forget('health:check');
            return 'ok';
        } catch (\Throwable) {
            return 'unavailable';
        }
    }

    protected function checkDb(): string
    {
        try {
            DB::connection()->getPdo();
            return 'ok';
        } catch (\Throwable) {
            return 'unavailable';
        }
    }

    protected function checkQueue(): string
    {
        try {
            return config('queue.default') === 'redis' ? 'ok' : 'database';
        } catch (\Throwable) {
            return 'unavailable';
        }
    }

    protected function checkRedis(): string
    {
        try {
            $client = Cache::getRedis();
            $client->ping();
            return 'ok';
        } catch (\Throwable) {
            return 'unavailable';
        }
    }
}
