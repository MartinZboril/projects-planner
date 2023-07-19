<?php

namespace App\Console;

use App\Jobs\Reminder\MilestoneReminder;
use App\Jobs\Reminder\ProjectReminder;
use App\Jobs\Reminder\TaskReminder;
use App\Jobs\Reminder\TicketReminder;
use App\Jobs\Reminder\ToDoReminder;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\ToDo;
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
            ToDoReminder::dispatch($todos);
        })->everyMinute();

        $schedule->call(function () {
            $tasks = Task::active()->overdue()->get();
            TaskReminder::dispatch($tasks);
        })->dailyAt('09:00');

        $schedule->call(function () {
            $tickets = Ticket::active()->overdue()->get();
            TicketReminder::dispatch($tickets);
        })->everyMinute();

        $schedule->call(function () {
            $milestones = Milestone::with('tasks', 'tasksCompleted')->overdue()->get()->where('progress', '<', 1);
            MilestoneReminder::dispatch($milestones);
        })->everyMinute();

        $schedule->call(function () {
            $projects = Project::active()->overdue()->get();
            ProjectReminder::dispatch($projects);
        })->everyMinute();
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
