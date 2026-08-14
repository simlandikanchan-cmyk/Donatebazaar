<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create default fundraiser level if not exists
$level = \App\Models\FundraiserLevel::first();
if (!$level) {
    $level = \App\Models\FundraiserLevel::create([
        'level_number' => 1,
        'level_name' => 'Starter',
        'max_goal_amount' => 500000.00,
        'max_active_campaigns' => 5,
        'is_default' => true,
    ]);
    echo "Created default fundraiser level\n";
}

// Assign level to creator
$creator = \App\Models\User::where('email', 'simlandikanchan@gmail.com')->first();
if ($creator) {
    \App\Models\UserFundraiserLevel::updateOrCreate(
        ['user_id' => $creator->id],
        ['current_level_id' => $level->id, 'status' => 'active']
    );
    echo "Assigned fundraiser level to creator\n";
}

// Assign level to donor too (needed for some tests)
$donor = \App\Models\User::where('email', 'simlandikanchan2@gmail.com')->first();
if ($donor) {
    \App\Models\UserFundraiserLevel::updateOrCreate(
        ['user_id' => $donor->id],
        ['current_level_id' => $level->id, 'status' => 'active']
    );
    echo "Assigned fundraiser level to donor\n";
}
