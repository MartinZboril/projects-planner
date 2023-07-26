<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Rate;
use App\Models\Timer;
use Tests\TestCase;

class ProjectBudgetPlanTest extends TestCase
{
    public function test_calculating_successfuly_project_full_budget_plan(): void
    {
        $project = new Project([
            'budget' => 1000,
        ]);

        $timer = new Timer([
            'since_at' => '2023-07-10 10:00',
            'until_at' => '2023-07-10 12:00',
        ]);
        $timer->rate = new Rate([
            'value' => 500,
        ]);

        $project->timers = collect([
            $timer,
        ]);

        $this->assertEquals(100.0, $project->budget_plan);
    }

    public function test_calculating_successfuly_project_remaining_budget_plan(): void
    {
        $project = new Project([
            'budget' => 1000,
        ]);

        $timer = new Timer([
            'since_at' => '2023-07-10 10:00',
            'until_at' => '2023-07-10 12:00',
        ]);
        $timer->rate = new Rate([
            'value' => 250,
        ]);

        $project->timers = collect([
            $timer,
        ]);

        $this->assertEquals(50.0, $project->budget_plan);
    }

    public function test_calculating_successfuly_project_overdue_budget_plan(): void
    {
        $project = new Project([
            'budget' => 1000,
        ]);

        $timer = new Timer([
            'since_at' => '2023-07-10 10:00',
            'until_at' => '2023-07-10 12:00',
        ]);
        $timer->rate = new Rate([
            'value' => 750,
        ]);

        $project->timers = collect([
            $timer,
        ]);

        $this->assertEquals(150.0, $project->budget_plan);
    }
}
