<?php

namespace App\Notifications\Ticket\Status;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReopenedTicketNotification extends Notification
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
        $viaOptions = [];

        if ($notifiable->trashed() || $notifiable->id === Auth::id()) {
            return $viaOptions;
        }

        if ($notifiable->settings['notifications']['ticket']['reopened']['mail'] ?? false) {
            array_push($viaOptions, 'mail');
        }

        if ($notifiable->settings['notifications']['ticket']['reopened']['database'] ?? false) {
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
            ->subject('The ticket has been reopened')
            ->greeting('Hello '.$notifiable->name)
            ->line('The ticket '.$this->ticket->subject.' has been reopened by the reporter.')
            ->action('Detail', route('tickets.show', $this->ticket));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'The ticket '.$this->ticket->subject.' has been reopened by the reporter.',
            'link' => route('tickets.show', $this->ticket),
        ];
    }
}
