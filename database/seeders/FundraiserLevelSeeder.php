<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

// class FundraiserLevelSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $levels = [

//             [
//                 'level_number'             => 1,
//                 'level_name'               => 'Starter',
//                 'description'              => 'New fundraiser. Limited to small campaigns while trust is established.',
//                 'max_goal_amount'          => 50000.00,
//                 'max_active_campaigns'     => 1,
//                 'min_campaigns_completed'  => 0,
//                 'min_raised_percent'       => 0.00,
//                 'requires_admin_approval'  => false,
//                 'kyc_requirement'          => 'basic',
//                 'badge_color'              => '#6B7280',
//             ],
             


            
//             [
//                 'level_number'             => 2,
//                 'level_name'               => 'Trusted',
//                 'description'              => 'Completed 1 campaign raising 50%+ of goal with approved KYC.',
//                 'max_goal_amount'          => 200000.00,
//                 'max_active_campaigns'     => 2,
//                 'min_campaigns_completed'  => 1,
//                 'min_raised_percent'       => 50.00,
//                 'requires_admin_approval'  => false,
//                 'kyc_requirement'          => 'full',
//                 'badge_color'              => '#0F6E56',
//             ],

//             [
//                 'level_number'             => 3,
//                 'level_name'               => 'Verified',
//                 'description'              => 'Completed 2+ campaigns raising 80%+ of goal. Org documents on file.',
//                 'max_goal_amount'          => 1000000.00,
//                 'max_active_campaigns'     => 5,
//                 'min_campaigns_completed'  => 2,
//                 'min_raised_percent'       => 80.00,
//                 'requires_admin_approval'  => false,
//                 'kyc_requirement'          => 'org',
//                 'badge_color'              => '#534AB7',
//             ],

            
//             [
//                 'level_number'             => 4,
//                 'level_name'               => 'Champion',
//                 'description'              => 'Proven track record. 3+ campaigns, 85%+ raised. Admin-reviewed.',
//                 'max_goal_amount'          => 99999999.00,
//                 'max_active_campaigns'     => 10,
//                 'min_campaigns_completed'  => 3,
//                 'min_raised_percent'       => 85.00,
//                 'requires_admin_approval'  => true,
//                 'kyc_requirement'          => 'org',
//                 'badge_color'              => '#BA7517',
//             ],
//         ];

//         foreach ($levels as $level) {
//             DB::table('fundraiser_levels')->updateOrInsert(
//                 ['level_number' => $level['level_number']],
//                 array_merge($level, [
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ])
//             );
//         }

//         $this->command->info(' Fundraiser levels seeded (4 levels).');

//         // Seed all existing non-admin users at Level 1
//         $levelOneId = DB::table('fundraiser_levels')->where('level_number', 1)->value('id');

//         $users = DB::table('users')
//             ->whereIn('role', ['donor', 'ngo'])
//             ->pluck('id');

//         $existingUserIds = DB::table('user_fundraiser_levels')->pluck('user_id')->toArray();

//         $inserts = $users
//             ->reject(fn ($id) => in_array($id, $existingUserIds))
//             ->map(fn ($id) => [
//                 'user_id'                   => $id,
//                 'current_level_id'          => $levelOneId,
//                 'total_campaigns_completed' => 0,
//                 'total_amount_raised'       => 0.00,
//                 'level_upgraded_at'         => now(),
//                 'status'                    => 'active',
//                 'created_at'                => now(),
//                 'updated_at'                => now(),
//             ])
//             ->values()
//             ->toArray();

//         if (!empty($inserts)) {
//             DB::table('user_fundraiser_levels')->insert($inserts);
//             $this->command->info(" Seeded {$users->count()} existing users at Level 1.");
//         } else {
//             $this->command->info(' All existing users already have a level record.');
//         }
//     }
// }

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
 
class FundraiserLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'level_number'            => 1,
                'level_name'              => 'Starter',
                'description'             => 'New fundraiser. Limited to small campaigns while trust is established.',
                'max_goal_amount'         => 50_000.00,
                'max_active_campaigns'    => 1,
                'min_campaigns_completed' => 0,
                'min_raised_percent'      => 0.00,
                'requires_admin_approval' => false,
                'kyc_requirement'         => 'basic',
                'badge_color'             => '#6B7280',
            ],
            [
                'level_number'            => 2,
                'level_name'              => 'Trusted',
                'description'             => 'Completed 1 campaign raising 50%+ of goal with approved KYC.',
                'max_goal_amount'         => 200_000.00,
                'max_active_campaigns'    => 2,
                'min_campaigns_completed' => 1,
                'min_raised_percent'      => 50.00,
                'requires_admin_approval' => false,
                'kyc_requirement'         => 'full',
                'badge_color'             => '#0F6E56',
            ],
            [
                'level_number'            => 3,
                'level_name'              => 'Verified',
                'description'             => 'Completed 2+ campaigns raising 80%+ of goal. Org documents on file.',
                'max_goal_amount'         => 1_000_000.00,
                'max_active_campaigns'    => 5,
                'min_campaigns_completed' => 2,
                'min_raised_percent'      => 80.00,
                'requires_admin_approval' => false,
                'kyc_requirement'         => 'org',
                'badge_color'             => '#534AB7',
            ],
            [
                'level_number'            => 4,
                'level_name'              => 'Champion',
                'description'             => 'Proven track record. 3+ campaigns, 85%+ raised. Admin-reviewed.',
                'max_goal_amount'         => null, // null = no cap; handle in application logic
                'max_active_campaigns'    => 10,
                'min_campaigns_completed' => 3,
                'min_raised_percent'      => 85.00,
                'requires_admin_approval' => true,
                'kyc_requirement'         => 'org',
                'badge_color'             => '#BA7517',
            ],
        ];
 
        foreach ($levels as $level) {
            DB::table('fundraiser_levels')->updateOrInsert(
                ['level_number' => $level['level_number']],
                array_merge($level, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
 
        $this->command->info('✔ Fundraiser levels seeded (4 levels).');
 
        // ---------------------------------------------------------------------------
        // Assign Level 1 to all existing non-admin users who don't have a level yet
        // ---------------------------------------------------------------------------
 
        $levelOneId = DB::table('fundraiser_levels')
            ->where('level_number', 1)
            ->value('id');
 
        if (! $levelOneId) {
            throw new RuntimeException('Level 1 record not found after seeding — aborting user assignment.');
        }
 
        $allUserIds = DB::table('users')
            ->whereIn('role', ['donor', 'ngo'])
            ->pluck('id');
 
        // Use array_flip for O(1) lookups instead of O(n) in_array checks
        $existingUserIds = array_flip(
            DB::table('user_fundraiser_levels')->pluck('user_id')->toArray()
        );
 
        $inserts = $allUserIds
            ->reject(fn ($id) => isset($existingUserIds[$id]))
            ->map(fn ($id) => [
                'user_id'                   => $id,
                'current_level_id'          => $levelOneId,
                'total_campaigns_completed' => 0,
                'total_amount_raised'       => 0.00,
                'level_upgraded_at'         => now(),
                'status'                    => 'active',
                'created_at'                => now(),
                'updated_at'                => now(),
            ])
            ->values()
            ->toArray();
 
        if (! empty($inserts)) {
            DB::table('user_fundraiser_levels')->insert($inserts);
            // Report the number actually inserted, not total users found
            $this->command->info('✔ Seeded ' . count($inserts) . ' existing users at Level 1.');
        } else {
            $this->command->info('✔ All existing users already have a level record.');
        }
    }
}