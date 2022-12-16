<?php

namespace App\Enums\Routes;

use App\Traits\ManagesEnumValues;

enum NoteRouteEnum: string
{
    use ManagesEnumValues;

    case Index = 'notes.index';
}