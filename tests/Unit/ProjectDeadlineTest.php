<?php

namespace Tests\Unit;

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
use Carbon\Carbon;
use Tests\TestCase;

class ProjectDeadlineTest extends TestCase
{
    public function test_check_if_past_deadline_days_calculating_correctly(): void
    {
        $project = new Project([
            'status' => ProjectStatusEnum::active->value,
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(-1, $project->deadline);
    }

    public function test_check_if_future_deadline_days_calculating_correctly(): void
    {
        $project = new Project([
            'status' => ProjectStatusEnum::active->value,
            'dued_at' => Carbon::tomorrow(),
        ]);

        $this->assertEquals(1, $project->deadline);
    }

    public function test_check_if_deadline_days_calculating_by_default_zero(): void
    {
        $project = new Project([
            'status' => ProjectStatusEnum::active->value,
            'dued_at' => Carbon::now(),
        ]);

        $this->assertEquals(0, $project->deadline);
    }

    public function test_check_if_deadline_days_calculating_by_zero_for_finished_project(): void
    {
        $project = new Project([
            'status' => ProjectStatusEnum::finish->value,
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(0, $project->deadline);
    }

    public function test_check_if_deadline_days_calculating_by_zero_for_archived_project(): void
    {
        $project = new Project([
            'status' => ProjectStatusEnum::archive->value,
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(0, $project->deadline);
    }
}
