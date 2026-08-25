<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$repository = app('migrator')->repository;
$migrator = app('migrator');
$files = $migrator->getMigrationFiles(database_path('migrations'));
$pending = $migrator->getStatusFor(true); // pending only

if (empty($pending)) {
    echo "No pending migrations.\n";
    exit(0);
}

echo "Pending migrations: " . count($pending) . "\n";
foreach ($pending as $migration) {
    echo "  - " . $migration->name . "\n";
}

$count = 0;
foreach ($pending as $migration) {
    $name = $migration->name;
    try {
        $migrator->run(database_path('migrations'), ['realpath' => true]);
        echo "All done after $count successful.\n";
        break;
    } catch (\Throwable $e) {
        $msg = $e->getMessage();
        if (strpos($msg, 'already exists') !== false || strpos($msg, 'Base table or view already exists') !== false) {
            DB::table('migrations')->insert(['migration' => $name, 'batch' => 78]);
            $count++;
            echo "Skipped (table exists): $name\n";
        } else {
            echo "ERROR on $name: $msg\n";
            break;
        }
    }
    break; // run() processes all pending; we only need one call
}
