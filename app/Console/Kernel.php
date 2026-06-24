<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {


        // $schedule->command('campaigns:expire')->hourly();

        



        $schedule->command('telescope:prune', ['--hours' => 24])->daily();



    }

    protected function commands()



    {

        $this->load(__DIR__.'/Commands');

        
    }


}
