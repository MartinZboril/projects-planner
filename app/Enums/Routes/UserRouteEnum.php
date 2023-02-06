<?php

namespace App\Enums\Routes;

use App\Traits\ManagesEnumValues;

enum UserRouteEnum: string
{
    use ManagesEnumValues;

    case Index = 'users.index';
    case Detail = 'users.show';
}