<?php

namespace App\Notifications\Task\Status;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use App\Services\Data\NotificationService;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResumedTaskNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Task $task,
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
        return $this->notificationService->handleGetDeliveryChannels($notifiable, 'task', 'resumed');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('The task has been resumed')
            ->greeting('Hello '.$notifiable->name)
            ->line('The task '.$this->task->name.' has been resumed by the user.')
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
            'content' => 'The task '.$this->task->name.' has been resumed by the user.',
            'link' => route('tasks.show', $this->task),
        ];
    }
}
