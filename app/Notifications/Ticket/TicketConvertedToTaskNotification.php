<?php

namespace App\Notifications\Ticket;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketConvertedToTaskNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Ticket $ticket
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
            ->subject('The ticket has been converted to a task')
            ->greeting('Hello '.$notifiable->name)
            ->line('The ticket '.$this->ticket->subject.' was converted to a task by the author.')
            ->action('Detail', route('tasks.show', $this->ticket->task->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'The ticket '.$this->ticket->subject.' was converted to a task by the author.',
            'link' => route('tasks.show', $this->ticket->task->id),
        ];
    }
}
