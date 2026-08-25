<?php

namespace App\Traits;

use App\Enums\NotificationType;
use App\Models\NotificationPreference;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasNotificationPreferences
{
    public static function bootHasNotificationPreferences(): void
    {
        static::saved(function ($user) {
            if ($user->wasRecentlyCreated) {
                $user->initializeDefaultPreferences();
            }
        });
    }

    public function notificationPreferences(): HasMany
    {
        return $this->hasMany(NotificationPreference::class);
    }

    public function preferNotification(string $type, string $channel = 'email'): bool
    {
        $preference = $this->notificationPreferences()
            ->where('notification_type', $type)
            ->where('channel', $channel)
            ->first();

        if (!$preference) {
            $channels = NotificationType::CHANNELS;
            return in_array($channel, $channels, true);
        }

        return $preference->enabled;
    }

    public function getPreference(string $type, string $channel): ?NotificationPreference
    {
        return $this->notificationPreferences()
            ->where('notification_type', $type)
            ->where('channel', $channel)
            ->first();
    }

    public function getFrequency(string $type, string $channel = 'email'): string
    {
        $preference = $this->getPreference($type, $channel);
        return $preference?->frequency ?? NotificationType::defaultFrequency($type);
    }

    public function updatePreference(string $type, string $channel, bool $enabled, string $frequency = 'immediate'): NotificationPreference
    {
        return $this->notificationPreferences()->updateOrCreate(
            [
                'notification_type' => $type,
                'channel' => $channel,
            ],
            [
                'enabled' => $enabled,
                'frequency' => $frequency,
            ]
        );
    }

    public function resetPreference(string $type, string $channel): void
    {
        $this->notificationPreferences()
            ->where('notification_type', $type)
            ->where('channel', $channel)
            ->delete();
    }

    public function resetAllPreferences(): void
    {
        $this->notificationPreferences()->delete();
        $this->initializeDefaultPreferences();
    }

    public function initializeDefaultPreferences(): void
    {
        $defaults = $this->getDefaultPreferencesByRole();

        foreach ($defaults as $type => $channels) {
            foreach ($channels as $channel => $settings) {
                $this->notificationPreferences()->firstOrCreate(
                    [
                        'notification_type' => $type,
                        'channel' => $channel,
                    ],
                    [
                        'enabled' => $settings['enabled'],
                        'frequency' => $settings['frequency'] ?? 'immediate',
                    ]
                );
            }
        }
    }

    public function getDefaultPreferencesByRole(): array
    {
        $role = $this->role ?? 'donor';
        $allTypes = NotificationType::ALL;

        $defaults = [];

        foreach ($allTypes as $type) {
            $isMandatory = !NotificationType::canBeDisabled($type);
            $defaultFreq = NotificationType::defaultFrequency($type);

            foreach (NotificationType::CHANNELS as $channel) {
                $enabled = $this->isChannelEnabledByDefault($role, $type, $channel);
                if ($isMandatory && $channel === 'email') {
                    $enabled = true;
                }
                if ($enabled) {
                    $defaults[$type][$channel] = [
                        'enabled' => $enabled,
                        'frequency' => $defaultFreq,
                    ];
                }
            }
        }

        return $defaults;
    }

    protected function isChannelEnabledByDefault(string $role, string $type, string $channel): bool
    {
        if ($channel === 'database') {
            return true;
        }

        $emailEnabled = match ($role) {
            'admin' => in_array($type, [
                NotificationType::KYC_SUBMITTED,
                NotificationType::SETTLEMENT_MANUAL_REVIEW,
                NotificationType::SETTLEMENT_FAILED,
                NotificationType::SETTLEMENT_REQUESTED,
            ], true),
            'fundraiser' => in_array($type, [
                NotificationType::DONATION_RECEIVED,
                NotificationType::FUNDS_AVAILABLE,
                NotificationType::KYC_REQUESTED,
                NotificationType::SETTLEMENT_REQUESTED,
                NotificationType::SETTLEMENT_AUTO_APPROVED,
                NotificationType::SETTLEMENT_PAID,
                NotificationType::SETTLEMENT_FAILED,
                NotificationType::CAMPAIGN_APPROVED,
                NotificationType::CAMPAIGN_REJECTED,
            ], true),
            default => in_array($type, [
                NotificationType::DONATION_RECEIVED,
                NotificationType::CAMPAIGN_UPDATED,
            ], true),
        };

        if ($this->isMandatoryEmail($type)) {
            return true;
        }

        return $emailEnabled;
    }

    protected function isMandatoryEmail(string $type): bool
    {
        return in_array($type, [
            NotificationType::KYC_REQUESTED,
            NotificationType::SETTLEMENT_FAILED,
        ], true);
    }
}
