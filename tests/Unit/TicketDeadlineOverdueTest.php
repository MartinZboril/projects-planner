<?php

namespace Tests\Unit;

use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use Carbon\Carbon;
use Tests\TestCase;

class TicketDeadlineOverdueTest extends TestCase
{
    public function test_check_if_overdue_works_on_yesterday_dued_at(): void
    {
        $ticket = new Ticket([
            'status' => TicketStatusEnum::open->value,
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(true, $ticket->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_tomorrow_dued_at(): void
    {
        $ticket = new Ticket([
            'status' => TicketStatusEnum::open->value,
            'dued_at' => Carbon::tomorrow(),
        ]);

        $this->assertEquals(false, $ticket->deadline_overdue);
    }

    public function test_check_if_overdue_dont_turn_on_closed_ticket(): void
    {
        $ticket = new Ticket([
            'status' => TicketStatusEnum::close->value,
            'dued_at' => Carbon::yesterday(),
        ]);

        $this->assertEquals(false, $ticket->deadline_overdue);
    }
}
