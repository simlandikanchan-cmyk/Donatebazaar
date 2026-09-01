<?php
$migrationsPath = __DIR__ . '/database/migrations';
$files = glob($migrationsPath . '/*.php');
echo "Found " . count($files) . " migration files\n";
foreach ($files as $f) {
    echo basename($f) . "\n";
}
