<?php

namespace App\Notifications\Project;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use App\Services\Data\NotificationService;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectDeletedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Project $project,
        private NotificationService $notificationService=new NotificationService,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->notificationService->handleGetDeliveryChannels($notifiable, 'project', 'deleted');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Project has been deleted')
            ->greeting('Hello '.$notifiable->name)
            ->line('The '.$this->project->name.' project has been deleted and work on it is finished.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'The '.$this->project->name.' project has been deleted and work on it is finished.',
        ];
    }
}
