<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Comment;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Rate;
use App\Models\Role;
use App\Models\Task;
use App\Models\Ticket;
use App\Observers\ClientObserver;
use App\Observers\CommentObserver;
use App\Observers\MilestoneObserver;
use App\Observers\ProjectObserver;
use App\Observers\RateObserver;
use App\Observers\RoleObserver;
use App\Observers\TaskObserver;
use App\Observers\TicketObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Client::observe(ClientObserver::class);
        Comment::observe(CommentObserver::class);
        Milestone::observe(MilestoneObserver::class);
        Project::observe(ProjectObserver::class);
        Rate::observe(RateObserver::class);
        Role::observe(RoleObserver::class);
        Task::observe(TaskObserver::class);
        Ticket::observe(TicketObserver::class);
    }
}
