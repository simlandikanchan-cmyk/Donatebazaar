<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@DonateBazaar.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@DonateBazaar.com',
                'password' => Hash::make('admin@123'),
                'role'     => 'admin',
            ]
        );

        $this->command->info(' Admin user created:');
        $this->command->info('   Email    → admin@DonateBazaar.com');
        $this->command->info('   Password → admin@123');
    }
}