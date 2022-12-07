<?php

namespace App\Enums;

use App\Traits\ManagesEnumValues;
use Illuminate\Support\Collection;

enum TaskStatusEnum: int
{
    use ManagesEnumValues;

    case new = 1;
    case in_progress = 2;
    case complete = 3;
}