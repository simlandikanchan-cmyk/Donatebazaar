<?php

namespace App\Enums;

class NotificationType
{
    const DONATION_RECEIVED = 'donation_received';
    const FUNDS_AVAILABLE = 'funds_available';
    const KYC_REQUESTED = 'kyc_requested';
    const KYC_SUBMITTED = 'kyc_submitted';
    const SETTLEMENT_REQUESTED = 'settlement_requested';
    const SETTLEMENT_AUTO_APPROVED = 'settlement_auto_approved';
    const SETTLEMENT_MANUAL_REVIEW = 'settlement_manual_review';
    const SETTLEMENT_REJECTED = 'settlement_rejected';
    const SETTLEMENT_PROCESSING_STARTED = 'settlement_processing_started';
    const SETTLEMENT_PAID = 'settlement_paid';
    const SETTLEMENT_FAILED = 'settlement_failed';
    const SETTLEMENT_RETRY_SCHEDULED = 'settlement_retry_scheduled';
    const SETTLEMENT_CANCELLED = 'settlement_cancelled';
    const CAMPAIGN_APPROVED = 'campaign_approved';
    const CAMPAIGN_REJECTED = 'campaign_rejected';
    const CAMPAIGN_UPDATED = 'campaign_updated';

    const ALL = [
        self::DONATION_RECEIVED,
        self::FUNDS_AVAILABLE,
        self::KYC_REQUESTED,
        self::KYC_SUBMITTED,
        self::SETTLEMENT_REQUESTED,
        self::SETTLEMENT_AUTO_APPROVED,
        self::SETTLEMENT_MANUAL_REVIEW,
        self::SETTLEMENT_REJECTED,
        self::SETTLEMENT_PROCESSING_STARTED,
        self::SETTLEMENT_PAID,
        self::SETTLEMENT_FAILED,
        self::SETTLEMENT_RETRY_SCHEDULED,
        self::SETTLEMENT_CANCELLED,
        self::CAMPAIGN_APPROVED,
        self::CAMPAIGN_REJECTED,
        self::CAMPAIGN_UPDATED,
    ];

    const CHANNELS = ['email', 'database'];

    public static function label(string $type): string
    {
        return match ($type) {
            self::DONATION_RECEIVED => 'Donation Received',
            self::FUNDS_AVAILABLE => 'Funds Available',
            self::KYC_REQUESTED => 'KYC Requested',
            self::KYC_SUBMITTED => 'KYC Submitted',
            self::SETTLEMENT_REQUESTED => 'Payout Requested',
            self::SETTLEMENT_AUTO_APPROVED => 'Payout Approved',
            self::SETTLEMENT_MANUAL_REVIEW => 'Payout Manual Review',
            self::SETTLEMENT_REJECTED => 'Payout Rejected',
            self::SETTLEMENT_PROCESSING_STARTED => 'Payout Processing',
            self::SETTLEMENT_PAID => 'Payout Paid',
            self::SETTLEMENT_FAILED => 'Payout Failed',
            self::SETTLEMENT_RETRY_SCHEDULED => 'Payout Retry Scheduled',
            self::SETTLEMENT_CANCELLED => 'Payout Cancelled',
            self::CAMPAIGN_APPROVED => 'Campaign Approved',
            self::CAMPAIGN_REJECTED => 'Campaign Rejected',
            self::CAMPAIGN_UPDATED => 'Campaign Updated',
            default => $type,
        };
    }

    public static function description(string $type): string
    {
        return match ($type) {
            self::DONATION_RECEIVED => 'When someone donates to your campaign',
            self::FUNDS_AVAILABLE => 'When donation hold period expires and funds are released',
            self::KYC_REQUESTED => 'When admin requests you to submit KYC documents',
            self::KYC_SUBMITTED => 'When a user submits KYC documents (admin)',
            self::SETTLEMENT_REQUESTED => 'When a payout request is submitted',
            self::SETTLEMENT_AUTO_APPROVED => 'When a payout is automatically approved',
            self::SETTLEMENT_MANUAL_REVIEW => 'When a payout requires manual review',
            self::SETTLEMENT_REJECTED => 'When a payout request is rejected',
            self::SETTLEMENT_PROCESSING_STARTED => 'When payout processing begins',
            self::SETTLEMENT_PAID => 'When a payout is completed successfully',
            self::SETTLEMENT_FAILED => 'When a payout fails',
            self::SETTLEMENT_RETRY_SCHEDULED => 'When a payout retry is scheduled',
            self::SETTLEMENT_CANCELLED => 'When a payout is cancelled',
            self::CAMPAIGN_APPROVED => 'When your campaign is approved',
            self::CAMPAIGN_REJECTED => 'When your campaign is rejected',
            self::CAMPAIGN_UPDATED => 'When a campaign you donated to has an update',
            default => '',
        };
    }

    public static function canBeDisabled(string $type): bool
    {
        return !in_array($type, [
            self::KYC_REQUESTED,
            self::SETTLEMENT_FAILED,
        ], true);
    }

    public static function defaultFrequency(string $type): string
    {
        return match ($type) {
            self::CAMPAIGN_UPDATED => 'daily',
            default => 'immediate',
        };
    }

    public static function notificationClass(string $type): string
    {
        return match ($type) {
            self::DONATION_RECEIVED => \App\Notifications\DonationReceived::class,
            self::FUNDS_AVAILABLE => \App\Notifications\FundsAvailableNotification::class,
            self::KYC_REQUESTED => \App\Notifications\KycRequestedNotification::class,
            self::KYC_SUBMITTED => \App\Notifications\KycSubmittedNotification::class,
            self::SETTLEMENT_REQUESTED => \App\Notifications\SettlementRequestedNotification::class,
            self::SETTLEMENT_AUTO_APPROVED => \App\Notifications\SettlementApprovedNotification::class,
            self::SETTLEMENT_MANUAL_REVIEW => \App\Notifications\SettlementManualReviewNotification::class,
            self::SETTLEMENT_REJECTED => \App\Notifications\SettlementRejectedNotification::class,
            self::SETTLEMENT_PROCESSING_STARTED => \App\Notifications\SettlementProcessingStartedNotification::class,
            self::SETTLEMENT_PAID => \App\Notifications\SettlementPaidNotification::class,
            self::SETTLEMENT_FAILED => \App\Notifications\SettlementFailedNotification::class,
            self::SETTLEMENT_RETRY_SCHEDULED => \App\Notifications\SettlementRetryScheduledNotification::class,
            self::SETTLEMENT_CANCELLED => \App\Notifications\SettlementCancelledNotification::class,
            self::CAMPAIGN_APPROVED => \App\Notifications\CampaignApprovedNotification::class,
            self::CAMPAIGN_REJECTED => \App\Notifications\CampaignRejectedNotification::class,
            self::CAMPAIGN_UPDATED => \App\Notifications\CampaignUpdatedNotification::class,
            default => '',
        };
    }
}
