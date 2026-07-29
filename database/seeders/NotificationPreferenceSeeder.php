<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->notificationPreferences()->count() === 0) {
                $user->initializeDefaultPreferences();
            }
        }

        $this->command?->info('Notification preferences seeded for all users.');
    }
}
