<?php

namespace App\Notifications\Project\Status;

use App\Models\Project;
use App\Services\Data\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArchivedProjectNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Project $project,
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
        return $this->notificationService->handleGetDeliveryChannels($notifiable, 'project', 'archived');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('The project has been archived')
            ->greeting('Hello '.$notifiable->name)
            ->line('The project '.$this->project->name.' has been archived by the founder.')
            ->action('Detail', route('projects.show', $this->project));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'The project '.$this->project->name.' has been archived by the founder.',
            'link' => route('projects.show', $this->project),
        ];
    }
}
