<?php

namespace App\Enums\Routes;

use App\Traits\ManagesEnumValues;

enum ClientRouteEnum: string
{
    use ManagesEnumValues;

    case Index = 'clients.index';
    case Detail = 'clients.detail';
    case Notes = 'clients.notes';
}