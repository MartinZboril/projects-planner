<?php

namespace App\Notifications\Ticket;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use App\Services\Data\NotificationService;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AssigneeAssignedNotification extends Notification
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
        return $this->notificationService->handleGetDeliveryChannels($notifiable, 'ticket', 'assigned');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Assigned to a new ticket')
            ->greeting('Hello '.$notifiable->name)
            ->line('You have been assigned to the ticket '.$this->ticket->subject)
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
            'content' => 'You have been assigned to the ticket '.$this->ticket->subject,
            'link' => route('tickets.show', $this->ticket),
        ];
    }
}
