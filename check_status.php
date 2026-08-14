<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = new App\Models\User();
echo 'Default status: ' . var_export($user->status, true) . PHP_EOL;
