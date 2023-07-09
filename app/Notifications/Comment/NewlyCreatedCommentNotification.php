<?php

namespace App\Notifications\Comment;

use App\Models\Comment;
use App\Services\Data\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewlyCreatedCommentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Comment $comment,
        private object $object,
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
        return $this->notificationService->handleGetDeliveryChannels($notifiable, $this->object->data->type, 'commented');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Newly created comment in ...')
            ->greeting('Hello '.$notifiable->name)
            ->line('In the '.$this->object->data->type.' '.$this->object->data->name.' was newly created comment.')
            ->action('Detail', $this->object->detail);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'In the '.$this->object->data->type.' '.$this->object->data->name.' was newly created comment.',
            'link' => $this->object->detail,
        ];
    }
}
