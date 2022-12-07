<?php

namespace App\Enums;

use App\Traits\ManagesEnumValues;
use Illuminate\Support\Collection;

enum TicketStatusEnum: int
{
    use ManagesEnumValues;

    case open = 1;
    case close = 2;
    case archive = 3;
}