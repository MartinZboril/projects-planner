<?php

namespace Tests\Unit;

use App\Enums\TaskStatusEnum;
use App\Models\Milestone;
use App\Models\Task;
use Carbon\Carbon;
use Tests\TestCase;

class MilestoneDeadlineOverdueTest extends TestCase
{
    public function test_check_if_overdue_works_on_yesterday_dued_at(): void
    {
        $milestone = new Milestone([
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(true, $milestone->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_tomorrow_dued_at(): void
    {
        $milestone = new Milestone([
            'dued_at' => Carbon::tomorrow(),
        ]);

        $this->assertEquals(false, $milestone->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_completed_milestone(): void
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

        $this->assertEquals(false, $milestone->deadline_overdue);
    }
}
