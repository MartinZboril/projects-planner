<?php

namespace App\Services\Data;

use Illuminate\Notifications\DatabaseNotification;

class NotificationService
{
    /**
     * Seen specific notification.
     */
    public function handleSeenNotification(DatabaseNotification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Seen all notifications.
     */
    public function handleSeenAllNotifications(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }
}
