<?php

namespace App\Notifications\Task;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserUnassignedNotification extends Notification
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

        if ($notifiable->settings['notifications']['task']['unassigned']['mail'] ?? false) {
            array_push($viaOptions, 'mail');
        }

        if ($notifiable->settings['notifications']['task']['unassigned']['database'] ?? false) {
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
            ->subject('Unassigned from the task')
            ->greeting('Hello '.$notifiable->name)
            ->line('You have been unassigned from the task '.$this->task->name)
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
            'content' => 'You have been unassigned from the task '.$this->task->name,
            'link' => route('tasks.show', $this->task),
        ];
    }
}
