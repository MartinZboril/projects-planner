<?php

namespace App\Notifications\Task\Status;

use App\Models\Task;
use App\Services\Data\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReturnedTaskNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Task $task,
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
        return $this->notificationService->handleGetDeliveryChannels($notifiable, 'task', 'returned');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('The task has been returned')
            ->greeting('Hello '.$notifiable->name)
            ->line('The task '.$this->task->name.' has been returned by the author.')
            ->action('Detail', route('tasks.show', $this->task));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'The task '.$this->task->name.' has been returned by the author.',
            'link' => route('tasks.show', $this->task),
        ];
    }
}
