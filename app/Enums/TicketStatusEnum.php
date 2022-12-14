<?php

namespace App\Enums;

use App\Traits\ManagesEnumValues;

enum TicketStatusEnum: int
{
    use ManagesEnumValues;

    case open = 1;
    case close = 2;
    case archive = 3;
}