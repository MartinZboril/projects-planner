<?php

namespace App\Enums;

use App\Traits\ManagesEnumValues;
use Illuminate\Support\Collection;

enum TicketTypeEnum: int
{
    use ManagesEnumValues;

    case error = 1;
    case inovation = 2;
    case help = 3;
    case other = 4;
}