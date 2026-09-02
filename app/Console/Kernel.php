<?php

namespace App\Console;

use App\Jobs\ReconciliationJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('campaigns:expire')->hourly();
        $schedule->command('campaigns:send-ending-soon')->dailyAt('09:00');
        $schedule->command('product-reservations:prune-expired')->everyFiveMinutes();
        $schedule->command('telescope:prune', ['--hours' => 24])->daily();
        $schedule->command('wallet:release-reserves')->daily();
        $schedule->command('settlements:reconcile')->dailyAt('01:00');
        $schedule->command('db:backup')->dailyAt('02:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
