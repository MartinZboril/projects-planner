<?php

namespace App\Enums;

use App\Traits\ManagesEnumValues;
use Illuminate\Support\Collection;

enum TicketPriorityEnum: int
{
    use ManagesEnumValues;

    case low = 1;
    case medium = 2;
    case high = 3;
    case urgent = 4;
}