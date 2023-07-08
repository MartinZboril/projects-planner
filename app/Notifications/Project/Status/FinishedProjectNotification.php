<?php

namespace App\Notifications\Project\Status;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FinishedProjectNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Project $project
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

        if ($notifiable->settings['notifications']['project']['finished']['mail'] ?? false) {
            array_push($viaOptions, 'mail');
        }

        if ($notifiable->settings['notifications']['project']['finished']['database'] ?? false) {
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
            ->subject('The project has been finished')
            ->greeting('Hello '.$notifiable->name)
            ->line('The project '.$this->project->name.' has been finished by the founder.')
            ->action('Detail', route('projects.show', $this->project));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'The project '.$this->project->name.' has been finished by the founder.',
            'link' => route('projects.show', $this->project),
        ];
    }
}
