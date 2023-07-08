<?php

namespace App\Notifications\Ticket;

use App\Models\Ticket;
use App\Services\Data\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketReminderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Ticket $ticket,
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
        return $this->notificationService->handleGetDeliveryChannels($notifiable, 'ticket', 'reminder');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Ticket due date reminder')
            ->greeting('Hello '.$notifiable->name)
            ->line('Reminder of due date for task '.$this->ticket->subject)
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
            'content' => 'Reminder of due date for task '.$this->ticket->subject,
            'link' => route('tickets.show', $this->ticket),
        ];
    }
}
