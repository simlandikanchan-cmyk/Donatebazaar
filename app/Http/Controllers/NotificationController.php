<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * List the authenticated user's notifications.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->take(20)
            ->get()
            ->map(function (DatabaseNotification $notification) {
                $data = $notification->data;

                return [
                    'id'         => $notification->id,
                    'type'       => $data['type'] ?? 'info',
                    'title'      => $data['title'] ?? ($data['campaign'] ?? 'Notification'),
                    'message'    => $data['message'] ?? '',
                    'url'        => $data['url'] ?? null,
                    'read_at'    => $notification->read_at?->toIso8601String(),
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'ok'          => true,
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark all of the user's notifications as read.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'ok'          => true,
            'unread_count' => 0,
        ]);
    }
}
