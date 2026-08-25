<?php

namespace Tests\Unit;

use App\Enums\NotificationType;
use App\Models\NotificationPreference;
use App\Models\User;
use App\Notifications\DonationReceived;
use App\Notifications\CampaignApprovedNotification;
use App\Notifications\CampaignRejectedNotification;
use App\Notifications\CampaignUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_respects_user_preferences(): void
    {
        $user = User::factory()->create();

        $user->updatePreference(NotificationType::DONATION_RECEIVED, 'email', false);
        $user->updatePreference(NotificationType::DONATION_RECEIVED, 'database', true);

        $notification = new DonationReceived(
            amount: 100.00,
            donorName: 'Test Donor',
            campaignTitle: 'Test Campaign',
            campaignId: 1,
        );

        $channels = $notification->via($user);

        $this->assertNotContains('mail', $channels);
        $this->assertContains('database', $channels);
    }

    public function test_notification_not_sent_if_disabled(): void
    {
        $user = User::factory()->create();

        $user->updatePreference(NotificationType::DONATION_RECEIVED, 'email', false);
        $user->updatePreference(NotificationType::DONATION_RECEIVED, 'database', false);

        $notification = new DonationReceived(
            amount: 100.00,
            donorName: 'Test',
            campaignTitle: 'Test',
            campaignId: 1,
        );

        $channels = $notification->via($user);

        $this->assertEmpty($channels);
    }

    public function test_correct_channels_used_based_on_preferences(): void
    {
        $user = User::factory()->create();

        $user->updatePreference(NotificationType::SETTLEMENT_PAID, 'email', true);
        $user->updatePreference(NotificationType::SETTLEMENT_PAID, 'database', false);

        $notification = new \App\Notifications\SettlementPaidNotification(
            settlement: \App\Models\CampaignSettlement::factory()->make(),
            user: $user,
        );

        $channels = $notification->via($user);

        $this->assertContains('mail', $channels);
        $this->assertNotContains('database', $channels);
    }

    public function test_mandatory_notifications_always_sent(): void
    {
        $user = User::factory()->create();

        $user->updatePreference(NotificationType::KYC_REQUESTED, 'email', true);
        $user->updatePreference(NotificationType::KYC_REQUESTED, 'database', true);

        $notification = new \App\Notifications\KycRequestedNotification(
            campaign: \App\Models\Campaign::factory()->make(),
        );

        $channels = $notification->via($user);

        $this->assertNotEmpty($channels);
    }

    public function test_donation_notification_dispatched(): void
    {
        $user = User::factory()->create();
        $user->updatePreference(NotificationType::DONATION_RECEIVED, 'email', true);

        $notification = new DonationReceived(
            amount: 500.00,
            donorName: 'John Doe',
            campaignTitle: 'Help the Children',
            campaignId: 1,
        );

        $this->assertContains('mail', $notification->via($user));
        $this->assertEquals('New Donation Received!', $notification->toMail($user)->subject);
    }

    public function test_campaign_approved_notification_sent(): void
    {
        $user = User::factory()->create();
        $campaign = \App\Models\Campaign::factory()->make(['id' => 1, 'title' => 'Test Campaign']);

        $notification = new CampaignApprovedNotification($campaign);

        $this->assertContains('database', $notification->via($user));
        $this->assertStringContainsString('approved', $notification->toDatabase($user)['message']);
    }

    public function test_campaign_rejected_notification_sent(): void
    {
        $user = User::factory()->create();
        $campaign = \App\Models\Campaign::factory()->make(['id' => 1, 'title' => 'Test Campaign']);

        $notification = new CampaignRejectedNotification($campaign, 'Insufficient details');

        $this->assertContains('database', $notification->via($user));
        $this->assertStringContainsString('Insufficient details', $notification->toDatabase($user)['reason']);
    }

    public function test_preference_trait_methods(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(method_exists($user, 'preferNotification'));
        $this->assertTrue(method_exists($user, 'getPreference'));
        $this->assertTrue(method_exists($user, 'getFrequency'));
        $this->assertTrue(method_exists($user, 'updatePreference'));
        $this->assertTrue(method_exists($user, 'resetPreference'));
        $this->assertTrue(method_exists($user, 'resetAllPreferences'));
        $this->assertTrue(method_exists($user, 'notificationPreferences'));
    }

    public function test_preference_default_values(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($user->preferNotification(NotificationType::DONATION_RECEIVED, 'email'));
        $this->assertTrue($user->preferNotification(NotificationType::DONATION_RECEIVED, 'database'));
    }
}
