<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NotificationPreferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): JsonResponse
    {
        $preferences = Auth::user()->notificationPreferences()
            ->get()
            ->groupBy('notification_type');

        $result = [];
        foreach (NotificationType::ALL as $type) {
            $channels = [];
            foreach (NotificationType::CHANNELS as $channel) {
                $pref = $preferences[$type] ?? collect();
                $setting = $pref->firstWhere('channel', $channel);
                $channels[$channel] = [
                    'enabled' => (bool) ($setting?->enabled ?? $this->defaultEnabled($type, $channel)),
                    'frequency' => $setting?->frequency ?? NotificationType::defaultFrequency($type),
                    'can_be_disabled' => NotificationType::canBeDisabled($type),
                ];
            }
            $result[$type] = [
                'label' => NotificationType::label($type),
                'description' => NotificationType::description($type),
                'channels' => $channels,
            ];
        }

        return response()->json(['data' => $result]);
    }

    public function getTypes(): JsonResponse
    {
        $types = [];
        foreach (NotificationType::ALL as $type) {
            $types[] = [
                'type' => $type,
                'label' => NotificationType::label($type),
                'description' => NotificationType::description($type),
                'available_channels' => NotificationType::CHANNELS,
                'can_be_disabled' => NotificationType::canBeDisabled($type),
                'default_frequency' => NotificationType::defaultFrequency($type),
            ];
        }

        return response()->json(['data' => $types]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'notifications' => ['required', 'array', 'min:1'],
            'notifications.*.type' => ['required', 'string', Rule::in(NotificationType::ALL)],
            'notifications.*.channel' => ['required', 'string', Rule::in(NotificationType::CHANNELS)],
            'notifications.*.enabled' => ['required', 'boolean'],
            'notifications.*.frequency' => ['required', 'string', Rule::in(['immediate', 'daily', 'weekly'])],
        ]);

        $user = Auth::user();

        foreach ($validated['notifications'] as $notif) {
            if (!$notif['enabled'] && !NotificationType::canBeDisabled($notif['type'])) {
                return response()->json([
                    'message' => 'The notification type "' . NotificationType::label($notif['type']) . '" cannot be disabled.',
                    'errors' => ['notifications' => ['Mandatory notification cannot be disabled.']],
                ], 403);
            }

            $user->updatePreference(
                $notif['type'],
                $notif['channel'],
                $notif['enabled'],
                $notif['frequency']
            );
        }

        return response()->json(['message' => 'Preferences updated successfully.']);
    }

    public function update(Request $request, string $type, string $channel): JsonResponse
    {
        if (!in_array($type, NotificationType::ALL, true)) {
            return response()->json(['message' => 'Invalid notification type.'], 422);
        }

        if (!in_array($channel, NotificationType::CHANNELS, true)) {
            return response()->json(['message' => 'Invalid channel.'], 422);
        }

        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'frequency' => ['required', 'string', Rule::in(['immediate', 'daily', 'weekly'])],
        ]);

        if (!$validated['enabled'] && !NotificationType::canBeDisabled($type)) {
            return response()->json([
                'message' => 'This notification type cannot be disabled.',
            ], 403);
        }

        Auth::user()->updatePreference(
            $type,
            $channel,
            $validated['enabled'],
            $validated['frequency']
        );

        return response()->json(['message' => 'Preference updated successfully.']);
    }

    public function destroy(string $type, string $channel): JsonResponse
    {
        if (!in_array($type, NotificationType::ALL, true)) {
            return response()->json(['message' => 'Invalid notification type.'], 422);
        }

        if (!in_array($channel, NotificationType::CHANNELS, true)) {
            return response()->json(['message' => 'Invalid channel.'], 422);
        }

        Auth::user()->resetPreference($type, $channel);

        return response()->json(['message' => 'Preference reset to default.']);
    }

    public function resetAll(): JsonResponse
    {
        Auth::user()->resetAllPreferences();

        return response()->json(['message' => 'All preferences reset to defaults.']);
    }

    private function defaultEnabled(string $type, string $channel): bool
    {
        if ($channel === 'database') {
            return true;
        }

        return match ($type) {
            NotificationType::DONATION_RECEIVED,
            NotificationType::FUNDS_AVAILABLE,
            NotificationType::KYC_REQUESTED,
            NotificationType::SETTLEMENT_REQUESTED,
            NotificationType::SETTLEMENT_PAID,
            NotificationType::SETTLEMENT_FAILED,
            NotificationType::CAMPAIGN_APPROVED,
            NotificationType::CAMPAIGN_REJECTED => true,
            default => false,
        };
    }
}
