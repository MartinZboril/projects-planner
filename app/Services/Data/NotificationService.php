<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationService
{
    /**
     * Get delivery channels for notifiable object.
     */
    public function handleGetDeliveryChannels(object $notifiable, string $parent, string $action): array
    {
        $deliveryChannels = [];

        if ($notifiable->trashed() || $notifiable->id === Auth::id()) {
            return $deliveryChannels;
        }

        foreach (['mail', 'database'] as $channel) {
            if ($this->checkDeliveryChannelAvailability($notifiable, $parent, $action, $channel)) {
                array_push($deliveryChannels, $channel);
            }
        }

        return $deliveryChannels;
    }

    /**
     * Check if notifiable object has set up channel for action.
     */
    private function checkDeliveryChannelAvailability(object $notifiable, string $parent, string $action, string $channel): bool
    {
        return ($notifiable->settings['notifications'][$parent][$action][$channel] ?? false) ? true : false;
    }

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
