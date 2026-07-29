<?php

namespace Tests\Feature;

use App\Enums\NotificationType;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationPreferenceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_fetch_preferences(): void
    {
        $this->user->initializeDefaultPreferences();

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/notification-preferences');

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_user_can_update_single_preference(): void
    {
        $this->user->initializeDefaultPreferences();

        $response = $this->actingAs($this->user)
            ->putJson('/api/v1/notification-preferences/' . NotificationType::DONATION_RECEIVED . '/email', [
                'enabled' => false,
                'frequency' => 'daily',
            ]);

        $response->assertOk();

        $this->assertFalse(
            $this->user->preferNotification(NotificationType::DONATION_RECEIVED, 'email')
        );
    }

    public function test_user_can_bulk_update_preferences(): void
    {
        $this->user->initializeDefaultPreferences();

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/notification-preferences', [
                'notifications' => [
                    [
                        'type' => NotificationType::SETTLEMENT_PAID,
                        'channel' => 'email',
                        'enabled' => false,
                        'frequency' => 'immediate',
                    ],
                    [
                        'type' => NotificationType::SETTLEMENT_PAID,
                        'channel' => 'database',
                        'enabled' => true,
                        'frequency' => 'weekly',
                    ],
                ],
            ]);

        $response->assertOk();

        $this->assertFalse(
            $this->user->preferNotification(NotificationType::SETTLEMENT_PAID, 'email')
        );
        $this->assertTrue(
            $this->user->preferNotification(NotificationType::SETTLEMENT_PAID, 'database')
        );
    }

    public function test_user_cannot_disable_mandatory_notification(): void
    {
        $this->user->initializeDefaultPreferences();

        $response = $this->actingAs($this->user)
            ->putJson('/api/v1/notification-preferences/' . NotificationType::KYC_REQUESTED . '/email', [
                'enabled' => false,
                'frequency' => 'immediate',
            ]);

        $response->assertStatus(403);
    }

    public function test_user_can_reset_to_default(): void
    {
        $this->user->initializeDefaultPreferences();

        $this->user->updatePreference(NotificationType::DONATION_RECEIVED, 'email', false);

        $response = $this->actingAs($this->user)
            ->deleteJson('/api/v1/notification-preferences/' . NotificationType::DONATION_RECEIVED . '/email');

        $response->assertOk();

        $this->assertTrue(
            $this->user->preferNotification(NotificationType::DONATION_RECEIVED, 'email')
        );
    }

    public function test_user_can_reset_all(): void
    {
        $this->user->initializeDefaultPreferences();

        $this->user->updatePreference(NotificationType::DONATION_RECEIVED, 'email', false);
        $this->user->updatePreference(NotificationType::SETTLEMENT_PAID, 'database', false);

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/notification-preferences/reset-all');

        $response->assertOk();

        $this->assertTrue(
            $this->user->fresh()->preferNotification(NotificationType::DONATION_RECEIVED, 'email')
        );
    }

    public function test_preferences_created_on_user_creation(): void
    {
        $user = User::factory()->create();

        $this->assertGreaterThan(
            0,
            $user->notificationPreferences()->count()
        );
    }

    public function test_unauthorized_user_cannot_access_preferences(): void
    {
        $response = $this->getJson('/api/v1/notification-preferences');
        $response->assertUnauthorized();
    }

    public function test_invalid_notification_type_rejected(): void
    {
        $this->user->initializeDefaultPreferences();

        $response = $this->actingAs($this->user)
            ->putJson('/api/v1/notification-preferences/invalid_type/email', [
                'enabled' => false,
                'frequency' => 'immediate',
            ]);

        $response->assertStatus(422);
    }

    public function test_invalid_channel_rejected(): void
    {
        $this->user->initializeDefaultPreferences();

        $response = $this->actingAs($this->user)
            ->putJson('/api/v1/notification-preferences/' . NotificationType::DONATION_RECEIVED . '/sms', [
                'enabled' => false,
                'frequency' => 'immediate',
            ]);

        $response->assertStatus(422);
    }

    public function test_invalid_frequency_rejected(): void
    {
        $this->user->initializeDefaultPreferences();

        $response = $this->actingAs($this->user)
            ->putJson('/api/v1/notification-preferences/' . NotificationType::DONATION_RECEIVED . '/email', [
                'enabled' => true,
                'frequency' => 'yearly',
            ]);

        $response->assertStatus(422);
    }

    public function test_notification_types_endpoint(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/notification-types');

        $response->assertOk()
            ->assertJsonCount(count(NotificationType::ALL), 'data');
    }
}
