<?php

namespace App\Notifications\Task;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskReminderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Task $task
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $viaOptions = [];

        if ($notifiable->trashed() || $notifiable->id === Auth::id()) {
            return $viaOptions;
        }

        if ($notifiable->settings['notifications']['task']['deleted']['mail'] ?? false) {
            array_push($viaOptions, 'mail');
        }

        if ($notifiable->settings['notifications']['task']['deleted']['database'] ?? false) {
            array_push($viaOptions, 'database');
        }

        return $viaOptions;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Task has been deleted')
            ->greeting('Hello '.$notifiable->name)
            ->line('The '.$this->task->name.' task has been deleted and work on it is finished.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'The '.$this->task->name.' task has been deleted and work on it is finished.',
        ];
    }
}
