<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('campaigns:expire')->dailyAt('00:01');

Schedule::command('campaigns:send-kyc-reminders')->dailyAt('09:00');

Schedule::command('telescope:prune --hours=48')->daily();

Schedule::command('wallet:release-reserves')->daily();

Schedule::command('settlements:reconcile')->dailyAt('01:00');
