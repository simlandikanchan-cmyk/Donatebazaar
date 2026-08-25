<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::factory()->create(['role' => 'donor']);
echo "status: " . $u->status . PHP_EOL;
echo "isActive: " . ($u->isAccountActive() ? 'true' : 'false') . PHP_EOL;
