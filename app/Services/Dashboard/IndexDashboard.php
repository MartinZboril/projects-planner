<?php

namespace App\Services\Dashboard;

use App\Models\{Project, Timer, Task, ToDo, Ticket};
use App\Services\Data\{ProjectService, TaskService, TicketService, ToDoService};
use Illuminate\Support\Collection;

class IndexDashboard
{
    /**
     * Get dashboard for index.
     */
    public function getDashboard(): Collection
    {
        $year = now()->format('Y');
        $data = collect([
            'today_timers_total_time_sum' => round(Timer::whereDate('created_at', now()->format('Y-m-d'))->get()->sum('total_time'), 2),
            'today_timers_amount_sum' => round(Timer::whereDate('created_at', now()->format('Y-m-d'))->get()->sum('amount'), 2),
            'active_projects_count' => Project::active()->count(),
            'active_tasks_count' => Task::active()->stopped(false)->count(),
            'active_tickets_count' => Ticket::active()->count(),
            'today_summary' =>$this->getTodaySummary(),
        ]);

        return $data;
    }

    protected function getTodaySummary(): Collection
    {
        $summary = collect();
        $summary = $this->pushItemsToSummary($summary, 'project', Project::active()->overdue()->get());
        $summary = $this->pushItemsToSummary($summary, 'task', Task::active()->overdue()->get());
        $summary = $this->pushItemsToSummary($summary, 'ticket', Ticket::active()->overdue()->get());
        $summary = $this->pushItemsToSummary($summary, 'todo', ToDo::finished(false)->overdue()->get());

        return $summary;
    }

    protected function pushItemsToSummary(Collection $summary, string $type, Collection $items): Collection
    {
        $items->each(function ($item, $key) use($summary, $type) {
            $summaryItem = collect([
                'name' => $type == 'ticket' ? $item->subject : $item->name,
                'type' => $type,
                'due_date' => $type == 'todo' ? $item->deadline : $item->due_date,
                'url' => $this->getItemUrl($type, $type == 'todo' ? $item->task->id : $item->id),
            ]);

            $summary->push($summaryItem);
        }); 

        return $summary;
    }

    protected function getItemUrl(string $type, int $id): string
    {
        switch ($type) {
            case 'project':
                return route('projects.detail', ['project' => $id]);
                break;
            
            case 'task':
                return route('tasks.detail', ['task' => $id]);
                break;  

            case 'ticket':
                return route('tickets.detail', ['ticket' => $id]);
                break;

            case 'todo':
                return route('tasks.detail', ['task' => $id]);
                break;

            default:
                return '';
        }
    }
}