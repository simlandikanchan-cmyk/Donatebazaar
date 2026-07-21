<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@DonateBazaar.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@DonateBazaar.com',
                'password' => Hash::make('admin@123'),
                'role' => 'admin',
            ]
        );

        $this->command->info(' Admin user created:');
        $this->command->info('   Email    → admin@donatebazar.com');
        $this->command->info('   Password → admin@123');
    }
}
