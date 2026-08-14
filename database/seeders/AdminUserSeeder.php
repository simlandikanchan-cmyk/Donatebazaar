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

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin@123'),
            ]
        );

        $admin->role = 'admin';
        $admin->save();

        $this->command->info(' Admin user created:');
        $this->command->info("   Email    → {$email}");
        $this->command->info('   Password → admin@123');
    }
}
