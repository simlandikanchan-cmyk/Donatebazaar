<?php

try {
    $campaign = \App\Models\Campaign::find(98);

    $products = $campaign->products()
        ->where('is_active', 1)
        ->where('approval_status', 'approved')
        ->with('categoryProduct')
        ->withCount(['reservations as active_reserved_sum' => function ($q) {
            $q->where('expires_at', '>', now());
        }])
        ->get();

    echo "QUERY OK, count=" . $products->count() . "\n";
} catch (\Throwable $e) {
    echo "QUERY FAILED: " . get_class($e) . ": " . $e->getMessage() . "\n";
}
