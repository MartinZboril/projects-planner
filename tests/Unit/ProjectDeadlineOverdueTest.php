<?php

namespace Tests\Unit;

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
use Carbon\Carbon;
use Tests\TestCase;

class ProjectDeadlineOverdueTest extends TestCase
{
    public function test_check_if_overdue_works_on_yesterday_dued_at(): void
    {
        $project = new Project([
            'status' => ProjectStatusEnum::active->value,
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(true, $project->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_tomorrow_dued_at(): void
    {
        $project = new Project([
            'status' => ProjectStatusEnum::active->value,
            'dued_at' => Carbon::tomorrow(),
        ]);

        $this->assertEquals(false, $project->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_finished_task(): void
    {
        $project = new Project([
            'status' => ProjectStatusEnum::finish->value,
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(false, $project->deadline_overdue);
    }
}
