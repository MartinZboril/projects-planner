<?php

namespace App\Notifications\Milestone;

use App\Models\Milestone;
use App\Services\Data\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerAssignedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Milestone $milestone,
        private NotificationService $notificationService = new NotificationService,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->notificationService->handleGetDeliveryChannels($notifiable, 'milestone', 'assigned');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Assigned to a new milestone')
            ->greeting('Hello '.$notifiable->name)
            ->line('You have been assigned to the milestone '.$this->milestone->name)
            ->action('Detail', route('projects.milestones.show', ['project' => $this->milestone->project, 'milestone' => $this->milestone]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'You have been assigned to the milestone '.$this->milestone->name,
            'link' => route('projects.milestones.show', ['project' => $this->milestone->project, 'milestone' => $this->milestone]),
        ];
    }
}
