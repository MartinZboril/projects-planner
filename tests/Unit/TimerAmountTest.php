<?php

namespace Tests\Unit;

use App\Models\Rate;
use App\Models\Timer;
use Tests\TestCase;

class TimerAmountTest extends TestCase
{
    public function test_calculating_successfuly_timer_amount(): void
    {
        $timer = new Timer([
            'since_at' => '2023-07-10 10:00',
            'until_at' => '2023-07-10 12:00',
        ]);

        $timer->rate = new Rate([
            'value' => 100,
        ]);

        $this->assertEquals(200, $timer->amount);
    }
}
