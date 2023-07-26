<?php

namespace Tests\Unit;

use App\Models\File;
use Tests\TestCase;

class FileCountingKilobytesSizeTest extends TestCase
{
    public function test_check_if_counting_kilobytes_size_work(): void
    {
        $file = new File();
        $file->size = 10000;

        $this->assertEquals(10, $file->kilobytes_size);
    }

    public function test_check_if_counting_kilobytes_size_work_with_zero_size(): void
    {
        $file = new File();
        $file->size = 0;

        $this->assertEquals(0, $file->kilobytes_size);
    }
}
