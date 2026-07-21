<?php

return [
    'max_retry_attempts' => env('SETTLEMENT_MAX_RETRY_ATTEMPTS', 4),
    'retry_backoff_minutes' => [1, 5, 15, 60],
    'reconciliation' => [
        'batch_size' => env('RECONCILIATION_BATCH_SIZE', 100),
        'processing_stuck_minutes' => env('RECONCILIATION_PROCESSING_STUCK_MINUTES', 30),
    ],
];
