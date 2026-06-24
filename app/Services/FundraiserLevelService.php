<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\FundraiserLevel;
use App\Models\User;

/**
 * FundraiserLevelService
 *
 * Centralises all fundraiser-level business rules:
 *  - canCreateCampaign()   → full check (count + goal cap)  — used on STORE
 *  - canEditCampaignGoal() → goal cap only, NO count check  — used on UPDATE
 */
class FundraiserLevelService
{
    /**
     * Full check used when CREATING a new campaign.
     * Verifies both:
     *  1. User has not exceeded their allowed active campaign count.
     *  2. Goal amount is within their level's cap.
     *
     * @return array{allowed: bool, reason: string|null, level: FundraiserLevel|null}
     */
    public function canCreateCampaign(User $user, float $goalAmount): array
    {
        $level = $this->resolveLevel($user);

        if (! $level) {
            return [
                'allowed' => false,
                'reason'  => 'No fundraiser level assigned. Please contact support.',
                'level'   => null,
            ];
        }

        // ── 1. Campaign count check ────────────────────────────────
        if ($level->max_active_campaigns !== null) {
            $activeCount = Campaign::where('user_id', $user->id)
                ->whereIn('campaign_state', [
                    Campaign::STATE_ACTIVE,
                    Campaign::STATE_PENDING,
                    Campaign::STATE_PAUSED,
                ])
                ->count();

            if ($activeCount >= $level->max_active_campaigns) {
                return [
                    'allowed' => false,
                    'reason'  => "You can have at most {$level->max_active_campaigns} active campaign(s) "
                               . "at your current level ({$level->name}). "
                               . "Complete or close an existing campaign first.",
                    'level'   => $level,
                ];
            }
        }

        // ── 2. Goal amount cap ─────────────────────────────────────
        if ($level->max_goal_amount !== null && $goalAmount > $level->max_goal_amount) {
            return [
                'allowed' => false,
                'reason'  => "Your level ({$level->name}) allows a maximum goal of "
                           . '₹' . number_format($level->max_goal_amount) . '.',
                'level'   => $level,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'level' => $level];
    }

    /**
     * Restricted check used when EDITING an existing campaign.
     * Only verifies goal amount cap — does NOT re-check campaign count
     * because the user already owns this campaign and it's already counted.
     *
     * @param  Campaign $campaign  The campaign being edited (for context/logging).
     * @return array{allowed: bool, reason: string|null, level: FundraiserLevel|null}
     */
    public function canEditCampaignGoal(User $user, float $newGoalAmount, Campaign $campaign): array
    {
        $level = $this->resolveLevel($user);

        if (! $level) {
            return [
                'allowed' => false,
                'reason'  => 'No fundraiser level assigned. Please contact support.',
                'level'   => null,
            ];
        }

        // Only check the goal cap — NOT campaign count
        if ($level->max_goal_amount !== null && $newGoalAmount > $level->max_goal_amount) {
            return [
                'allowed' => false,
                'reason'  => "Your level ({$level->name}) allows a maximum goal of "
                           . '₹' . number_format($level->max_goal_amount) . '. '
                           . 'Please contact support to raise your limit.',
                'level'   => $level,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'level' => $level];
    }

    /**
     * Resolve the effective level for a user:
     * If an admin override exists (level_override_by), use that;
     * otherwise fall back to the user's assigned fundraiser level.
     */
    // private function resolveLevel(User $user): ?FundraiserLevel
    // {
    //     // Admin can override a user's level for a specific campaign context.
    //     // Here we load the user's own assigned level; campaign-level override
    //     // is handled separately in the Campaign model / admin controller.
    //     return $user->fundraiserLevel           // user's own level relationship
    //         ?? FundraiserLevel::where('is_default', true)->first();  // site default
    // }

    private function resolveLevel(User $user): ?FundraiserLevel
{
    // assignedLevel is a hasOneThrough that returns FundraiserLevel directly.
    // fundraiserLevel (the pivot row) is NOT used here — that returns UserFundraiserLevel.
    // return $user->assignedLevel
    //     ?? FundraiserLevel::where('is_default', true)->first();

   return $user->assignedLevel
    ?? FundraiserLevel::first();

}

}