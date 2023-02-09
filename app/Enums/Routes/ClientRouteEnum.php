<?php

namespace App\Enums\Routes;

use App\Traits\ManagesEnumValues;

enum ClientRouteEnum: string
{
    use ManagesEnumValues;

    case Index = 'clients.index';
    case Detail = 'clients.show';
    case Notes = 'clients.notes';
    case Comments = 'clients.comments';
    case Files = 'clients.files';
}