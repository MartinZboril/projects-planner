<?php

namespace App\Notifications\ToDo;

use App\Models\ToDo;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ToDoReminderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private ToDo $todo,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($notifiable->trashed() || $notifiable->id === Auth::id()) {
            return [];
        }

        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Todo due date reminder')
            ->greeting('Hello '.$notifiable->name)
            ->line('Reminder of due date for todo '.$this->todo->name)
            ->action('Detail', route('tasks.show', $this->todo->task));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'Reminder of due date for todo '.$this->todo->name,
            'link' => route('tasks.show', $this->todo->task),
        ];
    }
}
