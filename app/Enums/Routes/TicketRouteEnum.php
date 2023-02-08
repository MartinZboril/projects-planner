<?php

namespace App\Enums\Routes;

use App\Traits\ManagesEnumValues;

enum TicketRouteEnum: string
{
    use ManagesEnumValues;

    case Index = 'tickets.index';
    case Detail = 'tickets.show';
}