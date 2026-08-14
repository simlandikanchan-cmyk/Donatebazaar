<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'simlandikanchan@gmail.com')->first();
if ($user) {
    $user->role = 'ngo';
    $user->save();
    echo "Updated creator role to ngo\n";
} else {
    echo "Creator not found\n";
}
