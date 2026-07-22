<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@DonateBazaar.com';

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'email' => $email,
                'password' => Hash::make('admin@123'),
                'role' => 'admin',
            ]
        );

        $this->command->info(' Admin user created:');
        $this->command->info("   Email    → {$email}");
        $this->command->info('   Password → admin@123');
    }
}