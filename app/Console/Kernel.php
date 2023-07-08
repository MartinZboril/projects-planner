<?php

namespace App\Console;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\ToDo;
use App\Models\User;
use App\Notifications\Milestone\MilestoneReminderNotification;
use App\Notifications\Project\ProjectReminderNotification;
use App\Notifications\Task\TaskReminderNotification;
use App\Notifications\Ticket\TicketReminderNotification;
use App\Notifications\ToDo\ToDoReminderNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ----- Reminders -----

        $schedule->call(function () {
            $todos = ToDo::finished(false)->overdue()->get();
            $todos->each(function (ToDo $todo) {
                $todo->user->notify(new ToDoReminderNotification($todo));
            });
        })->dailyAt('09:00');

        $schedule->call(function () {
            $tasks = Task::active()->overdue()->get();
            $tasks->each(function (Task $task) {
                $task->author->notify(new TaskReminderNotification($task));
                $task->user->notify(new TaskReminderNotification($task));
            });
        })->dailyAt('09:00');

        $schedule->call(function () {
            $tickets = Ticket::active()->overdue()->get();
            $tickets->each(function (Ticket $ticket) {
                $ticket->reporter->notify(new TicketReminderNotification($ticket));
                $ticket->assignee ? $ticket->assignee->notify(new TicketReminderNotification($ticket)) : null;
            });
        })->dailyAt('09:00');

        $schedule->call(function () {
            $milestones = Milestone::with('tasks', 'tasksCompleted')->overdue()->get()->where('progress', '<', 1);
            $milestones->each(function (Milestone $milestone) {
                $milestone->owner->notify(new MilestoneReminderNotification($milestone));
            });
        })->dailyAt('09:00');

        $schedule->call(function () {
            $projects = Project::active()->overdue()->get();
            $projects->each(function (Project $project) {
                $project->team->each(function (User $user) use ($project) {
                    $user->notify(new ProjectReminderNotification($project));
                });
            });
        })->dailyAt('09:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
