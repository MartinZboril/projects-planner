<?php

namespace App\Services\Dashboard;

use App\Models\Milestone;
use App\Enums\ProjectStatusEnum;
use App\Models\{Project, Timer};
use Illuminate\Support\Collection;
use App\Services\Report\ProjectReport;

class ProjectDashboard
{
    /**
     * Get dashboard for projects.
     */
    public function getDashboard(): Collection
    {
        $year = now()->format('Y');
        $data = collect([
            'today_timers_total_time_sum' => round(Timer::whereDate('created_at', now()->format('Y-m-d'))->get()->sum('total_time'), 2),
            'this_week_timers_total_time_sum' => round(Timer::whereBetween('created_at', [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ])->get()->sum('total_time'), 2),
            'budget_avg' => round(Project::get()->avg('budget_plan'), 2),
            'spent_time_avg' => round(Project::get()->avg('total_time'), 2),
            'total_projects_count' => Project::whereYear('created_at', '<=', $year)->count(),
            'active_work_projects_count' => Project::active()->count(),
            'done_projects_count' => Project::done()->count(),
            'overdue_projects_count' => Project::active()->overdue()->count(),
            'overdue_projects' => Project::active()->overdue()->get(),
            'overdue_milestones' => Milestone::overdue()->get()->where('progress', '<', 1),
            'report' => (new ProjectReport)->getReportPerYear($year),
            'active_projects_count' => Project::status(ProjectStatusEnum::active)->count(),
            'finish_projects_count' => Project::status(ProjectStatusEnum::finish)->count(),
            'archive_projects_count' => Project::status(ProjectStatusEnum::archive)->count(),
        ]);

        return $data;
    }
}