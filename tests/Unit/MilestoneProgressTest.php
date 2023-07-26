<?php

namespace Tests\Unit;

use App\Enums\TaskStatusEnum;
use App\Models\Milestone;
use App\Models\Task;
use Carbon\Carbon;
use Tests\TestCase;

class MilestoneProgressTest extends TestCase
{
    public function test_check_if_progress_is_completed(): void
    {
        $milestone = new Milestone([
            'dued_at' => Carbon::yesterday(),
        ]);

        $completedTask = new Task([
            'status' => TaskStatusEnum::complete->value,
        ]);

        $milestone->tasks = collect([
            $completedTask,
        ]);

        $milestone->tasksCompleted = collect([
            $completedTask,
        ]);

        $this->assertEquals(1.0, $milestone->progress);
    }

    public function test_check_if_progress_is_uncompleted(): void
    {
        $milestone = new Milestone([
            'dued_at' => Carbon::yesterday(),
        ]);

        $completedTask = new Task([
            'status' => TaskStatusEnum::complete->value,
        ]);

        $uncompletedTask = new Task([
            'status' => TaskStatusEnum::complete->value,
        ]);

        $milestone->tasks = collect([
            $completedTask,
            $uncompletedTask,
        ]);

        $milestone->tasksCompleted = collect([
            $completedTask,
        ]);

        $this->assertEquals(0.5, $milestone->progress);
    }

    public function test_check_if_progress_is_zero_by_default(): void
    {
        $milestone = new Milestone([
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(0, $milestone->progress);
    }
}
