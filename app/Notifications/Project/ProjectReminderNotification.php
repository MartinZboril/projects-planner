<?php

namespace App\Notifications\Project;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectReminderNotification extends Notification
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

        if ($notifiable->settings['notifications']['project']['reminder']['mail'] ?? false) {
            array_push($viaOptions, 'mail');
        }

        if ($notifiable->settings['notifications']['project']['reminder']['database'] ?? false) {
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
            ->subject('Project due date reminder')
            ->greeting('Hello '.$notifiable->name)
            ->line('Reminder of due date for project '.$this->project->name)
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
            'content' => 'Reminder of due date for project '.$this->project->name,
            'link' => route('projects.show', $this->project),
        ];
    }
}
