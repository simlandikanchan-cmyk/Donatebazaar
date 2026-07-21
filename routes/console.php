<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('campaigns:expire')->dailyAt('00:01');

// Send KYC reminders to campaign owners who haven't uploaded KYC
Schedule::command('campaigns:send-kyc-reminders')->dailyAt('09:00');

// to delete telescopies entries after 48 hours
Schedule::command('telescope:prune --hours=48')->daily();

// Release matured reserved wallet funds into available balance
Schedule::command('wallet:release-reserves')->daily();
