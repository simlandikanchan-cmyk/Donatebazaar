<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * BlogSeeder
 *
 * Seeds blog-specific tags (categories already exist in DonateBazar).
 * Run with: php artisan db:seed --class=BlogSeeder
 */
class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTags();
        $this->command->info('✔  Blog tags seeded.');
    }

    private function seedTags(): void
    {
        $tags = [
            // Cause-based
            'Charity', 'Fundraising', 'Donation', 'NGO', 'Volunteer',
            'Education', 'Healthcare', 'Water & Sanitation', 'Food Security',
            'Mental Health', 'Disability Support', 'Refugee Aid',
            'Child Welfare', 'Women Empowerment', 'Senior Care',
            'Animal Rescue', 'Environment', 'Climate Action',
            'Disaster Relief', 'Rural Development', 'Skill Development',

            // Blog format
            'Success Story', 'Impact Report', 'Campaign Update',
            'Event Recap', 'Volunteer Spotlight', 'Founder Story',

            // Platform
            'DonateBazar', 'Community', 'Appeal', 'Awareness',
        ];

        foreach ($tags as $name) {
            DB::table('tags')->updateOrInsert(
                ['slug' => Str::slug($name)],
                [
                    'name'       => $name,
                    'slug'       => Str::slug($name),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}