<?php

namespace Tests\Unit;

use App\Models\Todo;
use Carbon\Carbon;
use Tests\TestCase;

class TodoDeadlineOverdueTest extends TestCase
{
    public function test_check_if_overdue_works_on_yesterday_dued_at(): void
    {
        $todo = new Todo([
            'dued_at' => Carbon::yesterday(),
            'is_finished' => false,
        ]);

        $this->assertEquals(true, $todo->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_tomorrow_dued_at(): void
    {
        $todo = new Todo([
            'dued_at' => Carbon::tomorrow(),
            'is_finished' => false,
        ]);

        $this->assertEquals(false, $todo->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_finished_todo(): void
    {
        $todo = new Todo([
            'dued_at' => Carbon::yesterday(),
            'is_finished' => true,
        ]);

        $this->assertEquals(false, $todo->deadline_overdue);
    }
}
