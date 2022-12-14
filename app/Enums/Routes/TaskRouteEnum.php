<?php

namespace App\Enums\Routes;

use App\Traits\ManagesEnumValues;

enum TaskRouteEnum: string
{
    use ManagesEnumValues;

    case Index = 'tasks.index';
    case Detail = 'tasks.detail';
}