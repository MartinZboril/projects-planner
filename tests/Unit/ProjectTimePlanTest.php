<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Timer;
use Tests\TestCase;

class ProjectTimePlanTest extends TestCase
{
    public function test_calculating_successfuly_project_full_time_plan(): void
    {
        $project = new Project([
            'estimated_hours' => 10,
        ]);

        $timer = new Timer([
            'since_at' => '2023-07-10 10:00',
            'until_at' => '2023-07-10 20:00',
        ]);

        $project->timers = collect([
            $timer,
        ]);

        $this->assertEquals(100.0, $project->time_plan);
    }

    public function test_calculating_successfuly_project_remaining_time_plan(): void
    {
        $project = new Project([
            'estimated_hours' => 10,
        ]);

        $timer = new Timer([
            'since_at' => '2023-07-10 10:00',
            'until_at' => '2023-07-10 15:00',
        ]);

        $project->timers = collect([
            $timer,
        ]);

        $this->assertEquals(50.0, $project->time_plan);
    }

    public function test_calculating_successfuly_project_overdue_time_plan(): void
    {
        $project = new Project([
            'estimated_hours' => 10,
        ]);

        $timer = new Timer([
            'since_at' => '2023-07-10 10:00',
            'until_at' => '2023-07-11 01:00',
        ]);

        $project->timers = collect([
            $timer,
        ]);

        $this->assertEquals(150.0, $project->time_plan);
    }
}
