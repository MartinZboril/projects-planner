<?php

namespace Tests\Unit;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Carbon\Carbon;
use Tests\TestCase;

class TaskDeadlineOverdueTest extends TestCase
{
    public function test_check_if_overdue_works_on_yesterday_dued_at(): void
    {
        $task = new Task([
            'status' => TaskStatusEnum::new->value,
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(true, $task->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_tomorrow_dued_at(): void
    {
        $task = new Task([
            'status' => TaskStatusEnum::new->value,
            'dued_at' => Carbon::tomorrow(),
        ]);

        $this->assertEquals(false, $task->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_completed_task(): void
    {
        $task = new Task([
            'status' => TaskStatusEnum::complete->value,
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(false, $task->deadline_overdue);
    }
}
