<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait TryRedis
{
    protected function rememberWithFallback(string $key, \DateInterval|int $ttl, \Closure $callback, array $tags = []): mixed
    {
        try {
            if (!empty($tags)) {
                return Cache::tags($tags)->remember($key, $ttl, $callback);
            }

            return Cache::remember($key, $ttl, $callback);
        } catch (\Throwable $e) {
            return $callback();
        }
    }

    protected function cacheStore(): string
    {
        return Config::get('cache.default', 'file');
    }
}
