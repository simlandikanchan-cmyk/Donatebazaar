<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class InitializeNotificationPreferencesCommand extends Command
{
    protected $signature = 'notification-preferences:init';
    protected $description = 'Initialize notification preferences for all users who do not have them';

    public function handle(): int
    {
        $users = User::whereDoesntHave('notificationPreferences')->get();
        $count = $users->count();

        if ($count === 0) {
            $this->info('All users already have notification preferences.');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($users as $user) {
            $user->initializeDefaultPreferences();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Initialized preferences for {$count} user(s).");

        return Command::SUCCESS;
    }
}
