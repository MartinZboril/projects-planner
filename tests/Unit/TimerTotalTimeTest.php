<?php

namespace Tests\Unit;

use App\Models\Timer;
use Tests\TestCase;

class TimerTotalTimeTest extends TestCase
{
    public function test_calculating_successfuly_timer_total_time(): void
    {
        $timer = new Timer([
           'since_at' => '2023-07-10 10:00',
           'until_at' => '2023-07-10 12:00',
        ]);

        $this->assertEquals(2.0, $timer->total_time);
    }

    public function test_calculating_unsuccessfuly_timer_total_time(): void
    {
        $timer = new Timer([
           'since_at' => '2023-07-10 10:00',
           'until_at' => '2023-07-10 12:00',
        ]);

        $this->assertNotEquals(1.0, $timer->total_time);
    }

    public function test_calculating_timer_total_time_with_decimals(): void
    {
        $timer = new Timer([
           'since_at' => '2023-07-10 10:00',
           'until_at' => '2023-07-10 12:30',
        ]);

        $this->assertEquals(2.5, $timer->total_time);
    }
}
